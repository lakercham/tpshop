<?php

namespace app\admin\controller;

use think\Controller;
use think\Env;
use think\Request;

class Brand extends Controller
{
    public function list(){
        $brandRes = db('brand')->order('id','desc')->paginate(6);
        //获取分心啊显示
        $page = $brandRes->render();
        $this->assign([
            'brandRes'=>$brandRes,
            'page'=>$page
        ]);
        return view();
    }

    //编辑品牌
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            //进行数据验证
            $validate = validate('Brand');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            if($data['brand_url'] && stripos($data['brand_url'],'http://')=== false){
                //没有前缀
                $data['brand_url']= 'http://'.$data['brand_url'];
            }
            //处理图片上传的操作
            if($_FILES['brand_img']['tmp_name']){
                //判断之前是否存在图片
                $oldBrands = db('brand')->field('brand_img')->find($data['id']);
                $oldBrandImg =  IMG_UPLOADS.$oldBrands['brand_img'];
                if(file_exists($oldBrandImg)){
                    @unlink($oldBrandImg);
                }
                $data['brand_img'] = $this->upload();
            }
            $save = db('brand')->update($data);
            if($save){
                $this->success('编辑商品成功','list');
            }else{
                $this->error('编辑商品失败');
            }
            return;
        }

        $id = input('id');
        $brands = db('brand')->find($id);
        $this->assign([
            'brands'=>$brands ,
        ]);
        return view();
    }

 public function create(){
     if(request()->isPost()){
        $data = input('post.');
         $validate = validate('Brand');
         if(!$validate->check($data)){
             $this->error($validate->getError());
         }
        if($data['brand_url'] && stripos($data['brand_url'],'http://')=== false){
            //没有前缀
            $data['brand_url']= 'http://'.$data['brand_url'];
        }
        //处理图片上传的操作
         if($_FILES['brand_img']['tmp_name']){
             $data['brand_img'] = $this->upload();
         }
        $add = db('brand')->insert($data);
        if($add){
            $this->success('添加商品成功','list');
        }else{
            $this->error('添加商品失败');
        }
         return;
     }
     return view();
 }

 //上传图片的操作
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('brand_img');
        // 移动到框架应用根目录/uploads/ 目录下
        $dir ='./uploads/products';
        $info = $file->move( $dir);
        if($info){
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

   //删除商品
    public function del($id){
        $del = db('brand')->delete($id);
        if($del){
            $this->success('删除商品成功','list');
        }else{
            $this->error('添加商品失败');
        }
        return;
    }
}
