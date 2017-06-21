<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Category as cate;
use think\Session;
class Category extends Controller
{
    public function _initialize(){
        if(!Session::get('name')){
            $this->error('请先登录账户','Login/','','1');
        }
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
       
        $cates = cate::select();
        $count = cate::count();
        foreach($cates as $cate){
          /*  if($cate['fpid']!=0){
            $fname = cate::where(['fpid'=>$cate['fpid']])->field('name')->find();
            $fname['name']=$fname['name'].'>>';
            
            $cate['fname']=$fname['name'];
            }
            if($cate['spid']!=0){
            $sname = cate::where(['spid'=>$cate['spid']])->field('name')->find();
            $sname['name']=$sname['name'].'>>';
            $cate['sname']=$sname['name'];
          }*/
          if($cate['fpid']==0){
            $cate['level']='一级栏目';

          }elseif($cate['spid']==0){
            $cate['level']='二级栏目';
          }else{
            $cate['level']='三级栏目';
          }
        }
        //var_dump($cates);exit;
        return view('category_list',array('cates'=>$cates,'count'=>$count));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
      
        $cates = cate::where(array('fpid'=>0))->select();
        return view('category_add',array('cates'=>$cates));

    }

    //选出二级分类

  public function scate()
    {
        //
        $where=[];
        if(request()->isPost()){
            $fcate = request()->param('fpid');
            $where['fpid']=$fcate;
            $where['spid']=0;
            $spid  = cate::where($where)->select();
            
            return $spid;
        }
        

    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = $request->param();
        array_shift($data);
        $data['create_time'] = time();
        $data['status'] =1;
        
        $cate = new cate();
        $res = $cate->save($data);
        if($res){
            $this->success('添加成功','Category/create','',1);
        }else{
            $this->error('添加失败','Category/create','',1);
        }
    }

    /**
     * 改变状态
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function changeStatus()
    {
        //
        if(request()->isPost()){
            $param = request()->param();
            $res = cate::where(['id'=>$param['id']])->update(['status'=>$param['status']]);
            if($res){
                return array('msg'=>1);
            }else{
                return array('msg'=>0);
            }
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $cate = cate::where(['id'=>$id])->find();
        $cates = cate::where(array('fpid'=>0))->select();
        $scate = cate::where(['fpid'=>$cate['fpid'],'spid'=>0])->select();
        return view('category_edit',['cate'=>$cate,'cates'=>$cates,'scate'=>$scate]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //

        if(request()->isPost()){
            $data = request()->param();
            array_shift($data);
            $res = cate::where(['id'=>$id])->update($data);
            if($res || $res==0){
                $this->success('修改成功','Category/index','',1);
            }else{
                $this->success('修改失败','Category/edit','',1);
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        //
        if(request()->isPost()){
            $id = request()->only('id');

            $cate = new cate();
            $res = $cate->where($id)->delete();
           
            
            if($res){
                return array("msg"=>1);
            }else{
                return array("msg"=>0);
            }
        }

    }

    //批量删除
    public function delAll(){
        if(request()->param('method')=='mdel'){
            $ids = request()->only(['id']);
            //var_dump($ids);exit;
            foreach($ids['id'] as $id){
                $res = cate::where(array('id'=>$id))->delete();
            }
            if($res){
                $this->success('删除成功','Category/index','',1);
            }else{
                $this->error('删除失败','Category/index','',1);
            }
        }
    }
}
