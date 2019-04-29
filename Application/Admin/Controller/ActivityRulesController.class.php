<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ActivityRulesController extends BaseController {
    //获取规则列表
    public function getRulesList(){
        $ActivityRulesTable=M('activity_rules');
        $list=$ActivityRulesTable->find();
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'list' => $list
                )
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
    //修改或添加规则
    public function saveRules(){
        $ActivityRulesTable=M('activity_rules');
        $id=1;  //主键id
        $content=I('post.content');
        $temp = strtr($content, array("&lt;" => '<'));
        $temp = strtr($temp, array("&gt;" => '>'));
        $RulesData['content']=$temp;
        $RulesData['datetime']=date('Y-m-d H:i:s',time());
        //修改
        $save=$ActivityRulesTable->where("id=$id")->save($RulesData);
        if($save){
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $id
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'修改失败',
                'data' => ''
            );
        }
//        $result=array(
//            'success'=>true,
//            'msg'=>'修改成功',
//            'data' => $temp
//        );
        $this->ajaxReturn($result);
    }
    //删除规则
    public function deleteRules(){
        $id=I('id');
        $ActivityRulesTable=M('activity_rules');
        $delSql=$ActivityRulesTable->where("id=$id")->delete();
        if($delSql){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $delSql
            );
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}