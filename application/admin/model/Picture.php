<?php
namespace app\admin\model;

use think\Model;
//use app\admin\model\Picture as PictureModel;
class Picture extends Model{

	protected $obj;

	public function _initialize(){
		//parent::initialize();
		$this->obj = model('Picture');

	}

	public function add($data){
		$this->obj->data($data);
		return $this->obj->save();
	}

	
}