<?php
use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
if($currentPaths=="/")
 {
	 $pagetitle="Home";
 }
 else 
 {
	 $pagetitle=$currentPaths;
 }
 
?>	
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
    </script>
    <title><?php echo $setts[0]->site_name; ?> - 
        <?php
        if ($pagetitle == "login") {
            echo 'Login';
        } else {
            echo "";
        }
        if ($pagetitle == "register") {
            echo 'Register';
        } else {
            echo "";
        }
        ?>
    </title>
     @include('style')
    <script src="/public/jquery-mask-plugin.js" defer></script><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" integrity="sha256-u7MY6EG5ass8JhTuxBek18r5YG6pllB9zLqE4vZyTn4=" crossorigin="anonymous"></script>
    <script src="<?php echo $url;?>/local/resources/views/theme/js/jquery-1.11.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo $url;?>/local/resources/views/theme/js/jquery.maskedinput.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body class="index">
@include('header')

<div id="app">
    @yield('content')
</div>
    
</body>
</html>
