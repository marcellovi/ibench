<?php

namespace Responsive\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use File;
use Image;
use Mail;
use Carbon\Carbon;
use PaytmWallet;
use URL;

class PaytmController extends Controller
{
    
	
	public function order(Request $request)
    {


        $this->validate($request, [
            'name' => 'required',
            'mobile_number' => 'required',
            
        ]);

        $url = URL::to("/");
        $data = $request->all();
        $order_id = $data['order_id'];
		$name = $data['name'];
		$mobile_number = $data['mobile_number'];
        $amount = $data['amount'];
		$email = $data['email'];
		$currency = $data['currency'];


        


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
		  'user' => $name,
          'mobile_number' => $mobile_number,
		  'currency' => $currency,
          'email' => $email,
          'amount' => $amount,
          'callback_url' => $url.'/paytm/status'
        ]);
        return $payment->receive();
    }
	
	
	
	
	 public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');


        $response = $transaction->response();
        $cid = $transaction->getOrderId();
		$payment_id = $transaction->getTransactionId();
		
		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();


        if($transaction->isSuccessful()){
          /*EventRegistration::where('order_id',$order_id)->update(['status'=>2, 'transaction_id'=>$transaction->getTransactionId()]);*/

          
		  $orderupdate = DB::table('product_orders')
						->where('purchase_token', '=', $cid)
						->where('order_status', '=', 'pending')
						->update(['order_status' => 'completed', 'payment_status' => 'completed', 'payment_token' => $payment_id, 'payment_type' => 'paytm']);
		 $checkoutupdate = DB::table('product_checkout')
						->where('purchase_token', '=', $cid)
						->where('payment_status', '=', 'pending')
						->update(['payment_status' => 'completed', 'payment_token' => $payment_id]);
		
			
			
			$get_viewr = DB::table('product_orders')
		->where('purchase_token', '=', $cid)
		->where('order_status', '=', 'completed')
		->count();
		
		
		
		
		$view_orders = DB::table('product_orders')
						->where('purchase_token', '=', $cid)
						->where('order_status', '=', 'completed')
						->get();
		
		foreach($view_orders as $views)
		{
		
		$ord_id = $views->ord_id;
		
		$subtotal = $views->quantity * $views->price;
		
		$total = $subtotal + $views->shipping_price;
		
		
		DB::update('update product_orders set subtotal="'.$subtotal.'",total="'.$total.'" where order_status="completed" and ord_id = ?', [$ord_id]);
		
		
		
		}				
		
						
		
		
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
		
		Mail::send('shop_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('Payment Received');
			
            $message->from($admin_email, 'Admin');

            $message->to($admin_email);

        }); 
		

		Mail::send('shop_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('Payment Received');
			
            $message->from($admin_email, 'Admin');

            $message->to($email);

        }); 
		 
				
				
		
		
		$dataa = array('payment_id' => $payment_id);
		return view('paytm_shop_success')->with($dataa);
		
		  
		  
		  
		  
		  
         
        }else if($transaction->isFailed()){
          /*EventRegistration::where('order_id',$order_id)->update(['status'=>1, 'transaction_id'=>$transaction->getTransactionId()]);*/
         return view('cancel');
        }
    } 
	
	
	
	
	
	public function paytm_success($payment_id)
	{
	   
	 
	 $dataa = array('payment_id' => $payment_id);
	return view('paytm_shop_success')->with($dataa);
	
	}
	
	
	
}
