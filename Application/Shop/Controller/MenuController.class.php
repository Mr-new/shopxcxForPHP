<?php
namespace Shop\Controller;
use Think\Controller;
class MenuController extends Controller {
    //获取首页热门爆品列表
    public function getMenuList(){
        $menuTable=M('shop_menu');
        $menuList=$menuTable->field("id,title,engtitle,img,fontcolor,url")->order("sort desc")->select();
        if($menuList){
            $images=A('Common/Images');
            $menuList=$images->getImagesList($menuList,'img', 'img');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $menuList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到所需数据',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }

}