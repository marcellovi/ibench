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
        $category = DB::Table('subcategory')->select('subcat_name')
                ->where('status', 1)
                ->where('delete_status', '')
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

    /* Download the Model to import products on demand */
    public function downloadExcelModel() {

        $userid = Auth::user()->id;
        $type = 'xls';

        $data = ['Nome', 'Categoria', 'Marca', 'Descricao', 'Quantidade', 'Preco', 'Preco_Promocional', 'Tags'];
        // Headings//
        //$headers[] = ['Id', 'Name'];
        $tempname = "planilha_modelo_" . uniqid();
        return Excel::create($tempname, function($excel) use ($data) {
                    $excel->sheet('produtos', function($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download($type);
    }

    public function importExcel(Request $request)
    {      
        if($request->hasFile('import_file')){ 			
	
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {			
		
                $userid = Auth::user()->id;
                $status_seller = $this->checkStatusSeller($userid);
                $prod_visibility = $this->checkProdVisibility($status_seller);
                $prod_type = 'fisico';
                $prod_sub_cat_type = 'subcat';
				
                foreach ($reader->toArray() as $key => $row) 
		{                 
                    if(!empty($row)){ 
                                /*
				if(!empty($row['prod_tags'])){ $prod_tags = $row['prod_tags']; } else { $prod_tags = ""; }
				if(!empty($data['prod_offer_price'])) { $prod_offer_price = $data['prod_offer_price']; } else { $prod_offer_price = 0; }
				if(!empty($data['prod_featured'])) { $prod_featured = $data['prod_featured']; } else { $prod_featured = ""; }
				if(!empty($row['prod_zipfile'])) { $prod_zipfile = $row['prod_zipfile']; } else { $prod_zipfile = ""; }
				if(!empty($row['prod_external_url'])) { $prod_external_url = $row['prod_external_url']; } else { $prod_external_url = ""; }
				if(!empty($row['delete_status'])){ $delete_status = $row['delete_status']; } else { $delete_status = ""; }
				if(!empty($row['prod_available_qty'])) { $prod_available_qty = $row['prod_available_qty']; } else { $prod_available_qty = 0; }
				if(!empty($row['prod_attribute'])) { $prod_attribute = $row['prod_attribute']; } else { $prod_attribute = ""; } 
				*/
                             	if(!empty($row['categoria'])) { $prod_categoria = $this->findCategory($row['categoria']); } else { $prod_categoria = ''; } // Se nao for preenchido vai para Outros
				if(!empty($row['descricao'])) { $prod_desc = $this->formatDescription($row['descricao']); } else { $prod_desc = ""; }
				if(!empty($row['nome'])) { $prod_name = $row['nome']; } else { $prod_name = "Sem Nome"; }
				if(!empty($row['tags'])) { $prod_tags = $row['tags']; } else { $prod_tags = ""; }
				if(!empty($row['preco'])){ $prod_price = $row['preco']; } else { $prod_price = 0; }
				if(!empty($row['preco_promocional'])) { $prod_offer_price = $row['preco_promocional']; } else { $prod_offer_price = 0; }
                                if(!empty($row['quantitdade'])){ $prod_available_qty = $row['quantitdade']; } else { $prod_available_qty = 0; }
				if(!empty($row['marca'])) { $prod_attribute = $this->findAttribute($row['marca']); } else { $prod_attribute = ""; } 
				//if(!empty($row['marca'])) { $prod_attribute = 1; } else { $prod_attribute = 0; } 
                                //if(!empty($row['categoria'])) { $prod_categoria = 1; } else { $prod_categoria = 0; }
                                
                        $data['user_id'] = $userid;
                        //$data['prod_token'] = $row['prod_token'];
                        $data['prod_token'] = uniqid();
                        $data['prod_slug'] = $this->normalizeString($prod_name);                    
                        $data['prod_category'] = $prod_categoria;
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

                        if(!empty($data)) {
                            DB::table('product')->insert($data);						
                        }
                    }else{ break; }  // leaves when empty row on excel 
                }
            });
            
            return back()->with('success', 'Importado com Sucesso');	
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
        
        /* */
        public function findAttribute($attcatname){
            
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
	
        /* */
        public function findCategory($subcategname){
            
            $scategname = strtolower($subcategname); // Fazer tratamento no texto para caracteres
            
            $subcount = DB::table('subcategory')
		->where('delete_status','=','')
		->where('status','=',1)
                ->where('delete_status','=','')
                ->whereRaw('LOWER(subcat_name) = ?', [$scategname])->count();
							
		if(!empty($subcount)){
                    $subcategory = DB::table('subcategory')
                    ->where('delete_status','=','')
                    ->where('status','=',1)
                    ->where('delete_status','=','')
                    ->whereRaw('LOWER(subcat_name) = ?', [$scategname])
                    ->orderBy('subcat_name', 'asc')->get();
                    
                    return $subcategory[0]->subid;
                }else{
                    return 13; // Categoria Equipamento >> Subcategoria Outros ( DoTo: Descobrir o que fazer neste caso )
                }
        }
}
