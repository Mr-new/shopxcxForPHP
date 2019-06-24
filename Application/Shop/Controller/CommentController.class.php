<?php
namespace Shop\Controller;
use Think\Controller;
class CommentController extends Controller {
    //获取评论内容
    public function getComment(){
        $commentTable=M('shop_commodity_comment');
        $id=I('id');
        $commentDetails=$commentTable->where("id=$id")->field("id,xing,content,imglist,datetime")->find();
        if($commentDetails){
            $images=A('Common/Images');
            $commentDetails=$images->spliceIdGetImgList($commentDetails,'imglist','imglist');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commentDetails
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'查找失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //提交评论内容
    public function addComment(){
        $data['orderid']=I('orderid');
        $data['shopid']=I('shopid');
        $data['userid']=I('userid');
        $data['xing']=I('xing');
        $data['content']=I('content');
        $data['imglist']=I('imglist');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $commentTable=M('shop_commodity_comment');
        $add=$commentTable->add($data);
        if($add){
            $result=array(
                'success'=>true,
                'msg'=>'提交评论成功',
                'data' => $add
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'提交评论失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //删除评论图片
    public function delCommentImages(){
        $imgId=I('id');
        $images=A('Common/Images');
        $delReturn=$images->deleteImage($imgId);
        if($delReturn){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $delReturn
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