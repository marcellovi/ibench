<?php
	use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		$headertype = $setts[0]->header_type;
	?>
<!DOCTYPE html>

<html class="no-js"  lang="en">
<head>
		

   @include('style')
   


<?php if(!empty($post_count)){?>
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo $post[0]->post_title;?>">
<meta property="og:description" content="<?php echo substr($post[0]->post_desc,0,280).'...';?>">
<meta property="og:url" content="<?php echo $url;?>/blog/<?php echo $post[0]->post_slug;?>">
<meta property="og:site_name" content="<?php echo $setts[0]->site_name;?>">
<?php if(!empty($post[0]->post_image)){ ?>
<meta property="og:image" content="<?php echo $url.'/local/images/media/'.$post[0]->post_image;?>">
<?php } else { ?>
<meta property="og:image" content="<?php echo $url;?>/local/images/noimage.jpg">
<?php } ?>
<meta property="og:image:width" content="400">
<meta property="og:image:height" content="300">
<?php } ?>
</head>
<body class="cnt-home blogpage">

    

    <!-- fixed navigation bar -->
    @include('header')

    
     
    
   
	
        
        
        
        <div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='<?php if(!empty($blog_count)){?>active<?php } ?>'>@lang('languages.blog')</li>
                <?php if(!empty($post_count)){?>
                                    <li  class="<?php if(!empty($post_count)){?>active<?php } ?>"><?php echo $post[0]->post_title; ?></li>
                                    <?php } ?>
			</ul>
		</div>
	</div>
</div>
    

	
	
     <div class="clear"></div>
    <?php if(!empty($post_count)){?>
    <section class="padT70 padB10">
    <div class="body-content my-wishlist-page">
	<div class="container-fluid">
    
           
            
            <div class="row">
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.blog')</div></div>
                <div class="col-md-6 text-right"></div>
        </div>
         <div class="height20 clearfix"></div>
                <div class="row">
                    <div class="col-md-9 col-sm-8 col-xs-12 pull-right marB30 border_left">
                        <div class="row">
                            <div class="Single-blog">
                            <?php 
							$date = $post[0]->post_date;
					
					$old_date = strtotime($date);
					$dateonly = date('d F Y', $old_date);
					?>
                            
                                <div class="col-md-12 col-sm-12 col-xs-12 marB30">
                                    <div class="sale-date-tag marB30">
                                       
                                        
                                        
                                        <?php if($post[0]->post_media_type=="image"){ ?>
                    
                    
    				<?php if(!empty($post[0]->post_image)){ ?>
          			<img src="<?php echo $url.'/local/images/media/'.$post[0]->post_image;?>" class="blog_responsive" title="<?php echo $post[0]->post_title;?>">
        			<?php } else {?>
       				<img src="<?php echo $url;?>/local/images/noimage.jpg" class="blog_responsive" title="<?php echo $post[0]->post_title;?>">
        			<?php } ?>
                    
                    <?php } ?>
                   
                    
                    <?php if($post[0]->post_media_type=="mp3"){ ?>
                   <?php /* MP3 */?>

<script src="<?php echo $url;?>/local/resources/views/theme/mp3/mp3.js"></script>

<script>
    $(document).ready(function () {
        $('.mediPlayer').mediaPlayer();
    });
</script>

<?php /* MP3 */?>

                     <div class="text-center">
                    <div class="height40"></div>
                     <div class="mediPlayer">
    				<audio class="listen" preload="none" data-size="250" src="<?php echo $url;?>/local/images/media/<?php echo $post[0]->post_audio;?>"></audio>
					</div>
                    <div class="height40"></div>
                    </div>
                   
                    <?php } ?>
                    
                    
                    <?php 
					if($post[0]->post_media_type=="video"){?>
                   
                    <?php
					if (strpos($post[0]->post_video, 'youtube') > 0) {
					 $vurl = $post[0]->post_video;
						preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $vurl, $matches);
						$id = $matches[1];
						
						$height = '420px';
					?>
                    <iframe id="ytplayer" type="text/html" width="100%" height="<?php echo $height ?>" src="https://www.youtube.com/embed/<?php echo $id ?>?rel=0&showinfo=0&color=white&iv_load_policy=3" frameborder="0" allowfullscreen></iframe>
                    
                    <?php } 
					if (strpos($post[0]->post_video, 'vimeo') > 0) {
					$vimeo = $post[0]->post_video;
					?>
                    <div class="embed-container">
                    <iframe src="https://player.vimeo.com/video/<?php echo (int) substr(parse_url($vimeo, PHP_URL_PATH), 1);?>" width="100%" height="420" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
					<?php }?>
                   
					<?php } ?>
                    
                                        
                                    </div>
                                    <div class="blog-detail marB30">
                                        <h3><?php echo $post[0]->post_title;?></h3>
                                        <p class="ash_color">
                                            
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i><?php echo $dateonly;?></span>
                                        </p>
                                        
                                       <p class="ash_color"> <?php echo $post[0]->post_desc;?> </p>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    
                                    
                                    <div class="blog-detail marB30 text-center demo-02">
                        <?php 
						if($post[0]->post_media_type=="image")
						{ 
						?>            
						<?php 
						if(!empty($post[0]->post_image))
						{ 
						$share_img = $url.'/local/images/media/'.$post[0]->post_image;
						}
						else
						{
						 $share_img = $url.'/local/images/noimage.jpg';
						}
						}
						else if($post[0]->post_media_type=="video")
						{
						 $share_img = $url.'/local/images/blogvideo.png';
						}
						else if($post[0]->post_media_type=="mp3")
						{
						 $share_img = $url.'/local/images/blogaudio.png';
						}
						else
						{
						  $share_img = $url.'/local/images/noimage.jpg';
						}
						
						?>
                        
                        <div class="height10"></div>
                   <div id="share1"
         data-url="<?php echo $url;?>/blog/<?php echo $post[0]->post_slug;?>"
         data-title="<?php echo $post[0]->post_title;?>"
         data-description="<?php echo $post[0]->post_desc;?>"
         data-image="<?php echo $share_img;?>" class="text-left"></div>      
                        
                        
                        
						
</div>
             
                     <div class="clearfix height20"></div>         
                              <div class="row">
                              <div class="col-sm-12 col-md-12 paddingoff">
                               <div class="col-md-6 text-left">
          <?php if(!empty($previous)){
		
		?>
        <a href="<?php echo $url;?>/blog/<?php echo $previous_link[0]->post_slug;?>" class="btn-upper btn btn-primary"> @lang('languages.previous')</a>
         <?php } ?>
        </div> 
         
         <div class="col-md-6 text-right">
         <?php if(!empty($next)){
		
		 ?>
         <a href="<?php echo $url;?>/blog/<?php echo $next_link[0]->post_slug;?>" class="btn-upper btn btn-primary">@lang('languages.next') </a>
          <?php } ?>
          </div>
         </div>  
         </div>    
                              <div class="clearfix height20"></div>      
                                    

                                    <div class="comment-section">
                                    <?php
		$pcomment = DB::table('post')
							 ->where('post_parent', '=', $post[0]->post_id)
							 ->where('post_comment_type', '=', 'blog')
							 ->where('post_type', '=', 'comment')
							 ->where('post_status', '=', '1')
							 ->orderBy('post_id', 'DESC')
							 ->get();
							 $pcnt = DB::table('post')
							 ->where('post_parent', '=', $post[0]->post_id)
							 ->where('post_comment_type', '=', 'blog')
							 ->where('post_type', '=', 'comment')
							 ->where('post_status', '=', '1')
							 ->count();
							 
						 ?>
                                        <div class="comment-list">
                                        <?php if(!empty($pcnt)){ ?>
                                            <h3 class="marB30 marT50">@lang('languages.comments')</h3>
                                            <?php 
		 
							 foreach($pcomment as $viwcomment){
							 $user = DB::table('users')
							         ->where('id', '=', $viwcomment->post_user_id)
									 ->get();
		                      ?>		
                                            <div class="comment-box marB40">
                                                <div class="row marB20">
                                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                                        
                                                     
                                                        <?php 
					   $userphoto="/media/";
						$path ='/local/images'.$userphoto.$user[0]->photo;
						if($user[0]->photo!=""){
						?>
						 <img src="<?php echo $url.$path;?>" class="img-circle cmd_thumb">
						 <?php } else { ?>
						  <img src="<?php echo $url.'/local/images/nophoto.jpg';?>" class="img-circle cmd_thumb">
						 <?php } ?>
                         
                         
                                                        
                                                    </div>
                                                    <div class="col-md-10 col-sm-9 text-left">
                                                        <div class="fontsize18"><?php echo $viwcomment->post_title;?> <span class="color-gray">| <?php echo date('d M Y h:i:s a',strtotime($viwcomment->post_date));?></span></div>
                                                        
                                                       <p> <?php echo $viwcomment->post_desc;?></p>
                                                    </div>
                                                </div>
                                            </div>
                                           <?php } ?>
        
        <?php } ?>
        
        
         
             
                                        </div>
                                        <div class="comment-review">
                                            
                                            <h3 class="marB30 marT60">@lang('languages.leave_a_reply')</h3>
                                            
                                            <?php if(Auth::check()) { ?>
                                            
                                       <form role="form" method="POST" action="{{ route('blog') }}" class="register-form" id="formID" enctype="multipart/form-data">
                                       {{ csrf_field() }}
                                            <div class="row">
                                                
                                                <div class="col-md-6 paddingoff">
                                                
                                                <div class="col-md-12">
		
       
        
			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.your_name') <span>*</span></label>
		    <input type="text" placeholder="Your Name" class="form-control unicase-form-control validate[required]" id="name" name="name" />
            
		  </div>
		
	</div>
                    
                    
                    <div class="col-md-12">
		
       
        
			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.your_email') <span>*</span></label>
		    
            
             <input type="text" placeholder="Your Email" value="<?php echo Auth::user()->email;?>" class="form-control unicase-form-control validate[required,custom[email]]" id="email" name="email" readonly />
                                                </div>
                                                <input type="hidden" name="post_comment_type" value="blog">
          
           <input type="hidden" name="post_type" value="comment">
           
           <input type="hidden" name="post_user_id" value="<?php echo Auth::user()->id;?>">
           
          
          <input type="hidden" name="post_parent" value="<?php echo $post[0]->post_id;?>">
            
		  </div>
		
	
    
    
    
    <div class="col-md-12">
		
       
        
			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.your_message') <span>*</span></label>
		    
             <textarea  placeholder="Your Message" rows="9" class="form-control unicase-form-control validate[required]"  id="msg" name="msg"></textarea>
		  </div>
		
	</div>
    
    
    
                    
                  <div class="col-md-12">
                                                   
                                                    <button type="submit" class="btn-upper btn btn-primary">
                                                    <span>@lang('languages.reply')</span>
                                                    </button>
                                                </div>
                                                
                                                
                                                
                                    </div>            
                                                
                                                
                                            
                                            </div>
                                        </form>
                                        <?php } else { ?>
                                        
                                        <p>@lang('languages.you_must') <a href="<?php echo $url;?>/login" class="gold bold theme_color">@lang('languages.logged')</a> @lang('languages.post_a_comment')</p>
                                        
                                        <?php } ?>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                     
                    
                    
                    
                    <div class="col-md-3 col-sm-4 Sidebar pull-left">
                        <div class="row">
                            <div class="wow fadeInUp animated">
                                
                                
                                <div class="col-md-12">
                                <?php if(!empty($post_count)){?>
                                    <div class="fontsize19">@lang('languages.recent_posts')</div>
                                    <div class="height20"></div>
                                    <div class="row">
                                        <div class="recent-posts" style="    display: flex;
    flex-direction: column;">
                                        <?php foreach($popular_blog as $popular){?>
                                            <div class="blog-recent-post">
                                                <div class="col-md-4 col-sm-4 col-xs-4">
                                                    
                                                    
                                                    
                                                    <?php if($popular->post_media_type=="image"){ ?>
                                                     <figure>
    				<?php if(!empty($popular->post_image)){ ?>
          			<a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url.'/local/images/media/'.$popular->post_image;?>" class="img-responsive blogpost"></a>
        			<?php } else {?>
       				<a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/noimage.jpg" class="img-responsive blogpost"></a>
        			<?php } ?>
                    </figure>
                    <?php } ?>
                    
                    <?php if($popular->post_media_type=="mp3"){ ?>
                    <figure>
                    <a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/blogaudio.png" class="img-responsive blogpost"></a>
                   </figure>
                    <?php } ?>
                    <?php if($popular->post_media_type=="video"){?>
                    <figure>
                    <a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/blogvideo.png" class="img-responsive blogpost"></a>
                    </figure>
                    <?php } ?>
                                                    
                                                    
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-8 padL0 text-left">
                                                    <h4 class="sidehead"><a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><?php echo $popular->post_title;?></a></h4>
                                                    <p class="ash_color"><?php echo date("d M Y h:i:s a",strtotime($popular->post_date));?></p>
                                                        <p><a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" class="theme-text-color theme_color"> @lang('languages.read_more')</a>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                
                                
                                
                                
                                
                                
                                <div class="col-md-12">
                                    <div class="blog-meta-tags marB30">
                                        <h3>@lang('languages.meta') </h3>
                                        <div class="tag-list">
                                                                                       
                                            <?php 
					$post_tags = explode(',',$post[0]->post_tags);
					
					foreach($post_tags as $tags)
                    {
					$tag =strtolower(str_replace(" ","-",$tags)); 
					
					if(!empty($tags))
					{
					?>
                    <a href="<?php echo $url;?>/tag/blog/<?php echo $tag;?>"><?php echo $tags;?></a>
                    <?php
					}
					}
					?>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-md-12">
                                 <?php 
					
					if(!empty($setts[0]->site_blog_ads)){?>
                    <div class="animate-box">
                    <div class="clearfix height20"></div>
                    <?php echo html_entity_decode($setts[0]->site_blog_ads);?>
                    </div>
                    <?php } ?>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>
        </section>
        <?php } ?>
        <div class="clear"></div>

    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="body-content my-wishlist-page">
	
            <div class="container-fluid">
            <?php if(!empty($blog_count)){?>
            <div class="row">
            <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.blog')</div></div>
                <div class="col-md-6 text-right"></div>
             </div>   
            
            <div class="height20 clearfix"></div>
            <?php } ?>
            
                <div class="row">
                <?php if(!empty($blog_count)){?>
                    <div class="col-md-9 col-sm-9 col-xs-12 pull-right border_left">
                        <div class="row">
                            <div class="blog-grid bloglist">
                            
                             <?php foreach($blogs as $blog){
						$date = $blog->post_date;
						
						$old_date = strtotime($date);
						$dateonly = date('j M Y', $old_date);
						
						?>
                            
                            
                                <div class="col-md-12 col-sm-12 col-xs-12 marB30 mbottom40">
                                    <div class="sale-date-tag marB30">
                                        
                                        
                                        <?php if($blog->post_media_type=="image"){ ?>
                            
    				<?php if(!empty($blog->post_image)){ ?>
          			<a href="<?php echo $url;?>/blog/<?php echo $blog->post_slug;?>" title="<?php echo $blog->post_title;?>"><img src="<?php echo $url.'/local/images/media/'.$blog->post_image;?>" class="blog_responsive" alt="<?php echo $blog->post_title;?>"></a>
        			<?php } else {?>
       				<a href="<?php echo $url;?>/blog/<?php echo $blog->post_slug;?>" title="<?php echo $blog->post_title;?>"><img src="<?php echo $url;?>/local/images/noimage.jpg" class="blog_responsive" alt="<?php echo $blog->post_title;?>"></a>
        			<?php } ?>
                  
                    <?php } ?>
                            
                             <?php if($blog->post_media_type=="mp3"){ ?>
                             <?php /* MP3 */?>

<script src="<?php echo $url;?>/local/resources/views/theme/mp3/mp3.js"></script>

<script>
    $(document).ready(function () {
        $('.mediPlayer').mediaPlayer();
    });
</script>

<?php /* MP3 */?>

                    <div class="text-center">
                    <div class="height40"></div>
                     <div class="mediPlayer">
    				<audio class="listen" preload="none" data-size="250" src="<?php echo $url;?>/local/images/media/<?php echo $blog->post_audio;?>"></audio>
					</div>
                    <div class="height40"></div>
                    </div>
                    
                    <?php } ?>
                    
                    
                    
                    <?php 
					if($blog->post_media_type=="video"){
					if (strpos($blog->post_video, 'youtube') > 0) {
					 $vurl = $blog->post_video;
						preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $vurl, $matches);
						$id = $matches[1];
						
						$height = '420px';
					?>
                    
                    <iframe id="ytplayer" type="text/html" width="100%" height="<?php echo $height ?>" src="https://www.youtube.com/embed/<?php echo $id ?>?rel=0&showinfo=0&color=white&iv_load_policy=3" frameborder="0" allowfullscreen></iframe>
                    
                    <?php } 
					if (strpos($blog->post_video, 'vimeo') > 0) {
					$vimeo = $blog->post_video;
					?>
                    <div class='embed-container'>
                    <iframe src="https://player.vimeo.com/video/<?php echo (int) substr(parse_url($vimeo, PHP_URL_PATH), 1);?>" frameborder="0" width="100%" height="420"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
					<?php }
					} ?>
                   

                                        
                                   
                                   
                            <?php
					$post_comment = DB::table('post')
							 ->where('post_parent', '=', $blog->post_id)
							 ->where('post_comment_type', '=', 'blog')
							 ->where('post_type', '=', 'comment')
							 ->where('post_status', '=', '1')
							 ->count();
					?>
                            
        
                                   
                                   
                                        
                                    </div>
                                    <div class="blog-detail marB30">
                                        <h3><a href="<?php echo $url;?>/blog/<?php echo $blog->post_slug;?>" title="<?php echo $blog->post_title;?>">
										<?php echo $blog->post_title;?></a></h3>
                                        <p class="ash_color">
                                            
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i><?php echo $dateonly;?></span>
                                        </p>
                                        
                                        <p><?php echo substr($blog->post_desc,0,300).'...';?>
                                        </p>
                                        
                                        <p class="cat-pan fontsize14 theme_color"><i class="fa fa-comments"></i> <?php echo $post_comment;?> @lang('languages.comment')</p>
                                    </div>
                                    <p class="clearfix">
                                    <a href="<?php echo $url;?>/blog/<?php echo $blog->post_slug;?>" class="btn-upper btn btn-primary">
                                    <span>@lang('languages.read_more')</span>
                                    </a></p>
                                </div>
                                
                                <?php } ?>
                                
                                
                                
                                
                            </div>
                            
                            <div class="pagess"></div>
                        </div>
                        
                    </div>
                    
                    
                    
                    <?php } ?>
                    
                    <?php if(!empty($blog_count)){?>
                    <div class="col-md-3 col-sm-4 Sidebar pull-left marB30">
                        <div class="row">
                        
                            <div class="blog-sidebar ">
                                
                                
                                <div class="col-md-12 ">
                                    <div class="fontsize19">@lang('languages.recent_posts')</div>
                                    <div class="height20"></div>
                                    <div class="row">
                                        <div class="recent-posts ">
                                            
                     <?php if(!empty($popular_blog_cnt)){?>
                    <?php foreach($popular_blog as $popular){ ?>
                                            
                                            <div class="blog-recent-post">
                                                <div class="col-md-4 col-sm-4 col-xs-4">
                                                   
                                                    <?php if($popular->post_media_type=="image"){ ?>
                                                    <figure>
    				<?php if(!empty($popular->post_image)){ ?>
                    
          			<a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url.'/local/images/media/'.$popular->post_image;?>" class="img-responsive blogpost"></a>
        			<?php } else {?>
       				<a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/noimage.jpg" class="img-responsive blogpost"></a>
        			<?php } ?>
                    </figure>
                    <?php } ?>
                    
                    <?php if($popular->post_media_type=="mp3"){ ?>
                     <figure>
                    <a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/blogaudio.png" class="img-responsive blogpost"></a>
                   </figure>
                    <?php } ?>
                    <?php if($popular->post_media_type=="video"){?>
                     <figure>
                    <a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><img src="<?php echo $url;?>/local/images/blogvideo.png" class="img-responsive blogpost"></a>
                    </figure>
                    <?php } ?>
                                                    
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-8 padL0 text-left">
                                                    <h4 class="sidehead"><a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" title="<?php echo $popular->post_title;?>"><?php echo $popular->post_title;?></a></h4> <p class="ash_color"><?php echo date("d M Y h:i:s a",strtotime($popular->post_date));?></p>
                                                    <p>
                                                        <a href="<?php echo $url;?>/blog/<?php echo $popular->post_slug;?>" class="theme_color under_line"> @lang('languages.read_more')</a>
                                                    </p>
                                                </div>
                                            </div>
                                             <?php } ?>
    <?php } ?>
	
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                   <?php if(!empty($blog_count)){?>     
                    <?php 
					
					if(!empty($setts[0]->site_blog_ads)){?>
                    <div class="animate-box">
                    <div class="clearfix height20"></div>
                    <?php echo html_entity_decode($setts[0]->site_blog_ads);?>
                    </div>
                    <?php } ?>
                    
                    <?php } ?>
                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            
            </div>
        
    
    
    
    
    
	
	<div class="clearfix height30"></div>
	
    

      @include('footer')
     
</body>
</html>