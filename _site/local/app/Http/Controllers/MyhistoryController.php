<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use Auth;
use File;
use Image;
use URL;
use Mail;

class MyhistoryController extends Controller
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
    public function __construct()
    {
        $this->middleware('auth');
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
	$get_shop = DB::table('shop')
		          ->where('user_id','=', $logged)
				  ->get();
	$shop_id = $get_shop[0]->shop_id;
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
			
			DB::insert('insert into withdraw (shop_id,user_id,withdraw_amount,withdraw_payment_type,paypal_id,stripe_id,bank_account_no,bank_info,bank_ifsc,withdraw_status) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$shop_id,$logged,$withdraw_amount,$withdraw_type,$paypal_id,$stripe_id,$bank_acc_no,$bank_name,$ifsc_code,$withdraw_status]);
			
			
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
	
	
	
	public function avigher_refund_data(Request $request) 
	{
		 $data = $request->all();
		 $set_id=1;
	     $setting = DB::table('settings')->where('id', $set_id)->get();
		 
		 $prod_id = $data['prod_id'];
		 $purchase_token = $data['purchase_token'];
		 $order_id = $data['order_id'];
		 $payment_date = $data['payment_date'];
		 $buyer_id = $data['buyer_id'];
		 $vendor_id = $data['vendor_id'];
		 $payment = $data['payment'];
		 $subjected = $data['subject'];
		 $messaged = $data['message'];
		 $payment_type = $data['payment_type'];
		 $request_date = date("Y-m-d");
		 
		 $check = DB::table('product_refund')
		          ->where('purchase_token','=', $purchase_token)
				  ->where('order_id','=', $order_id)
				  ->where('buyer_id','=', $buyer_id)
				  ->where('vendor_id','=', $vendor_id)
				  ->count();
				  
		if(empty($check))
		{
		
		   DB::insert('insert into product_refund (purchase_token,request_date,order_id,prod_id,payment_date,buyer_id,vendor_id,payment,payment_type,subject,message) values (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?)', [$purchase_token,$request_date,$order_id,$prod_id,$payment_date,$buyer_id,$vendor_id,$payment,$payment_type,$subjected,$messaged]);
		   
		   
		   
		 $url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.$setting[0]->site_logo;
		
		$site_name = $setting[0]->site_name;
		
		$currency = $setting[0]->site_currency;
		
		
		
		$vendor_details = DB::table('users')
		 ->where('id', '=', $vendor_id)
		 ->get();
		
		$vendor_email = $vendor_details[0]->email;
		$vendor_name = $vendor_details[0]->name;
		$vendor_slug = $vendor_details[0]->post_slug;
		
		
		$buyer_details = DB::table('users')
		 ->where('id', '=', $buyer_id)
		 ->get();
		
		$buyer_email = $buyer_details[0]->email;
		$buyer_name = $buyer_details[0]->name;
		$buyer_slug = $buyer_details[0]->post_slug;
		
		
		
		$product_details = DB::table('product')
		 ->where('prod_id', '=', $prod_id)
		 ->get();
		 
		 
		 $prod_slug = $product_details[0]->prod_slug;
		 $prod_name = $product_details[0]->prod_name;
		
		
		
		$aid=1;
		$admindetails = DB::table('users')
		 ->where('id', '=', $aid)
		 ->get();
		
		$admin_email = $admindetails[0]->email;	
		
            $datas = [
            'prod_id' => $prod_id, 'prod_name' => $prod_name, 'prod_slug' => $prod_slug, 'purchase_token' => $purchase_token, 'order_id' => $order_id, 'payment_date' => $payment_date, 'currency' => $currency, 'site_logo' => $site_logo, 'site_name' => $site_name, 'buyer_id' => $buyer_id, 'buyer_name' => $buyer_name, 'buyer_email' => $buyer_email, 'buyer_slug' => $buyer_slug, 'vendor_id' => $vendor_id, 'vendor_email' => $vendor_email, 'vendor_name' => $vendor_name, 'vendor_slug' => $vendor_slug, 'payment' => $payment, 'subjected' => $subjected, 'messaged' => $messaged, 'payment_type' => $payment_type, 'url' => $url
        ];
			
		/* vendor email */
		
			
		Mail::send('refund_email', $datas , function ($message) use ($admin_email,$vendor_email)
        {
            $message->subject('Cancellation & Refund Request');
			
            $message->from($admin_email,'Admin');

            $message->to($vendor_email);
        });    		
		
		/* vendor email */  		
		
		
		
		/* admin email */
		
            Mail::send('refund_email', $datas , function ($message) use ($admin_email,$vendor_email)
        {
            $message->subject('Cancellation & Refund Request');
			
            $message->from($admin_email,'Admin');

            $message->to($admin_email);			

        });    	
		
		/* admin email */ 		   
		  
	    return back()->with('success', 'Your cancellation & refund request has been sent');
		
            }
            else
            {

             return back()->with('error', 'Your request already sent. Please wait admin will send the confirmation');

            }	
	}	
	
	
	public function avigher_review_data(Request $request) 
	{
		$data = $request->all();
                $rating = $data['rating'];
		$review = $data['review'];
		 
		$user_id = $data['user_id'];
		$prod_id = $data['prod_id'];
		$product_order_id = $data['product_order_id'];
		 
		$check = DB::table('product_rating')
                            ->where('user_id','=', $user_id)
                            ->where('prod_id','=', $prod_id)
                            ->where('product_order_id','=', $product_order_id)
                            ->count();
				  
		if(empty($check))
		{
		    DB::insert('insert into product_rating (user_id,prod_id,product_order_id,rating,review) values (?, ?, ?, ?, ?)', [$user_id,$prod_id,$product_order_id,$rating,$review]);
		
		}
		else
		{
		   DB::update('update product_rating set rating="'.$rating.'",review="'.$review.'" where user_id="'.$user_id.'" and product_order_id="'.$product_order_id.'" and prod_id = ?', [$prod_id]);
		}	
		 
		return back()->with('success', 'Sua avalia&ccedil;&atilde;o foi registrada');	
	}
	
	
	public function avigher_view_orderdetails($ord_id,$user_id)
	{
	
	    $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();
		
		
		$viewcount = DB::table('product_orders')
		                ->where('ord_id', '=', $ord_id)
				->count();
		
        $viewproduct = DB::table('product_orders')
		                ->where('ord_id', '=', $ord_id)
				->get();
						 
		$data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting);				 
		
	    return view('view-orders')->with($data);	
	}	
	
	
	public function avigher_view_myorders() {
		
            $logged = Auth::user()->id;	 
            $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();		
      
            
            /* Future fixing to group up the orders like avigher_view_myshopping 
            $viewcount = DB::table('product_orders')
	                ->where('prod_user_id', '=', $logged)
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')
                        ->orderBy('purchase_token', 'desc')
                        ->groupBy('purchase_token','desc')
                        ->count();
		
            $viewproduct = DB::table('product_orders')
	                ->where('prod_user_id', '=', $logged)
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')
                        ->orderBy('purchase_token', 'desc')
                        ->groupBy('purchase_token','desc')
			->get();	
			
	   $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged);
		*/
            
            $logged = Auth::user()->id;	 
            $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();
            
            $user = DB::table('user')
                    ->where('id','=',$logged);
      
            $viewcount = DB::table('product_orders')
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')
	                ->where('prod_user_id', '=', $logged)
                        ->count();
		
            $viewproduct = DB::table('product_orders')
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')
	                ->where('prod_user_id', '=', $logged)
                        ->orderBy('ord_id','desc')
			->get();	
			
	   $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged,'user' => $user);
           
           
      return view('my-orders')->with($data);
   }
   
   
        /* See all the information and details of only one purchase */
	public function view_seller_order($purchase_token) {
	                           
            $logged = Auth::user()->id;	 
            $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();
            
            $user = DB::table('user')
                    ->where('id','=',$logged);
      
            $viewcount = DB::table('product_orders')
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '=', $purchase_token)
	                ->where('prod_user_id', '=', $logged)
                        ->count();
		
            $viewproduct = DB::table('product_orders')
                        ->join('product','product.prod_id', '=', 'product_orders.prod_id')
                        ->select('ord_id','product_orders.prod_id', 'subtotal','product_orders.user_id',
                                'prod_name', 'purchase_token', 'quantity','product_orders.price',
                                'product_orders.prod_attribute',DB::raw('SUM(total) as total'),
                                DB::raw('SUM(product_orders.shipping_price) as total_shipping')
                                )
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '=', $purchase_token)
	                ->where('prod_user_id', '=', $logged)
                        ->groupBy('ord_id')
                        ->orderBy('ord_id','desc')
			->get();
            
            
            $all_costs = DB::table('product_orders')
                    ->select(DB::raw('SUM(shipping_price) as full_shipping'),
                            DB::raw('SUM(total) as full_total'),
                            DB::raw('SUM(subtotal) as full_subtotal'))
                    ->where('order_status', '=', 'completed')
                    ->where('purchase_token', '=', $purchase_token)
	            ->where('prod_user_id', '=', $logged)
                    ->get();
                    
		//dd($all_costs);exit();	
	   $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged,'user' => $user, 'all_costs' => $all_costs);
           
           
      return view('view-seller-order')->with($data);
   }
   
   public function upload_nf(Request $request){  
        $data = $request->all();  
        $userid = Auth::user()->id; 
        $purchase_token = $data['hid_purchase_token'];
        
        $aid=1;
	$admindetails = DB::table('users')
            ->where('id', '=', $aid)
            ->get();
		
	$admin_email = $admindetails[0]->email;	
        
        $setid=1;
        $setting = DB::table('settings')->where('id', $setid)->get();
        $url = URL::to("/");
        $site_logo=$url.'/local/images/media/'.$setting[0]->site_logo;
        $site_name = $setting[0]->site_name;  
        
        $vendor = DB::table('users')
                    ->select('name')
                    ->where('id', '=', $userid)
                    ->get();  
        $vendor_name = $vendor[0]->name;
        
        $buyer = DB::table('users')
                    ->join('product_orders', 'user_id', '=', 'id')
                    ->select('full_name', 'email')
                    ->where('purchase_token','=',$purchase_token)
                    ->get();  
        
        $buyer_name = $buyer[0]->full_name;
        $buyer_email = $buyer[0]->email;
        
        /* NF Setting */
        $target_dir = "nfs/";
        $target_file = $target_dir . basename($_FILES["nf"]["name"]);
        $uploadOk = 1;
        
        $nf = '';
		if ($request->hasFile('nf')) {
                    $file = $request->file('nf');
                    
                    $newName = date('His',time()).rand(101, 999);
                    $nf = $newName.".".$file->getClientOriginalExtension();                    
                    $destinationPath = base_path('images/nfs/');
                    $file->move($destinationPath, $nf);                   
	}
        
        DB::update('update product_orders set nf="'.$nf.'" where prod_user_id='.$userid.' and purchase_token = ?', [$purchase_token]);
	
        
        /* envio email pesquisador */
	$email_info = ['site_logo' => $site_logo, 'site_name' => $site_name,'vendor_name' => $vendor_name,'buyer_name' => $buyer_name,'purchase_token' => $purchase_token];	
			
	Mail::send('nf_mail', $email_info , function ($message) use ($admin_email,$buyer_email)
        
        {
            $message->subject('Nota Fiscal Disponivel');
			
            $message->from($admin_email,'Admin');

            $message->to($buyer_email);
        });    		
		
		/* vendor email */  
        
        

        return redirect('my-orders')->with('success', 'NF Inserida e Enviada com sucesso!');
        
   }
   
   public function view_seller_orders_update(){
       DB::update('update product_orders set nf=1 where purchase_token="13357427"');
		
       return $this->view_seller_orders();
   }
   
    /** Marcello - Trim & Strip Special Characters & Make String Lower Case (transformar em funcao) **/
	public function normalizeString($normalizeTxt){
                $newTxt = str_replace(' ', '',mb_strtolower($normalizeTxt));
                $newTxt = str_replace('.', '',mb_strtolower($normalizeTxt));
                
                $search = explode(",","ç,æ,œ,á,ã,é,í,ó,õ,ú,à,è,ì,ò,ù,â,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
                $replace = explode(",","c,ae,oe,a,a,e,i,o,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");

                return str_replace($search, $replace, $newTxt);
        }


   /* List all the sellers orders by the purchase_token */
    public function view_seller_orders() {
		
            $logged = Auth::user()->id;	 
            $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();		
     
            
            $logged = Auth::user()->id;	 
            $set_id=1;
            $setting = DB::table('settings')->where('id', $set_id)->get();
            
            $user = DB::table('user')
                    ->where('id','=',$logged);

            $viewcount = DB::table('product_orders')
                        ->select(DB::raw('count(distinct(purchase_token)) as total_orders'))
                        ->where('prod_user_id', '=', $logged)
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')
                        ->get();                          
        
            
            $viewproduct = DB::table('product_orders')
                        ->select('user_id','nf','purchase_token',DB::raw('purchase_token, SUM(quantity) as quantity , SUM(total) as total , SUM(shipping_price) as shipping_price'))
                        ->where('prod_user_id', '=', $logged)
                        ->where('order_status', '=', 'completed')
                        ->where('purchase_token', '!=', '')	                
                        ->groupby('purchase_token')
                        ->orderby('ord_id','DESC')
                        ->paginate(15);	
            
        $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged,'user' => $user);
           
      return view('my-orders')->with($data);
   }
   
   public function avigher_view_mybalance()
   {
   
      $logged = Auth::user()->id;
		 
		 
		 $set_id=1;
		$site_setting = DB::table('settings')->where('id', $set_id)->get();
		
		$commission_mode = $site_setting[0]->commission_mode;
		$commission_amt = $site_setting[0]->commission_amt;
		
        	/************* FIRST STEP ************/	
				 
				$balance_count = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 0)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->count();
				 
				 
		if(!empty($balance_count))
		{
			$balance = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 0)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->get();
				 
				 
				 $shopping_id = $balance[0]->shop_id;
				 
			   $sum="";
				   foreach($balance as $sumvalue){ 
				   if($commission_mode=="percentage")
				   {
					   $commission_amount = ($commission_amt * $sumvalue->total_amount) / 100;
				   }
				   if($commission_mode=="fixed")
				   {
					    if($sumvalue->total_amount < $commission_amt)
						{
							$commission_amount = 0;
						}
						else
						{
							$commission_amount = $commission_amt;
						}
				   }
				   
				   $sum +=$sumvalue->total_amount - $commission_amount; }
				   
				   $sumvalue = $sum;
				   
		}
		else
		{
			$sumvalue = 0;
			$shopping_id = "";
		}
		
		
		
		/******************* SECOND STEP *************/
		
		$balance_count_two = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 2)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->count();
				 
				 
		if(!empty($balance_count_two))
		{
			$balance_two = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 2)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->get();
				 
				 $shopping_id = $balance_two[0]->shop_id;
				 
			   $sum="";
				   foreach($balance_two as $sumvalue_two){ 
				   if($commission_mode=="percentage")
				   {
					   $commission_amount = ($commission_amt * $sumvalue_two->total_amount) / 100;
				   }
				   if($commission_mode=="fixed")
				   {
					    if($sumvalue_two->total_amount < $commission_amt)
						{
							$commission_amount = 0;
						}
						else
						{
							$commission_amount = $commission_amt;
						}
				   }
				   
				   $sum +=$sumvalue_two->total_amount - $commission_amount; }
				   
				   $sumvalue_two = $sum;
				   
		}
		else
		{
			$sumvalue_two = 0;
			$shopping_id = "";
		}
		
		
		
		/********************* THIRD STEP ****************/
		
		
		$balance_count_third = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 1)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->count();
				 
				 
		if(!empty($balance_count_third))
		{
			$balance_third = DB::table('booking')
		           ->leftJoin('shop', 'shop.shop_id', '=', 'booking.shop_id')
				   ->where('shop.status', '=', 1)
				   ->where('booking.payment_approval', '=', 1)
				    ->where('booking.payment_status', '=', 'paid')
				   ->where('shop.user_id', '=', $logged)
				   ->orderBy('booking.book_id', 'desc')
				 ->get();
				 
				 $shopping_id = $balance_third[0]->shop_id;
				 
				 
			   $sum="";
				   foreach($balance_third as $sumvalue_third){ 
				   if($commission_mode=="percentage")
				   {
					   $commission_amount = ($commission_amt * $sumvalue_third->total_amount) / 100;
				   }
				   if($commission_mode=="fixed")
				   {
					    if($sumvalue_third->total_amount < $commission_amt)
						{
							$commission_amount = 0;
						}
						else
						{
							$commission_amount = $commission_amt;
						}
				   }
				   
				   $sum +=$sumvalue_third->total_amount - $commission_amount; }
				   
				   $sumvalue_third = $sum;
				   
		}
		else
		{
			$sumvalue_third = 0;
			$shopping_id = "";
		}		
		
		
		$withdraw_count = DB::table('withdraw')
		                 ->where('shop_id', '=', $shopping_id)
				         ->where('user_id', '=', $logged)
				         /*->where('withdraw_status', '=', 'completed')*/
				         ->count(); 
		
		if(!empty($withdraw_count))
		{
				$withdraw = DB::table('withdraw')
								 ->where('shop_id', '=', $shopping_id)
								 ->where('user_id', '=', $logged)
								/* ->where('withdraw_status', '=', 'completed')*/
								 ->get();
				$amount = "";
				foreach($withdraw as $draw)
				{
				   $amount += $draw->withdraw_amount;
				}
		
		}
		else
		{
		    $amount = 0;
			$withdraw = "";
		}
		
		
		
		$pending_withdraw_cnt = DB::table('withdraw')
		                 ->where('shop_id', '=', $shopping_id)
				         ->where('user_id', '=', $logged)
				         ->where('withdraw_status', '=', 'pending')
				         ->count(); 
				 
		$pending_withdraw = DB::table('withdraw')
		                 ->where('shop_id', '=', $shopping_id)
				         ->where('user_id', '=', $logged)
				         ->where('withdraw_status', '=', 'pending')
				         ->get(); 		 
		
		
		
		$complete_withdraw_cnt = DB::table('withdraw')
		                 ->where('shop_id', '=', $shopping_id)
				         ->where('user_id', '=', $logged)
				         ->where('withdraw_status', '=', 'completed')
				         ->count(); 
				 
		$complete_withdraw = DB::table('withdraw')
		                 ->where('shop_id', '=', $shopping_id)
				         ->where('user_id', '=', $logged)
				         ->where('withdraw_status', '=', 'completed')
				         ->get(); 		 		  
				 
		
		$data=array('site_setting' => $site_setting, 'sumvalue' => $sumvalue, 'balance_count' => $balance_count, 'sumvalue_two' => $sumvalue_two, 'balance_count_two' => $balance_count_two, 'sumvalue_third' => $sumvalue_third, 'balance_count_third' => $balance_count_third, 'amount' => $amount, 'withdraw_count' => $withdraw_count, 'withdraw' => $withdraw, 'pending_withdraw_cnt' => $pending_withdraw_cnt, 'pending_withdraw'=> $pending_withdraw, 'complete_withdraw_cnt' => $complete_withdraw_cnt, 'complete_withdraw' => $complete_withdraw);
		 
		
      return view('my-balance')->with($data);   
   }  
   
   
   public function avigher_myshopping($token)
   {
   
      $logged = Auth::user()->id;
		 
		 
		$set_id=1;
		$setting = DB::table('settings')->where('id', $set_id)->get();	
		
		$viewcount = DB::table('product_orders')
		                ->where('user_id', '=', $logged)
				->where('purchase_token', '=', $token)
				->count();
		
        $viewproduct = DB::table('product_orders')
		                ->where('user_id', '=', $logged)
				->where('purchase_token', '=', $token)
				->get();
				 
        /* verifica se ja foi emitida a nota fiscal */
        $nf = '0';
        foreach($viewproduct as $product){
            if($product->nf != 0 && $product->nf != ''){
                $nf = $product->nf;
                break;
            }                      
        }
	   
   $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged,'nf' => $nf);
	   
	   return view('view-shopping')->with($data);
   }       
   
   
   public function avigher_view_myshopping()
   {
	$logged = Auth::user()->id;		 
		 
	$set_id=1;
	$setting = DB::table('settings')->where('id', $set_id)->get();
		
	$viewcount = DB::table('product_checkout')
	                ->where('user_id', '=', $logged)
                        ->orderBy('cid','desc')
			->count();
		
        $viewproduct = DB::table('product_checkout')
		        ->where('user_id', '=', $logged)
			->orderBy('cid','desc')
			->get();
					
	   $data=array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'setting' => $setting, 'logged' => $logged);
	   
	   return view('my-shopping')->with($data);
   }
   
   
   
  public function avigher_destroy($id) {	
	  
      DB::delete('delete from booking where book_id = ?',[$id]);	   
      return back();      
   }	
}