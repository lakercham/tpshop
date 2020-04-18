<?php
namespace app\admin\validate;
use think\Validate;
class Category extends Validate
{
    protected $rule =   [
        'cate_name'  => 'require|unique:category',
    ];

    protected $message  =   [
        'cate_name.require' => '品牌名称必须',
        'cate_name.unique'     => '品牌名称不能重复',
    ];


}