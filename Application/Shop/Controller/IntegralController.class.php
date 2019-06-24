<?php
namespace Shop\Controller;
use Think\Controller;
class IntegralController extends Controller {
    //获取用户积分
    public function getUserIntegral(){
        $userid=I('userid');
        $table=M("shop_integral");
        $integralList=$table->where("userid=$userid")->field("number")->select();
        $temp=0;
        foreach ($integralList as $k=>$v){
            $temp+=$integralList[$k]['number'];
        }
        if($integralList){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $temp
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'查找失败',
                'data' => 0
            );
        }
        $this->ajaxReturn($result);
    }
    //签到获得积分
    public function addUserIntegral(){
        $userid=I('userid');
        $table=M("shop_integral");
        $curretnDate=date("Y-m-d");
        $map['userid']=$userid;
        $map['date']=$curretnDate;
        $find=$table->where($map)->find();
        if($find){
            $result=array(
                'success'=>false,
                'msg'=>'您今天已经签到过了哟,明天再来签到吧！',
                'data' => ''
            );
        }else{
            $data['userid']=$userid;
            $data['number']=1;
            $data['date']=$curretnDate;
            $table->add($data);
            $result=array(
                'success'=>true,
                'msg'=>'恭喜您签到成功获得1积分!',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}