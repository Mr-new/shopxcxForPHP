<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepGiftController extends BaseController {
    //获取抽奖奖品列表
    public function getStepGiftList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $prizeTable=M('step_gift');
        $map['gift']=array('like',"%$select_word%");
        $list=$prizeTable->where($map)->order('id asc')->page($pageIndex.",$number")->select();
        $count=$prizeTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
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

    //修改或添加抽奖奖品信息
    public function saveStepGift(){
        $prizeTable=M('step_gift');
        $id=I('id')?I('id'):null;  //主键id
        $gift=I('gift');
        $PrizeData['gift']=$gift;
        $PrizeData['datetime']=date('Y-m-d H:i:s',time());
        if(empty($id)){
            //添加
            $add=$prizeTable->add($PrizeData);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $add,
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'添加失败',
                    'data' => ''
                );
            }
        }else{
            //修改
            $save=$prizeTable->where("id=$id")->save($PrizeData);
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
        }

        $this->ajaxReturn($result);
    }
    //删除抽奖奖品
    public function deleteStepGift(){
        $id=I('id');
        $prizeTable=M('step_gift');
        $delSql=$prizeTable->where("id=$id")->delete();
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