<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Auth;
use File;
use Image;
use Mail;
use URL;
use Illuminate\Support\Facades\Validator;
use Responsive\Http\Requests;

class WalletController extends Controller
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
    
	
	
	
	
	
	public function avigher_my_balance()
	{
	
	   $logged = Auth::user()->id;
		 
		 
		 $set_id=1;
		$site_setting = DB::table('settings')->where('id', $set_id)->get();
		
		$get_users_stage1 = DB::table('users')
		          ->where('id','=', $logged)
				  ->get();
		
		$pending_withdraw_cnt = DB::table('product_withdraw')
		                        ->where('user_id','=', $logged)
		          				->where('withdraw_status','=', 'pending')
				  				->count();
		$pending_withdraw = DB::table('product_withdraw')
		                        ->where('user_id','=', $logged)
		          				->where('withdraw_status','=', 'pending')
				  				->get();
								
								
								
		$complete_withdraw_cnt = DB::table('product_withdraw')
		                        ->where('user_id','=', $logged)
		          				->where('withdraw_status','=', 'completed')
				  				->count();
		$complete_withdraw = DB::table('product_withdraw')
		                        ->where('user_id','=', $logged)
		          				->where('withdraw_status','=', 'completed')
				  				->get();						
								
	
	   $data=array('site_setting' => $site_setting, 'get_users_stage1' => $get_users_stage1, 'pending_withdraw_cnt' => $pending_withdraw_cnt, 'pending_withdraw' => $pending_withdraw, 'complete_withdraw_cnt' => $complete_withdraw_cnt, 'complete_withdraw' => $complete_withdraw);
	   return view('my-balance')->with($data);
	}
	
	
	
	
	public function avigher_balance_data(Request $request) 
	{
	
	$data = $request->all();
	$withdraw_amount = $data['withdraw_amount'];
	$withdraw_type = $data['withdraw_type'];
	if(!empty($data['paypal_id'])){ $paypal_id = $data['paypal_id']; } else { $paypal_id = ""; }
	if(!empty($data['stripe_id'])) { $stripe_id = $data['stripe_id']; } else { $stripe_id = ""; }
	if(!empty($data['bank_acc_no'])) { $bank_acc_no = $data['bank_acc_no']; } else { $bank_acc_no = ""; }
	if(!empty($data['bank_name'])) { $bank_name = $data['bank_name']; } else { $bank_name = ""; }
	if(!empty($data['ifsc_code'])) { $ifsc_code = $data['ifsc_code']; } else  { $ifsc_code = ""; }
	$withdraw_status = "pending";
	$logged = Auth::user()->id;
	
	$set_id=1;
	$setting = DB::table('settings')->where('id', $set_id)->get();
		if($setting[0]->withdraw_amt > $withdraw_amount)
		{
			return back()->with('error', 'Please check minimum withdraw amount and available balance');
		}
		else
		{
		
		
		
		
		
		$url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.$setting[0]->site_logo;
		
		$site_name = $setting[0]->site_name;
		
		$currency = $setting[0]->site_currency;
		
		$user_email = Auth::user()->email;
		$username = Auth::user()->name;
		
		$aid=1;
		$admindetails = DB::table('users')
		 ->where('id', '=', $aid)
		 ->get();
		
		$admin_email = $admindetails[0]->email;
	   
		
		
		
		
			if($data['available_amount'] >= $withdraw_amount)
			{
			
			
			$clear_balance = $data['available_amount'] - $withdraw_amount;
			
			DB::update('update users set earning="'.$clear_balance.'" where id = ?', [Auth::user()->id]);
			
			
			DB::insert('insert into product_withdraw (user_id,withdraw_amount,withdraw_payment_type,paypal_id,stripe_id,bank_account_no,bank_info,bank_ifsc,withdraw_status) values (?, ?, ?, ?, ?, ?, ?, ?, ?)', [$logged,$withdraw_amount,$withdraw_type,$paypal_id,$stripe_id,$bank_acc_no,$bank_name,$ifsc_code,$withdraw_status]);
			
			
			$datas = [
            'withdraw_amount' => $withdraw_amount, 'withdraw_type' => $withdraw_type, 'paypal_id' => $paypal_id, 'stripe_id' => $stripe_id,
			'bank_acc_no' => $bank_acc_no, 'bank_name' => $bank_name, 'ifsc_code' => $ifsc_code, 'currency' => $currency, 'site_logo' => $site_logo, 'site_name' => $site_name
        ];
			
			
			Mail::send('withdraw_email', $datas , function ($message) use ($admin_email,$user_email,$username)
        {
            $message->subject('Withdrawal Request');
			
            $message->from($admin_email,'Admin');

            $message->to($admin_email);
			

        }); 
			
			
			
			
			
			
			return back()->with('success', 'Your withdraw request has been sent');
			
			}
			else
			{
			   return back()->with('error', 'Your withdraw amount is high. Please check available balance');
			}
			 
		}
	
	
	
		return back();		  
	
	
	}
	
	
	
	
	
   public function avigher_wallet_balance(Request $request)
	{
	
	   $data = $request->all();
	   $log_id = Auth::user()->id;
	   $amount_value = $data['amount'];
	   $cid = $data['cid'];
	   
	   
	   
	   $view_cnt = DB::table('product_orders')
						->where('purchase_token', '=', $cid)
						->where('order_status', '=', 'pending')
						->count();
		
		if(!empty($view_cnt))
		{
				$view_orders = DB::table('product_orders')
								->where('purchase_token', '=', $cid)
								->where('order_status', '=', 'pending')
								->get();
				
				foreach($view_orders as $views)
				{
				
				$ord_id = $views->ord_id;
				
				$subtotal = $views->quantity * $views->price;
				
				$total = $subtotal + $views->shipping_price;
				
				
				DB::update('update product_orders set subtotal="'.$subtotal.'",total="'.$total.'",payment_type="wallet",order_status="completed",payment_status="completed" where ord_id = ?', [$ord_id]);
				
				
				
				}	
				
				
				
		$wallet_get = DB::table('users')
              
			       ->where('id', '=', $log_id)
			   
                   ->get();	
				   
		$wallet_balance = 	$wallet_get[0]->earning - $amount_value;
		
		DB::update('update users set earning="'.$wallet_balance.'" where id = ?', [$log_id]);	   	
				
		DB::update('update product_checkout set payment_status="completed" where purchase_token = ?', [$cid]);		
				
		$get_details = DB::table('product_checkout')
              
			       ->where('purchase_token', '=', $cid)
			   
                   ->get();
				   
			$user_details = DB::table('users')
              
			       ->where('id', '=', $get_details[0]->user_id)
			   
                   ->get();	   
				   
				   				
						
				$order_id = $cid;
				$name = $user_details[0]->name;
				$email = $user_details[0]->email;
				$phone = $user_details[0]->phone;			
				$amount = $get_details[0]->total;
				
				
						
		
		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
		$url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.$setts[0]->site_logo;
		
		$site_name = $setts[0]->site_name;
		
		
		$aid=1;
		$admindetails = DB::table('users')
		 ->where('id', '=', $aid)
		 ->first();
		
		$admin_email = $admindetails->email;
		
		
		
		
		$datas = [
            'site_logo' => $site_logo, 'site_name' => $site_name, 'name' => $name,  'email' => $email, 'phone' => $phone, 'amount' => $amount, 'url' => $url, 'order_id' => $order_id
        ];
		
		Mail::send('cashon_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('“Pedido Realizado com Sucesso”'); // Marcello Order Details Received
			
            $message->from($admin_email, 'Admin');

            $message->to($admin_email);

        }); 
		
		
		
		
		Mail::send('cashon_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('“Pedido Realizado com Sucesso”'); // Marcello Order Details Received
			
            $message->from($admin_email, 'Admin');

            $message->to($email);

        }); 
		
				
				
				
				
						
		
		}
		
		
		
		
		$datas = array('cid' => $cid);
      return view('wallet-balance')->with($datas);
	
	   
	   
	   
	   
	   
	   
   
  
	}
	
	
	public function avigher_walletpage() {

      return view('wallet-balance');
   }
	
	
	
	
	
	
}
