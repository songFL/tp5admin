<?php
namespace app\admin\validate;
use think\Validate;

class Login extends Validate{
	protected $rule = [
		'name' => 'require|max:30',
		'password' => 'require|min:8',
		'verify|验证码' => 'require|captcha',
	];
}