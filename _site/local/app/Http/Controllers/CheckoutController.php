<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Auth;
use File;
use Image;
use Mail;
use Illuminate\Support\Facades\Validator;
use Responsive\Http\Requests;

class CheckoutController extends Controller
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
    
	public function avigher_update_cart(Request $request)
	{	
	   $data = $request->all();  
          
	   $log_id = Auth::user()->id;
	   
	   $shipping_charge = $data['shipping_charge'];
	   $prod_user_id = $data['prod_user_id'];
	   
	   $local_shipping_price = 0;
	   $world_shipping_price = 0;
	   
	   $local_shipping_separate = "";
	   $world_shipping_separate = "";
           
           // Marcello : Created to organize shipping and users (fornecedor)
           $view_prod_user = [];  
           $view_prod_user_id = []; 
           
           // Marcello : Loop for not to repeat the same prod_user (fornecedor)
           foreach($prod_user_id as $id)
	   {
               $view_user = DB::table('users')
                                ->where('id', '=', $id)
                                ->get();
              
               if(empty($view_prod_user)){
                   $view_prod_user[] = $view_user;
                   array_push($view_prod_user_id,$view_user[0]->id);  
                   
                    $local_shipping_price += $view_user[0]->local_shipping_price;
		    $world_shipping_price += $view_user[0]->world_shipping_price;
                    
                    $local_shipping_separate .= $view_user[0]->local_shipping_price.',';
		    $world_shipping_separate .= $view_user[0]->world_shipping_price.',';
                  
               }else 
                   if(!in_array ($view_user[0]->id, $view_prod_user_id)){
                       
                       $local_shipping_price += $view_user[0]->local_shipping_price;
                       $world_shipping_price += $view_user[0]->world_shipping_price;
                       
                       $local_shipping_separate .= $view_user[0]->local_shipping_price.',';
		       $world_shipping_separate .= $view_user[0]->world_shipping_price.',';
                  
                       array_push($view_prod_user,$view_user);  
                       array_push($view_prod_user_id,$view_user[0]->id);                        
               } else{ 
                       // Marcello : When it's the same you dont add with anything
                       $local_shipping_price += 0;
                       $world_shipping_price += 0;
                       
                       $local_shipping_separate .= '0,';
		       $world_shipping_separate .= '0,';
               }              
               
           } 
           
           /* Original
	   foreach($prod_user_id as $id)
	   {
               
	      $view_user = DB::table('users')
		               ->where('id', '=', $id)
                       ->get();            
                
		  $local_shipping_price += $view_user[0]->local_shipping_price;
		  $world_shipping_price += $view_user[0]->world_shipping_price;	
				  
		  $local_shipping_separate .= $view_user[0]->local_shipping_price.',';
		  $world_shipping_separate .= $view_user[0]->world_shipping_price.',';
	   }
           **/
	   
	   if($shipping_charge == "local_shipping")
	   {
	      $ship_price = $local_shipping_price;
		  $ship_separate = rtrim($local_shipping_separate,',');
	   }
	   else if($shipping_charge == "world_shipping")
	   {
	      $ship_price = $world_shipping_price;
		  $ship_separate = rtrim($world_shipping_separate,',');
	   }
		/*
		 if($available > $quantity)
		   {
		      
			   DB::update('update product_orders set quantity="'.$quantity.'" where user_id="'.$log_id.'" and status="pending" and ord_id = ?', [$order_id]);
			  
		   }
		   else
		   {
		      return back()->with('error', 'Please check available stock!'); 
		   }
		*/
		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();	
		
		$countries = array('Brazil');
		
		$login_user_count = DB::table('product_checkout')
                                ->where('user_id', '=', $log_id)
                                ->count();
		
		
		$login_user = DB::table('product_checkout')
                                ->where('user_id', '=', $log_id)
                                ->get();
		

		//$prod_ids = DB::table('product_orders')
		//               ->whereIn('ord_id', explode(",",$data['order_ids']))
                //               ->pluck('prod_id');

                $prod_ords = DB::table('product_orders')
                                ->whereIn('ord_id', explode(",",$data['order_ids']))
                                ->get();

		$check_qty = 0;
		foreach($prod_ords as $prod_ord){
			$prod = DB::table('product')
                         ->where('prod_id', '=', $prod_ord->prod_id)
						 ->get();

                    if($prod[0]->prod_available_qty < $prod_ord->quantity){
                                 $check_qty = 1;
                    }
		}
		
		$cart_total = $data['cart_total'];
		$processing_fee = $data['processing_fee'];
		$order_ids = $data['order_ids'];  
		
		$product_names = $data['product_names'];
                
                // Will be used on the Boleto Payment
                $listcompanies = $data['companies'];
                
                $data = array('listcompanies' => $listcompanies,'ship_price' => $ship_price, 'ship_separate' => $ship_separate, 'setts' => $setts, 'login_user_count' => $login_user_count, 'login_user' => $login_user,  'countries' => $countries, 'processing_fee' => $processing_fee, 'cart_total' => $cart_total, 'order_ids' => $order_ids, 'product_names' => $product_names,'check_qty_ord' => $check_qty);
	 
	   return view('checkout')->with($data);
	   
	   /*return redirect()->back()->with(['data' => $data]);*/ 
	}
	
	public function avigher_remove_cart($token)
	{	
	   DB::delete('delete from product_orders where ord_id = ?',[$token]);
	   return back();
	}	
	
	
	public function avigher_view_checkout()
	{
	   if(Auth::check()) {
	   $log_id = Auth::user()->id;
	   
	   $cart_views_count = DB::table('product_orders')		
		->where('user_id', '=', $log_id)
		->where('status', '=', 'pending')		
		->count();
	   
	   
	   $cart_views = DB::table('product_orders')		
		->where('user_id', '=', $log_id)
		->where('status', '=', 'pending')		
		->get();
		
		}
		else
		{
		$cart_views_count = 0;
		$cart_views = "";
		
		}
		
		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
		$admin_details = DB::table('users')
						 ->where('id', '=', 1)
						 ->get();
	   
	    $data = array('cart_views_count' => $cart_views_count, 'cart_views' => $cart_views, 'setts' => $setts, 'admin_details' => $admin_details);
	   
	   return view('checkout')->with($data);
	}
	

	public function avigher_view_no_checkout()
	{	
            return redirect('/404');	
	}	

}
