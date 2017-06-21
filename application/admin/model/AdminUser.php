<?php
namespace app\admin\model;

use think\Model;
use think\Session;
class AdminUser extends Model{

	protected $table = 'tp_admin_user';

	//查找所有管理员
	public function allMember(){
		$all = $this->where(['status'=>1])->select();
		$count = $this->where(['status'=>1])->count();
		$user = model('user');
		$arr=[];
		foreach($all as $one){
			$arr[] = $user->where(['id'=>$one['userid']])->find();
		}
		$brr=[];
		$brr[]=$arr;
		$brr[] = $count;
		return $brr;
	}

	//查找所有非管理员用户

	public function allUser(){
		$users = model('user');
		$arr=[];
		$allMem = $this->select();
		foreach($allMem as $v){
			$arr[] = $v['userid'];
		}

		$brr = [];
		$allUsers = $users->where('id',"not in",$arr)->where(['status'=>1])->select();
		$allcount = $users->where('id',"not in",$arr)->where(['status'=>1])->count();
		$brr[] = $allUsers;
		$brr[] = $allcount;
		return $brr;
	}

	//从管理员表中删除用户

	public function delAdmin($id){
		if(is_numeric($id)){
			$res = $this->where('userid',$id)->delete();

			if($res){
				return true;
			}else{
				return false;
			}
		}
	}

	//启用管理员
	public function startAdmin($id){
		if(is_numeric($id)){
			$user = model('user');
			$userinfo = $user->where('name','like',Session::get('name'))->find();
			$data=[];
			$data['userid'] = $id;
			$data['groupid'] = 1;
			$data['intro'] = '管理员';
			$data['create_user_id'] = $userinfo['id'];
			$data['create_time'] = time();
			$data['status'] = 1;
			$this->data($data);
			$res = $this->save();

			if($res){
				return true;
			}else{
				return false;
			}
		}
	}
}