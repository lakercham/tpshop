<?php

namespace app\admin\controller;

use think\Controller;
use think\Env;
use think\Request;

class Goods extends Controller
{
    public function create(){
        $this->assign([
            ''
        ]);
        return view();
    }
}
