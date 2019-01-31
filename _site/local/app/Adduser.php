<?php


namespace Responsive;


use Illuminate\Database\Eloquent\Model;


class Adduser extends Model

{

    public $timestamps = false;
	public $table = 'users';


	public $fillable = ['name','full_name','post_slug','email','password','phone','created_at'];


}