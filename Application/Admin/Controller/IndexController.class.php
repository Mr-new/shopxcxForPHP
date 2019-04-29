<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class IndexController extends BaseController {
    //显示首页
    public function test(){
        $menuTable=M('admin_menu');
        $list=$menuTable->order("id asc")->select();
        echo '<pre>';
        print_r($list);
    }
}