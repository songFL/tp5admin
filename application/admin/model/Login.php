<?php
namespace app\admin\model;

use think\Model;
use think\Session;

class Login extends Model
{
	protected $table='tp_user';
	

	

	public function search($where){
		if(is_array($where)){

			$res = $this->where($where)->find();
			if($res){
				$admin_user = model('admin_user');
				$admin_res = $admin_user->where(['userid'=>$res['id'],'status'=>1])->find();
				if($admin_res){
					return $res;
				}else{
					return false;
				}
				
			}else{
				return false;
			}
			
		}else{
			$this->error('参数必须为数组形式');
		}
		
	}

	//密码验证,验证成功设置session
	public function passverify($pass1,$pass2,$username){
		//pass1:用户输入密码;pass2:数据库密码

		if(password_verify($pass1,$pass2)){
			$sename = Session::set('name',$username);
			$sepass = Session::set('password',$pass1);
			if(Session::get('name') && Session::get('password')){
				return true;
			}else{
				return false;
			}
		}
	}

	//登录成功更新登录信息
	public function upInfo($userid){

		$userinfo = $this->where(['id'=>$userid])->find();

		$data=[];
		$data['login_num'] = $userinfo['login_num']+1;
		$data['lastlogin_time']=time();
		$data['login_ip'] = $this->getip();

		$upres = $this->where(['id'=>$userinfo['id']])->update($data);

		$adminuser = model('admin_user');

		$upadminres = $adminuser->where(['userid'=>$userinfo['id']])->update($data);

		if($upres){
			return true;
		}

	}

	//获取ip
	public function getip() {
        $unknown = 'unknown';
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        /*
        处理多层代理的情况
        或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
        */
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip;
    }
}