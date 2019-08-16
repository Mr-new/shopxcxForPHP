<?php
namespace Shop\Controller;
use Think\Controller;
class YestarController extends Controller {
    //获取艺星详细内容
    public function getDetails(){
        $Table=M("shop_hospital");
        $Details=$Table->where("id=1")->getField("yestardetails");
        if($Details){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $Details
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