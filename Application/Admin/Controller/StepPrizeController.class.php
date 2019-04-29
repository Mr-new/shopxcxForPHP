<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class StepPrizeController extends BaseController {
    //获取优惠券奖品列表
    public function getStepPrizeList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $prizeTable=M('step_prize');
        $map['title']=array('like',"%$select_word%");
        $list=$prizeTable->where($map)->order('id asc')->page($pageIndex.",$number")->select();
        $count=$prizeTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $slist=$images->getImagesList($list,'imgid', 'imgUrl');
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $slist
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

    //修改或添加奖品信息
    public function saveStepPrize(){
        $prizeTable=M('step_prize');
        $id=I('id')?I('id'):null;  //主键id
        $title=I('title');
        $condition=I('condition');
        $imgId=I('imgid')?I('imgid'):null;  //图片id
        $PrizeData['title']=$title;
        $PrizeData['condition']=$condition;
        $PrizeData['imgid']=$imgId;
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
    //删除奖品
    public function deleteStepPrize(){
        $id=I('id');
        $prizeTable=M('step_prize');
        $imgId=$prizeTable->where("id=$id")->getField('imgid');
        $images=A('Common/Images');
        $images->deleteImage($imgId);
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