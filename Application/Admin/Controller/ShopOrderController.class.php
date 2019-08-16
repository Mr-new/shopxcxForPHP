<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopOrderController extends BaseController {
    //获取订单列表
    public function getOrderList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_order');
        $status=I("status");
        if($status!=0){
            $map['status']=$status;
        }else{
            $map['ordernumber']=array('like',"%$select_word%");
            $map['wechat_shop_order.tel']=array('like',"%$select_word%");
            $map['nickName']=array('like',"%$select_word%");
            $map['sumprice']=array('like',"%$select_word%");
            $map['_logic']='OR';
        }
        $list=$Table->join("wechat_shop_user ON wechat_shop_order.userid = wechat_shop_user.id")->where($map)->field("wechat_shop_user.nickName,wechat_shop_order.id,wechat_shop_order.ordernumber,wechat_shop_order.userid,wechat_shop_order.shopid,wechat_shop_order.specstitle,wechat_shop_order.specsprice,wechat_shop_order.tel,wechat_shop_order.remarks,wechat_shop_order.sumprice, wechat_shop_order.reduceprice, wechat_shop_order.payprice, wechat_shop_order.number,wechat_shop_order.status,wechat_shop_order.datetime")->order('wechat_shop_order.datetime desc')->page($pageIndex.",$number")->select();
        $count=$Table->join("wechat_shop_user ON wechat_shop_order.userid = wechat_shop_user.id")->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $commodityTable=M('shop_commodity');
            $commentTable=M('shop_commodity_comment');
            foreach ($list as $k=>$v){
                $find=$commentTable->where("orderid='{$list[$k]['id']}'")->field("xing,content,imglist,datetime")->find();
                if($find){
                    $find['xing']=(int)$find['xing'];
                    $list[$k]['comment']=$images->spliceIdGetImgList($find,'imglist','imglist');  //已评论订单
                }else{
                    $list[$k]['comment']=false;  //未评论订单
                }
                $tempArr=array();
                //将字符串转为数组
                $list[$k]['shopid']=explode(",", $list[$k]['shopid']);
                $list[$k]['specstitle']=explode(",", $list[$k]['specstitle']);
                $list[$k]['specsprice']=explode(",", $list[$k]['specsprice']);
                $list[$k]['number']=explode(",", $list[$k]['number']);
                //遍历商品id数组
                foreach ($list[$k]['shopid'] as $key=>$value){
                    //查找相关商品
                    $temp=$commodityTable->where("id='{$list[$k]['shopid'][$key]}'")->field("id,name,thumbnail")->find();
                    //查找相关数量
                    $temp['number']=$list[$k]['number'][$key];
                    //查找相关规格
                    $temp['specs']->title=$list[$k]['specstitle'][$key];
                    $temp['specs']->price=$list[$k]['specsprice'][$key];
                    array_push($tempArr,$temp);
                }
                //拼接图片路径
                $tempArr=$images->getImagesList($tempArr,'thumbnail', 'thumbnail');
                $list[$k]['shopList']=$tempArr;
                unset($list[$k]['shopid']);
                unset($list[$k]['specstitle']);
                unset($list[$k]['specsprice']);
                unset($list[$k]['number']);
            }
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $list,
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
    //修改订单状态为已退款状态
    public function saveOrderStatus(){
        $Table=M('shop_order');
        $id=I('id');  //主键id
        $find=$Table->where("id=$id")->field("ordernumber,payprice")->find();
        $refund=A('Refund');
        //发起退款操作
        $re=$refund->wxRefundApi($find['ordernumber'],$find['payprice']);
        if($re['num']==1){
            $data['status']=5;
            $Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'退款成功',
                'data' => ''
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'退款失败',
                'data' => $re
            );
        }
        $this->ajaxReturn($result);
    }




    //删除日记
    public function deleteCase(){
        $id=I('id');
        $Table=M('shop_case');
        $imgId=$Table->where("id=$id")->getField('headerimg');
        $images=A('Common/Images');
        if($imgId){
            $images->deleteImage($imgId);
        }
        $swiperImgId=$Table->where("id=$id")->getField("imglist");
        if($swiperImgId){
            $swiperImgId=explode(",", $swiperImgId);
            foreach ($swiperImgId as $k=>$v){
                $images->deleteImage($v);
            }
        }
        $delSql=$Table->where("id=$id")->delete();
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
    //删除图片：vue删除图片
    public function delImage(){
        $imgId=I('imgId');
        $id=I('id');
        $Table=M('shop_case');
        $images=M('images');
        if(!empty($imgId)){
            $imgStr=$Table->where("id=$id")->getField("imglist");
            $imgArr=explode(",", $imgStr);
            foreach ($imgArr as $k=>$v){
                if($imgId==$v){
                    unset($imgArr[$k]);
                }
            }
            $saveData['imglist']=implode(",", $imgArr);
            $Table->where("id=$id")->save($saveData);
            $imgName=$images->where("id=$imgId")->getField('image');
            if($imgName){
                unlink('./Public/uploadImages/'.$imgName);  //删除图片文件
            }
            $delImage=$images->where("id=$imgId")->delete();
            if($delImage){
                $result=array(
                    'success'=>true,
                    'msg'=>'删除成功',
                    'data' => $delImage
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'删除失败',
                    'data' => ''
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}