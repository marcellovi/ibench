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
use Session;
use Carbon\Carbon;

class ProductController extends Controller {
  /**
   * Create a new controller instance.
   * @return void
   */
  public function __construct()  {

    $this->middleware('auth');
    /* Marcello  */
    //$userid = Auth::user()->id;
    //$editprofile = DB::select('select * from users where id = ?',[$userid]);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
    public function avigher_view_compare($id){

	 $loger_id = Auth::user()->id;
	 $token = $id;


	 $check_compare = DB::table('product_compare')
                        ->where('user_id','=',$loger_id)
                        ->where('prod_token','=',$token)
                        ->count();

		if(empty($check_compare)) {

      DB::insert('insert into product_compare (prod_token,user_id) values (?, ?)', [$token,$loger_id]);
      $again_compare = DB::table('product_compare')
                ->where('user_id','=',$loger_id)
                ->count();

		  if($again_compare > 3) {
		    $viewas = DB::table('product_compare')
			            ->where('user_id','=',$loger_id)
			            ->orderBy('pc_id','asc')
			            ->get();
		    $pcid = $viewas[0]->pc_id;
		    DB::delete('delete from product_compare where user_id="'.$loger_id.'" and pc_id = ?',[$pcid]);
		  }

      return back()->with('success', 'Produto adicionado!');

    } else {
	return back()->with('error', 'Already added this product!');
	}

	 /*if(empty($check_compare))
		{

		   DB::insert('insert into product_compare (prod_token,user_id) values (?, ?)', [$token,$loger_id]);


			return back()->with('success', 'Product added!');

		}
		else
		{
		    return back()->with('error', 'Already added this product!');
		}*/

	 }

    public function avigher_compare() {

  	$viewcount = DB::table('product_compare')
		          ->where('user_id','=',Auth::user()->id)
		          ->orderBy('pc_id','desc')
		          ->take(3)
		          ->count();

	$viewproduct = DB::table('product_compare')
		            ->where('user_id','=',Auth::user()->id)
		            ->orderBy('pc_id','desc')
		            ->take(3)
		            ->get();

	return view('compare', ['viewcount' => $viewcount, 'viewproduct' => $viewproduct]);

}


public function avigher_cart(Request $request) {

	$data = $request->all();
       
		$prod_id = $data['prod_id'];
		$quantity = $data['quantity'];
		$price = str_replace(",", ".", $data['price']); // Marcello - Troque o , pelo . ( para salvar no BD )
		$log_id = Auth::user()->id;
		$prod_token = $data['prod_token'];
		$prod_user_id = $data['prod_user_id'];

		$checker_count= DB::table('product')
                                ->where('prod_id','=',$prod_id)
                                ->count();

		if(!empty($checker_count)) {

		   $checker_get= DB::table('product')
					->where('prod_id','=',$prod_id)
					->get();

		if($checker_get[0]->prod_available_qty >= $quantity && $quantity > 0)	{

			$keys = Session::getId();

			$attr_id = "";
			foreach($data['attribute'] as $attri)	{
				$attr_id .=$attri.',';
                        }

			$nattri = rtrim($attr_id,',');

			$check = DB::table('product_orders')
                                        ->where('user_id','=',$log_id)
                                        ->where('prod_id','=',$prod_id)
                                        ->where('order_status','=','pending')
                                        ->whereIn('prod_attribute', [$nattri])
                                        ->count();
                               
			if(empty($check)) {
				DB::insert('insert into product_orders (user_id,prod_id,prod_user_id,prod_token,token,quantity,prod_attribute,price,order_status) values (?, ?, ?, ?, ?,?,?,?,?)', [$log_id,$prod_id,$prod_user_id,$prod_token,$keys,$quantity,$nattri,$price,'pending']);
                        }
                        else {
				DB::update('update product_orders set quantity="'.$quantity.'" where user_id="'.$log_id.'" and order_status="pending" and prod_attribute="'.$nattri.'" and prod_id = ?', [$prod_id]);
			}

         return redirect('/cart');

      } else {
          //return back()->with('error', 'Please check available stock');
      	return back()->with('error', 'Por favor, verifique a disponibilidade do produto no estoque!');
      }
    }else{
	//return back()->with('error', 'Please check available stock');
	return back()->with('error', 'Por favor, verifique a disponibilidade do produto no estoque!');
    }
}

    public function avigher_view_wishlist() {

    $viewcount = DB::table('wishlist')
                    ->where('user_id','=',Auth::user()->id)
                    ->count();

    $viewproduct = DB::table('wishlist')
	            ->where('user_id','=',Auth::user()->id)
	            ->get();

    return view('my-wishlist', ['viewcount' => $viewcount, 'viewproduct' => $viewproduct]);
}

public function avigher_wishlist_delete($prod_token) {

    $loger_id = Auth::user()->id;
    DB::delete('delete from wishlist where prod_token="'.$prod_token.'" and 	user_id = ?',[$loger_id]);

    return back()->with('success', 'Produto foi removido!');
}

public function add_waiting_list($user_id, $prod_token, $prod_user_id) {

	$check_waiting_list = DB::table('waiting_list')
				->where('user_id','=',$user_id)
				->where('product_id','=',$prod_token)
				->count();

	if(empty($check_waiting_list)) {
		DB::insert('insert into waiting_list (user_id,product_id, waiting, prod_user_id) values (?, ?, ?, ?)', [$user_id,$prod_token, true, $prod_user_id]);
		return back()->with('success', 'Obrigado! Voc&ecirc; ser&aacute; avisado quando o produto retornar ao estoque!');
	}else{
                DB::update('update waiting_list set waiting=1 where user_id ='.$user_id.' and prod_user_id='.$prod_user_id.' and product_id="'.$prod_token.'"' );
		return back()->with('success', 'Obrigado! Voc&ecirc; ser&aacute; avisado quando o produto retornar ao estoque!');
	}
    }


    public function avigher_wishlist($log_id,$prod_token)	{

	$check_wishlist = DB::table('wishlist')
                          ->where('user_id','=',$log_id)
                          ->where('prod_token','=',$prod_token)
                          ->count();

	if(empty($check_wishlist)) {
            DB::insert('insert into wishlist (user_id,prod_token) values (?, ?)', [$log_id,$prod_token]);
            return back()->with('success', 'Produto adicionado!');
	}else{
	    return back()->with('error', 'Produto ja foi adicionado!');
	}
	}
	
    public function avigher_edit_product($token){

    $userid = Auth::user()->id;

    $category = DB::table('category')
            ->where('delete_status','=','')
            ->where('status','=',1)
            ->orderBy('cat_name', 'asc')->get();

    $product_type = array("fisico","digital");	// $product_type = array("physical","digital","external");

    $typer_admin_count = DB::table('product_attribute_type')
	                    ->where('delete_status','=','')
	                    ->where('status','=',1)
	                    ->orderBy('attr_name', 'asc')->count();

    $typer_admin = DB::table('product_attribute_type')
		            ->where('delete_status','=','')
			    ->where('status','=',1)
			    ->orderBy('attr_name', 'asc')->get();

    $viewcount = DB::table('product')
		            ->where('prod_token', '=' , $token)
		            ->count();

    $viewproduct = DB::table('product')
		            ->where('prod_token', '=' , $token)
		            ->get();

    //$viewproduct[0]->prod_name = $viewproduct[0]->prod_name ;
    $viewproduct[0]->prod_name = htmlentities($viewproduct[0]->prod_name); // Marcello retirando os "/" do nome quando aparece aspas
    //$viewproduct[0]->prod_price = number_format($viewproduct[0]->prod_price)
    // $viewproduct[0]->prod_offer_price =

    return view('edit-product', ['category' => $category, 'product_type' => $product_type, 'typer_admin' => $typer_admin, 'typer_admin_count' => $typer_admin_count, 'viewcount' => $viewcount, 'viewproduct' => $viewproduct]);
    }


    public function avigher_delete_photo($delete,$id,$photo) {
	$orginalfile1 = base64_decode($photo);
	$userphoto1="/media/";
        $path1 = base_path('images'.$userphoto1.$orginalfile1);
	File::delete($path1);
	DB::delete('delete from product_images where prod_img_id = ?',[$id]);
	return back();
    }

    public function deleteDatasheet($datasheet_name,$prod_id) {

        $user_id = Auth::id();
        //print_r('update product set prod_datasheet = "" where prod_id = '.$prod_id.' and user_id = '.$user_id);exit();
        $updated = DB::update('update product set prod_datasheet = "" where prod_id = '.$prod_id.' and user_id = '.$user_id);
	$datasheetpath="images/datasheets/".$datasheet_name.".pdf";        
        $path1 = base_path($datasheetpath);		
   
        if($updated){
            File::delete($path1);
        }
        
	return back();
    }


    public function avigher_product() {

	$userid = Auth::user()->id;
	$viewcount = DB::table('product')
		        ->where('user_id', '=' , $userid)
			->where('delete_status','=','')
			->orderBy('prod_id','desc')
		        ->count();

	$viewproduct = DB::table('product')
		        ->where('user_id', '=' , $userid)
			->where('delete_status','=','')
			->orderBy('prod_id','desc')
		        ->get();

	$data = array('viewcount' => $viewcount, 'viewproduct' => $viewproduct);
	return view('my-product')->with($data);
    }

    public function waitingList() {
		
	$userid = Auth::user()->id;
	$viewcount_waiting = DB::table('waiting_list')
		        ->where('prod_user_id', '=' , $userid)
		        ->where('waiting', '=' , 1)
		        ->count();

        $viewcount_no_waiting = DB::table('waiting_list')
		        ->where('prod_user_id', '=' , $userid)
		        ->where('waiting', '=' , 0)
		        ->count();

	$viewproduct_waiting = DB::table('waiting_list')
			->where('prod_user_id', '=' , $userid)
			->where('waiting', '=' , 1)
		        ->get();

	$viewproduct_no_waiting = DB::table('waiting_list')
			->where('prod_user_id', '=' , $userid)
			->where('waiting', '=' , 0)
		        ->get();

	$data = array('viewcount_waiting' => $viewcount_waiting, 'viewcount_no_waiting' => $viewcount_no_waiting ,'viewproduct_waiting' => $viewproduct_waiting,'viewproduct_no_waiting' => $viewproduct_no_waiting);
	return view('waiting-list')->with($data);	
    }

    /** Marcello :: List of all Active & Inactive Products**/
  public function myProductListActiveInactive(Request $request) {
		
		$userid = Auth::user()->id;
		$req = $request->all();

		$category = DB::table('category')
			->where('delete_status','=','')
			->where('status','=',1)
			->orderBy('cat_name', 'asc')->get();
                   

		// Condições de Consultas dos Produtos
		$check_search = 0;
		$products_ids_result = [];

		// Verifica Nome
		if(array_key_exists('name', $req)) {
			
			if (isset($req['name'])){
				
				$check_search = 1;
				$viewproduct_name = DB::table('product')->where('user_id', '=' , $userid)->Where(function ($query) {
               									$query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                            })->orderBy('prod_id','desc');

				if (!empty($products_ids_result)){
					$name_prod_ids = $viewproduct_name->whereIn('prod_id',$products_ids_result)->where('prod_name', 'LIKE' , utf8_encode($req['name']) ? '%'.utf8_encode($req['name']).'%' : '%'.''.'%')->pluck('prod_id')->toArray();
				} else {
					$name_prod_ids = $viewproduct_name->where('prod_name', 'LIKE' , utf8_encode($req['name']) ? '%'.utf8_encode($req['name']).'%' : '%'.''.'%')->pluck('prod_id')->toArray();
				}

				$products_ids_result = $name_prod_ids;

			}
			
		}


		// Verifica Categoria
		if(array_key_exists('category', $req) &&  strlen($req['category']) > 0 ) {

			if (isset($req['category'])){

				$check_search = 1;
				$viewproduct_category = DB::table('product')->where('user_id', '=' , $userid)->Where(function ($query) {
               									$query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                            })->orderBy('prod_id','desc');

				$sub_categories = DB::table('subcategory')->where('subid','=',$req['category'])->get();

				if ($sub_categories[0]->post_slug == "outros") {
					$result_category = DB::table('subcategory')->where('post_slug','LIKE','%outros%')->pluck('subid')->toArray();
				} else {
					$result_category = [$req['category']];
				}

				if (!empty($products_ids_result)){
					$category_prod_ids = $viewproduct_category->whereIn('prod_id',$products_ids_result)->whereIn('prod_category', $result_category)->pluck('prod_id')->toArray();
				}else{
					$category_prod_ids = $viewproduct_category->whereIn('prod_category', $result_category)->pluck('prod_id')->toArray();
				}

				$products_ids_result = $category_prod_ids;
			}

		}
			
		// Verifica Valor Max. e Min.
		if(	array_key_exists('minvalue', $req) || array_key_exists('maxvalue', $req) ) {

			if (isset($req['minvalue']) || isset($req['maxvalue']) ) {
				
				$check_search = 1;

				$viewproduct_filtered = DB::table('product')->where('user_id', '=' , $userid)->Where(function ($query) {
               									$query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                            })->orderBy('prod_id','desc');

				$viewproduct_filtered_get = DB::table('product')->where('user_id', '=' , $userid)->Where(function ($query) {
               									$query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                            })->orderBy('prod_id','desc')->get();

				$value_min = isset($req['minvalue']) ? $req['minvalue'] : 1 ;
				$value_max = isset($req['maxvalue']) ? $req['maxvalue'] : 9999999 ;

				$result_array = [];
			
				if (!empty($products_ids_result)){

					$products_filtered = $viewproduct_filtered->whereIn('prod_id',$products_ids_result)->get();
				
					foreach ($products_filtered as $product_result) {

						if ($product_result->prod_offer_price > 0){

							if ( ($value_min <= $product_result->prod_offer_price) && ($product_result->prod_offer_price <= $value_max) ) {
								array_push($result_array,$product_result->prod_id);
							}

						} else {

							if ( ($value_min <= $product_result->prod_price) && ($product_result->prod_price <= $value_max) ){
								array_push($result_array,$product_result->prod_id);
							}

						}

					}

			
				} else {

					foreach ($viewproduct_filtered_get as $product_result) {

						if ($product_result->prod_offer_price > 0){

							if ( ($value_min <= $product_result->prod_offer_price) && ($product_result->prod_offer_price <= $value_max) ) {
								array_push($result_array,$product_result->prod_id);
							}

						} else {

							if ( ($value_min <= $product_result->prod_price) && ($product_result->prod_price <= $value_max) ){
								array_push($result_array,$product_result->prod_id);
							}

						}

					}

				}
				

				$uniq_ids = array_unique($result_array);
			
				$products_ids_result = $uniq_ids;

			}
			
		} 
			               

		//pagination ends
		$total_reg = "100";

		if (isset($req['pagina'])){
			$pc = $req['pagina'];
		} else {
			$pc = "1";
		}

		$inicio = $pc - 1;
		$inicio = $inicio * $total_reg;

	
		if($check_search > 0) {		

			$viewcount = DB::table('product')->whereIn('prod_id', $products_ids_result)->count();

			$tp = $viewcount / $total_reg;			

			$viewproduct = DB::table('product')->whereIn('prod_id', $products_ids_result)->offset($inicio)->limit($total_reg)->get();
			

		} else {

			$viewcount = DB::table('product')
				->where('user_id', '=' , $userid)
                        // Retirar abaixo quando for feito o task deletar cliente/seller
                          ->Where(function ($query) {
                                $query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                          })
                        ->orderBy('prod_id','desc');

			$viewproduct = DB::table('product')
                        ->where('user_id', '=' , $userid)
                        // Retirar abaixo quando for feito o task deletar cliente/seller
                            ->Where(function ($query) {
                                $query->where('delete_status','!=','deleted')
                                ->where('prod_status','!=',0);
                            })
												->orderBy('prod_id','desc');

			$viewcount = $viewcount->count();

			$tp = $viewcount / $total_reg;

			//$test = $viewproduct->whereBetween('prod_price', [1, 200])->pluck('prod_id')->toArray();

			//$myfile = fopen("logs_tests.txt", "wr");
			//$txt = implode(",", $test);
			//fwrite($myfile, $txt);
			//fclose($myfile);

			$viewproduct = $viewproduct->offset($inicio)->limit($total_reg);
							
			$viewproduct = $viewproduct->get();	

		}
			

 		$data = array('viewcount' => $viewcount, 'viewproduct' => $viewproduct, 'category' => $category, 'data' => $req, 'pc' => $pc, 'tp' => $tp);
 		
 		return view('my-product')->with($data);

	}

    public function avigher_add_form() {

    $userid = Auth::user()->id;
    $category = DB::table('category')
		    ->where('delete_status','=','')
		    ->where('status','=',1)
		    ->orderBy('cat_name', 'asc')->get();

    $product_type = array("fisico","digital");	// Marcello - $product_type = array("physical","digital","external");

    $typer_admin_count = DB::table('product_attribute_type')
		            ->where('delete_status','=','')
			    ->where('status','=',1)
			    ->orderBy('attr_name', 'asc')->count();

    $typer_admin = DB::table('product_attribute_type')
		            ->where('delete_status','=','')
			    ->where('status','=',1)
			    ->orderBy('attr_name', 'asc')->get();

	return view('add-product', ['category' => $category, 'product_type' => $product_type, 'typer_admin' => $typer_admin, 'typer_admin_count' => $typer_admin_count]);
  }


    public function clean($string) {

        $string = preg_replace("/[^\p{L}\/_|+ -]/ui","",$string);
        $string = preg_replace("/[\/_|+ -]+/", '-', $string);
        $string =  trim($string,'-');

    return mb_strtolower($string);
  }


    public function goodseo($string) {
	  $string = str_replace(" ","-",$string);
	  return $string;
    }

    /** Update do deleted status **/
    public function avigher_product_delete($token) {
        DB::update('update product set delete_status="deleted",prod_status="0" where prod_token = ?',[$token]);
        return back()->with('success', 'Produto foi deletado.'); // Marcello - Product status to deleted
    }

    /** Marcello :: Delete a product **/
    public function deleteProductSeller($token){
        DB::table('product')->where('prod_token', '=', $token)->delete();
        return back()->with('success', 'Produto foi deletado Permanentemente.'); // Marcello - Product has been deleted
	}
	
	/** Cristiano :: Delete a product waiting-list **/
	public function deleteWaitingListProduct($id){
		DB::table('waiting_list')->where('id', '=', $id)->delete();
		return back()->with('success', 'Produto foi excluido com sucesso.'); // Cristiano - Product has been deleted with success
	}

    /** Marcello :: Hide or make Visible a product by the Seller
     * 0 - Invisible
     * 1 - Visible
     ***/
    public function statusProductSeller($token, $id) {
        $userid = Auth::user()->id;
        if($id == 1){
            $pass = DB::update('update product set delete_status="" where user_id = ? and prod_token = ?',[$userid,$token]);
        }else{       
            $pass = DB::update('update product set delete_status="inactive" where user_id = ? and prod_token = ?',[$userid,$token]);
            // $pass = DB::table('product')->where('prod_token',$token)->update($data);
        }    
            //if($pass)
            //    return back()->with('success', 'Atualizado com sucesso.');
            // else
            //   return back()->with('error', 'Erro na atualiza&ccedil;&atilde;o.');
        return back();
	}

	// A ordem e' importante por que o produto ira permitir aspas que nao e' o caso da url
	public function productNameUrlFilter($productName,$data) {
	
		$productName = preg_replace('/[^A-Za-z0-9-]/', '', $data); // Marcello - Retirando o (automatico agora e caracteres especiais ) $productName = $data['url_slug'];
		$productName = str_replace("'", "", $productName); // Retirando Aspas simples
		$productName = str_replace('"', "", $productName); // Retirando Aspas duplas
		return $productName;
	}
	
	//Cristiano -  Filtro para e-mail e telefone campo descrição
	public function descriptionFilter($description) {
		
		$email_pattern = "/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/";
		$phone_pattern = "/(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))/";
		$tag_pattern = "/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i";
		$filter_pattern = [$email_pattern,$phone_pattern];
		
		$description = preg_replace($filter_pattern,str_repeat("*", 10),$description);//Cristiano -  Filtro para e-mail e telefone campo descrição
		$description = preg_replace($tag_pattern,'<$1$2>', $description );// Marcello - Cristiano - Retirando os atributos dentro das tags
		
		// Marcello - Tratando o erro de texto que utiliza aspas
		$description = str_replace('"','&quot;',$description);
		$description = str_replace("'",'&apos;',$description); 
		
		return $description;
	}
	// Marcello - Cristiano - Tratando os Tags que podem ser salvos
	public function descriptionFilterTag($description,$data) {
		
		$description = strip_tags($data,"<strong><p><em><h1><h2><h3><h4><br>"); 
		
		return $description;
	}

    public function avigher_edit_data (Request $request) {

    $userid = Auth::user()->id;

    /* Datasheet Setting */
    $target_dir = "datasheet/";
    $target_file = $target_dir . basename($_FILES["datasheet"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    

    $category = DB::table('category')
                  ->where('delete_status','=','')
                  ->where('status','=',1)
                  ->orderBy('cat_name', 'asc')->get();

    $product_type = array("fisico","digital");	//$product_type = array("physical","digital","external");

    $typer_admin_count = DB::table('product_attribute_type')
                            ->where('delete_status','=','')
                            ->where('status','=',1)
                            ->orderBy('attr_name', 'asc')->count();

    $typer_admin = DB::table('product_attribute_type')
                      ->where('delete_status','=','')
                      ->where('status','=',1)
                      ->orderBy('attr_name', 'asc')->get();

    $data = $request->all();
    $token = $data['prod_token'];

    $product_check = DB::table('product')
		                  ->where('prod_token', '=' , $data['prod_token'])
		                  ->get();

    $settings = DB::select('select * from settings where id = ?',[1]);
    $imgsize = $settings[0]->image_size;

    $rules = array(
      'image.*' => 'image|mimes:jpeg,png,jpg|max:'.$imgsize,
      'datasheet' => 'mimes:pdf|max:3000'
		);
    $messages = array(
      'image' => 'The :attribute field must only be image'
    );

    $validator = Validator::make(Input::all(), $rules, $messages);

    if ($validator->fails()) {
	$failedRules = $validator->failed();
	return back()->withErrors($validator);
    }else{
        // A ordem e' importante por que o produto ira permitir aspas que nao e' o caso da url		
		$url_slug = "";
		$url_slug = $this->productNameUrlFilter($url_slug,$data['product_name']);

        // Marcello - Acertando enconding de acento
		$product_name = $data['product_name'];
        //$product_name = str_replace("'", "", $product_name); // Retirando Aspas simples
        //$product_name = str_replace('"', "", $product_name); // Retirando Aspas duplas
        //$product_name = $mysqli->real_escape_string($product_name); // Marcello - Tratando o erro de texto que utiliza aspas
		$product_name = addslashes($product_name);
        $cat_id = $data['cat_id'];
        $pieces = explode("_", $cat_id);
		$category_id = $pieces[0];
		$category_type = $pieces[1];

		$prod_desc = "";
		$prod_desc = $this->descriptionFilterTag($prod_desc,$data['prod_desc']);// Marcello - Cristiano - Tratando os Tags que podem ser salvos
		$prod_desc = $this->descriptionFilter($prod_desc); //Cristiano -  Filtro para palavra reservada campo descrição
        
        $prod_type = $data['prod_type'];
        $prod_price = str_replace(".", "", $data['prod_price']); // Marcello - retira o Mil "." em portugues
		$prod_price = str_replace(",", ".", $prod_price); // Marcello - Trocando o "," pelo "." para salvar como centavos

	//echo "original : ".$data['prod_price'];
        //echo "<br> alterado : ".$prod_price;
        //exit();

	if($prod_type=="digital") {
            $prod_available_qty = 1;
	} else {
	    $prod_available_qty = $data['prod_available_qty'];
       }

        $prod_offer_price = str_replace(".", "", $data['prod_offer_price']); // Marcello - retira o Mil "." em portugues
	$prod_offer_price = str_replace(",", ".", $prod_offer_price); // Marcello - Trocando o "," pelo "." para salvar como centavos


	if(!empty($data['prod_external_url'])) {
	    $prod_external_url = $data['prod_external_url'];
	} else {
            $prod_external_url = "";
       }

	if(!empty($data['attribute'])) {
            $array = $data['attribute'];
            $val = "";
            foreach ($array as $key => $value){
                $val .= $value.',';
            }
            $name = rtrim($val,',');
	}else{
            $name = "";
	}
	if(!empty($data['prod_tags']))
	{
	   $prod_tags = $data['prod_tags'];
        }else{
	   $prod_tags = "";
	}

	if(!empty($data['prod_featured'])){
	     $prod_featured = $data['prod_featured'];
	}
	else{
	     $prod_featured = "";
	}
	 
        $zipfile = Input::file('zipfile');
	if(isset($zipfile)){
            
            $filename = time() . '.' . $zipfile->getClientOriginalName();

            $zipformat = base_path('images/media/');
            $zipfile->move($zipformat,$filename);
            $zipname = $filename;
	}else{
	    $zipname = $data['save_zipfile'];
	}

        
	if ($request->hasFile('datasheet')) {
            
            $destinationPath = base_path('images/datasheets/');
            
                    $datasheet = $product_check[0]->prod_datasheet;
                    if(!empty($datasheet)){
                        File::delete($destinationPath.$datasheet);
                        $datasheet = "";
                    }
                   
                    
                    $file = $request->file('datasheet');
                    
                    $newName = time().$this->normalizeString(substr($file->getClientOriginalName(), 0, 7));
                    $datasheet = $newName.".".$file->getClientOriginalExtension();                    
                    
                    $file->move($destinationPath, $datasheet);
                    
	}else{
            $datasheet = "";
	}
  
	if($settings[0]->with_submit_product==1){
            
	    $status_approval = 0;
            // Marcello - $submit_msg = 'Product has been updated. Once product has been approved. You will received the confirmation.';
            $submit_msg = 'Produto foi atualizado. Ap&oacute;s a aprova&ccedil;&atilde;o. Voc&ecirc; recebera uma confirma&ccedil;&atilde;o.';
        }else{
	    $status_approval = 1;
            $submit_msg = 'Produto foi atualizado.';
	}
	// Marcello - inseri para poder exibir e retirar as tags
	//$prod_desc =   strip_tags($prod_desc);
   	if ($product_check[0]->prod_available_qty == 0){
   		if ($prod_available_qty > $product_check[0]->prod_available_qty){
   			$check_waiting_list = DB::table('waiting_list')
						->where('waiting','=',1)
						->where('product_id','=',$data['prod_token'])
						->count();

			if(!empty($check_waiting_list)) {
				DB::table('waiting_list')->where('waiting','=',1)->where('product_id','=',$data['prod_token'])->update(array('waiting'=>0));
			}
   		}
   	}

	DB::update('update product set prod_slug="'.$url_slug.
                   '",prod_category="'.$category_id.
                   '",prod_cat_type="'.$category_type.
                   '",prod_name="'.$product_name.
                   '",prod_desc="'.$prod_desc.
                   '",prod_tags="'.$prod_tags.
                   '",prod_price="'.$prod_price.
                   '",prod_offer_price="'.$prod_offer_price.
                   '",prod_featured="'.$prod_featured.
                   '",prod_type="'.$prod_type.
                   '",prod_zipfile="'.$zipname.
                   '",prod_external_url="'.$prod_external_url.
                   '",prod_attribute="'.$name.
                   '",prod_datasheet="'.$datasheet.
                   '",prod_available_qty="'.$prod_available_qty.
                   '",prod_status="'.$status_approval.
                   '" where prod_token = ?', [$token]);

	   $picture = '';
		if ($request->hasFile('image')) {
			$files = $request->file('image');
			foreach($files as $file){

            // Marcello - Trim & Strip Special Characters & Make String Lower Case (transformar em funcao)
            $newName = $this->normalizeString($file->getClientOriginalName());

            $filename = $newName;
            $extension = $file->getClientOriginalExtension();
            $picture = time().$filename;
            $destinationPath = base_path('images/media/'.$picture);
            // $file->move($destinationPath, $picture);
            Image::make($file->getRealPath())->resize(300, 300)->save($destinationPath);

		DB::insert('insert into product_images (prod_token,image) values (?, ?)', [$token,$picture]);
		}
	}
   }
	return back()->with('success', $submit_msg);
   }


	/** Marcello - Trim & Strip Special Characters & Make String Lower Case (transformar em funcao) **/
	public function normalizeString($normalizeTxt){
                $newTxt = str_replace(' ', '',mb_strtolower($normalizeTxt));
                $newTxt = str_replace('.', '',mb_strtolower($normalizeTxt));

                $search = explode(",","ç,æ,œ,á,ã,é,í,ó,õ,ú,à,è,ì,ò,ù,â,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
                $replace = explode(",","c,ae,oe,a,a,e,i,o,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");

                return str_replace($search, $replace, $newTxt);
        }


        /** Marcello - Enable and Disable all products from a seller/user **/
        public function manageProducts($id,$type){

            if($type == 0){
                //Inactive all Products and User
                DB::update('update product set delete_status="inactive" where user_id='.$id);
                DB::update('update users set delete_status="inactive" where id='.$id);

                return back()->with('success', 'Sucesso - Todos os Produtos est&atilde;o Inativos !');
                // return back()->with('error', 'Already added this product!');
            }if($type == 1){
                // Active all Products and User
                DB::update('update product set delete_status="" where user_id='.$id);
                DB::update('update users set delete_status="" where id='.$id);

                return back()->with('success', 'Sucesso - Todos os Produtos est&atilde;o Ativos !');
                // return back()->with('error', 'Already added this product!');
            }
        }

        /* Gets the data of a product to quote and it's seller */
        public function quote_product($id){         
            $userid = Auth::user()->id;
            $qproduct = DB::select('select name_business,prod_id,prod_name from product,users where prod_id = ? and user_id=id',[$id]); 

            $user = DB::select('select id,full_name,email,phone from users where id = ?',[$userid]); 

        return view('quoteproduct', ['qproduct' => $qproduct, 'user' => $user]);
          
        }


	public function avigher_add_product(Request $request){

	$userid = Auth::user()->id;

        /* Datasheet Setting */
        $target_dir = "datasheet/";
        $target_file = $target_dir . basename($_FILES["datasheet"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Marcello :: Find the User Status
        $user_status = DB::select('select delete_status from users where id = ?',[$userid]);

	$category = DB::table('category')
		            ->where('delete_status','=','')
					->where('status','=',1)
					->orderBy('cat_name', 'asc')->get();

	$product_type = array("fisico","digital");	// $product_type = array("physical","digital","external");

	$typer_admin_count = DB::table('product_attribute_type')
		            ->where('delete_status','=','')
					->where('status','=',1)

					->orderBy('attr_name', 'asc')->count();

		 $typer_admin = DB::table('product_attribute_type')
		            ->where('delete_status','=','')
					->where('status','=',1)

					->orderBy('attr_name', 'asc')->get();

	   $data = $request->all();


	   $settings = DB::select('select * from settings where id = ?',[1]);
	      $imgsize = $settings[0]->image_size;
		 $zipsize = $settings[0]->zip_size;

		$rules = array(
		'image' => 'required',
		'image.*' => 'image|mimes:jpeg,png,jpg|max:'.$imgsize,
                'datasheet' => 'mimes:pdf|max:3000',
		'zipfile' => 'max:'.$zipsize.'|mimes:zip'
		);

		$messages = array(
                'image' => 'The :attribute field must only be image'
                );

            $validator = Validator::make(Input::all(), $rules, $messages);

	if ($validator->fails()){
			$failedRules = $validator->failed();
			return back()->withErrors($validator);
	}
	else{
            
	   /* Marcello - ordem deve ser mantida para retirar aspas do prod_name */
	   $url_slug = "";
	   $url_slug = $this->productNameUrlFilter($url_slug,$data['product_name']);

	   $product_name = $data['product_name'];
           $cat_id = $data['cat_id'];
	   $pieces = explode("_", $cat_id);
	   $category_id = $pieces[0];
	   $category_type = $pieces[1];

	   $prod_desc = "";
	   $prod_desc = $this->descriptionFilterTag($prod_desc,$data['prod_desc']);// Marcello - Cristiano - Tratando os Tags que podem ser salvos
	   $prod_desc = $this->descriptionFilter($prod_desc); //Cristiano -  Filtro para palavra reservada campo descrição

	   $prod_type = $data['prod_type'];
	   $prod_price = str_replace(",", ".", $data['prod_price']); // Marcello - Trocando o "," pelo "."
	   $prod_offer_price = str_replace(",", ".", $data['prod_offer_price']); // Marcello - Trocando o "," pelo "."

	if(!empty($data['prod_available_qty']))
	{
	   $prod_available_qty = $data['prod_available_qty'];
	}
	else{
	     $prod_available_qty = 0;
	}

	$token = $data['prod_token'];

	if(!empty($data['prod_external_url']))
	{
	   $prod_external_url = $data['prod_external_url'];
	}
	else
	{
	   $prod_external_url = "";
	}

	if(!empty($data['attribute']))
	{
	   $array = $data['attribute'];

	   $val = "";
	   foreach ($array as $key => $value)
	   {
	      $val .= $value.',';
	   }
	   $name = rtrim($val,',');
	}
	else{
	     $name = "";
	}

	if(!empty($data['prod_tags'])){
	   $prod_tags = $data['prod_tags'];
        }else{
	    $prod_tags = "";
	}
        
        $datasheet = '';
		if ($request->hasFile('datasheet')) {
                    $file = $request->file('datasheet');
                    
                    $newName = time().$this->normalizeString(substr($file->getClientOriginalName(), 0, 7));
                    $datasheet = $newName.".".$file->getClientOriginalExtension();                    
                    $destinationPath = base_path('images/datasheets/');
                    $file->move($destinationPath, $datasheet);                   
	}
        
	if(!empty($data['prod_featured'])){
	     $prod_featured = $data['prod_featured'];
	}else{
	     $prod_featured = "";
	}
        
	    $zipfile = Input::file('zipfile');
		if(isset($zipfile)){
                    $filename = time() . '.' . $zipfile->getClientOriginalName();

                    $zipformat = base_path('images/media/');
                    $zipfile->move($zipformat,$filename);
                    $zipname = $filename;
		}else{
		    $zipname = "";
		 }

	   if($settings[0]->with_submit_product==1)
	   {
	    $status_approval = 0;
            $submit_msg = 'Produto criado com sucesso. Esta pendente de aprovacao. You will received the confirmation.';
	   }
	   else{
	     $status_approval = 1;
		 $submit_msg = 'Produto criado com sucesso.';
	   }

           // Marcello :: In case the user has a delete_status of blocked
           if($user_status[0]->delete_status == "blocked"){
                $status_approval = 0;
		$submit_msg = 'Produto criado com sucesso. Esta pendente de aprova&ccedil;&atilde;o/libera&ccedil;&atilde;o.';
           }

	   $idd = DB::table('product')-> insertGetId(array(
		'user_id' => $userid,
		'prod_token' => $token,
                'prod_slug' => $this->goodseo($url_slug),
		'prod_category' => $category_id,
		'prod_cat_type' => $category_type,
		'prod_name' => $product_name,
		'prod_desc' => $prod_desc,
		'prod_tags' => $prod_tags,
		'prod_price' => $prod_price,
		'prod_offer_price' => $prod_offer_price,
		'prod_featured' => $prod_featured,
		'prod_zipfile' => $zipname,
		'prod_type' => $prod_type,
		'prod_external_url' => $prod_external_url,
		'prod_attribute' => $name,
		'prod_available_qty' => $prod_available_qty,
		'prod_status' => $status_approval,
                'prod_datasheet' => $datasheet,
	));
  }

	$picture = '';
		if ($request->hasFile('image')) {
                    $files = $request->file('image');
                    foreach($files as $file){

                        // Marcello - Trim & Strip Special Characters & Make String Lower Case (transformar em funcao)
                        $newName = $this->normalizeString($file->getClientOriginalName());
			$filename = $newName;

			$extension = $file->getClientOriginalExtension();
                        $picture = time().$filename;
                        $destinationPath = base_path('images/media/'.$picture);
			// $file->move($destinationPath, $picture);
                        Image::make($file->getRealPath())->resize(300, 300)->save($destinationPath);

			DB::insert('insert into product_images (prod_token,image) values (?, ?)', [$token,$picture]);
		}
	}
	return back()->with('success', $submit_msg);
	}
}
