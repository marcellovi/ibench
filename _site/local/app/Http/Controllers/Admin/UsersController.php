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

        // Marcello :: Diferenciar entre Comprador e Fornecedor

        /** Deletando Produtos do Fornecedor * */
        DB::update('update product set delete_status="deleted" where user_id!=1 and user_id = ?', [$id]);

        DB::delete('delete from post where post_type="comment" and post_user_id = ?', [$id]);

        DB::update('update users set delete_status="deleted" where id!=1 and id = ?', [$id]);

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
        // print_r($sql_product);exit();

        return back();
    }

}