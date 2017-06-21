<?php
namespace app\admin\validate;

use think\Validate;

class Picture extends Validate{

	protected $rule=[
		'file' => 'require|file|image:["jpg","png","gif"]|fileSize:30000000',
		'name' => 'require|max:50',
	];

	protected $scence = [
		'add'=> 'name|file',
		'edit' => 'name|file',
	];
}