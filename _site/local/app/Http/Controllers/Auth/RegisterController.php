<?php

namespace Responsive\Http\Controllers\Auth;

use Responsive\User;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use URL;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {       
       
        /** 
         * Marcello - fazendo retornar uma validacao customizavel 
         * Obs.: Foi incluido na pasta lang o arquivo validation.php 
         * onde ele customiza e valida todas as mensagens de erro abaixo.
         */
        $validator = Validator::make($data, [
            'name' => 'required|regex:/^[\w-]*$/|unique:users|max:255',
            'full_name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
	    'phone' => 'string|min:9|max:100',
            'password' => 'required|string|min:6|confirmed',
	    'cpf_cnpj' => 'required|string|min:9|unique:users',
            //'cktermuse' => 'required',
            
	    'g-recaptcha-response' => 'required|captcha',
			
        ]);
        
        return $validator;

        /** Marcello - Testes que eu fiz - Desconsiderar
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('name', 'Something is wrong with this field!');
            }
        });
        
        if ($validator->fails()) {
    
            }
         * 
         */
    }
    
    public function checagem_cpf_cnpj(){
        
        /** $dataVal sera utilizado no validador com o cpf/cnpj boolean **/
      $dataVal = $data;
      
     /**  Marcello - Variaveis Customizaveis     */
     $validou = false;
    
        $cpf_cnpj = $dataVal['cpf_cnpj'];
        
        /** somente fica com os numeros **/
        $cpf_cnpj = preg_replace( '/[^0-9]/is', '', $cpf_cnpj );
        
        /** Caso for 11 numeros CPF ; Se for 14 CNPJ 
         *  && 
         *  O Usuario Pesquisador "0" - CPF
         *  O usuario Fornecedor "2" - CNPJ 
         **/
        if ( strlen( $cpf_cnpj ) == 11 && $dataVal['usertype'] == 0) {
            
            /** pesquisador **/
            if($dataVal['usertype'] == 0){
                $cpf = $cpf_cnpj;

                    if ( ! function_exists('calc_digitos_posicoes') ) {
                function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
                    // Faz a soma dos dígitos com a posição
                    // Ex. para 10 posições: 
                    //   0    2    5    4    6    2    8    8   4
                    // x10   x9   x8   x7   x6   x5   x4   x3  x2
                    //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                    for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                        $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
                        $posicoes--;
                    }

                    // Captura o resto da divisão entre $soma_digitos dividido por 11
                    // Ex.: 196 % 11 = 9
                    $soma_digitos = $soma_digitos % 11;

                    // Verifica se $soma_digitos é menor que 2
                    if ( $soma_digitos < 2 ) {
                        // $soma_digitos agora será zero
                        $soma_digitos = 0;
                    } else {
                        // Se for maior que 2, o resultado é 11 menos $soma_digitos
                        // Ex.: 11 - 9 = 2
                        // Nosso dígito procurado é 2
                        $soma_digitos = 11 - $soma_digitos;
                    }

                    // Concatena mais um dígito aos primeiro nove dígitos
                    // Ex.: 025462884 + 2 = 0254628842
                    $cpf = $digitos . $soma_digitos;

                    // Retorna
                    return $cpf;
                }
            }

                // Verifica se o CPF foi enviado
                if ( ! $cpf ) {
                    return false;
                }

                // Remove tudo que não é número do CPF
                // Ex.: 025.462.884-23 = 02546288423
                $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

                // Verifica se o CPF tem 11 caracteres
                // Ex.: 02546288423 = 11 números
                if ( strlen( $cpf ) != 11 ) {
                    $validou = false;
                }   

                // Captura os 9 primeiros dígitos do CPF
                // Ex.: 02546288423 = 025462884
                $digitos = substr($cpf, 0, 9);

                // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
                $novo_cpf = calc_digitos_posicoes( $digitos );

                // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
                $novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );

                // Verifica se o novo CPF gerado é idêntico ao CPF enviado
                if ( $novo_cpf === $cpf ) {
                    // CPF válido
                    $validou = true;
                } else {
                    // CPF inválido
                    $validou = false;
                }


            }
             /** fim pesquisador **/ 
            
            
        } else if   ( strlen( $cpf_cnpj ) == 14 && $dataVal['usertype'] == 2){
            
               /** Inicio fornecedor **/    

                     $cnpj = $cpf_cnpj;
                     echo $cnpj;
                     exit();
                     


                              // Deixa o CNPJ com apenas números
                              $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );

                              // Garante que o CNPJ é uma string
                              $cnpj = (string)$cnpj;

                              // O valor original
                              $cnpj_original = $cnpj;

                              // Captura os primeiros 12 números do CNPJ
                              $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );

                              /**
                               * Multiplicação do CNPJ
                               *
                               * @param string $cnpj Os digitos do CNPJ
                               * @param int $posicoes A posição que vai iniciar a regressão
                               * @return int O
                               *
                               */
                              if ( ! function_exists('multiplica_cnpj') ) {
                                  function multiplica_cnpj( $cnpj, $posicao = 5 ) {
                                      // Variável para o cálculo
                                      $calculo = 0;

                                      // Laço para percorrer os item do cnpj
                                      for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
                                          // Cálculo mais posição do CNPJ * a posição
                                          $calculo = $calculo + ( $cnpj[$i] * $posicao );

                                          // Decrementa a posição a cada volta do laço
                                          $posicao--;

                                          // Se a posição for menor que 2, ela se torna 9
                                          if ( $posicao < 2 ) {
                                              $posicao = 9;
                                          }
                                      }
                                      // Retorna o cálculo
                                      return $calculo;
                                  }
                              }

                              // Faz o primeiro cálculo
                              $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );

                              // Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
                              // Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
                              $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );

                              // Concatena o primeiro dígito nos 12 primeiros números do CNPJ
                              // Agora temos 13 números aqui
                              $primeiros_numeros_cnpj .= $primeiro_digito;

                              // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
                              $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
                              $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );

                              // Concatena o segundo dígito ao CNPJ
                              $cnpj = $primeiros_numeros_cnpj . $segundo_digito;

                              // Verifica se o CNPJ gerado é idêntico ao enviado
                              if ( $cnpj === $cnpj_original ) {
                                  $validou = true;
                              }

               /** Fim fornecedor **/    
            
        }else {
           $validou = false;
        }
       
        
       if($validou){
           // Usado para informar que o cpf/cnpj validou e retira todos os caracteres
          $dataVal['cpf_cnpj'] = preg_replace( '/[^0-9]/is', '', $dataVal['cpf_cnpj'] );
       }else{
           $dataVal['cpf_cnpj'] = false; //"Favor verificar os campos CPF/CNPJ & Perfil Usuario";
       }
       /*** FIM DO CNPJ & CPF ****/ 
    }
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
	 
	public function clean($string) 
	{
    
     $string = preg_replace("/[^\p{L}\/_|+ -]/ui","",$string);

    
    $string = preg_replace("/[\/_|+ -]+/", '-', $string);

    
    $string =  trim($string,'-');

    return mb_strtolower($string);
	}  
	 
        
    /**
     * Verify if CPF is Valid.
     *
     * @param  array  $data
     * @return User
     */
    public function checkCPF($ckcpf) 
    {
        
        /**  Marcello - Variaveis Customizaveis     */
        $validou = false;

        /** somente fica com os numeros **/
        $cpf = preg_replace( '/[^0-9]/is', '', $ckcpf );
        
        
        /** Caso for 11 numeros CPF ; Se for 14 CNPJ 
         *  && 
         *  O Usuario Pesquisador "0" - CPF
         *  O usuario Fornecedor "2" - CNPJ 
         **/
        //if ( strlen( $cpf_cnpj ) == 11 && $dataVal['usertype'] == 0) {
            
 

            if ( ! function_exists('calc_digitos_posicoes') ) {
                function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
                    // Faz a soma dos dígitos com a posição
                    // Ex. para 10 posições: 
                    //   0    2    5    4    6    2    8    8   4
                    // x10   x9   x8   x7   x6   x5   x4   x3  x2
                    //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                    for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                        $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
                        $posicoes--;
                    }

                    // Captura o resto da divisão entre $soma_digitos dividido por 11
                    // Ex.: 196 % 11 = 9
                    $soma_digitos = $soma_digitos % 11;

                    // Verifica se $soma_digitos é menor que 2
                    if ( $soma_digitos < 2 ) {
                        // $soma_digitos agora será zero
                        $soma_digitos = 0;
                    } else {
                        // Se for maior que 2, o resultado é 11 menos $soma_digitos
                        // Ex.: 11 - 9 = 2
                        // Nosso dígito procurado é 2
                        $soma_digitos = 11 - $soma_digitos;
                    }

                    // Concatena mais um dígito aos primeiro nove dígitos
                    // Ex.: 025462884 + 2 = 0254628842
                    $cpf = $digitos . $soma_digitos;

                    // Retorna
                    return $cpf;
                }
            }

                // Verifica se o CPF foi enviado
                //if ( ! $cpf ) {
                //    return false;
                //}

                // Remove tudo que não é número do CPF
                // Ex.: 025.462.884-23 = 02546288423
                $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

                // Verifica se o CPF tem 11 caracteres
                // Ex.: 02546288423 = 11 números
                if ( strlen( $cpf ) != 11 ) {
                    $validou = false;
                }   

                // Captura os 9 primeiros dígitos do CPF
                // Ex.: 02546288423 = 025462884
                $digitos = substr($cpf, 0, 9);

                // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
                $novo_cpf = calc_digitos_posicoes( $digitos );

                // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
                $novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );

                // Verifica se o novo CPF gerado é idêntico ao CPF enviado
                if ( $novo_cpf === $cpf ) {
                    // CPF válido
                    $validou = true;
                } else {
                    // CPF inválido
                    $validou = false;
                }

                return $validou;
            //}
             /** fim pesquisador **/ 
            
            
       // } 
        
        
    
    //$string = preg_replace("/[^\p{L}\/_|+ -]/ui","",$string);    
    //$string = preg_replace("/[\/_|+ -]+/", '-', $string);    
    //$string =  trim($string,'-');   
    //return mb_strtolower($string);
   }  
   
    /**
     * Verify if CNPJ is Valid.
     *
     * @param  array  $data
     * @return User
     */
    public function checkCNPJ($string) 
    {
    
     $string = preg_replace("/[^\p{L}\/_|+ -]/ui","",$string);
    
    $string = preg_replace("/[\/_|+ -]+/", '-', $string);
    
    $string =  trim($string,'-');   

    return mb_strtolower($string);
   }  
   
   
   
   
   
   protected function register(Request $request)
    {
       
       /** Marcello Verificar CPF & CNPJ **/
        $data = $request->all();
	$validou = $this->checkCPF($data['cpf_cnpj']);
         
        /*
        if($data['cktermuse'] ==3){
            echo "marcou";
        }else{
            echo "nao marcou";
        }
        exit();
         * 
         */
         
       /* 
       if($validou){
           // Usado para informar que o cpf/cnpj validou e retira todos os caracteres
          $dataVal['cpf_cnpj'] = preg_replace( '/[^0-9]/is', '', $dataVal['cpf_cnpj'] );
       }else{
           $dataVal['cpf_cnpj'] = false; //"Favor verificar os campos CPF/CNPJ & Perfil Usuario";
       }
       */
       
       /* Marcello - Adicionei o cpf_cnpj e o checkbox do termo de uso*/
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\w-]*$/|unique:users|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'cpf_cnpj' => 'required|string|unique:users',
            //'myCheck' => 'min:2',
			//'gender' => 'required|string|max:255',
			'usertype' => 'required|string|max:255',
			'g-recaptcha-response' => 'required|captcha',
			
			
        ]);

        $data = $request->all();
		 $post_slugs = $this->clean($data['name']);
             
        /*
        $validacao = $this->checkCPF($data['cpf_cnpj']);
        if($validacao)
            echo "validacao: correta";
        else 
            echo "validacao: invalida";
        
        
        exit();
         * 
         */

        if ($validator->passes()) {

            $data = $request->all();
			
			$name = $data['name'];
                        $full_name = $data['full_name']; // Add fullname
			$post_slug = $post_slugs;
			$email = $data['email'];
			$keyval = uniqid();
			$pass = bcrypt($data['password']);
			$phoneno = $data['phone'];
			//$gender = $data['gender'];
			$usertype = $data['usertype'];
			$country = $data['country'];                        
                        $cpf_cnpj = $data['cpf_cnpj']; // Marcello - Add cpf cnpj
			
			
			$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
		$temp_id = uniqid();
			
			$confirmation = 0;
			
			// Marcello ( gender ) DB::insert('insert into users (name,post_slug,email,password,confirm_key,confirmation,gender,phone,admin,country) values (?, ?, ?, ?, ?, ?,?, ?,?,?)', [$name,$post_slug,$email,$pass,$keyval,$confirmation,$gender,$phoneno,$usertype,$country]);
			DB::insert('insert into users (name,full_name,post_slug,email,password,confirm_key,confirmation,phone,admin,country,cpf_cnpj) values (?, ?, ?, ?, ?,?, ?,?,?,?,?)', [$name,$full_name,$post_slug,$email,$pass,$keyval,$confirmation,$phoneno,$usertype,$country,$cpf_cnpj]);
			
			
				
			$admin_idd=1;
		
		$admin_email = DB::table('users')
                ->where('id', '=', $admin_idd)
                ->get();
		
		$url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.'logo_email.jpg'; // Marcello.$setts[0]->site_logo;
		
		$site_name = $setts[0]->site_name;
		
		$adminemail = $admin_email[0]->email;
		
		// $adminname = $admin_email[0]->name; Marcello 
                $adminname = "iBench Market";
		
		$datas = [
            'name' => $name, 'email' => $email, 'keyval' => $keyval, 'site_logo' => $site_logo,
			'site_name' => $site_name, 'url' => $url
        ];
		
		Mail::send('confirm_mail', $datas , function ($message) use ($adminemail,$adminname,$email)
        {
		
		
		
	    // Marcello :: Email Confirmation for Registration
            $message->subject('Confirme seu e-mail - Cadastro iBench');
			
            $message->from($adminemail, $adminname);

            $message->to($email);

        }); 
		
		
			// Marcello - Internacionalizar o texto abaixo :: We sent you an activation code. Check your email and click on the link to verify.
			return redirect('login')->with('success', 'Foi enviado o c&oacute;digo de ativa&ccedil;&atilde;o. Verifique seu email e clique no link para confirmar a verifi&ccedil;&atilde;o.');
			
			

            

        }
		else
		{
        
		  $failedRules = $validator->failed();
			 
			return back()->withErrors($validator);
        /*return redirect('login')->with('error', 'Invalid input fields. Please try again');*/
        }
	
	
	}
	
   
   
   
	
	
	
	
	protected function create(array $data)
    {
		
		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
		
		
		$name = $data['name'];
                $full_name = $data['full_name'];
		$email = $data['email'];
		$keyval = uniqid();
		$pass = bcrypt($data['password']);
			$phoneno = $data['phoneno'];
			//$gender = $data['gender'];
			$usertype = $data['usertype'];
		
		
		$temp_id = uniqid();
		
        return User::create([
            'name' => $data['name'],
            'full_name' => data['full_name'],
	    'post_slug' => $this->clean($data['name']),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
			'cpf_cnpj' => $data['cpf_cnpj'],
			//'gender' => $data['gender'],
			'phone' => $data['phone'],
			'confirmation' => 0,		
			'photo' => '',
			'country' => $data['country'],
			'admin' => $data['usertype'],
			'confirm_key' => $keyval,
			
			
			
			
			
        ]);
		
		
		$admin_idd=1;
		
		$admin_email = DB::table('users')
                ->where('id', '=', $admin_idd)
                ->get();
		
		
		$url = URL::to("/");
		
		$site_logo=$url.'/local/images/media/'.$setts[0]->site_logo;
		
		$site_name = $setts[0]->site_name;
		
		$adminemail = $admin_email[0]->email;
		
		$adminname = $admin_email[0]->name;
		
		
		
		$datas = [
            'name' => $name, 'email' => $email, 'keyval' => $keyval, 'site_logo' => $site_logo,
			'site_name' => $site_name, 'url' => $url
        ];
		
		Mail::send('confirm_mail', $datas , function ($message) use ($adminemail,$adminname,$email)
        {
		
		
		
		// Marcello :: Email Confirmation for Registration
            $message->subject('Confirma&ccedil;&atilde;o do Cadastro - iBench');
			
            $message->from($adminemail,$adminname);

            $message->to($email);

        }); 
		// Marcello :: We sent you an activation code. Check your email and click on the link to verify.
		return redirect('login')->with('success', 'Foi enviado o c&oacute;digo de ativa&ccedil;&atilde;o. Verifique seu email e clique no link para confirmar a verifi&ccedil;&atilde;o.');
		
		
		
		
    }
	
	
	
	
}
