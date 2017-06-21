<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
class Index extends Controller
{
	public function _initialize(){
		if(!Session::get('name')){
			$this->error('请先登录账户','Login/','','1');
		}
	}
    public function index()
    {

    	$sename = Session::get('name');
    	
        return view('index',['username'=>$sename]);
    }

    public function welcome(){
    	return view('welcome');
    }
}
