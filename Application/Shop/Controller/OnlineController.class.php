<?php
namespace Shop\Controller;
use Think\Controller;
class OnlineController extends Controller {
    //提交预约信息操作
    public function addOnline(){
        $Table=M('shop_online');
        $data['name']=I('patients');
        $data['sex']=I('sex');
        $data['tel']=I('tel');
        $data['date']=I('yydate');
        $data['typename']=I("typename");
        $data['content']=I('content');
        $data['mind']=I('mind');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $find=$Table->where("tel='{$data['tel']}'")->find();
        if($find){
            $result=array(
                'success'=>true,
                'msg'=>'您已经预约过了哟！',
                'data' => ''
            );
        }else{
            $add=$Table->add($data);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'您已预约成功,我们的客服将会稍后联系您！',
                    'data' => $add
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'预约失败，请稍后重新提交',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
}