<?php

namespace Responsive\Http\Controllers;

use Mockery\Exception;
use Moip\Exceptions\ValidationException;
use Moip\Resource\Customer;
use Moip\Resource\Holder;
use Moip\Resource\Multiorders;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class NonAuthWirecardController extends Controller
{

    protected function wirecard_log_update_pay($id,$status){
        DB::update('update wirecard_payment set status="'.$status.'" where payment_id = ?', [$id]);
    }
    protected function wirecard_log_update_pay_escrow($id,$status){
        DB::update('update wirecard_payment set escrow_status="'.$status.'" where escrow_id = ?', [$id]);
    }

    protected function wirecard_order_update_pay($id,$status){
        DB::update('update product_orders set payment_status="'.$status.'" where payment_id = ?', [$id]);
    }
    protected function wirecard_webhook(Request $request){
        header("HTTP/1.1 200 OK");
        if(Input::server("REQUEST_METHOD") == "POST"){

            $data = $request->all();
            @file_put_contents(dirname(__FILE__).'/log3.txt',print_r($data,true));
            if (isset($data['event'])) {
                $event = $data['event'];
                if ($event === "MULTIPAYMENT.AUTHORIZED") {
                    $multipaymentID = $data['resource']['payment']['id'];
                    $multipaymentStatus = $data['resource']['payment']['status'];
                                $this->wirecard_log_update_pay($multipaymentID, $multipaymentStatus);
                    $payments = $data['resource']['payment']['payments'];
                    if (!empty($payments)) {
                        foreach ($payments as $payment) {
                            if ($payment['status'] == "AUTHORIZED") {
                                $this->wirecard_log_update_pay($payment['id'], $payment['status']);
                                $this->wirecard_order_update_pay($payment['id'], 'completed');
                            }
                        }
                    }
                    DB::table('product_checkout')
                        ->where('payment_token', '=', $multipaymentID)
                        ->where('payment_status', '=', 'pending')
                        ->update(array('payment_status' => 'completed'));
                }
            }
        }
    }
}