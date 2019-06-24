<?php
namespace Shop\Controller;
use Think\Controller;
class HospitalController extends Controller {
    //获取医院信息
    public function getHospitalMsg(){
        $hospitalTable=M('shop_hospital');
        $map['id'] = 1;
        $hospitalMsg=$hospitalTable->where($map)->find();
        if($hospitalMsg){
            $images=A('Common/Images');
            $hospitalMsg=$images->getImagesList($hospitalMsg,'logo', 'logo');
            $hospitalMsg=$images->getImagesList($hospitalMsg,'teamimg', 'teamimg');
            $hospitalMsg=$images->getImagesList($hospitalMsg,'hospitalimg', 'hospitalimg');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $hospitalMsg
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