<?php
namespace app\user\controller;

use think\Controller;
use app\user\model\Register as Reg;
use think\Request;
use think\Loader;
use think\Validate;

class register extends Controller
{
    protected $obj;
    public function _initialize(){
        $this->obj = new Reg();
    }

    public function index(){
        return view('regist');
    }
    
    public function register(Request $request){
        $param =$request->param();
        
        $validate = Loader::Validate('register');
            if(!$validate->check($param)){
               return $validate->getError();
                
            }
        $res = $this->obj->register($param);
        
        if($res){
            $this->success('注册成功','/admin/login','','1');

        }else{

            $this->error('注册失败','user/register','','1');
        }

    }
}