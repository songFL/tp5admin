<?php
namespace app\index\controller;

use app\index\model\Category;
use think\Controller;
use think\request;
class Index extends Controller
{
    public function index()
    {
        $category = Category::where('pid',0)->select();
        $tcate=[];
        
        
        $secate = Category::where('pid!=0')->select();
        foreach($category as $v)
        {

        $tcate[] = Category::where('pid',$v['id'])->limit(2)->select();
        }
        //var_dump($tcate);
        $this->assign('secate',$secate);
        $this->assign('tcate',$tcate);
        $this->assign('category',$category);
        return view('index');
    }

   public function hello(Request $request ,$id=1)
   {
    
       return $request->url(true);

   }

   public function helloName($name='')
   {
       return $name.'你好';
   }

}


