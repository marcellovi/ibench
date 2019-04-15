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

class BannersController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $slideshow = DB::table('banners')
		                ->orderBy('id','desc')
					   ->get();

        return view('admin.banners', ['slideshow' => $slideshow]);
    }
	
	
	protected function delete_all(Request $request)
    {
		
		
	   $data = $request->all();
	   $slide_id = $data['slide_id'];
	   
	   foreach($slide_id as $postt)
	   {
	      
		$image = DB::table('slideshow')->where('id', $postt)->first();
		$orginalfile=$image->slide_image;
		$testimonialphoto="/media/";
       $path = base_path('images'.$testimonialphoto.$orginalfile);
	  File::delete($path);
      DB::delete('delete from slideshow where id = ?',[$postt]);
	   
	   }
	
	return back();
	
	
	}

	public function addBannerForm() {
	   
		return view('admin.add-banner');
	 }

	protected function addNewBanner(Request $request) {
		
 
		$data = $request->all();
			
 			
		$input['name'] = Input::get('name');
       
		$settings = DB::select('select * from settings where id = ?',[1]);
		$imgsize = $settings[0]->image_size;
		$imgtype = $settings[0]->image_type;	
		  
		  
		$rules = array(
		
		
		'photo' => 'max:'.$imgsize.'|mimes:'.$imgtype
		
		
		);

		
		
		
		$messages = array(
            
            
			
        );

		$validator = Validator::make(Input::all(), $rules, $messages);
		
		

		if ($validator->fails())
		{
			$failedRules = $validator->failed();
			return back()->withErrors($validator);
		}
		else
		{  
		  	
		
		
		
		$image = Input::file('photo');
        if($image!="")
		{	
            $testimonialphoto="/media/";
			$filename  = time() . '.' . $image->getClientOriginalExtension();
        
            $path = base_path('images'.$testimonialphoto.$filename);
			$destinationPath=base_path('images'.$testimonialphoto);
      
                 Image::make($image->getRealPath())->resize(555, 180)->save($path);
				/*Input::file('photo')->move($destinationPath, $filename);*/
				$savefname=$filename;
		}			
		$position = $data['position'];
		$slide_btn_link = $data['slide_btn_link'];
		$slide_status = $data['slide_status'];

	

		DB::insert('insert into banners (position,slide_btn_link,slide_image,slide_status) values (?, ? ,?,?)', [$position,$slide_btn_link,$filename,$slide_status]);
		
		return back()->with('success', 'Record has been added');
	}
	 
	}
	
	
	public function destroy($id) {
		
		$image = DB::table('banners')->where('id', $id)->first();
		$orginalfile=$image->slide_image;
		$testimonialphoto="/media/";
       $path = base_path('images'.$testimonialphoto.$orginalfile);
	  File::delete($path);
      DB::delete('delete from banners where id = ?',[$id]);
	   
      return back();
   }
	
}