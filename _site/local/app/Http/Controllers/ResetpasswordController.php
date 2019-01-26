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

class ResetpasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
	
	
	
	protected function avigher_reset_view($id)
    {
       
	   $data = array('id' => $id);
	   return view('reset-password')->with($data);
	   
	}
	
	
	
	 protected function avigher_reset_password(Request $request)
    {
       
		
		
		
		 $this->validate($request, [

        		

        		'email' => 'required|email'
                
        		
				
				

        	]);
         
		 $data = $request->all();
			
         
        			
		$input['email'] = Input::get('email');
       
		$id = $data['email'];
		$password_token = $data['password_token'];
		$password = bcrypt($data['password']);
		$new_pass = $data['password'];
		
		$rules = array(
        
       
		
        'email'=>'required|email|unique:users,email,'.$id
		
		
		
        );
		
		
		$messages = array(
            
            'email' => "N&atilde;o foi poss&iacute;vel encontrar seu email. Favor tente novamente."
            
			
        );
		
		
		
		
		
		 $validator = Validator::make(Input::all(), $rules, $messages);

		

		if ($validator->fails())
		{
		
		    $email=$data['email'];
			
			$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
		$url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.'logo_email.jpg'; // Marcello $setts[0]->site_logo;
		
		$site_name = $setts[0]->site_name;
		
		
		$aid=1;
		$admindetails = DB::table('users')
		 ->where('id', '=', $aid)
		 ->first();
		
		$admin_email = $admindetails->email;
		
		
		$count = DB::table('users')
		 ->where('email', '=', $email)
		 ->where('remember_token', '=', $password_token)
		 ->count();
		 
		 if($count == 1)
		 {
		    DB::update('update users set password="'.$password.'" where email = "'.$email.'" and remember_token = ?', [$password_token]);
		
		    $getpassword = DB::table('users')
			->where('email', '=', $email)
			->get();
			
			$token = $getpassword[0]->remember_token;
			$pass = $getpassword[0]->password;
			
				$datas = [
				 'email' => $email, 'token' => $token, 'new_pass' => $new_pass, 'site_logo' => $site_logo, 'site_name' => $site_name, 'url' => $url
			];
		
			Mail::send('resetemail', $datas , function ($message) use ($admin_email,$email)
			{
				$message->subject('Senha Redefinida - iBench');
				
				$message->from($admin_email, 'iBench Market');
	
				$message->to($email);
	
			}); 
			
			return back()->with('success', 'Voc&ecirc; receber&aacute; um email confirmando a altera&ccedil;&atilde;o da sua senha'); // Marcello We have e-mailed your password details
			
		
		 }
		 
		 else
		 {
		     return back()->with('error', "Oops! Dados Invalidos"); // Marcello Invalid Details
		 }
		
		
		
		
		
		
		
		
		
		
		
			
			
		
		
	   
	     
		
			 
		}
		else
		{ 
		  

		$failedRules = $validator->failed();
			 
			return back()->with('error', "N&atilde;o foi poss&iacute;vel encontrar seu email. Favor tente novamente."); // Marcello We can't find a user with that email address.
		
        }
		
		
		
		
    }
	
	
}
