<?php
namespace app\user\model;

use think\Model;

class Register extends Model
{
	
	protected $table = 'tp_user';
	
	public function register($data){
		if(is_array($data)){
			
			$data['reg_time']=time();
			$data['reg_ip'] = $this->getIp();
			
    		array_shift($data);
        	$data['password']=password_hash($data['password'],PASSWORD_DEFAULT);
        	unset($data['repassword']);
        	unset($data['verify']);

			$this->data($data);
			
			return $this->save();
        	
        	
		}else{
			return $this->error('参数不合法');
		}
	}

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