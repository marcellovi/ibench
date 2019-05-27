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
use Excel;
use Responsive\Product;

class ImportExportController extends Controller
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
	
    public function importExport()
    {    
       /* $category = DB::Table('subcategory')->select('subcat_name')
                ->where('status', 1)
                ->where('delete_status', '')
                ->get();  
        */
        $category = DB::Table('category')
                ->join('subcategory','subcategory.cat_id','=','category.id')
                ->where('category.status',1)
                ->where('subcategory.status',1)
                ->where('category.delete_status','')
                ->where('subcategory.delete_status','')
                ->select('category.cat_name','subcategory.subcat_name')
                ->get();
       
        $type = DB::Table('product_attribute_value')->select('attr_value')
                ->where('status', 1)
                ->where('delete_status', '')
                ->get();
        
        $data = array('category' => $category, 'type' => $type);
	return view('importExport')->with($data);
        //return view('importExport',['data' => $data]);
    }
    
    public function downloadExcel($type) {

        $userid = Auth::user()->id;

        $data = Product::where(['user_id' => $userid])->get()->toArray();
        $tempname = "planilha_ibench_" . uniqid();
        return Excel::create($tempname, function($excel) use ($data) {
                    $excel->sheet('produtos', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download($type);
    }

    /* Download the Model to import products on demand ( TODO )*/
    public function downloadExcelModel() {
        $file_name = 'planilha_importacao.xls';
        $file_path = public_path('images/');
        $headers = array( 'Content-Type: application/excell', );
        //print_r($file_path);exit();
         //return response()->download('/home/benchfind/public_html/_site/local/images/planilha_importacao.xls');
         
         return response()->download($file_path, $file_name, $headers);
         
         
        /*
        $userid = Auth::user()->id;
        $type = 'xls';

        $data = ['Nome', 'Categoria', 'SubCategoria', 'Marca', 'Descricao', 'Quantidade', 'Preco', 'Preco_Promocional', 'Tags'];
        // Headings//
        //$headers[] = ['Id', 'Name'];
        $tempname = "planilha_modelo_" . uniqid();
        return Excel::create($tempname, function($excel) use ($data) {
                    $excel->sheet('produtos', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download($type);
         * 
         */
    }
    
    /* 
     *  Returns the name of the image/photo to be used as default when mass uploading
     *  Obs.: In case there isnt any photo it will use a default image ( system default no_image.jpg )
     */
    public function getDefaultIMG($id=0){
        
        if($id==0){ return 'no_image.jpg'; }
        
        $name_img = DB::select('select photo from users where id = ?', [$id]);
        
        if(!empty($name_img[0]->photo) || $name_img[0]->photo != null ){
            return $name_img[0]->photo;
        }else{
            return 'no_image.jpg';
        }
    }

    public function importExcel(Request $request)
    {      
        $msg_error = '';
        $cnt_sucss = 0;
        
        if($request->hasFile('import_file')){ 			
	
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use (&$msg_error, &$cnt_sucss) {			
    
                $reader->takeColumns(9); // Only reads the first 9 columns   
               
                $userid = Auth::user()->id;            
                $status_seller = $this->checkStatusSeller($userid);
                $prod_visibility = $this->checkProdVisibility($status_seller);
                $prod_type = 'fisico';
                $prod_sub_cat_type = 'subcat';
                $default_img = $this->getDefaultIMG($userid); 
                
                
                foreach ($reader->toArray() as $key => $row) 
		{                
                    if(empty($row)){break;} // End of the row or all the coluns of the row is blank 
                    else if(empty($row['nome']) || empty($row['categoria']) || empty($row['marca'])){
                        //$msg_error .= 'Erro na Linha ['.($key+2).'] : Nome/Categoria/SubCategoria [Em Branco] <br>';
                    }
                    else{
                        
                       $prod_categoria_sub = $this->findCategory($row['categoria']);                               
                       $prod_attribute = $this->findAttribute($row['marca']);  
                       
                       if(empty($prod_categoria_sub) || $prod_categoria_sub == null || $prod_attribute == '' ){                                    
                            $msg_error .= 'Erro na Linha ['.($key+2).'] : Categoria/SubCategoria [N&atilde;o Est&atilde;o Relacionadas] <br>';                                 
                            
                       }else{
                            $prod_name = $row['nome'];
                          
                            if(!empty($row['descricao'])) { $prod_desc = $this->formatDescription($row['descricao']); } else { $prod_desc = ""; }
                            if(!empty($row['tags'])) { $prod_tags = $row['tags']; } else { $prod_tags = ""; }
                            if(!empty($row['preco']) && is_numeric($row['preco'])){ $prod_price = $row['preco']; } else { $prod_price = 0; $prod_visibility = 'inactive';  }
                            if(!empty($row['preco_promocional']) && is_numeric($row['preco_promocional'])) { $prod_offer_price = $row['preco_promocional']; } else { $prod_offer_price = 0; }
                            if(!empty($row['quantidade']) && is_numeric($row['quantidade'])){ $prod_available_qty = $row['quantidade']; } else { $prod_available_qty = 0; } 
                          
                            $data['user_id'] = $userid;
                            $data['prod_token'] = uniqid();
                            $data['prod_slug'] = $this->normalizeString($prod_name);                    
                            $data['prod_category'] = $prod_categoria_sub;
                            $data['prod_cat_type'] = $prod_sub_cat_type;
                            $data['prod_name'] = $prod_name;
                            $data['prod_desc'] = $prod_desc;
                            $data['prod_tags'] = $prod_tags;
                            $data['prod_price'] = $prod_price;
                            $data['prod_offer_price'] = $prod_offer_price;
                            $data['prod_featured'] = '';
                            $data['prod_type'] = $prod_type;
                            $data['prod_zipfile'] = '';
                            $data['prod_external_url'] = '';
                            $data['prod_attribute'] = $prod_attribute;
                            $data['prod_available_qty'] = $prod_available_qty;
                            $data['delete_status'] = $prod_visibility; //checkSellerStatus($delete_status);
                            $data['prod_status'] = $status_seller; //checkSellerStatus($row['prod_status']);
                            print_r($data);exit();
                            if(!empty($data)) {                               
                               $pass = true; //DB::table('product')->insert($data);
                               $cnt_sucss++;
                               if($pass){  } // DB::insert('insert into product_images (image, prod_token) values (?, ?)', [$default_img, $data['prod_token']]);   }
                            }
                        } // Fim do Else interno
                    } // Fim do Else externo                             
                } // Fim do ForEach
            });
            //$msg_error = implode(",", $msg_error);
            $msgs = array('success' => 'Importado com Sucesso! Total de '.$cnt_sucss.' produto(s) adicionado(s).', 'error' => $msg_error);
            return back()->with($msgs);	
        }             
    }

    /** Marcello - Trim & Strip Special Characters & Make String Lower Case (transformar em funcao) **/
	public function normalizeString($normalizeTxt){
                $newTxt = str_replace(' ', '',mb_strtolower($normalizeTxt)); // Retirando espacos em Branco
                $newTxt = str_replace("'", "", $newTxt); // Retirando Aspas simples
                $newTxt = str_replace('"', "", $newTxt); // Retirando Aspas duplas
                //$newTxt = preg_replace('/[^A-Za-z0-9-]/', '', $data['product_name']); // Marcello - Retirando o (automatico agora e caracteres especiais )

                $search = explode(",","ç,æ,œ,á,ã,é,í,ó,õ,ú,à,è,ì,ò,ù,â,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
                $replace = explode(",","c,ae,oe,a,a,e,i,o,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
                
                return str_replace($search, $replace, $newTxt);
        }
	
	public function formatDescription($formatTxt){
                $format_desc = strip_tags($formatTxt,"<strong><p><em><h1><h2><h3><h4><br>"); // Marcello - Tratando os Tags que podem ser salvos
                $format_desc = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $format_desc ); // Marcello - Retirando os atributos dentro das tags
                $format_desc = str_replace('"','\"',$format_desc);              
                
                return $format_desc;
        } 
	
        /* Check the status of the Seller if not blocked, deleted or inactive all products are 1 (allowed or active) */
        public function checkStatusSeller($id){
                        
                $seller = DB::table('users')->where('id', $id)->get();
                $prod_status = 0;
                
                if($seller[0]->delete_status == 'blocked' || $seller[0]->delete_status == 'inactive' || $seller[0]->delete_status == 'deleted' ){
                    $prod_status = 0;
                }else{
                    $prod_status = 1;
                }   
                return $prod_status;
        } 
        
        /* Check the status of the Seller if not blocked, deleted or inactive all delete_status are (visible) */
        public function checkProdVisibility($prod_st){
                $visibility = 'inactive';
                
                if($prod_st){
                    $visibility = ''; // product visible
                }else{
                    $visibility = 'inactive'; // product invisible
                }   
                return $visibility;
        } 
        
        /* Return all the ids of the Attribute */
        public function findAttribute_old($attcatname){
             
            $attcname = explode(',' , strtolower($attcatname)); // Fazer tratamento no texto para caracteres
            $att_id = $value = '';

            foreach($attcname as $attcname){
                $value_cnt = DB::table('product_attribute_value')
                        ->where('delete_status','=','')
			->where('status','=',1)
                        ->whereRaw('LOWER(attr_value) = ?', [$attcname])
                	->orderBy('attr_value', 'asc')->count();
                
                if(!empty($value_cnt)){
                    $value = DB::table('product_attribute_value')
                            ->where('delete_status','=','')
                            ->where('status','=',1)
                            ->whereRaw('LOWER(attr_value) = ?', [$attcname])
                            ->orderBy('attr_value', 'asc')->get();

                    $att_id .= $value[0]->value_id.","; // Separador
                }                
            }
            return rtrim($att_id,','); // Retira o ultimo ','
        }
	
        /*
         *  Return de ID withing the [..] in the Excel column "Categoria"
         */
        public function findCategory($nameCateg,$pre = 0, $pos = 0){
            $pre = strpos($nameCateg, '[')+1;
            $pos = strpos($nameCateg, ']')-$pre;
            $rest = substr($nameCateg, $pre ,  $pos ); 
            
            return $rest;
        }
        
        /*
         *  Return de ID withing the [..] in the Excel column "Marca"
         */
        public function findAttribute($nameAttribute,$pre = 0, $pos = 0){
            $pre = strpos($nameAttribute, '[')+1;
            $pos = strpos($nameAttribute, ']')-$pre;
            $rest = substr($nameAttribute, $pre ,  $pos ); 
            
            return $rest;
        }
        
        /* Find & Check if the Category is Related to the SubCategory */
        public function findCategory_old($categname,$subcategname){
                                  
            $this->stripSubCatID($categname);
            exit();
            $cname = strtolower($categname); // Fazer tratamento no texto para caracteres
            $subcname = strtolower($subcategname); // Fazer tratamento no texto para caracteres
           
            /* Find id Category */
            $cname_count = DB::table('category')
                ->select('id')
		->where('delete_status','=','')
		->where('status','=',1)
                ->whereRaw('LOWER(cat_name) = ?', [$cname])->get();    
            
            /* Check if Categ_id belongs to the Right SubCategory */
            $subcname_count = DB::table('subcategory')
                ->select('subid')
		->where('delete_status','=','')
		->where('status','=',1)
                ->whereRaw('cat_id = ?')
                ->whereRaw('LOWER(replace(subcat_name, ",", "")) = ?', [$cname_count[0]->id,$subcname])->get();
                //->whereRaw('LOWER(subcat_name) = ?', [$cname_count[0]->id,$subcname])->get();        
              
            /* Find id Sub-Category 
            $subcname_count = DB::table('subcategory')
                ->select('cat_id')
		->where('delete_status','=','')
		->where('status','=',1)
                ->whereRaw('LOWER(subcat_name) = ?', [$subcname])->get();
             */
             
            if(empty($cname_count[0]) || empty($subcname_count[0])){
                return 0; 
            }
            return $subcname_count[0]->subid;
 
            /* If they exist check if they are related 		
            $subcategory_count = DB::table('category')
                    ->select('category.id','subcategory.subid')
                    ->join('subcategory','category.id','=','subcategory.cat_id')
                    ->where('category.delete_status','=','')
                    ->where('subcategory.delete_status','=','')
                    ->where('category.status','=',1)
                    ->where('subcategory.status','=',1)
                    ->whereRaw('category.id = ?', [$cname_count])
                    ->whereRaw('subcategory.cat_id = ?', [$cname_count])
                    ->orderBy('subcat_name', 'asc')->count();
            */ 
        }              
}
