<?php

use Illuminate\Support\Facades\Route;

$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid = 1;
$setts = DB::table('settings')
  ->where('id', '=', $setid)
  ->get();
$headertype = $setts[0]->header_type;

?>