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
use Carbon\Carbon;

class CategoryController extends Controller {
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


	public function avigher_product_details( $prod_id, $prod_slug ) {


		$viewcount = DB::table( 'product' )
		               //->where( 'delete_status', '=', '' )
		               //->where( 'prod_status', '=', 1 )
		               ->where( 'prod_id', '=', $prod_id )
		               ->count();

		$viewproduct = DB::table( 'product' )
		                // ->where( 'delete_status', '=', '' )
		                // ->where( 'prod_status', '=', 1 )
		                 ->where( 'prod_id', '=', $prod_id )
		                 ->get();


		$latestcount = DB::table( 'product' )
		                 ->where( 'delete_status', '=', '' )
		                 ->where( 'prod_status', '=', 1 )
		                 ->take( 3 )
		                 ->orderBy( 'prod_id', 'desc' )
		                 ->count();

		$latest_product = DB::table( 'product' )
		                    ->where( 'delete_status', '=', '' )
		                    ->where( 'prod_status', '=', 1 )
		                    ->take( 3 )
		                    ->orderBy( 'prod_id', 'desc' )
		                    ->get();


		$relatedcount = DB::table( 'product' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'prod_status', '=', 1 )
		                  ->where( 'prod_id', '!=', $prod_id )
		                  ->take( 10 )
		                  ->orderBy( 'prod_id', 'desc' )
		                  ->count();

		$relatedproduct = DB::table( 'product' )
		                    ->where( 'delete_status', '=', '' )
		                    ->where( 'prod_status', '=', 1 )
		                    ->where( 'prod_id', '!=', $prod_id )
		                    ->take( 10 )
		                    ->orderBy( 'prod_id', 'desc' )
		                    ->get();


		$viewcount_rating = DB::table( 'product_rating' )
		                      ->where( 'prod_id', '=', $prod_id )
		                      ->groupBy( 'user_id' )
		                      ->orderBy( 'rate_id', 'desc' )
		                      ->count();
		$view_rating      = DB::table( 'product_rating' )
		                      ->where( 'prod_id', '=', $prod_id )
		                      //->groupBy( 'user_id' )
		                      ->orderBy( 'rate_id', 'desc' )
		                      ->get();


		return view( 'product', [ 'viewcount'        => $viewcount,
		                          'viewproduct'      => $viewproduct,
		                          'latestcount'      => $latestcount,
		                          'latest_product'   => $latest_product,
		                          'relatedcount'     => $relatedcount,
		                          'relatedproduct'   => $relatedproduct,
		                          'viewcount_rating' => $viewcount_rating,
		                          'view_rating'      => $view_rating,
		                          'prod_id'          => $prod_id
		] );


	}

       /* Retirar */
    function sanitizeString($str)
            {        
             // matriz de entrada
            $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

            // matriz de saída
            $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_' );

            // devolver a string
            $newstr = str_replace($what, $by, html_entity_decode($str));
            return $newstr;
      }
      
       /** Marcello :: Tratamento para trocar palavras com acento retirando somente o acento
         * 
         * @param type $str
         * @return type
         */
      public function remove_accent($str) 
        { 
          $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
          $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
          return str_replace($a, $b, $str); 
        } 

        /* Upper Search Box on the Shop Blade */
	public function avigher_search_data( Request $request ) {
       
		$category_cnt = DB::table( 'category' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->orderBy( 'cat_name', 'asc' )->count();


		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->where( 'status', '=', 1 )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();
                
                $sellers_count = DB::table( 'users' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'wirecard_app_data', '!=', '' )
		                  ->where( 'admin', '=', 2 )
		                  ->orderBy( 'name', 'asc' )->count(); 

		$sellers = DB::table( 'users' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'wirecard_app_data', '!=', '' )
		                  ->where( 'admin', '=', 2 )
		                  ->orderBy( 'name', 'asc' )->get();


		$pager = "";
		$type  = "";
		$id    = "";
                $name = "";
                $sellers_id = "";
                $prices = "all";
                $list_prices = array('all','0_50','50_100','100_200','200_500','500_10000');
                
		$data = $request->all();                                        
                
		if ( ! empty( $data['category'] ) ) { 

			if ( $data['category'] == "all" && empty( $data['search_text'] ) ) { 
				$category_field = $data['category'];
				$viewcount      = DB::table( 'product' )
				                    ->where( 'delete_status', '=', '' )
				                    ->where( 'prod_status', '=', 1 )
				                    ->orderBy( 'prod_id', 'desc' )
				                    ->count();

				$viewproduct = DB::table( 'product' )
				                 ->where( 'delete_status', '=', '' )
				                 ->where( 'prod_status', '=', 1 )
				                 ->orderBy( 'prod_id', 'desc' )
				                 ->get();


			} else if ( $data['category'] != "all" && empty( $data['search_text'] ) ) {
                            
				$pieces         = explode( "_", $data['category'] );
				$id             = $pieces[0];
				$type           = $pieces[1];
				$category_field = $data['category'];                             
                      
                                
                              if($type=='cat'){ 
                                  
                                  $viewcount = DB::select("select count(*) as count from product inner join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id = $id where product.delete_status = '' and prod_status = 1 order by prod_id desc");
                                        //DB::select("SELECT count(*) as count FROM product WHERE delete_status = '' AND prod_status = 1 AND (prod_category = $id{$params_str}) ORDER BY prod_id = ?",['desc']);
                                  $viewcount = $viewcount[0]->count;
                                  
                                  $viewproduct = DB::select("select * from product inner join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id = $id where product.delete_status = '' and prod_status = 1 order by prod_id desc");
                             
                                  
                              }else if($type=='subcat'){ 
                                 
                                  $viewcount = DB::select("select count(*) as count from product where product.delete_status = '' and prod_status = 1 and prod_category = $id order by prod_id desc");
                                        //DB::select("SELECT count(*) as count FROM product WHERE delete_status = '' AND prod_status = 1 AND (prod_category = $id{$params_str}) ORDER BY prod_id = ?",['desc']);
                                  $viewcount = $viewcount[0]->count;
                                  
                                  $viewproduct = DB::select("select * from product where product.delete_status = '' and prod_status = 1 and prod_category = $id order by prod_id desc");
                             
                              }else{
                                  $viewcount = DB::select("select count(*) as count from product inner join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id = $id where product.delete_status = '' and prod_status = 1 order by prod_id desc");
                                        //DB::select("SELECT count(*) as count FROM product WHERE delete_status = '' AND prod_status = 1 AND (prod_category = $id{$params_str}) ORDER BY prod_id = ?",['desc']);
                                  $viewcount = $viewcount[0]->count;
                                  
                                  $viewproduct = DB::select("select * from product inner join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id = $id where product.delete_status = '' and prod_status = 1 order by prod_id desc");
                              }     
			}

		} else {
			$category_field = "";
		}
               


		if ( ! empty( $data['category'] ) && ! empty( $data['search_text'] ) ) {                        
                
                        // Marcello :: inclusao do desc e tags
			if ( $data['category'] == "all" ) { 
				$category_field = $data['category'];
                                
                                // Marcello :: Retira o acento sem retirar a letra
                                $search_txt     = $this->remove_accent($data['search_text']);    
                                $search_accent = htmlentities($data['search_text'], ENT_COMPAT, "UTF-8");                       

                                                                       
                                $viewcount      = DB::table( 'product' )
				                    ->where( 'delete_status', '=', '' )
				                    ->where( 'prod_status', '=', 1 )
                                                    ->where(function($q) use ($search_txt,$search_accent) { // $search_txt is the search term on the query string
                                                        $q->where('prod_name', 'LIKE', '%' . $search_txt . '%')
                                                        ->orWhere('prod_desc', 'LIKE', '%' . $search_txt . '%')
                                                        ->orWhere('prod_desc', 'LIKE', '%' . $search_accent . '%')
                                                        ->orWhere('prod_tags', 'LIKE', '%' . $search_txt . '%');
                                                     })
				                    ->orderBy( 'prod_id', 'desc' )
				                    ->count();
				$viewproduct    = DB::table( 'product' )
				                    ->where( 'delete_status', '=', '' )
				                    ->where( 'prod_status', '=', 1 )
				                    ->where(function($q) use ($search_txt,$search_accent) { // $search_txt is the search term on the query string
                                                        $q->where('prod_name', 'LIKE', '%' . $search_txt . '%')
                                                        ->orWhere('prod_desc', 'LIKE', '%' . $search_txt . '%')
                                                        ->orWhere('prod_desc', 'LIKE', '%' . $search_accent . '%')
                                                        ->orWhere('prod_tags', 'LIKE', '%' . $search_txt . '%');
                                                     })
				                    ->orderBy( 'prod_id', 'desc' )
				                    ->get();
			} else {

                                // Marcello :: inclusao do desc e tags
				$category_field = $data['category'];
				$search_txt     = $this->remove_accent($data['search_text']);
                                $search_accent = htmlentities($data['search_text'], ENT_COMPAT, "UTF-8");
                                
				$pieces         = explode( "_", $data['category'] );
				$id             = $pieces[0];
				$type           = $pieces[1];

                                if($type=='cat'){  
                                  
                                  $viewcount = DB::select("select count(*) as count from product inner join subcategory on"
                                          . " product.prod_category = subcategory.subid and subcategory.cat_id = $id "
                                          . "where product.delete_status = '' and "
                                          . "(prod_name LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_accent%' or "
                                          . "prod_tags LIKE '%$search_txt%') and "
                                          . "prod_status = 1 "
                                          . "order by prod_id desc");
                                  
                                  $viewcount = $viewcount[0]->count;
                                  
                                  $viewproduct = DB::select("select * from product inner join subcategory on"
                                          . " product.prod_category = subcategory.subid and subcategory.cat_id = $id "
                                          . "where product.delete_status = '' and "
                                          . "(prod_name LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_accent%' or "
                                          . "prod_tags LIKE '%$search_txt%') and "
                                          . "prod_status = 1 "
                                          . "order by prod_id desc");
                             
                                  
                              }else if($type=='subcat'){                                    
                                  
                                  $viewcount = DB::select("select count(*) as count from product "
                                          . "where product.delete_status = '' and prod_status = 1 and "
                                          . "(prod_name LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_accent%' or "
                                          . "prod_tags LIKE '%$search_txt%') and "
                                          . "prod_category = $id order by prod_id desc");
                                  $viewcount = $viewcount[0]->count;
                                  
                                  $viewproduct = DB::select("select * from product "
                                          . "where product.delete_status = '' and prod_status = 1 and "
                                          . "(prod_name LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_accent%' or "
                                          . "prod_tags LIKE '%$search_txt%') and "
                                          . "prod_category = $id order by prod_id desc");                      
                              }                              
			}

		} else {
			$category_field = "";
			$search_txt     = "";
		}
                
		return view( 'shop', [ 'category'       => $category,
		                       'category_cnt'   => $category_cnt,
		                       'id'             => $id,
		                       'viewproduct'    => $viewproduct,
		                       'viewcount'      => $viewcount,
		                       'pager'          => $pager,
		                       'type'           => $type,
		                       'typers'         => $typers,
		                       'typers_count'   => $typers_count,
		                       'name'           => $name,
		                       'category_field' => $category_field,
		                       'search_txt'     => $search_txt,
                                       'sellers_count'  => $sellers_count,
                                       'sellers'        => $sellers,
                                       'sellers_id'     => $sellers_id,
                                       'list_prices'    => $list_prices,
                                       'price'          => $prices
		] );
	}

         /* Right Specific Search Attibutes on the Shop Blade */
	public function avigher_search_all_data( Request $request ) { 
            
		$pager = "";
		$type  = "";
		$id    = "";
              
                $name = "";   
                $sellers_id = ""; 
                
                $prices = "all";
                $list_prices = array('all','0_50','50_100','100_200','200_500','500_10000');
                 
                $search_sql = "";
                $search_sql_cnt = "";
                $search_where = "";      
                $search_order = '  ORDER BY prod_id ASC';
                        
		$data = $request->all();      
                
                $search_txt = $data['search_txt'];
               
                
		if ( ! empty( $data['inside_category'] ) ) { 

			if ( $data['inside_category'] == "all" ) {
                            
                            $category_field = $data['inside_category'];
                            
                            /* basic sql */
                            $search_sql = ' Select * from product ';
                            $search_sql_cnt = ' Select count(*) as count from product ';
                            $search_where = ' where product.delete_status="" and product.prod_status=1 ';                        
                            
			} 
                        else{  
                                $category_field = $data['inside_category'];
                                
				$pieces         = explode( "_", $data['inside_category'] );
				$id             = $pieces[0];
				$type           = $pieces[1];
				
                               
                              if($type=='cat'){
                                  
                                  $search_sql = 'Select * from product join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id ='.$id ;
                                  $search_sql_cnt = 'Select count(*) as count from product join subcategory on product.prod_category = subcategory.subid and subcategory.cat_id ='.$id ;
                                  $search_where = ' where product.delete_status="" and product.prod_status=1 '; 
                                  
                              }else if($type=='subcat'){
                                 
                                  $search_sql = ' select * from product ';
                                  $search_sql_cnt = ' select count(*) as count from product ';
                                  $search_where = ' where product.delete_status="" and product.prod_status=1 and product.prod_category ='.$id ; 
                                     
                              }else{
                                  
                                  $search_sql = ' Select * from product ';
                                  $search_sql_cnt = ' Select count(*) as count from product ';
                                  $search_where = ' where product.delete_status="" and product.prod_status=1 ';   
                              }              
			}
		} else {
			$category_field = "";
		}
                
                if(empty($data['price'])){  $prices = 'all'; }
                else{  $prices = $data['price']; }         
                
                 /* check price  if not all than check price range */
                if(!($prices == 'all')){
                  
                    //$prices = $data['price'];
                    $price  = explode( "_", $prices );
                    $price1 = $price[0];
                    $price2 = $price[1];
                    
                    // Check only for products without discount
                    $search_where .= ' and ( ( (product.prod_price >'. $price1.' and product.prod_price <'. $price2.') '
                            . ' and product.prod_offer_price = 0) '; 
                    
                    // Check only for products with discount
                    $search_where .= ' || ( (product.prod_offer_price >'. $price1.' and product.prod_offer_price <'. $price2.') '
                            . ' and product.prod_offer_price != 0) )'; 
                }
                
                /* check Seller */
                if ( ! empty( $data['seller'] ) ) {
                   
                    $array = $data['seller'];
                    $val = "";
                    $sel_id = "";    
			foreach ( $array as $value ) {
				$sel_id .= $value . ',';                                
                                $val .= ' product.user_id='.$value.' ||';
                               }   
                        $sellers_id =  rtrim( $sel_id, ',' ); 
                        $search_sql_sellers = rtrim( $val, '||' ); 
              
                    $search_where .= ' and ( '.$search_sql_sellers.' ) ';    
              }
              
                
                /* check attribute Marca */
                if ( ! empty( $data['attribute'] ) ) {
                   
                    $array = $data['attribute'];
                    $val = "";
                    $nom = "";    
			foreach ( $array as $value ) {
				$nom .= $value . ',';                                
                                $val .= ' FIND_IN_SET('.$value.',product.prod_attribute) ||';
                               }   
                        $name =  rtrim( $nom, ',' ); 
                        $val_att = rtrim( $val, '||' ); 
                        
                        $search_where .= ' and ('.$val_att.')';     
                             
                       /*
                      SELECT product.prod_id,product.prod_name,product.prod_attribute from product
                     * join  product_attribute_value on product_attribute_value.value_id = 
                     * FIND_IN_SET('8',product.prod_attribute) ORDER BY `prod_id`  ASC                                  
                     */
              }              
              
               /* check Seach Text */
                if ( ! empty( $data['search_txt'] ) ) {
                   
                    $search_txt     = $this->remove_accent($data['search_txt']);
                    $search_accent = htmlentities($data['search_txt'], ENT_COMPAT, "UTF-8");
                                
                       $search_where .=  " and (prod_name LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_txt%' or "
                                          . "prod_desc LIKE '%$search_accent%' or "
                                          . "prod_tags LIKE '%$search_txt%') ";  
              }                         
              
              
              /* SAFE AGAINST SQL INJECTION
               * 
               * $someVariable = Input::get("some_variable");
               * 
               * $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"),
               *  array( 'somevariable' => $someVariable, ));
               * 
               */
               
              
              $final_sql = $search_sql.$search_where.$search_order;  
              $final_sql_cnt = $search_sql_cnt.$search_where.$search_order; 
              //print_r($final_sql); exit();
              
              $viewproduct = DB::select( DB::raw($final_sql));   
              $viewcount = DB::select( DB::raw($final_sql_cnt));
              $viewcount = $viewcount[0]->count; 
              
                    
		$category_cnt = DB::table( 'category' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->orderBy( 'cat_name', 'asc' )->count();


		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->where( 'status', '=', 1 )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();
                
                $sellers_count = DB::table( 'users' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'wirecard_app_data', '!=', '' )
		                  ->where( 'admin', '=', 2 )
		                  ->orderBy( 'name', 'asc' )->count(); 

		$sellers = DB::table( 'users' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'wirecard_app_data', '!=', '' )
		                  ->where( 'admin', '=', 2 )
		                  ->orderBy( 'name', 'asc' )->get();
   		
                
		return view( 'shop', [ 'category'       => $category,
		                       'category_cnt'   => $category_cnt,
		                       'id'             => $id,
		                       'viewproduct'    => $viewproduct,
		                       'viewcount'      => $viewcount,
		                       'pager'          => $pager,
		                       'type'           => $type,
		                       'typers'         => $typers,
		                       'typers_count'   => $typers_count,
		                       'name'           => $name,
		                       'category_field' => $category_field,
                                       'search_txt'     => $search_txt,
                                       'sellers_count'  => $sellers_count,
                                       'sellers'        => $sellers,
                                       'sellers_id'     => $sellers_id,
                                       'list_prices'    => $list_prices,
                                       'price'          => $prices
		] );
	}
        

	public function avigher_category( $type, $id, $slug ) {

			$category_cnt = DB::table( 'category' )
			                  ->where( 'delete_status', '=', '' )
			                  ->where( 'status', '=', 1 )
			                  ->orderBy( 'cat_name', 'asc' )->count();


			$category = DB::table( 'category' )
			              ->where( 'delete_status', '=', '' )
			              ->where( 'status', '=', 1 )
			              ->orderBy( 'cat_name', 'asc' )->get();


		if ( $type == 'subcat' ) {
			$viewcount = DB::table( 'product' )
			               ->where( 'delete_status', '=', '' )
			               ->where( 'prod_status', '=', 1 )
			               ->where( 'prod_category', '=', $id )
			               ->where( 'prod_cat_type', '=', $type )
			               ->count();

			$viewproduct = DB::table( 'product' )
			                 ->where( 'delete_status', '=', '' )
			                 ->where( 'prod_status', '=', 1 )
			                 ->where( 'prod_category', '=', $id )
			                 ->where( 'prod_cat_type', '=', $type )
			                 ->orderBy( 'prod_id', 'desc' )
			                 ->get();
		}
		if ( $type == 'cat' ) {
			$sub_category_cnt = DB::table( 'subcategory' )
			                      ->where( 'delete_status', '=', '' )
			                      ->where( 'status', '=', 1 )
			                      ->where( 'cat_id', '=', $id )
			                      ->orderBy( 'subcat_name', 'asc' )->count();


			$sub_category = DB::table( 'subcategory' )
			                  ->where( 'delete_status', '=', '' )
			                  ->where( 'status', '=', 1 )
			                  ->where( 'cat_id', '=', $id )
			                  ->orderBy( 'subcat_name', 'asc' )->get();

			$params_arr = array();
			if ($sub_category_cnt >= 1){
				foreach ($sub_category as $i => $sub_cat){
					if ($i == 0){
						$params_arr[] = ' OR prod_category = '.$sub_cat->subid;
					} else {
						$params_arr[] = 'prod_category = ' . $sub_cat->subid;
					}
				}
			}
			$params_str = implode(' OR ',$params_arr);
			$viewproduct = DB::select("SELECT * FROM product WHERE delete_status = '' AND prod_status = 1 AND (prod_category = $id{$params_str}) ORDER BY prod_id = ?",['desc']);
			$viewcount = DB::select("SELECT count(*) as count FROM product WHERE delete_status = '' AND prod_status = 1 AND (prod_category = $id{$params_str}) ORDER BY prod_id = ?",['desc']);
			$viewcount = $viewcount[0]->count;

		}


		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();


		$pager = "";
		$type  = "";
                $prices = "";
                $search_txt = "";

		return view( 'shop', [ 'category'     => $category,
		                       'category_cnt' => $category_cnt,
		                       'id'           => $id,
		                       'viewproduct'  => $viewproduct,
		                       'viewcount'    => $viewcount,
		                       'pager'        => $pager,
		                       'type'         => $type,
		                       'typers'       => $typers,
		                       'typers_count' => $typers_count,
                                       'price'          => $prices,
                                       'search_txt'     => $search_txt
		] );

	}


	public function avigher_all_category() {

		$category_cnt = DB::table( 'category' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->orderBy( 'cat_name', 'asc' )->count();


		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->where( 'status', '=', 1 )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$id = "";

		$viewcount = DB::table( 'product' )
		               ->where( 'delete_status', '=', '' )
		               ->where( 'prod_status', '=', 1 )
		               ->count();

		/** Marcello - Foi adicionado ->orderBy('prod_featured','desc')  para ordenar tambem por feature (yes) **/
		$viewproduct = DB::table( 'product' )
		                 ->where( 'delete_status', '=', '' )
		                 ->where( 'prod_status', '=', 1 )
		                 ->orderBy( 'prod_featured', 'desc' )
		                 ->orderBy( 'prod_id', 'desc' )
		                 ->get();


		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();


		$pager = "";
		$type  = "";

		return view( 'shop', [ 'category'     => $category,
		                       'category_cnt' => $category_cnt,
		                       'id'           => $id,
		                       'viewproduct'  => $viewproduct,
		                       'viewcount'    => $viewcount,
		                       'pager'        => $pager,
		                       'type'         => $type,
		                       'typers'       => $typers,
		                       'typers_count' => $typers_count
		] );


	}


	public function avigher_sort_category( $sort, $type ) {


		$category_cnt = DB::table( 'category' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->orderBy( 'cat_name', 'asc' )->count();


		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->where( 'status', '=', 1 )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$id = "";

		if ( $type == "name" ) {
			$viewcount = DB::table( 'product' )
			               ->where( 'delete_status', '=', '' )
			               ->where( 'prod_status', '=', 1 )
			               ->orderBy( 'prod_name', 'asc' )
			               ->count();

			$viewproduct = DB::table( 'product' )
			                 ->where( 'delete_status', '=', '' )
			                 ->where( 'prod_status', '=', 1 )
			                 ->orderBy( 'prod_name', 'asc' )
			                 ->get();
		} else if ( $type == "price" ) {
			$viewcount = DB::table( 'product' )
			               ->where( 'delete_status', '=', '' )
			               ->where( 'prod_status', '=', 1 )
			               ->orderBy( 'prod_price', 'asc' )
			               ->count();

			$viewproduct = DB::table( 'product' )
			                 ->where( 'delete_status', '=', '' )
			                 ->where( 'prod_status', '=', 1 )
			                 ->orderBy( 'prod_price', 'asc' )
			                 ->get();
		}


		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();


		$pager = "";

		return view( 'shop', [ 'category'     => $category,
		                       'category_cnt' => $category_cnt,
		                       'id'           => $id,
		                       'viewproduct'  => $viewproduct,
		                       'viewcount'    => $viewcount,
		                       'pager'        => $pager,
		                       'type'         => $type,
		                       'typers'       => $typers,
		                       'typers_count' => $typers_count
		] );


	}


	public function avigher_pager_category( $pager ) {

		$category_cnt = DB::table( 'category' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->orderBy( 'cat_name', 'asc' )->count();


		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->where( 'status', '=', 1 )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$id = "";

		$viewcount = DB::table( 'product' )
		               ->where( 'delete_status', '=', '' )
		               ->where( 'prod_status', '=', 1 )
		               ->offset( 0 )
		               ->limit( $pager )
		               ->count();

		$viewproduct = DB::table( 'product' )
		                 ->where( 'delete_status', '=', '' )
		                 ->where( 'prod_status', '=', 1 )
		                 ->offset( 0 )
		                 ->limit( $pager )
		                 ->get();


		$typers_count = DB::table( 'product_attribute_type' )
		                  ->where( 'delete_status', '=', '' )
		                  ->where( 'status', '=', 1 )
		                  ->where( 'search', '=', 1 )
		                  ->orderBy( 'attr_name', 'asc' )->count();

		$typers = DB::table( 'product_attribute_type' )
		            ->where( 'delete_status', '=', '' )
		            ->where( 'status', '=', 1 )
		            ->where( 'search', '=', 1 )
		            ->orderBy( 'attr_name', 'asc' )->get();


		$type = "";

		return view( 'shop', [ 'category'     => $category,
		                       'category_cnt' => $category_cnt,
		                       'id'           => $id,
		                       'viewproduct'  => $viewproduct,
		                       'viewcount'    => $viewcount,
		                       'pager'        => $pager,
		                       'type'         => $type,
		                       'typers'       => $typers,
		                       'typers_count' => $typers_count
		] );


	}


	public function avigher_edit_product( $token ) {


		$userid   = Auth::user()->id;
		$category = DB::table( 'category' )
		              ->where( 'delete_status', '=', '' )
		              ->orderBy( 'cat_name', 'asc' )->get();

		$product_type = array( "normal", "external" );


		$typer_admin_count = DB::table( 'product_attribute_type' )
		                       ->where( 'delete_status', '=', '' )
		                       ->where( 'status', '=', 1 )
		                       ->orderBy( 'attr_name', 'asc' )->count();

		$typer_admin = DB::table( 'product_attribute_type' )
		                 ->where( 'delete_status', '=', '' )
		                 ->where( 'status', '=', 1 )
		                 ->orderBy( 'attr_name', 'asc' )->get();


		$viewcount = DB::table( 'product' )
		               ->where( 'prod_token', '=', $token )
		               ->count();

		$viewproduct = DB::table( 'product' )
		                 ->where( 'prod_token', '=', $token )
		                 ->get();

		return view( 'edit-product', [ 'category'          => $category,
		                               'product_type'      => $product_type,
		                               'typer_admin'       => $typer_admin,
		                               'typer_admin_count' => $typer_admin_count,
		                               'viewcount'         => $viewcount,
		                               'viewproduct'       => $viewproduct
		] );


	}


}
