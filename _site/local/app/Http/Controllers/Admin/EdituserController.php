<?php

namespace Responsive\Http\Controllers\Admin;

use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use File;
use Image;


class EdituserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showform($id) {
        $users = DB::select('select * from users where id = ?',[$id]);
	$userid = $id;


	$countries = array(
	'Brazil'
              );

	$setid=1;
	$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();

      return view('admin.edituser',['users'=>$users, 'userid' => $userid, 'countries' => $countries, 'setts' => $setts]);
   }

    public function clean($string)
    {
        $string = preg_replace("/[^\p{L}\/_|+ -]/ui","",$string);
        $string = preg_replace("/[\/_|+ -]+/", '-', $string);
        $string =  trim($string,'-');

        return mb_strtolower($string);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users'

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected $fillable = ['name', 'full_name', 'email','password','phone','cpf_cnpj'];

    protected function edituserdata(Request $request)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/

		 $this->validate($request, [
            'name' => 'required',
            'full_name' => 'required',
            'email' => 'required|email',
            'cpf_cnpj' => 'required|cpf_cnpj'
        	]);

		$data = $request->all();
                $id=$data['id'];
		$input['email'] = Input::get('email');
		$input['name'] = Input::get('name');
    $input['full_name'] = Input::get('full_name');
    $input['cpf_cnpj'] = Input::get('cpf_cnpj');
    $settings = DB::select('select * from settings where id = ?',[1]);
    $imgsize = $settings[0]->image_size;
		$imgtype = $settings[0]->image_type;

		$rules = array(
                    'email'=>'required|email|unique:users,email,'.$id,
                    'name' => 'required|regex:/^[\w-]*$/|max:255|unique:users,name,'.$id,
                    'full_name' => 'required|regex:/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/|max:255|unique:users,full_name,'.$id,
                    'photo' => 'max:'.$imgsize.'|mimes:'.$imgtype
                 );

		$messages = array(
                    'email' => 'The :attribute field is already exists',
                    'name' => 'The :attribute field must only be letters and numbers (no spaces)',
                    'full_name' => 'The :attribute field must only be letters'
                );

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails())
		{
			$failedRules = $validator->failed();
			return back()->withErrors($validator);
		}
		else
		{
			/*User::create([
            'name' => $data['name'],
            'email' => $data['email'],
			'admin' => '0',
            'password' => bcrypt($data['password']),
			'phone' => $data['phone']

        ]);*/
    $name=$data['name'];
    $full_name=$data['full_name'];
    $email=$data['email'];
    $cpf_cnpj=$data['cpf_cnpj'];
		$phone=$data['phone'];
		$currentphoto=$data['currentphoto'];
		$image = Input::file('photo');
        if($image!="")
	{
            $userphoto="/media/";
            $delpath = base_path('images'.$userphoto.$currentphoto);
            File::delete($delpath);
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = base_path('images'.$userphoto.$filename);
            Image::make($image->getRealPath())->resize(200, 200)->save($path);
            $savefname=$filename;
	}
        else
	{
            $savefname=$currentphoto;
	}


	if(!empty($data['password']))
	{
            $password=bcrypt($data['password']);
            $passtxt=$password;
	}
	else
	{
            $passtxt=$data['savepassword'];
	}

	if(!empty($data['country']))
	{
            $country = $data['country'];
	}
	else
	{
            $country = "";
	}

	if(!empty($data['earning']))
	{
            $earning = $data['earning'];
	}
	else
	{
	   $earning = 0;
	}

	$admin=$data['usertype'];

	DB::update('update post set post_email="'.$email.'" where post_type="comment" and post_user_id = ?', [$id]);

	if($admin==1)
	{
            DB::update('update users set name="'.$name.'",full_name="'.$full_name.'",post_slug="'.$this->clean($name,$full_name).'",email="'.$email.'",password="'.$passtxt.'",phone="'.$phone.'",country="'.$country.'",photo="'.$savefname.'",admin="'.$admin.'",cpf_cnpj="'.$cpf_cnpj.'" where id = ?', [$id]);
	}
	else
	{
            DB::update('update users set name="'.$name.'",full_name="'.$full_name.'",post_slug="'.$this->clean($name,$full_name).'",email="'.$email.'",password="'.$passtxt.'",phone="'.$phone.'",country="'.$country.'",photo="'.$savefname.'",admin="'.$admin.'",cpf_cnpj="'.$cpf_cnpj.'",delete_status="",earning="'.$earning.'" where id = ?', [$id]);
	}

	return back()->with('success', 'Conta Atualizada com Sucesso!');
        }
    }
}
