<?php

namespace app\admin\controller;

use think\Controller;
use think\Env;
use think\Request;

class Attr extends Controller
{
    public function list(){
        $typeid = input('type_id');
        $attrRes = db('attr')->alias('a')->field('a.*,t.type_name')->join('type t',"a.type_id = t.id")
            ->where(array('type_id'=>$typeid))->order('a.id','desc')->paginate(2);
        //获取分心啊显示
        $page = $attrRes->render();
        $this->assign([
            'attrRes'=>$attrRes,
            'page'=>$page
        ]);
        return view();
    }

    //编辑品牌
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            $data['attr_values']=str_replace('，', ',', $data['attr_values']);
            //验证
            $validate = validate('attr');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            $save = db('attr')->update($data);
            if($save){
                $this->success('编辑商品分类成功','list');
            }else{
                $this->error('编辑商品分类失败');
            }
            return;
        }
        $id = input('id');
        $attrs = db('attr')->find($id);
        $types = db('type')->select();
        $this->assign([
            'attrs'=>$attrs,
            'typeRes'=>$types
        ]);
        return view();
    }

 public function create(){
     if(request()->isPost()){
        $data = input('post.');
        $data['attr_values']=str_replace('，', ',', $data['attr_values']);
         //验证
         $validate = validate('attr');
         if(!$validate->check($data)){
             $this->error($validate->getError());
         }
        $add = db('attr')->insert($data);
        if($add){
            $this->success('添加商品类别成功','list');
        }else{
            $this->error('添加商品类别失败');
        }
         return;
     }
     //获取商品的类型
     $typeRes = db('type')->select();
     $this->assign([
         'typeRes'=>$typeRes,
     ]);
     return view();
 }

   //删除商品
    public function del($id){
        $del = db('attr')->delete($id);
        if($del){
            $this->success('删除商品成功','list');
        }else{
            $this->error('添加商品失败');
        }
        return;
    }
}
