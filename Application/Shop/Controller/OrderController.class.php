<?php
namespace Shop\Controller;
use Think\Controller;
class OrderController extends Controller {
    //生成20位订单号
    public function randNumber(){
        $number=date('Ymd',time()).time().rand(10,99);
        return $number;
    }
    //获取提交订单信息
    public function getOrderMsg(){
        $orderArr=json_decode($_POST['orderArr']);
        $couponid=I('couponid');
        $shopTable=M('shop_commodity');
        $specsTable=M('shop_commodity_specs');
        $shopDetails=array();
        $sumPrice=0;
        $tempArr=array();
        foreach ($orderArr as $k=>$v){
            $temp=$shopTable->where("id='{$orderArr[$k]->shopId}'")->field("id,name,thumbnail")->find();
            $temp['specs']=$specsTable->where("id='{$orderArr[$k]->specsId}'")->field("id,title,price")->find();
            $temp['number']=$orderArr[$k]->number;
            array_push($tempArr,$temp);
            $sumPrice=$sumPrice+($temp['specs']['price']*$orderArr[$k]->number);
        }
        if($tempArr){
            $shopDetails['shopList']=$tempArr;
            $images=A('Common/Images');
            //拼接图片路径
            $shopDetails['shopList']=$images->getImagesList($shopDetails['shopList'],'thumbnail', 'thumbnailimg');
            //couponid不为0说明用户选择了优惠券
            if($couponid!=0){
                $Table=M("shop_coupon_record");
                $shopDetails['coupon']=$Table->join("wechat_shop_coupon ON wechat_shop_coupon_record.couponid = wechat_shop_coupon.id")->where("wechat_shop_coupon_record.id=$couponid")->field("wechat_shop_coupon_record.id, wechat_shop_coupon_record.status, wechat_shop_coupon_record.datetime, wechat_shop_coupon.title, wechat_shop_coupon.full, wechat_shop_coupon.reduce, wechat_shop_coupon.reduce, wechat_shop_coupon.startdate, wechat_shop_coupon.enddate")->find();
                //计算使用优惠券后的价格
                $sumPrice-=$shopDetails['coupon']['reduce'];
            }
            $shopDetails['sumPrice']=number_format($sumPrice,2, '.', '');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $shopDetails
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到所需数据',
                'data' => $orderArr
            );
        }
        $this->ajaxReturn($result);
    }
    //获取用户手机号
    public function getUserTel(){
        $APPID = C('APPID');
        $encryptedData = $_POST['encryptedData'];
        $iv = $_POST['iv'];
        $userId=$_POST['userId'];
        $session_key = S($userId);  //从缓存中取出session_key
        Vendor('weChat.wxBizDataCrypt');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $pc->decryptData($encryptedData, $iv, $data);
        $data = json_decode($data);
        if($data){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $data->phoneNumber
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'获取手机号码失败',
                'data' => $data->phoneNumber
            );
        }
        $this->ajaxReturn($result);
    }
    //将数组转为字符串并用逗号隔开
    public function arr_to_str($arr,$item) {
        $t ='' ;
        foreach ($arr as $k=>$v) {
            $t=$t.$arr[$k]->$item.',';
        }
        $t = substr($t, 0, -1); // 利用字符串截取函数消除最后一个逗号
        return $t;
    }
    //提交订单
    public function submitOrder(){
        $orderTable=M('shop_order');
        $specsTable=M('shop_commodity_specs');

        $orderArr=json_decode($_POST['orderArr']);
        $titleStr='';
        $priceStr='';
        foreach ($orderArr as $k=>$v){
            $specsTitle=$specsTable->where("id='{$orderArr[$k]->specsId}'")->getField("title");
            $specsPrice=$specsTable->where("id='{$orderArr[$k]->specsId}'")->getField("price");
            $titleStr=$titleStr.$specsTitle.',';
            $priceStr=$priceStr.$specsPrice.',';

        }
        $titleStr = substr($titleStr, 0, -1); // 利用字符串截取函数消除最后一个逗号
        $priceStr = substr($priceStr, 0, -1); // 利用字符串截取函数消除最后一个逗号
        $data['ordernumber']=$this->randNumber();
        $data['userid']=I('userid');
        $data['shopid']=$this->arr_to_str($orderArr,'shopId');
        $data['specstitle']=$titleStr;
        $data['specsprice']=$priceStr;
        $data['tel']=I('tel');
        $data['remarks']=I("remarks");
        $data['number']=$this->arr_to_str($orderArr,'number');
        //总金额
        $data['sumprice']=I("sumprice");
        //实际支付金额
        $data['payprice']=I("payprice");
        //优惠减免金额
        $data['reduceprice']=I("reduceprice");
        //优惠券记录id
        $data['couponid']=I("couponid");
        $data['status']=1;
        $data['datetime']=date('Y-m-d H:i:s',time());
        $add=$orderTable->add($data);
        if($add){
            //修改优惠券状态为已使用
            $Table=M('shop_coupon_record');
            $savaData['status']=2;
            $Table->where("id='{$data['couponid']}'")->save($savaData);
            //获取支付相关信息
            $userTable=M("shop_user");
            $openid=$userTable->where("id='{$data['userid']}'")->getField("openid");  //用户openid
            $shopName=M('shop_commodity')->where("id='{$orderArr[0]->shopId}'")->getField("name");//商品名称
            $orderNumber=$data['ordernumber'];  //订单编号
            $sumPrice=$data['payprice'];  //实际支付金额
            //发起支付
            $Pay=A('Pay');
            $payResult=$Pay->pay($openid, $shopName, $orderNumber, $sumPrice);
            //提价订单后删除购物车商品数据
            $deleteId=$this->arr_to_str($orderArr,'cartId');
            if($deleteId){
                $cartTable=M('shop_cart');
                $cartTable->delete($deleteId);
            }
            $result=array(
                'success'=>true,
                'msg'=>'订单提交成功',
                'data' => $payResult,
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'订单提交失败',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
    //获取此用户订单列表
    public function getOrderList(){
        $userid=I('userid');
        $status=I('status');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $orderTable=M('shop_order');
        if($status==0){
            $map['userid']=$userid;
        }else if($status==4){
            $where=array(4,5);
            $map['status']=array('in', $where);
            $map['userid']=$userid;
        }else{
            $map['status']=$status;
            $map['userid']=$userid;
        }
        $orderList=$orderTable->where($map)->order("datetime desc")->page($pageIndex.",$number")->select();
        $count=$orderTable->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($orderList){
            $images=A('Common/Images');
            $commodityTable=M('shop_commodity');
            $commentTable=M('shop_commodity_comment');
            foreach ($orderList as $k=>$v){
                $find=$commentTable->where("orderid='{$orderList[$k]['id']}'")->getField("id");
                if($find){
                    $orderList[$k]['isComment']=$find;  //已评论订单
                }else{
                    $orderList[$k]['isComment']=false;  //未评论订单
                }
                $tempArr=array();
                //将字符串转为数组
                $orderList[$k]['shopid']=explode(",", $orderList[$k]['shopid']);
                $orderList[$k]['specstitle']=explode(",", $orderList[$k]['specstitle']);
                $orderList[$k]['specsprice']=explode(",", $orderList[$k]['specsprice']);
                $orderList[$k]['number']=explode(",", $orderList[$k]['number']);
                //遍历商品id数组
                foreach ($orderList[$k]['shopid'] as $key=>$value){
                    //查找相关商品
                    $temp=$commodityTable->where("id='{$orderList[$k]['shopid'][$key]}'")->field("id,name,thumbnail")->find();
                    //查找相关数量
                    $temp['number']=$orderList[$k]['number'][$key];
                    //查找相关规格
                    $temp['specs']->title=$orderList[$k]['specstitle'][$key];
                    $temp['specs']->price=$orderList[$k]['specsprice'][$key];
                    array_push($tempArr,$temp);
                }
                //拼接图片路径
                $tempArr=$images->getImagesList($tempArr,'thumbnail', 'thumbnail');
                $orderList[$k]['shopList']=$tempArr;
                unset($orderList[$k]['shopid']);
                unset($orderList[$k]['specstitle']);
                unset($orderList[$k]['specsprice']);
                unset($orderList[$k]['number']);
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
    //获取订单详情
    public function getOrderDetails(){
        $orderId=I("id");
        $orderTable=M('shop_order');
        $orderDetails=$orderTable->where("id=$orderId")->find();
        if($orderDetails){
            $images=A('Common/Images');
            $commodityTable=M('shop_commodity');
            $specsTable=M('shop_commodity_specs');
            $tempArr=array();
            //将字符串转为数组
            $orderDetails['shopid']=explode(",", $orderDetails['shopid']);
            $orderDetails['specstitle']=explode(",", $orderDetails['specstitle']);
            $orderDetails['specsprice']=explode(",", $orderDetails['specsprice']);
            $orderDetails['number']=explode(",", $orderDetails['number']);
            //遍历商品id数组
            foreach ($orderDetails['shopid'] as $key=>$value){
                //查找相关商品
                $temp=$commodityTable->where("id='{$orderDetails['shopid'][$key]}'")->field("name,thumbnail")->find();
                //查找相关数量
                $temp['number']=$orderDetails['number'][$key];
                //查找相关规格
                $temp['specs']->title=$orderDetails['specstitle'][$key];
                $temp['specs']->price=$orderDetails['specsprice'][$key];
                array_push($tempArr,$temp);
            }
            //拼接图片路径
            $tempArr=$images->getImagesList($tempArr,'thumbnail', 'thumbnail');
            $orderDetails['shopList']=$tempArr;
            unset($orderDetails['shopid']);
            unset($orderDetails['specstitle']);
            unset($orderDetails['specsprice']);
            unset($orderDetails['number']);
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
    //修改订单状态
    public function saveOrderStatus(){
        $orderId=I("id");
        $orderTable=M('shop_order');
        $data['status']=I("status");
        $save=$orderTable->where("id=$orderId")->save($data);
        if($save){
            $result=array(
                'success'=>true,
                'msg'=>'修改订单状态成功',
                'data' => $save
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'修改订单状态失败',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
    //支付未支付的订单
    public function payOrder(){
        $orderId=I("id");
        $orderTable=M('shop_order');
        $orderDetails=$orderTable->where("id=$orderId")->field("shopid,ordernumber,payprice,userid")->find();
        $orderArr=explode(",", $orderDetails['shopid']);
        $userTable=M("shop_user");
        $openid=$userTable->where("id='{$orderDetails['userid']}'")->getField("openid");
        $shopName=M('shop_commodity')->where("id='{$orderArr[0]}'")->getField("name");  //商品名称
        $orderNumber=$orderDetails['ordernumber'];  //订单编号
        $sumPrice=$orderDetails['payprice'];  //总支付金额
        //发起支付
        $Pay=A('Pay');
        $payResult=$Pay->pay($openid, $shopName, $orderNumber, $sumPrice);
        if($payResult['state']==200){
            $result=array(
                'success'=>true,
                'msg'=>'发起支付成功',
                'data' => $payResult
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'发起支付失败',
                'data' => $payResult
            );
        }
        $this->ajaxReturn($result);
    }

}