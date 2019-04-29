<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class WinPrizeController extends BaseController {
    //获取中奖列表
    public function getWinPrizeList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $smasheggsrecordTable=M('smasheggsrecord');
        $map['nickName']=array('like',"%$select_word%");
        $map['tel']=array('like',"%$select_word%");
        $map['code']=array('like',"%$select_word%");
        $map['prize']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$smasheggsrecordTable->join("wechat_user ON wechat_smasheggsrecord.userid=wechat_user.id")->join("wechat_prize ON wechat_smasheggsrecord.prizeid=wechat_prize.id")->where($map)->field("wechat_smasheggsrecord.id,wechat_smasheggsrecord.code,wechat_smasheggsrecord.datetime,wechat_smasheggsrecord.status,wechat_user.nickName,wechat_user.tel,wechat_user.city,wechat_user.province,wechat_prize.prize")->order('wechat_smasheggsrecord.datetime desc')->page($pageIndex.",$number")->select();
        foreach ($list as $k=>$v){
            if($list[$k]['status']==1){
                $list[$k]['statusMsg']="未领取";
            }else if($list[$k]['status']==2){
                $list[$k]['statusMsg']="已领取";
            }if($list[$k]['status']==3){
                $list[$k]['statusMsg']="已使用";
            }
        }
        $count=$smasheggsrecordTable->where($map)->count();// 查询满足要求的总记录数
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
    //修改中奖信息
    public function saveWinPrize(){
        $smasheggsrecordTable=M('smasheggsrecord');
        $id=I('id')?I('id'):null;  //主键id
        $data['status']=I('status');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $save=$smasheggsrecordTable->where("id=$id")->save($data);
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

        $this->ajaxReturn($result);
    }
    //删除中奖信息
    public function deleteInCode(){
        $id=I('id');
        $codeRecordTable=M('code_record');
        $delSql=$codeRecordTable->where("id=$id")->delete();
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
    //导出为excel表格
    public function exportExcel(){
        $smasheggsrecordTable=M('smasheggsrecord');
        $list=$smasheggsrecordTable->join("wechat_user ON wechat_smasheggsrecord.userid=wechat_user.id")->join("wechat_prize ON wechat_smasheggsrecord.prizeid=wechat_prize.id")->field("wechat_smasheggsrecord.id,wechat_smasheggsrecord.code,wechat_smasheggsrecord.datetime,wechat_smasheggsrecord.status,wechat_user.nickName,wechat_user.tel,wechat_user.city,wechat_user.province,wechat_prize.prize")->order('wechat_smasheggsrecord.datetime desc')->select();
        foreach ($list as $k=>$v){
            if($list[$k]['status']==1){
                $list[$k]['statusMsg']="未领取";
            }else if($list[$k]['status']==2){
                $list[$k]['statusMsg']="已领取";
            }if($list[$k]['status']==3){
                $list[$k]['statusMsg']="已使用";
            }
        }
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $list
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => ''
            );
        }

        $this->ajaxReturn($result);
    }

}