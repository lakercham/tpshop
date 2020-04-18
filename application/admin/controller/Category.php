<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;
class Category extends Controller
{
    public function list()
    {
        $cateTree =new Catetree();
        $cateObj = db('category');
        if(request()->isPost()){
            $data = input('post.');
            $res = $cateTree->sortCate($data['sort'],$cateObj);
            if($res){
                $this->success('修改成功',url('list'));
            }
        }
    	$cateRes=$cateObj->order('sort ASC')->select();
        $cateRes=$cateTree->catetree($cateRes);
        $this->assign([
            'cateRes'=>$cateRes,
            ]);
        return view();
    }

    //增加
    public function create()
    {
        //添加分类
        $cateTree =new Catetree();
    	if(request()->isPost()){
    		$data=input('post.');
            //处理图片上传的操作
            if($_FILES['cate_img']['tmp_name']){
                $data['cate_img'] = $this->upload();
            }
            $add = db('category')->insert($data);
            if($add){
                $this->success('添加分类成功','list');
            }else{
                $this->error('添加分类失败');
            }
            return;
    	}
        $cateRes=db('category')->select();
        $cateRes=$cateTree->catetree($cateRes);
        $this->assign([
            'cateRes'=>$cateRes,
            ]);
        return view();
    }

    //编辑
    public function edit(){
        $cateTree =new Catetree();
        if(request()->isPost()){
            $data = input('post.');
            //进行数据验证
            $validate = validate('Category');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            //处理图片上传的操作
            if($_FILES['cate_img']['tmp_name']){
                //判断之前是否存在图片
                $oldCategory = db('category')->field('cate_img')->find($data['id']);
                $oldBrandImg =  CATEGORY_IMG_UPLOADS.$oldCategory['cate_img'];
                if(file_exists($oldBrandImg)){
                    @unlink($oldBrandImg);
                }
                $data['cate_img'] = $this->upload();
            }
            $save = db('category')->update($data);
            if($save){
                $this->success('编辑分类成功','list');
            }else{
                $this->error('编辑分类失败');
            }
            return;
        }

        $id = input('id');
        $categorys = db('category')->find($id); //对应id的数据
        $categoryRes =db('category')->select();
        $categoryRes = $cateTree->catetree($categoryRes);
        $this->assign([
            'categoryRes'=>$categoryRes,  //数组数据
            'categorys'=>$categorys  //对象数据
        ]);
        return view();
    }



    //删除分类
    public function del($id){
        $cate = db('category');//实例化一个对象
        $cateTree = new Catetree();
        $sonIds = $cateTree->childrenids($id,$cate);
        $sonIds[]=intval($id);
        $del = $cate->delete($sonIds);
        if($del){
            $this->success('删除成功','list');
        }else{
            $this->error('添加失败');
        }
        return;
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('cate_img');
        // 移动到框架应用根目录/uploads/ 目录下
        $dir ='./uploads/category';
        $info = $file->move( $dir);
        if($info){
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }



}