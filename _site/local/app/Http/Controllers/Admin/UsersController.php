<?php

namespace Responsive\Http\Controllers\Admin;

use File;
use Image;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index() {
        $users = DB::table('users')
                ->where('admin', '=', 0)
                ->where('delete_status','=','')
                ->orderBy('id', 'desc')
                ->get();

        $users_cnt = DB::table('users')
                ->where('admin', '=', 0)
                ->orderBy('id', 'desc')
                ->get();

        $setid = 1;
        $setts = DB::table('settings')
                ->where('id', '=', $setid)
                ->get();

        return view('admin.users', ['users' => $users, 'users_cnt' => $users_cnt, 'setts' => $setts]);
    }

    public function vendor_index() {
        
        $users = DB::table('users')
                ->where('admin', '=', 2)
                ->Where(function ($query) {
                    $query->where('delete_status', '=', '')
                    ->orwhere('delete_status', '=', 'blocked')
                    ->orwhere('delete_status', '=', 'inactive');
                })
                ->orderBy('id', 'desc')
                ->get();

        $users_cnt = DB::table('users')
                ->where('admin', '=', 2)
                ->Where(function ($query) {
                    $query->where('delete_status', '=', '')
                    ->orwhere('delete_status', '=', 'blocked')
                    ->orwhere('delete_status', '=', 'inactive');
                })
                ->orderBy('id', 'desc')
                ->get();


        $prod_cnt_inactive = DB::select(
                        'select count(*) as qtd_prod_inactive,user_id,name from users, product '
                        . 'where admin= :ad and user_id = id and product.delete_status="inactive" 
                            group by user_id', ['ad' => 2]);

        $setid = 1;
        $setts = DB::table('settings')
                ->where('id', '=', $setid)
                ->get();

        return view('admin.vendors', ['users' => $users, 'users_cnt' => $users_cnt, 'setts' => $setts, 'prod_cnt_inactive' => $prod_cnt_inactive]);
    }

    protected function delete_all(Request $request) {

        $data = $request->all();
        $userid = $data['userid'];

        foreach ($userid as $uid) {

            DB::delete('delete from post where post_type="comment" and post_user_id!=1 and post_user_id = ?', [$uid]);
            DB::update('update users set delete_status="deleted" where id!=1 and id = ?', [$uid]);
        }

        return back();
    }

    public function destroy($id) {

        /* Inicialize Variables */
        $tot_product = 0;
        $tot_complete_order = 0;
        $tot_incomplete_order = 0;
        $total_payment = 0;
        $payment_detais = 'N/A';
        
        /* Getting the current time of the deleted users */
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('Y-m-d h:i:s', time());

        $user = DB::table('users')
                ->where('id', '=', $id)
                ->get(); 
        
        $product_ord = DB::table('product_orders')
                ->where('user_id', '=', $id)
                ->get(); 
        
        foreach( $product_ord as $prod){
            $tot_product++;
            if($prod->payment_status == 'completed'){
                $tot_complete_order++;
            }else if($prod->payment_status == 'pending'){
                $tot_incomplete_order++;
            }
            $total_payment = $total_payment + $prod->total;            
        }    
        

        /* Saving the Data in the History Table */ 
        DB::insert('insert into history_deleted_users (h_name, h_type_user, h_comission_percentage, h_created_at, '
                . 'h_deleted_at, h_tot_product, h_tot_complete_ord, h_tot_incomplete_ord, h_total_payment, h_payment_details) '
                . 'values (?,?,?,?,?,?,?,?,?,?)', [$user[0]->name, $user[0]->admin, $user[0]->comission_percentage, $user[0]->created_at,
                    $date, $tot_product,$tot_complete_order,$tot_incomplete_order,$total_payment,$payment_detais]); 
             
        /** Deletando Produtos do Fornecedor * */
        DB::delete('delete from post where post_type="comment" and post_user_id = ?', [$id]);
       
        /*
        DB::delete('delete from users where id = ?', [$id]);
        DB::delete('delete from product where user_id = ?', [$id]);
        DB::delete('delete from product_billing_shipping where user_id = ?', [$id]);
        DB::delete('delete from product_checkout where user_id = ?', [$id]);
        DB::delete('delete from product_compare where user_id = ?', [$id]);
        DB::delete('delete from wishlist where user_id = ?', [$id]);
        
        DB::delete('delete from product_rating where user_id = ?', [$id]); // if buyer
        DB::delete('delete from product_rating where prod_id = ?', [$id]); // if seller
        DB::delete('delete from product_orders where user_id = ?', [$id]); // if buyer
        DB::delete('delete from product_orders where prod_id = ?', [$id]); // if seller
        DB::delete('delete from waiting_list where user_id = ?', [$id]); // if buyer
        DB::delete('delete from waiting_list where prod_user_id = ?', [$id]); // if seller
        
        DB::update('update product set delete_status="deleted" where user_id!=1 and user_id = ?', [$id]);
        DB::update('update users set delete_status="deleted" where id!=1 and id = ?', [$id]);
        */
        
        /* ToDo : Delete Images ( product_images ) using the prod_token x (product) - Use foreach */
        return back();
    }

    /** Marcello :: Check the Status of the Seller or Block Sellers 
    * 
    * Types { blocked , active, inactive }
    * 0 : [status] >> Blocked - Seller is not allow to sell nor to be seen by the client
    * 1 : [status] >> Authorized - Seller is allowed to sell in the market place and products are invisible
    * 2 : [delete_status] >> Active - Products of the Seller are Invisible  ( Store is closed temporary )
    * 3 : [delete_status] >> Inactive - Products of the Seller are Visible
    * 
    * **/
  public function authorizeSeller($id, $type) {

        switch ($type) {
            case 0:
                $sql_user = 'update users set delete_status = "blocked" where id!=1 and id = ?';
                $sql_product = 'update product set prod_status=0 , delete_status = "active" where user_id!=1 and user_id = ?';
                break;
            case 1:
                $sql_user = 'update users set delete_status="" where id!=1 and id = ?';
                $sql_product = 'update product set prod_status=1 , delete_status = "" where user_id!=1 and user_id = ?';
                break;
            case 2:
                // $sql_user = 'update users set delete_status="active" where id!=1 and id = ?';
                // $sql_product = 'update product set prod_status=1 and delete_status = "inactive" where user_id!=1 and user_id = ?';
                break;
            case 3:
                // $sql_user = 'update users set delete_status="" where id!=1 and id = ?';
                // $sql_product = 'update product set prod_status=1 and delete_status = "inactive" where user_id!=1 and user_id = ?';
                break;
        }
        /** Autorizando o Fornecedor * */
        DB::update($sql_user, [$id]);

        DB::update($sql_product, [$id]);
        //DB::update('update product set prod_status=1 and delete_status = "inactive" where user_id!=1 and user_id = ?',[$id]);
        //DB::update('update users set delete_status="" where id!=1 and id = ?',[$id]);

        return back();
    }

}