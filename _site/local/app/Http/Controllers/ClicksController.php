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

class ClicksController extends Controller
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
    
	
	public function add_banner_clicks($id)
    {
	
        $url = URL::to("/");

        $post = DB::table('banners')
				  ->where('id', '=', $id)
				  ->get();
        $basecount = 0;
        if ($post[0]->clicks != null) {
        $basecount = $post[0]->clicks;
        $clicks = $basecount+1;
        DB::update('update banners set clicks='.$clicks.' where id = ?', [$post[0]->id]);
        } else {
            $clicks = 1;
            DB::update('update banners set clicks='.$clicks.' where id = ?', [$post[0]->id]);

        }
        
	 return redirect($post[0]->slide_btn_link);
	
	
	
    }
    
    public function add_home_banner_clicks($id)
    {
	
        $url = URL::to("/");

        $post = DB::table('home_banners')
				  ->where('id', '=', $id)
				  ->get();
        $basecount = 0;
        if ($post[0]->clicks != null) {
        $basecount = $post[0]->clicks;
        $clicks = $basecount+1;
        DB::update('update home_banners set clicks='.$clicks.' where id = ?', [$post[0]->id]);
        } else {
            $clicks = 1;
            DB::update('update home_banners set clicks='.$clicks.' where id = ?', [$post[0]->id]);

        }
        
	 return redirect($post[0]->slide_btn_link);
	
	
    }
    
    public function add_blog_clicks($post_id)
    {
        $url = URL::to("/");

        $post = DB::table('post')
		         ->where('post_status', '=', '1')
				 ->where('post_type', '=', 'blog')
				  ->where('post_slug', '=', $post_id)
				  ->get();
        $basecount = 0;
        if ($post[0]->clicks != null) {
        $basecount = $post[0]->clicks;
        $clicks = $basecount+1;
        DB::update('update post set clicks='.$clicks.' where post_id = ?', [$post[0]->post_id]);
        } else {
            $clicks = 1;
            DB::update('update post set clicks='.$clicks.' where post_id = ?', [$post[0]->post_id]);

        }
        
	 return redirect("$url/blog/$post_id");
	
	
	}
	
	
	 
	
	
}
