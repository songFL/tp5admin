<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
    //use think\Route;

	/*Route::get('login',function(){
		return '登录成功';
	});*/

	//Route::get('register','user/Register/index');
	//Route::get('hello/[:id]','index/index/hello');
	return [
	    '__pattern__' => [
	        'name' => '\w+',
	    ],
	    '[hello]'     => [
	        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
	        ':name' => ['index/hello',['method' => 'post']],
	        'name/:name' => ['index/helloName', ['method' => 'get']],
	    ],

	    'register' => 
	    	['user/Register/index',['method' => 'get']],

	    'login' =>
	    	function(){
	    		return '登录成功';
	    	},
	    
	];
