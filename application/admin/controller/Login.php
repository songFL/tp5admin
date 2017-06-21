<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Loader;
use app\admin\model\Login as Lo;
use think\Session;
class Login extends Controller
{
	protected $obj;
	public function _initialize(){
		$this->obj = new Lo();
	}
    public function index(){
        return view('login/login');

    }

    public function login(Request $request){
    	
    	$param = $request->param();
    	//var_dump($param);
    	$validate = Loader::validate('Login');

    	if(!$validate->check($param)){

    		return $validate->getError();
    	}

    	$where = [];

    	$where['name']=$param['name'];    	

    	$resname = $this->obj->search($where);
    	//echo $param['password'];exit;
    	if(!$resname){

    		$this->error('用户不存在','login/index','','1');

    	}else{
    		$passverify = $this->obj->passverify($param['password'],$resname['password'],$param['name']);
    		if($passverify){
                $upinfo = $this->obj->upInfo($resname['id']);
               

    			$this->success('登录成功','/admin/index','','1');


    		}else{

    			$this->error('密码错误','login/index','','1');
    		}
    	}
    }

    //退出

    public function logout(){
    	Session::set('name',null);
    	if(!Session::get('name')){
    		$this->success('退出成功','login/','',1);
    	}
    }
}