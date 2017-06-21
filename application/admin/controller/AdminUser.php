<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AdminUser as Admin;
use think\Session;
class AdminUser extends Controller{

	protected $obj;

	public function _initialize(){
		$this->obj = new Admin();
	}

	public function index (){

		$adminInfo = $this->obj->allMember();

		return view('member_list',['infos'=>$adminInfo[0],'count'=>$adminInfo[1]]);
	}

	//显示新建管理员

	public function create(){

		$allUsers = $this->obj->allUser();

		return view('member_add',['users'=>$allUsers[0],'count'=>$allUsers[1]]);
	}

	

	//加入管理员
	public function member_start(){
		if(request()->isPost()){
			
			$id = request()->param();
			
			$res = $this->obj->startAdmin($id['id']);
			if($res){
				return array('msg'=>1);
			}else{

				return array('msg'=>0);
			}

		}
	}
	//停用管理员
	public function member_stop(){
		if(request()->isPost()){
			$id = request()->param();

			$res = $this->obj->delAdmin($id['id']);
			if($res){
				return array('msg'=>1);
			}else{
				return array('msg'=>0);
			}

		}
	}

}