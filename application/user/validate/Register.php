<?php

namespace app\user\validate;

use think\Validate;

class Register extends Validate{
	
	protected $rule = [
		'name' => 'require|alphaDash|unique:user',
		'email' => 'require|email',
		'mobile' => 'require|number|length:11',
		'password' =>'require',
		'repassword' =>'require|confirm:password',
		'verify|验证码' => 'require|captcha',
	];
//信息提示
	protected $message = [
		'name.require' => '用户名不能为空',
		'name.alphaDash' => '用户名只能为数字,字母,下划线,破折号',
		'name.unique' => '用户名被占用',
		'mobile.require' => '手机号不能为空',
		'mobile.length' => '手机号长度不正确',
		'mobile.number' => '手机号必须是数字',
		'email.require' => '邮箱地址不能为空',
		'email.eamil' => '邮箱地址格式不正确',
		'verify.require' =>'验证码不能为空',
		'verify.captcha' => '验证码错误',
		

		'password.require' => '密码不能为空',
		'repassword.confirm' => '两次密码不一致',
		];
	//验证场景
	protected $scene =[
		'register'=>['username','email','mobile','password','repassword'],
	];
}