<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopConfigController extends BaseController {
    //获取基本配置信息
    public function getConfig(){
        $Table=M('shop_hospital');
        $list=$Table->where("id=1")->find();
        if($list){
            $images=A('Common/Images');
            $list=$images->getImagesList($list,'logo', 'logoImgUrl');
            $list=$images->getImagesList($list,'teamimg', 'teamImgUrl');
            $list=$images->getImagesList($list,'hospitalimg', 'hospitalImgUrl');
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $list
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //修改基本配置信息
    public function saveConfig(){
        $Table=M('shop_hospital');
        $id=I('id');  //主键id
        $data['hospitalname']=I('hospitalname');
        $data['time']=I('time');
        $data['address']=I('address');
        $data['tel']=I('tel');
        $data['lat']=I('lat');
        $data['lng']=I('lng');
        $data['city']=I('city');
        $data['integral']=I('integral');
        $data['yestardetails']=$_POST['yestardetails'];
        $save=$Table->where("id=$id")->save($data);
        $result=array(
            'success'=>true,
            'msg'=>'修改成功',
            'data' => $save
        );
        $this->ajaxReturn($result);
    }
}