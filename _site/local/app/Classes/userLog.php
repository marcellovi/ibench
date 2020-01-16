<?php
namespace Responsive\Classes;

use Illuminate\Support\Facades\DB;

class userLog{

   /* Register an error in the DB when it occurs in the platform */
   public function registerErrorLog( \Exception $e, $id, $type_error='default',$custom_msg_error='') {
        
           $ip = UserInfo::get_ip();
           $device = UserInfo::get_device();
           $os = UserInfo::get_os();
           $browser = UserInfo::get_browser();  
           //$error = $e->__toString(); 
                      
           /* Convert Array to String */
           $errors = serialize($e->getErrors());
                      
           $trace_error = $e->__toString(); 
            
           $result_error = '<b>Error Message:</b> '.$e->getMessage().'<br>';
           $result_error .= '<b>Error Code:</b> '.$e->getCode().'<br>';
           $result_error .= '<b>Error Line:</b> '.$e->getLine().'<br>';
           $result_error .= '<b>Trace Errors:</b> '.$errors.'<br>';            
            
           $result_error .='<b>Error Trace class:</b> '.$trace_error.'<br>';        
           $result_error .= '<b>Custom Mensage:</b> '.$custom_msg_error;
           
           date_default_timezone_set('America/Sao_Paulo');
           $date = date('Y-m-d h:i:s', time());       
        
        
         /* Saving the Data in the User_Logs Table */ 
         DB::insert('insert into user_logs (user_id, ip, device, os, '
                . 'browser, type_error, msg_error, error_created_at) '
                . 'values (?,?,?,?,?,?,?,?)', [$id, $ip, $device, $os,
                    $browser, $type_error, $result_error, $date]); 
    }
    
    /* Delete Logs from Users */
    public function destroyUserLogs($id) {  
        /** Deletando Logs  * */
        DB::delete('delete from user_logs where id = ?', [$id]);
    }
    
    /* User Logs when an Error occur in the platform */
    public function userLogs() { 
        /* */
        try{
            trigger_error("Cannot divide by zero", E_USER_ERROR);
        } catch (\Exception $ex) {
            $this->registerErrorLog($ex,135,'titulo erro','msg customizada erro');
            print_r('passou');//exit();
        }
       
	}

}