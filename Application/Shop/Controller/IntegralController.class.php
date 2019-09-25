<?php
namespace Shop\Controller;
use Think\Controller;
class IntegralController extends Controller {
    //获取用户积分
    public function getUserIntegral(){
        $userid=I('userid');
        if(empty($userid)){
            $result=array(
                'success'=>false,
                'msg'=>'查找失败',
                'data' => 0
            );
        }else{
            $table=M("shop_user");
            $temp=$table->where("id=$userid")->getField("integral");
            if($temp){
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
            $hospitalTable=M('shop_hospital');
            $data['number']=$hospitalTable->where("id=1")->getField("integral");
            $userTable=M('shop_user');
            $userTable->where("id=$userid")->setInc('integral',$data['number']);
            $data['date']=$curretnDate;
            $table->add($data);
            $result=array(
                'success'=>true,
                'msg'=>"恭喜您签到成功获得{$data['number']}积分!",
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取积分商品列表
    public function getIntegralGoodsList(){
        $integralTable=M('shop_integral_goods');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $map['isup']=1;  //查找已上架的商品
        $commodityList=$integralTable->where($map)->field("id,title,pic,integral,salesvolume,details")->order("sort desc")->page($pageIndex.",$number")->select();
        $count=$integralTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'pic', 'pic');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $commodityList
                )
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
    //根据积分商品id获取积分商品详情内容
    public function getIntegralGoodsDetails(){
        $integralTable=M('shop_integral_goods');
        $integralId=I('id');
        $userId=I('userid');
        $map['id']=$integralId;
        $commodityDetails=$integralTable->where($map)->field("id,title,imglist,integral,salesvolume,details")->find();
        if($commodityDetails){
            $images=A('Common/Images');
            //通过逗号分隔id从而查找图片列表
            $commodityDetails=$images->spliceIdGetImgList($commodityDetails,'imglist', 'imglist');
            $userTable=M('shop_user');
            $commodityDetails['userIntegral']=$userTable->where("id=$userId")->getField("integral");
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityDetails
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
    //获取提交积分订单商品信息
    public function getIntegralOrderMsg(){
        $shopId=I("shopId");
        $integralTable=M('shop_integral_goods');
        $map['id']=$shopId;
        $commodityDetails=$integralTable->where($map)->field("id,title,pic,integral")->find();
        if($commodityDetails){
            $images=A('Common/Images');
            //通过逗号分隔id从而查找图片列表
            $commodityDetails=$images->getImagesList($commodityDetails,'pic', 'pic');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityDetails
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
    //兑换积分商品操作
    public function submitOrder(){
        //执行操作：将用户提交订单信息存储到wechat_shop_integral_order表，扣除用户相应积分
        $order=A('Order');
        $data['ordernumber']=$order->randNumber();
        $data['userid']=I('userid');
        $data['integralgoodsid']=I('integralgoodsid');
        $data['tel']=I('tel');
        $data['remarks']=I('remarks');
        $data['sumintegral']=I('sumintegral');
        $data['datetime']=date('Y-m-d H:i:s',time());
        //添加用户积分兑换订单信息
        $orderTable=M('shop_integral_order');
        $add=$orderTable->add($data);
        if($add){
            //扣除用户相应积分
            $userTable=M('shop_user');
            $userTable->where("id={$data['userid']}")->setDec('integral',$data['sumintegral']);
            //增加积分商品销量
            $integralGoodsTable=M('shop_integral_goods');
            $integralGoodsTable->where("id={$data['integralgoodsid']}")->setInc('salesvolume');
            $result=array(
                'success'=>true,
                'msg'=>'兑换成功',
                'data' => array(
                    'orderNumber' => $data['ordernumber'],
                    'sumPrice' => $data['sumintegral']
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'兑换失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取此用户订单列表
    public function getOrderList(){
        $userid=I('userid');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $orderTable=M('shop_integral_order');
        $map['userid']=$userid;
        $orderList=$orderTable->where($map)->order("datetime desc")->page($pageIndex.",$number")->select();
        $count=$orderTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($orderList){
            $images=A('Common/Images');
            $commodityTable=M('shop_integral_goods');
            foreach ($orderList as $k=>$v){
                //查找相关商品
                $orderList[$k]['goods']=$commodityTable->where("id='{$orderList[$k]['integralgoodsid']}'")->field("id,title,pic")->find();
                //拼接图片路径
                $orderList[$k]['goods']=$images->getImagesList($orderList[$k]['goods'],'pic', 'pic');
            }

            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $orderList
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
    //获取用户积分订单详情信息
    public function getIntegralOrderDetails(){
        $orderId=I("id");
        $orderTable=M('shop_integral_order');
        $orderDetails=$orderTable->where("id=$orderId")->find();
        if($orderDetails){
            $images=A('Common/Images');
            $commodityTable=M('shop_integral_goods');
            $orderDetails['goods']=$commodityTable->where("id='{$orderDetails['integralgoodsid']}'")->field("title,pic")->find();
            //拼接图片路径
            $orderDetails['goods']=$images->getImagesList($orderDetails['goods'],'pic', 'pic');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $orderDetails
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
}