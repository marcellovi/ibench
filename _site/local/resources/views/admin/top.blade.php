 <?php $url = URL::to("/"); 
 $setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
 ?>
 
        
        
        
        
        
        <div id="header">
  <h1><?php echo $setts[0]->site_name;?>
  
  </h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
  
   <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text" style="font-weight:bold; color:#FFFFFF;">{{ Auth::user()->name }}</span> Earnings : <?php echo $setts[0]->site_currency;?> <?php echo Auth::user()->earning;?><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo $url;?>/admin/edituser/{{Auth::user()->id}}"><i class="icon-user"></i> My Profile</a></li>
        <li class="divider"></li>
       
       
        <li><a href="{{ route('logout') }}" title="logout"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>
    <!-- <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="<?php echo $url;?>/admin/settings"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
    -->
    <li class=""><a title="logout" href="{{ route('logout') }}"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<!--<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>-->
        
        