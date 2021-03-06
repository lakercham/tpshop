<?php
namespace app\admin\validate;
use think\Validate;
class Attr extends Validate
{
    protected $rule =   [
        'type_id'  => 'require',
        'attr_name'  => 'require|unique:attr', 
    ];
    
    protected $message  =   [
        'type_id.require' => '所属分类必须',
        'attr_name.require' => '属性名称必须',
        'attr_name.unique'     => '属性名称不能重复',
    ];


}