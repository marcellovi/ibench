<?php

namespace Responsive\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use File;
use Image;
use URL;
use Mail;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function avigher_my_profile($user_id, $user_slug) {

        $editprofile_count = DB::table('users')
                ->where('id', '=', $user_id)
                ->count();

        $editprofile = DB::table('users')
                ->where('id', '=', $user_id)
                ->get();

        $viewcount = DB::table('product')
                ->where('user_id', '=', $user_id)
                ->where('delete_status', '=', '')
                ->where('prod_status', '=', 1)
                ->orderBy('prod_id', 'desc')
                ->count();

        $viewproduct = DB::table('product')
                ->where('user_id', '=', $user_id)
                ->where('delete_status', '=', '')
                ->where('prod_status', '=', 1)
                ->orderBy('prod_id', 'desc')
                ->get();


        $data = array('editprofile' => $editprofile, 'editprofile_count' => $editprofile_count, 'viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'user_id' => $user_id);
        return view('profile')->with($data);
    }
    
    /* Save the Shipping for each state */
    public function config_shipping() {
        
        $user_id = Auth::user()->id;
        $editprofile_count = DB::table('users')
                ->where('id', '=', $user_id)
                ->count();

        $editprofile = DB::table('users')
                ->where('id', '=', $user_id)
                ->get();

        $data = array('editprofile' => $editprofile, 'editprofile_count' => $editprofile_count);
        return view('config-shipping')->with($data);        
    }

    /* Save the Shipping for each state */
    public function config_shipping2(Request $request){
        
        $data = $request->all();
        //$id = $data['user_id'];
        $AC = $data['AC'];
        $AL = $data['AL'];
        $AP = $data['AP'];
        $AM = $data['AM'];
        $BA = $data['BA'];
        $CE = $data['CE'];
        $DF = $data['DF'];
        $ES = $data['ES'];
        $GO = $data['GO'];
        $MA = $data['MA'];
        $MT = $data['MT'];
        $MS = $data['MS'];
        $MG = $data['MG'];
        $PA = $data['PA'];
        $PB = $data['PB'];
        $PR = $data['PR'];
        $PE = $data['PE'];
        $PI = $data['PI'];
        $RJ = $data['RJ'];
        $RN = $data['RN'];
        $RS = $data['RS'];
        $RO = $data['RO'];
        $RR = $data['RR'];
        $SC = $data['SC'];
        $SP = $data['SP'];
        $SE = $data['SE'];
        $TO = $data['TO'];
        
        $seller_shipping = DB::table('users_shipping_cost')
                ->where('user_id', '=', $id)
                ->count();
        
        if($seller_shipping>0){
          //  DB::update('update users_shipping_cost set (user_id,bill_firstname,bill_lastname,bill_companyname,bill_email,bill_phone,bill_country,bill_address,bill_city,bill_state,	bill_postcode,	enable_ship,ship_firstname,ship_lastname,ship_companyname,ship_email,ship_phone,ship_country,ship_address,ship_city,ship_state,ship_postcode) values (?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?)', [$id, $bill_firstname, $bill_lastname, $bill_companyname, $bill_email, $bill_phone, $bill_country, $bill_address, $bill_city, $bill_state, $bill_postcode, $enable_ship, $ship_firstname, $ship_lastname, $ship_companyname, $ship_email, $ship_phone, $ship_country, $ship_address, $ship_city, $ship_state, $ship_postcode]);
         // DB::table('users_shipping_cost')
         //   ->where('user_id', $id)
         //   ->update(['votes' => 1]);
            
        }else{
           // DB::insert('insert into users_shipping_cost (user_id,bill_firstname,bill_lastname,bill_companyname,bill_email,bill_phone,bill_country,bill_address,bill_city,bill_state,	bill_postcode,	enable_ship,ship_firstname,ship_lastname,ship_companyname,ship_email,ship_phone,ship_country,ship_address,ship_city,ship_state,ship_postcode) values (?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?)', [$id, $bill_firstname, $bill_lastname, $bill_companyname, $bill_email, $bill_phone, $bill_country, $bill_address, $bill_city, $bill_state, $bill_postcode, $enable_ship, $ship_firstname, $ship_lastname, $ship_companyname, $ship_email, $ship_phone, $ship_country, $ship_address, $ship_city, $ship_state, $ship_postcode]);
          }

    }

    public function index() {
        
        $userid = Auth::user()->id;
        $editprofile = DB::select('select * from users where id = ?', [$userid]);
        $check_waiting_list = DB::table('waiting_list')
                ->where('prod_user_id', '=', $userid)
                ->where('waiting', '=', true)
                ->count();

        $customer_waiting_list = DB::table('waiting_list')
                ->where('user_id', '=', $userid)
                ->get();

        $countries = array('Brazil');

        $viewpost = DB::table('post')
                ->where('post_type', '=', 'comment')
                ->where('post_user_id', '=', $userid)
                ->count();

        $edited_count = DB::table('product_billing_shipping')
                ->where('user_id', '=', $userid)
                ->count();

        $edited = DB::select('select * from product_billing_shipping where user_id = ?', [$userid]);

        $data = array('editprofile' => $editprofile, 'viewpost' => $viewpost, 'countries' => $countries, 'edited' => $edited, 'edited_count' => $edited_count, 'waiting_count' => $check_waiting_list, 'customer_waiting_list' => $customer_waiting_list);
        return view('dashboard')->with($data);
    }

    public function mycomments() {
        
        $userid = Auth::user()->id;

        $viewpost = DB::table('post')
                ->where('post_type', '=', 'comment')
                ->where('post_user_id', '=', $userid)
                ->get();

        $postcount = DB::table('post')
                ->where('post_type', '=', 'comment')
                ->where('post_user_id', '=', $userid)
                ->count();

        $data = array('viewpost' => $viewpost, 'postcount' => $postcount);
        return view('my-comments')->with($data);
    }

    public function mycomments_destroy($id) {
        DB::delete('delete from post where post_type="comment" and post_id = ?', [$id]);
        return back();
    }

    public function avigher_logout(){
	Auth::logout();
       return back();
    }


    public function avigher_deleteaccount() {
        $userid = Auth::user()->id;
        DB::delete('delete from post where post_type="comment" and post_user_id = ?', [$userid]);
        DB::delete('delete from users where id!=1 and id = ?', [$userid]);
        return back();
    }

    public function clean($string) {

        $string = preg_replace("/[^\p{L}\/_|+ -]/ui", "", $string);
        $string = preg_replace("/[\/_|+ -]+/", '-', $string);
        $string = trim($string, '-');

        return mb_strtolower($string);
    }

    public function avigher_contact_vendor(Request $request) {

        $data = $request->all();
        $name = $data['name'];
        $phone = $data['phone'];
        $msg = $data['msg'];
        $vendor_id = $data['vendor_id'];

        $setid = 1;
        $setts = DB::table('settings')
                ->where('id', '=', $setid)
                ->get();

        $url = URL::to("/");

        $site_logo = $url . '/local/images/media/' . $setts[0]->site_logo;
        $site_name = $setts[0]->site_name;

        $seller_details = DB::table('users')
                ->where('id', '=', $vendor_id)
                ->get();

        $slug = $seller_details[0]->post_slug;
        $seller_email = $seller_details[0]->email;
        $user_email = $data['email'];

        $data = [
            'slug' => $slug, 'url' => $url, 'site_logo' => $site_logo, 'site_name' => $site_name, 'name' => $name, 'user_email' => $user_email, 'phone' => $phone, 'msg' => $msg, 'seller_email' => $seller_email
        ];

        Mail::send('seller_contactmail', $data, function ($message) use ($user_email, $seller_email, $name) {
            $message->subject('Contato Fornecedor'); // Marcello Contact Vendor

            $message->from($user_email, $name);
            $message->bcc('ibench@ibench.com.br'); // Marcello BCC Email 
            $message->to($seller_email);
        });


        return back()->with('success', 'Em breve entraremos em contato, obrigado!');
    }

    protected function avigher_edituserdata(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $data = $request->all();

        $id = $data['id'];
        $fullname = $data['fullname'];
        $address = $data['address'];
        $input['email'] = Input::get('email');
        $input['name'] = Input::get('name');

        $providor = Auth::user()->provider;

        $settings = DB::select('select * from settings where id = ?', [1]);
        $imgsize = $settings[0]->image_size;
        $imagetype = $settings[0]->image_type;
        $mp3size = $settings[0]->mp3_size;

        if ($providor == "") {
            $rules = array(
                'email' => 'required|email|unique:users,email,' . $id,
                'name' => 'required|regex:/^[\w-]*$/|max:255|unique:users,name,' . $id,
                'photo' => 'max:' . $imgsize . '|mimes:' . $imagetype,
                'profile_banner' => 'max:' . $imgsize . '|mimes:' . $imagetype,
                'phone' => 'string|min:9|max:100,' . $id
                    // Marcello :: 'phone' => 'required|max:255|unique:users,phone,'.$id
            );
        } else {
            $rules = array(
                'email' => 'required|email:users,email,' . $id,
                'photo' => 'max:' . $imgsize . '|mimes:' . $imagetype,
                'profile_banner' => 'max:' . $imgsize . '|mimes:' . $imagetype,
                'phone' => 'string|min:9|max:100,' . $id
                    //'phone' => 'required|max:255|unique:users,phone,'.$id
            );
        }

        $messages = array(
            'email' => 'The :attribute field is already exists',
            'name' => 'The :attribute field must only be letters and numbers (no spaces)'
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {

            if (!empty($data['name'])) {
                $name = $data['name'];
            } else {
                $name = "";
            }
            if (!empty($data['email'])) {
                $email = $data['email'];
            } else {
                $email = "";
            }
            if (!empty($data['phone'])) {
                $phone = $data['phone'];
            } else {
                $phone = "";
            }
            if (!empty($data['fullname'])) {
                $fullname = $data['fullname'];
            } else {
                $fullname = "";
            }
            if (!empty($data['country'])) {
                $country = $data['country'];
            } else {
                $country = "";
            }
            if (!empty($data['address'])) {
                $address = $data['address'];
            } else {
                $address = "";
            }
            $password = bcrypt($data['password']);
            if ($data['password'] != "") {
                $passtxt = $password;
            } else {
                $passtxt = $data['savepassword'];
            }

            if (!empty($data['about'])) {
                $about_txt = $data['about'];
            } else {
                $about_txt = "";
            }


            /* billing fields */

            if (!empty($data['bill_firstname'])) {
                $bill_firstname = $data['bill_firstname'];
            } else {
                $bill_firstname = "";
            }
            if (!empty($data['bill_lastname'])) {
                $bill_lastname = $data['bill_lastname'];
            } else {
                $bill_lastname = "";
            }
            if (!empty($data['bill_companyname'])) {
                $bill_companyname = $data['bill_companyname'];
            } else {
                $bill_companyname = "";
            }
            if (!empty($data['bill_email'])) {
                $bill_email = $data['bill_email'];
            } else {
                $bill_email = "";
            }
            if (!empty($data['bill_phone'])) {
                $bill_phone = $data['bill_phone'];
            } else {
                $bill_phone = "";
            }
            if (!empty($data['bill_country'])) {
                $bill_country = $data['bill_country'];
            } else {
                $bill_country = "";
            }
            if (!empty($data['bill_address'])) {
                $bill_address = $data['bill_address'];
            } else {
                $bill_address = "";
            }
            if (!empty($data['bill_city'])) {
                $bill_city = $data['bill_city'];
            } else {
                $bill_city = "";
            }
            if (!empty($data['bill_state'])) {
                $bill_state = $data['bill_state'];
            } else {
                $bill_state = "";
            }
            if (!empty($data['bill_postcode'])) {
                $bill_postcode = $data['bill_postcode'];
            } else {
                $bill_postcode = "";
            }

            /* Marcello Incluir WalletId & CustomerId :: Nao e' mais usado
              if(!empty($data['cpf_cnpj'])) { $cpf_cnpj = $data['cpf_cnpj']; } else { $cpf_cnpj = ""; }
              if(!empty($data['customer_id'])) { $customer_id = $data['customer_id']; } else { $customer_id = ""; }
             */

            /* Marcello Incluir Campos Adicionais */
            if (!empty($data['name_business'])) {
                $name_business = $data['name_business'];
            } else {
                $name_business = "";
            }
            if (!empty($data['name_place'])) {
                $name_place = $data['name_place'];
            } else {
                $name_place = "";
            }

            /* end billing fields */


            /* shipping fields */

            if (!empty($data['ship_firstname'])) {
                $ship_firstname = $data['ship_firstname'];
            } else {
                $ship_firstname = "";
            }
            if (!empty($data['ship_lastname'])) {
                $ship_lastname = $data['ship_lastname'];
            } else {
                $ship_lastname = "";
            }
            if (!empty($data['ship_companyname'])) {
                $ship_companyname = $data['ship_companyname'];
            } else {
                $ship_companyname = "";
            }
            if (!empty($data['ship_email'])) {
                $ship_email = $data['ship_email'];
            } else {
                $ship_email = "";
            }
            if (!empty($data['ship_phone'])) {
                $ship_phone = $data['ship_phone'];
            } else {
                $ship_phone = "";
            }
            if (!empty($data['ship_country'])) {
                $ship_country = $data['ship_country'];
            } else {
                $ship_country = "";
            }
            if (!empty($data['ship_address'])) {
                $ship_address = $data['ship_address'];
            } else {
                $ship_address = "";
            }
            if (!empty($data['ship_city'])) {
                $ship_city = $data['ship_city'];
            } else {
                $ship_city = "";
            }
            if (!empty($data['ship_state'])) {
                $ship_state = $data['ship_state'];
            } else {
                $ship_state = "";
            }
            if (!empty($data['ship_postcode'])) {
                $ship_postcode = $data['ship_postcode'];
            } else {
                $ship_postcode = "";
            }

            /* end shipping fields */

            if (!empty($data['enable_ship'])) {
                $enable_ship = $data['enable_ship'];
            } else {
                $enable_ship = 0;
            }

            if (!empty($data['local_shipping_price'])) {
                $local_shipping_price = $data['local_shipping_price'];
            } else {
                $local_shipping_price = 0;
            }
            if (!empty($data['world_shipping_price'])) {
                $world_shipping_price = $data['world_shipping_price'];
            } else {
                $world_shipping_price = 0;
            }

            $currentphoto = $data['currentphoto'];
            $image = Input::file('photo');
            $currentbanner = $data['currentbanner'];
            $profile_image = Input::file('profile_banner');

            if ($image != "") {
                $userphoto = "/media/";
                $delpath = base_path('images' . $userphoto . $currentphoto);
                File::delete($delpath);
                $filename = time() . '.' . $image->getClientOriginalExtension();

                $path = base_path('images' . $userphoto . $filename);

                Image::make($image->getRealPath())->resize(200, 200)->save($path);
                $savefname = $filename;
            } else {
                $savefname = $currentphoto;
            }

            if ($profile_image != "") {
                $userphoto_two = "/media/";
                $delpath_two = base_path('images' . $userphoto_two . $currentbanner);
                File::delete($delpath_two);
                $filename_two = time() . '.' . $profile_image->getClientOriginalExtension();

                $path_two = base_path('images' . $userphoto_two . $filename_two);

                Image::make($profile_image->getRealPath())->resize(1140, 370)->save($path_two);
                $save_banners = $filename_two;
            } else {
                $save_banners = $currentbanner;
            }

            if ($image == "" && $profile_image == "") {
                $savefname = $currentphoto;
                $save_banners = $currentbanner;
            }

            if ($image != "" && $profile_image != "") {
                if ($image != "") {
                    $userphoto = "/media/";
                    $delpath = base_path('images' . $userphoto . $currentphoto);
                    File::delete($delpath);
                    $filename = time() . '.' . $image->getClientOriginalExtension();

                    $path = base_path('images' . $userphoto . $filename);

                    Image::make($image->getRealPath())->resize(200, 200)->save($path);
                    $savefname = $filename;
                }

                if ($profile_image != "") {

                    $userphoto_two = "/media/";
                    $delpath_two = base_path('images' . $userphoto_two . $currentbanner);
                    File::delete($delpath_two);
                    $filename_two = time() . '.' . $profile_image->getClientOriginalExtension();

                    $path_two = base_path('images' . $userphoto_two . $filename_two);

                    Image::make($profile_image->getRealPath())->resize(1140, 370)->save($path_two);
                    $save_banners = $filename_two;
                }
            }

            $viewcount = DB::table('product_billing_shipping')
                    ->where('user_id', '=', $id)
                    ->count();

            if (empty($viewcount)) {
                DB::insert('insert into product_billing_shipping (user_id,bill_firstname,bill_lastname,bill_companyname,bill_email,bill_phone,bill_country,bill_address,bill_city,bill_state,	bill_postcode,	enable_ship,ship_firstname,ship_lastname,ship_companyname,ship_email,ship_phone,ship_country,ship_address,ship_city,ship_state,ship_postcode) values (?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?,?,?, ?,?)', [$id, $bill_firstname, $bill_lastname, $bill_companyname, $bill_email, $bill_phone, $bill_country, $bill_address, $bill_city, $bill_state, $bill_postcode, $enable_ship, $ship_firstname, $ship_lastname, $ship_companyname, $ship_email, $ship_phone, $ship_country, $ship_address, $ship_city, $ship_state, $ship_postcode]);
            } else {

                DB::update('update product_billing_shipping set
		bill_firstname="' . $bill_firstname . '",
		bill_lastname="' . $bill_lastname . '",
		bill_companyname="' . $bill_companyname . '",
		bill_email="' . $bill_email . '",
		bill_phone="' . $bill_phone . '",
		bill_country="' . $bill_country . '",
		bill_address="' . $bill_address . '",
		bill_city="' . $bill_city . '",
		bill_state="' . $bill_state . '",
		bill_postcode="' . $bill_postcode . '",
		enable_ship="' . $enable_ship . '",
		ship_firstname="' . $ship_firstname . '",
		ship_lastname="' . $ship_lastname . '",
		ship_companyname="' . $ship_companyname . '",
		ship_email="' . $ship_email . '",
		ship_phone="' . $ship_phone . '",
		ship_country="' . $ship_country . '",
		ship_address="' . $ship_address . '",
		ship_city="' . $ship_city . '",
		ship_state="' . $ship_state . '",
		ship_postcode="' . $ship_postcode . '"

		 where user_id = ?', [$id]);
            }
            
            DB::update('update post set post_email="' . $email . '" where post_type="comment" and post_user_id = ?', [$id]);

            /* Marcello Update Users Wallet & Customer ID & Name Business & CPF/CNPJ & */
            DB::update('update users set name="' . $name . '",post_slug="' . $this->clean($name) .
                    '",email="' . $email . '",password="' . $passtxt .
                    '",phone="' . $phone . '",min_value="' . $data['min_value'] . '",full_name="' . $fullname .
                    '",country="' . $country . '",photo="' . $savefname . '",profile_banner="' . $save_banners .
                    '",about="' . addslashes($about_txt) . '",address="' . $address . '",local_shipping_price="' . $local_shipping_price .
                    '",name_business="' . $name_business . '",name_place="' . $name_place .
                    '",world_shipping_price="' . $world_shipping_price . '" where id = ?', [$id]);

            return back()->with('success', 'Conta Atualizada com Sucesso');
        }
    }

}
