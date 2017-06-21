<?php
namespace app\admin\model;
use think\Model;

class Category extends Model{
	protected $obj;

	public function _initialize(){
		$this->obj = model('Category');
	}

	public function add($data){
		$this->obj->data($data);
		return $this->obj->save();
	}
}