<?php
namespace app\admin\controller;
//use think\model;
use app\admin\model\Picture as Pic;
use app\admin\model\Category as cate;
use think\Controller;
use think\Session;
class Picture extends Controller{

	public function _initialize(){
		if(!Session::get('name')){
			$this->error('请先登录账户','Login/','','1');
		}
	}
	
	public function picture_add(){
		$map=[];
		$cate = new cate;
		$map['fpid']=['>',0];
		$map['spid'] = ['>',0];
		$cates = $cate->where($map)->select();
		return view('picture_add',['cates'=>$cates]);
	}

	public function picture_list(){
		$where=[];
		$param=[];
		if(request()->isPost()){
			$param = request()->param();
			
			if($param['stime'] && $param['etime']){
				$stime=strtotime($param['stime']);
				$etime = strtotime($param['etime']);
				$where['create_time']=['between',[$stime,$etime]];
			}

			if($param['name']){
				$where['name'] = ['like',"%".$param['name']."%"];
			}
			
		}
		$res = Pic::where($where)->paginate(42);
		$count = Pic::where($where)->count();
		
		
		return view('picture_list',array('pictures'=>$res,'count'=>$count,'where'=>$param));
	}

	//删除
	public function del(){
		if(request()->isPost()){
            $id = request()->only('id');

            $cate = new Pic();
            $res = $cate->where($id)->delete();
           
            
            if($res || $res==0){
                return array("msg"=>1);
            }else{
                return array("msg"=>0);
            }
        }
	}

	public function delAll(){
		if(request()->param('method')=='mdel'){
			$ids = request()->only(['id']);
			//var_dump($ids);exit;
			foreach($ids['id'] as $id){
				$res = Pic::where(array('id'=>$id))->delete();
			}
			if($res){
				$this->success('删除成功','picture/picture_list','',1);
			}else{
				$this->error('删除失败','picture/picture_list','',1);
			}
		}
	}

	public function save(){

		$data = request()->param();
		
		array_shift($data);
		
			// 获取表单上传文件
	    $files = request()->file('file');

		$data['create_time'] = time();
		$data['status'] = 1;
			//var_dump($data);exit;
			
	    //$picture = model('Picture');
	    //dump($picture);exit;
	    foreach($files as $file){
	        // 移动到框架应用根目录/public/uploads/ 目录下
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息

	            //获取文件格式
	            $ext = $info->getExtension();
	            //要保存的图片路径
	            $savename = $info->getSaveName();
	            $date = date('Ymd');
	            //保存的文件名
	            $pic=$info->getFilename();

	            //生成图片缩略图
	            $image = \think\Image::open(ROOT_PATH . 'public' . DS . 'uploads'.DS.$savename);
    			// 按照原图的比例生成一个最大为150*150的缩略图并保存在thumbs文件夹同名
    			//var_dump($image);exit;
    			$tdir = ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS.'thumbs';
    			if(!file_exists($tdir)){
    				mkdir(ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS.'thumbs',0777);
    			}
        		$tres = $image->thumb(200, 200)->save($tdir.DS.$pic);

        		$data['pic_path']=$savename;
				$data['thumb_pic'] = $date.'/thumbs/'.$pic;
				
				 $res = Pic::create($data);
				 
	           
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }

		
	}
	if($res){
			$this->success('保存成功','picture/picture_list','',1);
		}else{
			$this->error('保存失败','picture/picture_list','',1);
		}
				
	}

	//编辑
	public function edit(){
		$id = request()->param('id');
		$picone = Pic::find($id);
		$date = date('Ymd');
		$imxt = file_get_contents(ROOT_PATH . 'public' . DS . 'uploads'.DS.$picone['pic_path']);
		$picone['pic_path'] = base64_encode($imxt);
		if(request()->isPost()){

			$data = request()->param();
			
			array_shift($data);
			
				// 获取表单上传文件
		    $files = request()->file('file');

			$data['create_time'] = time();
			$data['status'] = 1;
				//var_dump($data);exit;
				//var_dump($data);exit;
		    //$picture = model('Picture');
		    //dump($picture);exit;
			if($files){
			    foreach($files as $file){
			        // 移动到框架应用根目录/public/uploads/ 目录下
			        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			        if($info){
			            // 成功上传后 获取上传信息

			            //获取文件格式
			            $ext = $info->getExtension();
			            //要保存的图片路径
			            $savename = $info->getSaveName();
			            
			            //保存的文件名
			            $pic=$info->getFilename();

			            //生成图片缩略图
			            $image = \think\Image::open(ROOT_PATH . 'public' . DS . 'uploads'.DS.$savename);
		    			// 按照原图的比例生成一个最大为150*150的缩略图并保存在thumbs文件夹同名
		    			//var_dump($image);exit;
		    			$tdir = ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS.'thumbs';
		    			if(!file_exists($tdir)){
		    				mkdir(ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS.'thumbs',0777);
		    			}
		        		$tres = $image->thumb(200, 200)->save($tdir.DS.$pic);

		        		$data['pic_path']=$savename;
						$data['thumb_pic'] = $date.'/thumbs/'.$pic;
						
						 $res = Pic::where(array('id'=>$picone['id']))->update($data);
						 
			           
			        }else{
			            // 上传失败获取错误信息
			            echo $file->getError();
			        }

				
				}
			}else{
				 $res = Pic::where(array('id'=>$picone['id']))->update($data);
			}
			if($res){
				$this->success('修改成功','picture/picture_list','',1);
			}else{
				$this->error('修改失败','picture/picture_list','',1);
			}
		}

		return view('picture_edit',array('picone'=>$picone));
	}
	
}