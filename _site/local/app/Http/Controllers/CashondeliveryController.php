<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use File;
use Image;
use URL;
use Mail;
use Carbon\Carbon;

class CashondeliveryController extends Controller
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
    
	
	
	public function avigher_showpage() {

      return view('cash-on-delivery');
   }
   
   
   
        /** Marcello - Envio de Email com sucesso na compra **/
	public function avigher_success(Request $request)
	{
	
	    $data = $request->all();
		
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
				
				
				DB::update('update product_orders set subtotal="'.$subtotal.'",total="'.$total.'",payment_type="cash-on-delivery",order_status="completed",payment_status="pending" where ord_id = ?', [$ord_id]);
				
				
				
				}	
				
				
				
		$get_details = DB::table('product_checkout')
              
			       ->where('purchase_token', '=', $cid)
			   
                   ->get();
				   
			$user_details = DB::table('users')
              
			       ->where('id', '=', $get_details[0]->user_id)
			   
                   ->get();	   
				   
				   				
						
				$order_id = $cid;
                                $type_user = $user_details[0]->admin; // Marcello :: Pega se e' fornecedor ou comprador
				$name = $user_details[0]->name;
				$email = $user_details[0]->email;
				$phone = $user_details[0]->phone;			
				$amount = $get_details[0]->total;
                                
                                /* Marcello :: Fornecedor Email */
                                //$id_fornecedor = $get_details[0]->total;
				
				
						
		
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
		
		
		
		
		$datas = [
            'site_logo' => $site_logo, 'site_name' => $site_name, 'name' => $name,  'email' => $email, 'phone' => $phone, 'amount' => $amount, 'url' => $url, 'order_id' => $order_id,'type_user' => $type_user
        ];
		
		Mail::send('cashon_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('Detalhamentos do Pedido Recebido'); // Marcello :: Order Details Received
			
            $message->from($admin_email, 'iBench Market');

            $message->to($admin_email);
            
            // Marcello - Copia Oculta : Envio abaixo para nosso email de contato //
            //$message->bcc('ibench@ibench.com.br');

        }); 
		
		
		
		
		Mail::send('cashon_email', $datas , function ($message) use ($admin_email,$email)
        {
            $message->subject('Detalhamentos do Pedido Recebido');
			
            $message->from($admin_email, 'iBench Market');

            $message->to($email);

        }); 
		
				
				
				
				
						
		
		}
		
		
		
		
		$datas = array('cid' => $cid);
      return view('cash-on-delivery')->with($datas);
	
	
	}
	
	
	
	
	
	
}
