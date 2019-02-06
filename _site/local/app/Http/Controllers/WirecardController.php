<?php
namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Mockery\Exception;
use Moip\Auth\Connect;
use Moip\Auth\OAuth;
use Moip\Exceptions\ValidationException;
use Moip\Moip;
use Moip\Resource\Customer;
use Moip\Resource\Holder;
use Moip\Resource\Multiorders;
use Responsive\Http\Requests;

/**
 * This Include the wirecard SDK to Wirecard laravel Controller
 */
require dirname(__DIR__).'/../moip-sdk/vendor/autoload.php';

class WirecardController extends Controller
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
     * This Function check if wirecard is in test or live mode
     *
     * @return bool
     */
    protected function is_test_mode(){
        return ($this->settings()->wirecard_mode == 'test') ? true : false;
    }
    /**
     * This Function get system settings
     *
     * @return mixed
     */
    protected function settings(){
        return DB::table('settings')
            ->where('id', '=', 1)
            ->get()[0];
    }

    /**
     * This Function check if the payment is completed or not
     *
     * @param $purchase_token
     * @return bool
     */
    protected function is_completed($purchase_token){
        $product_checkout = DB::table('product_checkout')
            ->where('purchase_token', '=', $purchase_token)
            ->where('payment_token', '!=', '')
            ->count();
        return (!empty($product_checkout)) ? true : false;
    }

    /**
     * This Function get payment ID
     *
     * @param $purchase_token
     * @param string $payment_token
     * @return mixed
     */
    protected function get_payment_id($purchase_token,$payment_token = 'payment_token'){
        $product_checkout = DB::table('product_checkout')
            ->where('purchase_token', '=', $purchase_token)
            // ->where('payment_status', '=', 'completed')
            ->get();
        return $product_checkout[0]->$payment_token;
    }

    /**
     * This Function get order data
     *
     * @param int $id
     * @return mixed
     */
    protected function order($id = 0){
        return DB::table('product_orders')
            ->where('ord_id', '=', $id)
            ->get()[0];
    }
    /**
     * This Function get order data
     *
     * @param string $id
     * @return mixed
     */
    protected function get_order($id = ""){
        return DB::table('product_orders')
            ->where('payment_id', '=', $id)
            ->get()[0];
    }

    /**
     * This Function get user data
     *
     * @param int $id
     * @return mixed
     */
    protected function user($id = 0){
        return DB::table('users')
            ->where('id', '=', $id)
            ->get()[0];
    }

    /**
     * This Function get product data
     *
     * @param int $id
     * @return mixed
     */
    protected function product($id = 0){
        return DB::table('product')
            ->where('prod_id', '=', $id)
            ->get()[0];
    }

    /**
     * This Function check if all necessary Wirecard information has been setup in the website
     *
     * @param bool $app
     * @return bool
     */
    protected function is_gateway_ready($app = false){
        return (($app || $this->settings()->wirecard_app_data != null) && $this->settings()->wirecard_mode != null && $this->settings()->wirecard_auth_token != null && $this->settings()->wirecard_auth_key != null) ? true : false;
    }

    /**
     * This Function get site wirecard app data
     *
     * @return array|mixed
     */
    protected function wirecard_app_data(){
        return ($this->is_gateway_ready()) ? unserialize($this->settings()->wirecard_app_data) : array();
    }

    /**
     * This Function Process the order after payment AUTHORIZED or WAITING
     *
     * @param array $params
     */
    protected function complete_order($params = array()){
        $params = array_merge(
            array(
                'order_id' => '',
                'purchase_token' => '',
                'wirecard_payment_token' => '',
                'wirecard_boleto_href' => '',
                'wirecard_boleto_print_href' => '',
                'status' => 'completed',
                'multipayment_id' => '',
                'multipayments' => array(),
                'escrow' => array(),
            ),
            $params
        );
        $order_id = $params['order_id'];
        $purchase_token = $params['purchase_token'];
        $wirecard_payment_token = $params['wirecard_payment_token'];
        $wirecard_boleto_href = $params['wirecard_boleto_href'];
        $wirecard_boleto_print_href = $params['wirecard_boleto_print_href'];
        $status = $params['status'];
        $multipayment_id = $params['multipayment_id'];
        $multipayments = $params['multipayments'];
        $escrow = $params['escrow'];


        $orderupdate = DB::table('product_orders')
            ->where('purchase_token', '=', $purchase_token)
            ->where('order_status', '=', 'pending')
            ->update(array('order_status' => 'completed', 'payment_status' => $status, 'payment_token' => $wirecard_payment_token, 'payment_type' => 'wirecard'));

        $checkoutupdate = DB::table('product_checkout')
            ->where('purchase_token', '=', $purchase_token)
            ->where('payment_status', '=', 'pending')
            ->update(array('payment_status' => $status, 'payment_token' => $wirecard_payment_token,'wirecard_boleto_href' => $wirecard_boleto_href,'wirecard_boleto_print_href' => $wirecard_boleto_print_href));


        $get_viewr = DB::table('product_orders')
            ->where('purchase_token', '=', $purchase_token)
            ->where('order_status', '=', 'completed')
            ->count();


        $view_orders = DB::table('product_orders')
            ->where('purchase_token', '=', $purchase_token)
            ->where('order_status', '=', 'completed')
            ->get();


        foreach ($view_orders as $key => $views) {
            $ord_id = $views->ord_id;
            $subtotal = $views->quantity * $views->price;
            $total = $subtotal + $views->shipping_price;
            DB::update('update product_orders set subtotal="' . $subtotal . '",total="' . $total . '",escrow_id="'.$escrow[$key]['id'].'",payment_id="'.$multipayments[$key]['id'].'" where order_status="completed" and ord_id = ?', [$ord_id]);
        }

        if (!empty($get_viewr)) {
            $get_stock = DB::table('product_orders')
                ->where('purchase_token', '=', $purchase_token)
                ->where('order_status', '=', 'completed')
                ->get();

            foreach ($get_stock as $stocker) {
                $checker_get = DB::table('product')
                    ->where('prod_id', '=', $stocker->prod_id)
                    ->get();

                $stock_value = $stocker->quantity;
                $count_qty = $checker_get[0]->prod_available_qty - $stock_value;
                DB::update('update product set prod_available_qty="' . $count_qty . '" where prod_status="1" and prod_id = ?', [$stocker->prod_id]);
//                        DB::update('update product set prod_available_qty="' . $count_qty . '" where prod_status="1" and parent = ?', [$stocker->prod_id]);

            }
        }

        $get_details = DB::table('product_checkout')
            ->where('purchase_token', '=', $purchase_token)
            ->get();

        $user_details = $this->user($get_details[0]->user_id);

        $name = $user_details->name;
        $email = $user_details->email;
        $phone = $user_details->phone;
        $amount = $get_details[0]->total;

        $url = URL::to("/");

        $site_logo = $url . '/local/images/media/'.'logo_email.jpg'; // Marcello $this->settings()->site_logo;
        $site_name = $this->settings()->site_name;

        $admindetails = $this->user(1);
        $admin_email = $admindetails->email;
        $datas = array(
            'site_logo' => $site_logo, 'site_name' => $site_name, 'name' => $name, 'email' => $email, 'phone' => $phone, 'amount' => $amount, 'url' => $url, 'order_id' => $purchase_token
        );

        Mail::send('shop_email', $datas, function ($message) use ($admin_email, $email) {
            $message->subject('Pedido Realizado com Sucesso');
            $message->from($admin_email, 'iBench Market');
            $message->to($admin_email);

        });


        Mail::send('shop_email', $datas, function ($message) use ($admin_email, $email) {
            $message->subject('Pedido Realizado com Sucesso');
            $message->from($admin_email, 'iBench Market');
            $message->to($email);

        });

        foreach ($view_orders as $views2) {
            $user_details2 = (array)$this->user($views2->prod_user_id);
            $email_seller = $user_details2['email'];
            $datas2 = array(
                'site_logo' => $site_logo, 'site_name' => $site_name, 'name' => $name, 'email' => $email, 'phone' => $phone, 'amount' => $views2->total, 'url' => $url, 'order_id' => $purchase_token
            );

            Mail::send('shop_email', $datas2, function ($message) use ($admin_email, $email_seller) {
                $message->subject('Pedido Realizado com Sucesso');
                $message->from($admin_email, 'iBench Market');
                $message->to($email_seller);
            });
        }
    }

    /**
     * This Function used to create order send to Wirecard
     *
     * @param Moip $moipMerchant
     * @param Multiorders|null $multiorder
     * @param Customer|null $customer
     * @param Holder|null $holder
     * @param null|array $error
     */
    protected function create_order(Moip $moipMerchant,Multiorders &$multiorder=null,Customer &$customer=null,Holder &$holder=null,&$error=null)
    {
        $user = Auth::user();
        try {
            // Creating an object customer to orders
            $customer->setOwnId(uniqid())
                ->setFullname($user->name)
                ->setEmail($user->email)
                ->setTaxDocument($user->cpf_cnpj)
                ->setPhone(11, @substr(@$_POST['bill_phone'], -9))
                ->addAddress('BILLING',
                    @$_POST['bill_address'], @substr(@$_POST['bill_phone'], -6),
                    @$_POST['bill_city'], @$_POST['bill_city'], @$_POST['bill_state'],
                    @$_POST['bill_postcode'], null, @$_POST['bill_country'])
                ->addAddress('SHIPPING',
                    @$_POST['bill_address'], @substr(@$_POST['bill_phone'], -6),
                    @$_POST['bill_city'], @$_POST['bill_city'], @$_POST['bill_state'],
                    @$_POST['bill_postcode'], null, @$_POST['bill_country']);
            // Creating an object customer to orders
            $holder
                ->setFullname(@$_POST['cc_holder'])
                ->setTaxDocument('')
                ->setPhone(11, @substr(@$_POST['bill_phone'], -9));

            // Creating an multiorder and setting receiver for each order with `addReceiver` method
            $multiorder->setOwnId(uniqid());

            $ordersList = explode(',', $_POST['order_id']);
            $shipFeeList = explode(',', $_POST['shipping_fee_separate']);
            
            // Verificador QuatroG Variavel 
            // Quando for true e' por que ja inseriu o shipping entao nao precisa fazer novamente para nao incrementar
            $quatroG = false;
            $quatroGShipping = 27500; // Valor de 275 reais
            
            foreach ($ordersList as $key => $item) {
                $order_details = (array)$this->order($item);
                $product_details = (array)$this->product($order_details['prod_id']);
                $user_details = (array)$this->user($order_details['prod_user_id']);
                if ($user_details['wirecard_app_data'] != null) {
                    /** QuartoG - Marcello Frete Customizado **/
                    if($order_details['prod_user_id']==113){
                        
                        // Se true ja foi incluido o frete
                        if(!$quatroG){
                            $user_wirecard_app_data_array = unserialize($user_details['wirecard_app_data']);
                    $order = $moipMerchant->orders()->setOwnId(uniqid())
                        ->addItem($product_details['prod_name'], $order_details['quantity'], @substr(@strip_tags($product_details['prod_desc']), 0, 100), (int) $order_details['price'] * 100, null)
                        ->setShippingAmount($quatroGShipping)
                        ->setCustomer($customer)
                        ->addReceiver($this->settings()->wirecard_acc_id, 'PRIMARY', null, (int)$user_details['comission_percentage'])
                        ->addReceiver($user_wirecard_app_data_array['moipAccount']->id, 'SECONDARY', null, (100 - (int)$user_details['comission_percentage']));
                    $multiorder->addOrder($order); 
                        $quatroG = true;
                        }else{
                            $user_wirecard_app_data_array = unserialize($user_details['wirecard_app_data']);
                    $order = $moipMerchant->orders()->setOwnId(uniqid())
                        ->addItem($product_details['prod_name'], $order_details['quantity'], @substr(@strip_tags($product_details['prod_desc']), 0, 100), (int) $order_details['price'] * 100, null)
                        ->setShippingAmount(0)
                        ->setCustomer($customer)
                        ->addReceiver($this->settings()->wirecard_acc_id, 'PRIMARY', null, (int)$user_details['comission_percentage'])
                        ->addReceiver($user_wirecard_app_data_array['moipAccount']->id, 'SECONDARY', null, (100 - (int)$user_details['comission_percentage']));
                    $multiorder->addOrder($order); 
                        }
                        
                    } /** Fim -- Abaixo Original -- **/
                    else{
                       $user_wirecard_app_data_array = unserialize($user_details['wirecard_app_data']);
                       // Marcello - ( Associar o Numero do Pedido do Minhas Compras com a do Dashboard do Wirecard )
                    $order = $moipMerchant->orders()->setOwnId($order_details['purchase_token'])
                        ->addItem($product_details['prod_name'], $order_details['quantity'], @substr(@strip_tags($product_details['prod_desc']), 0, 100), (int) $order_details['price'] * 100, null)
                        ->setShippingAmount((int) @$shipFeeList[$key] * 100)
                        ->setCustomer($customer)
                        ->addReceiver($this->settings()->wirecard_acc_id, 'PRIMARY', null, (int)$user_details['comission_percentage'])
                        ->addReceiver($user_wirecard_app_data_array['moipAccount']->id, 'SECONDARY', null, (100 - (int)$user_details['comission_percentage']));
                    $multiorder->addOrder($order); 
                    }
                    
                    
                }
            }
            $create_order = $multiorder->create();
        } catch (\Moip\Exceptions\UnautorizedException $e) {
            $error[] = $e->__toString();
        } catch (ValidationException $e) {
            $error[] = $e->__toString();
        } catch (\Moip\Exceptions\UnexpectedException $e) {
            $error[] = $e->__toString();
        }
    }

    /**
     * This Function use to Process the Credit Card Payment
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function api_cc(Request $request){

        $_POST = $request->all();

        if ($this->is_gateway_ready()) {
            $wirecard_app_data_array = $this->wirecard_app_data();

            $token = $wirecard_app_data_array['accessToken'];
            $moipMerchant = new Moip(new OAuth($token), $this->is_test_mode() ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION);

            $user = Auth::user();
            $wirecard_payment_token = "";
            $purchase_token = $_POST['order_no'];
            $order_id = $_POST['order_id'];
            $payment_type = $_POST['payment_type'];
            $success = false;
            $status = 'cancelled';
            $multi_status = '';
            $multi_amount = '';
            if ($this->is_completed($purchase_token) === true){
                return view('wirecard-shop-success')->with(array_merge(
                    @$_POST,
                    array(
                        'wirecard_payment_token' => $this->get_payment_id($purchase_token),
                        'settings' => $this->settings()
                    )
                ));
            } else {
                $errors = array();
                $escrow = array();
                $multipayments = array();
                try {
                    $customer = $moipMerchant->customers();
                    $holder = $moipMerchant->holders();
                    $multiorder = $moipMerchant->multiorders();

                    $this->create_order($moipMerchant,$multiorder,$customer,$holder,$error);
                    // Creating multipayment to multiorder
                    $multipayment = $multiorder->multipayments()
                        ->setCreditCard(trim($_POST['cc_exp_m']), @substr(trim($_POST['cc_exp_y']),-2), trim($_POST['cc_number']), trim($_POST['ccv']), $holder)
                        ->setEscrow($wirecard_app_data_array['name'])
                        ->setStatementDescriptor($wirecard_app_data_array['name'])
                        ->execute();

                    $wirecard_payment_token = $multipayment->getId();
                    $multi_status = $multipayment->getStatus();
                    $multi_amount = $multipayment->getAmount()->total;

                    if ($multipayment->getStatus() == 'AUTHORIZED') {
                        $status = 'completed';
                        $success = true;
                    } elseif ($multipayment->getStatus() == 'WAITING') {
                        $status = 'pending';
                        $success = true;
                    } elseif ($multipayment->getStatus() == 'PRE_AUTHORIZED') {
                        $status = 'pending';
                        $success = true;
                    }
                    foreach ($multipayment->getPayments() as $payment) {
                        $a = array(
                            'id' => $payment->escrows[0]->id,
                            'status' => $payment->escrows[0]->status,
                            'amount' => $payment->escrows[0]->amount
                        );
                        $escrow[] = $a;
                        $b = array(
                            'id' => $payment->id,
                            'status' => $payment->status,
                            'amount' => $payment->amount->total
                        );
                        $multipayments[] = $b;
                    }

                    if ($error != null){
                        $errors = $error;
                    }

                } catch (\Moip\Exceptions\UnautorizedException $e) {
                    $errors[] = $e->__toString();
                } catch (ValidationException $e) {
                    $errors[] = $e->__toString();
                } catch (\Moip\Exceptions\UnexpectedException $e) {
                    $errors[] = $e->__toString();
                } catch (\Exception $e){
                    $errors[] = $e->getMessage();
                }

                if ($success === true) {

                    // if true complete the order with the above status

                    $params = array(
                        'order_id' => $order_id,
                        'purchase_token' => $purchase_token,
                        'wirecard_payment_token' => $wirecard_payment_token,
                        'wirecard_boleto_href' => '',
                        'wirecard_boleto_print_href' => '',
                        'status' => $status,
                        'multipayment_id' => $wirecard_payment_token,
                        'multipayments' => $multipayments,
                        'escrow' => $escrow,
                        'multi_status' => $multi_status,
                        'multi_amount' => $multi_amount,
                    );

                    $this->complete_order($params);
                    $this->wirecard_log_pay($params);
                    return view('wirecard-shop-success')->with(array_merge(
                        @$_POST,
                        array(
                            'wirecard_payment_token' => $wirecard_payment_token,
                            'settings' => $this->settings()
                        )
                    ));
                } else {
                    // else show cancel to user
                    return view('wirecard-cancel')->with(array(
                        'settings' => $this->settings(),
                        'reason' => implode("\n\n", $errors)
                    ));
                }
            }
        } else {
            // if gateway is not ready show "Wirecard payment gateway not ready yet!" to user
            return view('wirecard-shop-success')->with(array_merge(
                @$_POST,
                array(
                    'error' => "Wirecard payment gateway not ready yet!",
                    'settings' => $this->settings()
                )
            ));
        }
    }

    /**
     * This function is use to process Boleto Payment
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function api_boleto(Request $request){

        $_POST = $request->all();

        if ($this->is_gateway_ready()) {
            $wirecard_app_data_array = $this->wirecard_app_data();

            $token = $wirecard_app_data_array['accessToken'];
            $moipMerchant = new Moip(new OAuth($token), $this->is_test_mode() ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION);

            $user = Auth::user();
            $wirecard_payment_token = "";
            $wirecard_boleto_href = "";
            $wirecard_boleto_print_href = "";
            $purchase_token = $_POST['order_no'];
            $order_id = $_POST['order_id'];
            $payment_type = $_POST['payment_type'];
            $status = 'cancelled';
            $multi_status = 'cancelled';
            $multi_amount = 'cancelled';
            $success = false;
            if ($this->is_completed($purchase_token) === true){
                return view('wirecard-shop-success')->with(array_merge(
                        @$_POST,
                    array(
                        'wirecard_payment_token' => $this->get_payment_id($purchase_token),
                        'wirecard_boleto_href' => $this->get_payment_id($purchase_token,'wirecard_boleto_href'),
                        'wirecard_boleto_print_href' => $this->get_payment_id($purchase_token,'wirecard_boleto_print_href'),
                        'settings' => $this->settings()
                    )
                ));
            } else {
                $errors = array();
                $escrow = array();
                $multipayments = array();
                try {
                    $customer = $moipMerchant->customers();
                    $holder = $moipMerchant->holders();
                    $multiorder = $moipMerchant->multiorders();

                    $this->create_order($moipMerchant,$multiorder,$customer,$holder,$error);
                    // Creating multipayment to multiorder
                    $multipayment = $multiorder->multipayments()
                        ->setBoleto(
                            date("Y-m-d",strtotime("+ 1 week")),
                            $this->settings()->wirecard_boleto_logo_uri,
                            array(
                                $this->settings()->wirecard_boleto_line_1,
                                $this->settings()->wirecard_boleto_line_2,
                                $this->settings()->wirecard_boleto_line_3
                            )
                        )
                        ->setEscrow($wirecard_app_data_array['name'])
                        ->execute();


                    $wirecard_payment_token = $multipayment->getId();
                    $wirecard_boleto_href = $multipayment->getHrefBoleto();
                    $wirecard_boleto_print_href = $multipayment->getHrefPrintBoleto();

                    $multi_status = $multipayment->getStatus();
                    $multi_amount = $multipayment->getAmount()->total;

                    if ($multipayment->getStatus() == 'AUTHORIZED') {
                        $status = 'completed';
                        $success = true;
                    } elseif ($multipayment->getStatus() == 'WAITING') {
                        $status = 'pending';
                        $success = true;
                    } elseif ($multipayment->getStatus() == 'PRE_AUTHORIZED') {
                        $status = 'pending';
                        $success = true;
                    }

                    foreach ($multipayment->getPayments() as $payment) {
                        $a = array(
                            'id' => $payment->escrows[0]->id,
                            'status' => $payment->escrows[0]->status,
                            'amount' => $payment->escrows[0]->amount
                        );
                        $escrow[] = $a;
                        $b = array(
                            'id' => $payment->id,
                            'status' => $payment->status,
                            'amount' => $payment->amount->total
                        );
                        $multipayments[] = $b;
                    }

                    if ($error != null){
                        $errors = $error;
                    }

                } catch (\Moip\Exceptions\UnautorizedException $e) {
                    $errors[] = $e->__toString();
                } catch (ValidationException $e) {
                    $errors[] = $e->__toString();
                } catch (\Moip\Exceptions\UnexpectedException $e) {
                    $errors[] = $e->__toString();
                }

                if ($success === true) {
                    $params = array(
                        'order_id' => $order_id,
                        'purchase_token' => $purchase_token,
                        'wirecard_payment_token' => $wirecard_payment_token,
                        'wirecard_boleto_href' => $wirecard_boleto_href,
                        'wirecard_boleto_print_href' => $wirecard_boleto_print_href,
                        'status' => $status,
                        'multipayment_id' => $wirecard_payment_token,
                        'multipayments' => $multipayments,
                        'escrow' => $escrow,
                        'multi_status' => $multi_status,
                        'multi_amount' => $multi_amount
                    );

                    $this->complete_order($params);
                    $this->wirecard_log_pay($params);
                    // if true complete the order with the above status
                    return view('wirecard-shop-success')->with(array_merge(
                        @$_POST,
                        array(
                            'wirecard_payment_token' => $wirecard_payment_token,
                            'wirecard_boleto_href' => $wirecard_boleto_href,
                            'wirecard_boleto_print_href' => $wirecard_boleto_print_href,
                            'settings' => $this->settings()
                        )
                    ));
                } else {
                    // else show cancel to user
                    return view('wirecard-cancel')->with(array(
                        'settings' => $this->settings(),
                        'reason' => implode("\n\n", $errors)
                    ));
                }
            }
        } else {
            // if gateway is not ready show "Wirecard payment gateway not ready yet!" to user
            return view('wirecard-shop-success')->with(array_merge(
                @$_POST,
                array(
                    'error' => "Wirecard payment gateway not ready yet!",
                    'settings' => $this->settings()
                )
            ));
        }
    }

    protected function pretty_print($data, $exit = true){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($exit)
            exit();
    }

    /**
     * This Function get wirecard gateway URI
     *
     * @return string
     */
    protected function gateway_url(){
        $url = "";
        if ($this->settings()->wirecard_mode == 'test'){
            $url = "https://sandbox.moip.com.br/v2/";
        }
        if ($this->settings()->wirecard_mode == 'live'){
            $url = "https://api.moip.com.br/v2/";
        }
        return $url;
    }

    /**
     * This Function check if Seller has connected his/her wirecard account to site wirecard app
     * or
     * if the site owner already created wirecard app
     *
     * @param int $id
     * @return bool
     */
    protected function is_app_exists($id = 0){
        if ($id != 0){
            $user_details = $this->user($id);
            if ($user_details->wirecard_app_data != null){
                $app_data = unserialize($user_details->wirecard_app_data);
                if (!empty($app_data['access_token']))
                    return true;
            }
        } else {
            if ($this->settings()->wirecard_app_data != null){
                $app_data = unserialize($this->settings()->wirecard_app_data);
                if (!empty($app_data['id']))
                    return true;
            }
        }
        return false;
    }

    /**
     * This Function Create Wirecard App
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    protected function create_app(Request $request){
        if (Auth::user()->id != 1) {
            return redirect('index');
        } else {
            $_POST = $request->all();
            $data = array(
                'headers' => array(
                    'Authorization: Basic ' . base64_encode($this->settings()->wirecard_auth_token . ':' . $this->settings()->wirecard_auth_key),
                    'Content-Type: application/json'
                ),
                'data' => json_encode(array(
                    'name' => @substr($_POST['wirecard_name'], 0, 50),
                    'description' => @substr(htmlentities($_POST['wirecard_desc'],ENT_QUOTES,'UTF-8'), 0, 100),
                    'site' => @rtrim($this->settings()->site_url, '/'),
                    'redirectUri' => @rtrim($this->settings()->site_url, '/') . '/wirecard-callback',
                )),
                'url' => $this->gateway_url() . 'channels/'
            );
            
           // print_r($data);
           // exit();
            
            
            $output = array();
            if ($this->http_post_form_curl($data, $response, $error) == 0) {

                @file_put_contents(dirname(__FILE__).'/log5.txt',print_r($data,true)."\n\n");
                @file_put_contents(dirname(__FILE__).'/log5.txt',$response."\n\n",8);
                @file_put_contents(dirname(__FILE__).'/log5.txt',$data['data']."\n\n",8);

                $response = json_decode($response, true);
                $response = serialize($response);
                $response_array = unserialize($response);
                if (isset($response_array['ERROR'])) {
                    $error_wc = $response_array['ERROR'];
                    $output['error'] = sprintf('WireCard App creation failed due to %1$s', '<strong>' . $error_wc . '<strong>');
                } elseif (isset($response_array['errors'])) {
                    $error_wc = "";
                    foreach ($response_array['errors'] as $error_wc_loop) {
                        $error_wc .= "; " . $error_wc_loop['description'];
                    }
                    $output['error'] = sprintf('WireCard App creation failed due to %1$s', '<strong>' . $error_wc . '<strong>');

                } else {
                    DB::update("UPDATE settings SET wirecard_app_data='$response' WHERE id = ?", [1]);
                    $output['success'] = sprintf('WireCard App created <br/> App ID: %s <br/> App Name: %s <br/> App Desc: %s', $response_array['id'], $response_array['name'], $response_array['description']);
                    $this->create_notifications_webhook();
                }
            } else {
                $output['error'] = sprintf('WireCard App creation failed due to %1$s', '<strong>' . $error . '</strong>');
            }
            return view('admin.wirecard-app')->with(array(
                    'is_app_exists' => $this->is_app_exists(),
                    'wirecard_app_data' => serialize($this->wirecard_app_data()),
                    'settings' => $this->settings()
                ) + $output);
        }
    }

    /**
     * This Function Print wirecard app form to admin
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    protected function wirecard_app_form(){
        if (Auth::user()->id != 1) {
            return redirect('index');
        } else {
            $add_array = array();
            if (!$this->is_gateway_ready(true)){
                $add_array['error'] = "Wirecard gateway is not ready yet, please setup wirecard auth token, auth key, public key, mode first and come back here";
            }
            return view('admin.wirecard-app')->with(array(
                    'is_app_exists' => $this->is_app_exists(),
                    'wirecard_app_data' => serialize($this->wirecard_app_data()),
                    'settings' => $this->settings()
                ) + $add_array);
        }
    }
    /**
     * This Function send http request to wirecard api
     *
     * @param array $data
     * @param string $response
     * @param string $error
     * @return int
     */
    protected function http_post_form_curl(array $data, &$response = "", &$error = ""){
        $data = array_merge(
            array(
                'headers' => null,
                'url' => null,
                'data' => null
            ),
            $data
        );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $data['headers'] );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_URL, $data['url'] );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data['data'] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_ENCODING, 'UTF-8' );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        $response = curl_exec( $ch );
        $err_no   = curl_errno( $ch );
        $error   = curl_error( $ch );
        curl_close( $ch );
        return $err_no;
    }

    /**
     * This Function process the wirecard connect account callback
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function connect_to_app_callback(){
        $code = @$_GET['code'];
        $error = @$_GET['error'];
        $wirecard_app_data = $this->wirecard_app_data();
        $userID = Auth::user()->id;
        $add_array = array();
        if (!$this->is_app_exists($userID)) {
            try {
                if (empty($error) && !empty($code)) {
                    $connect = new Connect($wirecard_app_data['redirectUri'], $wirecard_app_data['id'], true, $this->is_test_mode() ? Connect::ENDPOINT_SANDBOX : Connect::ENDPOINT_PRODUCTION);
                    $connect->setClientSecret($wirecard_app_data['secret']);
                    $connect->setCode($code);

                    /*
                     * After the user authorize your app, you must generate an OAuth token
                     * to make transactions in his name.
                     */
                    $authorize = $connect->authorize();
//            print_r($authorize);
//            exit();

                    if (!empty($authorize->access_token)) {
                        $auth = (array)$authorize;
                        $auth = serialize($auth);
                        DB::update("UPDATE users SET wirecard_app_data='$auth' WHERE id = ?", array($userID));
                        $add_array['success'] = 'Congrats, your Wirecard account has successfully connected with App ' . $wirecard_app_data['name'];
                    } else {
                        $add_array['error'] = 'Oops, your Wirecard account failed to connect with App ' . $wirecard_app_data['name'];
                    }
                } else {
                    $add_array['error'] = 'Oops, your Wirecard account failed to connect with App ' . $wirecard_app_data['name'] . ' due to ' . $error;
                }
            } catch (Exception $e) {
                $add_array['error'] = 'Oops, your Wirecard account failed to connect with App ' . $wirecard_app_data['name'] . ' due to ' . $e->getMessage() . ' ' . $e->getCode();
            }

        }
        return view('wirecard-connect')->with(array(
                'auth_link' => $this->create_auth_c_link(),
                'is_app_exists' => $this->is_app_exists($userID),
                'wirecard_app_data' => $this->user($userID)->wirecard_app_data,
                'settings' => $this->settings()
            ) + $add_array);
    }

    /**
     * This Function generate the authorize link for wirecard connect link
     *
     * @return string
     */
    protected function create_auth_c_link(){
        $userID = Auth::user()->id;
        $auth_link = "";
        if (!$this->is_app_exists($userID) && $this->is_gateway_ready()) {
            // Now it's time to create a URL then redirect your user to ask him permissions to create projects in his name
            $wirecard_app_data = $this->wirecard_app_data();
            $connect = new Connect($wirecard_app_data['redirectUri'], $wirecard_app_data['id'], true, $this->is_test_mode() ? Connect::ENDPOINT_SANDBOX : Connect::ENDPOINT_PRODUCTION);
            $connect->setScope(Connect::RECEIVE_FUNDS)
                ->setScope(Connect::REFUND)
                ->setScope(Connect::MANAGE_ACCOUNT_INFO)
                ->setScope(Connect::RETRIEVE_FINANCIAL_INFO);
            $auth_link = $connect->getAuthUrl();
        }
        return $auth_link;
    }
    /**
     * This Function print authorize link to seller for connect his/her wirecard account
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function connect_to_app(){
        $userID = Auth::user()->id;
        $auth_link = $this->create_auth_c_link();
        $add_array = array();
        if (!$this->is_gateway_ready(true)){
            $add_array['error'] = "Wirecard gateway is not ready yet, try again";
        }
        return view('wirecard-connect')->with(array(
                'auth_link' => $auth_link,
                'is_app_exists' => $this->is_app_exists($userID),
                'wirecard_app_data' => $this->user($userID)->wirecard_app_data,
                'settings' => $this->settings()
            ) + $add_array);
    }

    protected function create_notifications_webhook()
    {
        $wirecard_app_data = $this->wirecard_app_data();
        $data = array(
            'headers' => array(
                'Authorization: Basic ' . base64_encode($this->settings()->wirecard_auth_token . ':' . $this->settings()->wirecard_auth_key),
                'Content-Type: application/json'
            ),
            'data' => json_encode(array(
                'events' => array(
                    'ORDER.*',
                    'PAYMENT.*',
                    'MULTIPAYMENT.*'
                ),
                'target' => rtrim($this->settings()->site_url, '/') . '/wirecard-webhook',
                'media' => 'WEBHOOK'
            )),
            'url' => $this->gateway_url() . 'preferences/'.$wirecard_app_data['id'].'/notifications'
        );
        if ($this->http_post_form_curl($data, $response, $error) == 0) {
            $response = serialize(json_decode($response,true));
            DB::update("UPDATE settings SET wirecard_app_notify='$response' WHERE id = ?", [1]);
            return true;
        } else {
            return false;
        }
    }

    protected function wirecard_log_pay($params=array()){
        $params = array_merge(
            array(
                'purchase_token' => '',
                'multipayment_id' => '',
                'multipayments' => array(),
                'multi_status' => '',
                'multi_amount' => '',
                'escrow' => array()
            ),
            $params
        );

        //
        DB::insert(
            'insert into wirecard_payment (payment_id, parent_id, status, purchase_token, escrow_id, escrow_status, amount, created_at) values (?, ?, ?, ? ,?, ?, ?, ?)',
            array(
                $params['multipayment_id'],
                '',
                $params['multi_status'],
                $params['purchase_token'],
                '',
                '',
                $params['multi_amount'],
                time(),
            )
        );

        //
        if (!empty($params['multipayments'])){
            foreach ($params['multipayments'] as $key => $multipayment) {
                DB::insert(
                    'insert into wirecard_payment (payment_id, parent_id, status, purchase_token, escrow_id, escrow_status, amount, created_at) values (?, ?, ?, ? ,?, ?, ?, ?)',
                    array(
                        $multipayment['id'],
                        $params['multipayment_id'],
                        $multipayment['status'],
                        $params['purchase_token'],
                        $params['escrow'][$key]['id'],
                        $params['escrow'][$key]['status'],
                        $multipayment['amount'],
                        time(),
                    )
                );
            }
        }
    }

    protected function wirecard_log_pay_refunds($params=array()){
        DB::update('update wirecard_payment set refunds_status="'.$params['status'].'" where payment_id = ?', [$params['_links']['payment']['title']]);

        //
        DB::insert(
            'insert into wirecard_payment_refunds (payment_id, order_id, ref_id, status, type, method, amount, currency, card, created_at) values (?, ?, ?, ? ,?, ?, ?, ?, ?, ?)',
            array(
                $params['_links']['payment']['title'],
                $params['_links']['order']['title'],
                $params['id'],
                $params['status'],
                $params['type'],
                $params['refundingInstrument']['method'],
                $params['amount']['total'],
                $params['amount']['currency'],
                "{$params['refundingInstrument']['creditCard']['brand']} {$params['refundingInstrument']['creditCard']['first6']}...{$params['refundingInstrument']['creditCard']['last4']}",
                time(),
            )
        );

    }

    protected function wirecard_log_update_pay($id,$status){
        DB::update('update wirecard_payment set status="'.$status.'" where payment_id = ?', [$id]);
    }
    protected function wirecard_log_update_pay_escrow($id,$status){
        DB::update('update wirecard_payment set escrow_status="'.$status.'" where escrow_id = ?', [$id]);
    }
    protected function wirecard_order_update_pay($id,$status){
        DB::update('update product_orders set payment_status="'.$status.'" where payment_id = ?', [$id]);
    }

    /**
     * @param $id
     * @return bool
     */
    protected function cancel_pay($id){
        $wirecard_app_data_array = $this->wirecard_app_data();
        $token = $wirecard_app_data_array['accessToken'];

        $data = array(
            'headers' => array(
                'Authorization: OAuth ' . $token,
                'Content-Type: application/json'
            ),
            'data' => json_encode(array()),
            'url' => $this->gateway_url() . 'multipayments/'.$id.'/void'
        );
        if ($this->http_post_form_curl($data, $response, $error) == 0) {
            $response = json_decode($response,true);
            @file_put_contents(dirname(__FILE__).'/log4.txt',print_r($response,true));
	        if (empty($response)){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard return empty response'
		        ));
	        } elseif(isset($response['error'])){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard error: '. $response['error']
		        ));
	        } else {
                $multipaymentID = $response['id'];
                $multipaymentStatus = $response['status'];
	            $this->wirecard_log_update_pay($multipaymentID,$multipaymentStatus);
	            $payments = $response['payments'];
                if (!empty($payments)){
                    foreach ($payments as $payment) {
                        if ($payment['status'] == "CANCELLED") {
                            $this->wirecard_log_update_pay($payment['id'],$payment['status']);
                            $this->wirecard_order_update_pay($payment['id'],'cancelled');
                        }
                    }
                }
                DB::table('product_checkout')
                    ->where('payment_token', '=', $multipaymentID)
                    ->update(array('payment_status' => 'cancelled'));

                return redirect('admin/wirecard-transaction')->with(array(
                    'success' => 'Payment '.$multipaymentID.' '.$response['status']
                ));
            }
        } else {
            return redirect('admin/wirecard-transaction')->with(array(
                'error' => $error
            ));
        }
    }

    /**
     * @param $id
     * @return bool
     */
    protected function approve_pay($id){
        $wirecard_app_data_array = $this->wirecard_app_data();
        $token = $wirecard_app_data_array['accessToken'];

        $data = array(
            'headers' => array(
                'Authorization: OAuth ' . $token,
                'Content-Type: application/json'
            ),
            'data' => json_encode(array()),
            'url' => $this->gateway_url() . 'multipayments/'.$id.'/capture'
        );
        if ($this->http_post_form_curl($data, $response, $error) == 0) {
            $response = json_decode($response,true);
            @file_put_contents(dirname(__FILE__).'/log5.txt',print_r($response,true));
	        if (empty($response)){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard return empty response'
		        ));
	        } elseif(isset($response['error'])){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard error: '. $response['error']
		        ));
	        } else {
	            $multipaymentID = $response['id'];
	            $multipaymentStatus = $response['status'];
	            $this->wirecard_log_update_pay($multipaymentID,$multipaymentStatus);
                $payments = $response['payments'];
                if (!empty($payments)){
                    foreach ($payments as $payment) {
                        if ($payment['status'] == "AUTHORIZED") {
                            $this->wirecard_log_update_pay($payment['id'],$payment['status']);
                            $this->wirecard_order_update_pay($payment['id'],'completed');
                        }
                    }
                }
                DB::table('product_checkout')
                    ->where('payment_token', '=', $multipaymentID)
                    ->update(array('payment_status' => 'completed'));

                return redirect('admin/wirecard-transaction')->with(array(
                    'success' => 'Payment '.$multipaymentID.' '.$response['status']
                ));
            }
        } else {
            return redirect('admin/wirecard-transaction')->with(array(
                'error' => $error
            ));
        }
    }

    protected function escrow_release_pay($id){

        $wirecard_app_data_array = $this->wirecard_app_data();
        $token = $wirecard_app_data_array['accessToken'];

        $data = array(
            'headers' => array(
                'Authorization: OAuth ' . $token,
                'Content-Type: application/json'
            ),
            'data' => json_encode(array()),
            'url' => $this->gateway_url() . 'escrows/'.$id.'/release'
        );
        if ($this->http_post_form_curl($data, $response, $error) == 0) {
            $response = json_decode($response,true);
            @file_put_contents(dirname(__FILE__).'/log4.txt',print_r($response,true));
	        if (empty($response)){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard return empty response'
		        ));
	        } elseif(isset($response['error'])){
		        return redirect('admin/wirecard-transaction')->with(array(
			        'error' => 'Wirecard error: '. $response['error']
		        ));
	        } else {
                $this->wirecard_log_update_pay_escrow($response['id'],$response['status']);
                return redirect('admin/wirecard-transaction')->with(array(
                    'success' => 'Payment Escrow '.$response['id'].' '.$response['status']
                ));
            }
        } else {
            return redirect('admin/wirecard-transaction')->with(array(
                'error' => $error
            ));
        }
    }

    protected function refund_pay_json(){
        $json = '{"id":"REF-X4W2YX66B207","status":"COMPLETED","events":[{"type":"REFUND.COMPLETED","createdAt":"2019-01-19T11:54:53.000-02"},{"type":"REFUND.REQUESTED","createdAt":"2019-01-19T11:54:53.000-02"}],"amount":{"total":40000,"fees":0,"currency":"BRL"},"receiversDebited":[{"amount":36800,"moipAccount":"MPA-WASPDVP1P0X6"},{"amount":935,"moipAccount":"MPA-E47C4B9E73BD"}],"type":"FULL","refundingInstrument":{"creditCard":{"brand":"VISA","first6":"407302","last4":"0002","store":true},"method":"CREDIT_CARD"},"createdAt":"2019-01-19T11:54:53.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/refunds/REF-X4W2YX66B207"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-9NLP0GVTX53M","title":"ORD-9NLP0GVTX53M"},"payment":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-V5C5XY18IVON","title":"PAY-V5C5XY18IVON"}}}';
        $de_json = json_decode($json,true);
        $this->wirecard_log_pay_refunds($de_json);
        $this->pretty_print($de_json);
    }

    protected function escrow_refund_pay($id){

        $wirecard_app_data_array = $this->wirecard_app_data();
        $token = $wirecard_app_data_array['accessToken'];

        $data = array(
            'headers' => array(
                'Authorization: OAuth ' . $token,
                'Content-Type: application/json'
            ),
            'url' => $this->gateway_url() . 'payments/'.$id.'/refunds'
        );

        if ($this->http_post_form_curl($data, $response, $error) == 0) {
            $response = json_decode($response,true);
            @file_put_contents(dirname(__FILE__).'/log4.txt',print_r($response,true));

            if (empty($response)){
                return redirect('admin/wirecard-transaction')->with(array(
		            'error' => 'Wirecard return empty response'
	            ));
            } elseif(isset($response['error'])){
	            return redirect('admin/wirecard-transaction')->with(array(
		            'error' => 'Wirecard error: '. $response['error']
	            ));
            } else {
                $this->wirecard_log_pay_refunds($response);
                return redirect('admin/wirecard-transaction')->with(array(
                    'success' => 'Payment Refunds '.$response['id'].' '.$response['status']
                ));
            }
        } else {
            return redirect('admin/wirecard-transaction')->with(array(
                'error' => $error
            ));
        }
    }

    protected function transaction_list($add_array = array()){
        if (Auth::user()->id != 1) {
            return redirect('index');
        } else {

            $total_trans = 0;
            $total_trans_get = array();
            try {
                $total_trans = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->count();
                $total_trans_get = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->get();
            } catch (\Exception $exception){

            }
            $total_trans_refunds = 0;
            $total_trans_refunds_get = array();
            try {
                $total_trans_refunds = DB::table('wirecard_payment_refunds')
                    ->count();
                $total_trans_refunds_get = DB::table('wirecard_payment_refunds')
                    ->get();
            } catch (\Exception $exception){

            }
            $total_trans_waiting = 0;
            $total_trans_waiting_get = array();
            try {
                $total_trans_waiting = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','WAITING')
                    ->count();
                $total_trans_waiting_get = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','WAITING')
                    ->get();
            } catch (\Exception $exception){

            }
            $total_trans_authorized = 0;
            $total_trans_authorized_get = array();
            try {
                $total_trans_authorized = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','AUTHORIZED')
                    ->count();
                $total_trans_authorized_get = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','AUTHORIZED')
                    ->get();
            } catch (\Exception $exception){

            }
            $total_trans_cancelled = 0;
            $total_trans_cancelled_get = array();
            try {
                $total_trans_cancelled = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','CANCELLED')
                    ->count();
                $total_trans_cancelled_get = DB::table('wirecard_payment')
                    ->where('parent_id','=','')
                    ->where('status','=','CANCELLED')
                    ->get();
            } catch (\Exception $exception){

            }

            $output = array(
                'all' => array(),
                'waiting' => array(),
                'authorized' => array(),
                'cancelled' => array(),
            );

            // All Transaction
            if ($total_trans > 0){
                foreach ($total_trans_get as $item) {
                    $a = array(
                        'id' => $item->id,
                        'payment_id' => $item->payment_id,
                        'status' => $item->status,
                        'amount' => $item->amount
                    );
                    $b = array();
                    $sub_payment = DB::table('wirecard_payment')
                        ->where('parent_id','=',$item->payment_id)
                        ->get();
                    if (!empty($sub_payment)){
                        foreach ($sub_payment as $pay) {
                            $c = array(
                                'id' => $pay->id,
                                'payment_id' => $pay->payment_id,
                                'status' => $pay->status,
                                'escrow_id' => $pay->escrow_id,
                                'escrow_status' => $pay->escrow_status,
                                'refunds_status' => $pay->refunds_status,
                                'amount' => $pay->amount
                            );
                            $b[] = $c;
                        }
                    }
                    $a['payments'] = $b;
                    $output['all'][] = $a;
                }
            }

            // All Transaction Waiting
            if ($total_trans_waiting > 0){
                foreach ($total_trans_waiting_get as $item_waiting) {
                    $a = array(
                        'id' => $item_waiting->id,
                        'payment_id' => $item_waiting->payment_id,
                        'status' => $item_waiting->status,
                        'amount' => $item_waiting->amount
                    );
                    $b = array();
                    $sub_payment = DB::table('wirecard_payment')
                        ->where('parent_id','=',$item_waiting->payment_id)
                        ->get();
                    if (!empty($sub_payment)){
                        foreach ($sub_payment as $pay_waiting) {
                            $c = array(
                                'id' => $pay_waiting->id,
                                'payment_id' => $pay_waiting->payment_id,
                                'status' => $pay_waiting->status,
                                'escrow_id' => $pay_waiting->escrow_id,
                                'escrow_status' => $pay_waiting->escrow_status,
                                'refunds_status' => $pay_waiting->refunds_status,
                                'amount' => $pay_waiting->amount
                            );
                            $b[] = $c;
                        }
                    }
                    $a['payments'] = $b;
                    $output['waiting'][] = $a;
                }
            }

            // All Transaction Authorized
            if ($total_trans_authorized > 0){
                foreach ($total_trans_authorized_get as $item_authorized) {
                    $a = array(
                        'id' => $item_authorized->id,
                        'payment_id' => $item_authorized->payment_id,
                        'status' => $item_authorized->status,
                        'amount' => $item_authorized->amount
                    );
                    $b = array();
                    $sub_payment = DB::table('wirecard_payment')
                        ->where('parent_id','=',$item_authorized->payment_id)
                        ->get();
                    if (!empty($sub_payment)){
                        foreach ($sub_payment as $pay_authorized) {
                            $c = array(
                                'id' => $pay_authorized->id,
                                'payment_id' => $pay_authorized->payment_id,
                                'status' => $pay_authorized->status,
                                'escrow_id' => $pay_authorized->escrow_id,
                                'escrow_status' => $pay_authorized->escrow_status,
                                'refunds_status' => $pay_authorized->refunds_status,
                                'amount' => $pay_authorized->amount
                            );
                            $b[] = $c;
                        }
                    }
                    $a['payments'] = $b;
                    $output['authorized'][] = $a;
                }
            }

            // All Transaction Cancelled
            if ($total_trans_cancelled > 0){
                foreach ($total_trans_cancelled_get as $item_cancelled) {
                    $a = array(
                        'id' => $item_cancelled->id,
                        'payment_id' => $item_cancelled->payment_id,
                        'status' => $item_cancelled->status,
                        'amount' => $item_cancelled->amount
                    );
                    $b = array();
                    $sub_payment = DB::table('wirecard_payment')
                        ->where('parent_id','=',$item_cancelled->payment_id)
                        ->get();
                    if (!empty($sub_payment)){
                        foreach ($sub_payment as $pay_cancelled) {
                            $c = array(
                                'id' => $pay_cancelled->id,
                                'payment_id' => $pay_cancelled->payment_id,
                                'status' => $pay_cancelled->status,
                                'escrow_id' => $pay_cancelled->escrow_id,
                                'escrow_status' => $pay_cancelled->escrow_status,
                                'refunds_status' => $pay_cancelled->refunds_status,
                                'amount' => $pay_cancelled->amount
                            );
                            $b[] = $c;
                        }
                    }
                    $a['payments'] = $b;
                    $output['cancelled'][] = $a;
                }
            }

            // All Transaction Refunds
            if ($total_trans_refunds > 0){
                foreach ($total_trans_refunds_get as $item_refunds) {
                    $output['refunds'][] = (array) $item_refunds;
                }
            }

            if (!$this->is_gateway_ready(true)){
                $add_array['error'] = "Wirecard gateway is not ready yet, please setup wirecard auth token, auth key, public key, mode first and come back here";
            }
            return view('admin.wirecard-transaction')->with(array(
                    'is_app_exists' => $this->is_app_exists(),
                    'wirecard_app_data' => serialize($this->wirecard_app_data()),
                    'settings' => $this->settings(),
                    'output' => $output,
                ) + $add_array);
        }
    }
}