<?php

namespace app\admin\controller;

use think\Controller;
use think\Env;
use think\Request;

class Type extends Controller
{
    public function list(){
        $typeRes = db('type')->order('id','desc')->paginate(6);
        //获取分心啊显示
        $page = $typeRes->render();
        $this->assign([
            'typeRes'=>$typeRes,
            'page'=>$page
        ]);
        return view();
    }

    //编辑品牌
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            $save = db('type')->update($data);
            if($save){
                $this->success('编辑商品分类成功','list');
            }else{
                $this->error('编辑商品分类失败');
            }
            return;
        }
        $id = input('id');
        $types = db('type')->find($id);
        $this->assign([
            'types'=>$types  ,
        ]);
        return view();
    }

 public function create(){
     if(request()->isPost()){
        $data = input('post.');

        $add = db('type')->insert($data);
        if($add){
            $this->success('添加商品类别成功','list');
        }else{
            $this->error('添加商品类别失败');
        }
         return;
     }
     return view();
 }

   //删除商品
    public function del($id){
        $del = db('type')->delete($id);
        if($del){
            $this->success('删除商品成功','list');
        }else{
            $this->error('添加商品失败');
        }
        return;
    }
}
