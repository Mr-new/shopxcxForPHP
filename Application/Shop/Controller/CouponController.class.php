<?php
namespace Shop\Controller;
use Think\Controller;
class CouponController extends Controller {
    //获取可领取优惠券列表
    public function getCouponList(){
        //获取到当前日期，查看当前日期是否在优惠券设置的日期内；如果在则说明此优惠券是可以领取的，否则将不能领取->即不用展现给用户
        //当前时间>=开始时间；当前时间<=结束时间
        $Table=M('shop_coupon');
        $currentDate=date('Y-m-d',time());  //当前日期
        $map['isup'] = 1;
        $map['startdate'] = array('ELT', $currentDate);
        $map['enddate'] = array('EGT', $currentDate);
        $map['number'] = array('NEQ', 0);
        $map['_logic'] = 'AND';
        $List=$Table->where($map)->field("id,title,full,reduce,number,startdate,enddate")->order("sort desc")->select();
        if($List){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $List
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有可以领取的优惠券哟',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //用户领取优惠券操作:  检测用户是否领取过此优惠券；检测优惠券数量是否还有
    public function ReceiveCoupon(){
        $Table=M('shop_coupon_record');
        $data['userid']=I('userid');
        $data['couponid']=I('couponid');
        $find=$Table->where($data)->find();
        if($find){
            //此时用户领取过此优惠券
            $result=array(
                'success'=>false,
                'msg'=>'您已经领取过此优惠券了哟!',
                'data' => ''
            );
        }else{
            //此时用户没有领取过此优惠券
            $couponTable=M('shop_coupon');
            $map['id']=$data['couponid'];
            $couponNumber=$couponTable->where($map)->getField("number");
            if($couponNumber==0){
                //此时优惠券可领取数量为0
                $result=array(
                    'success'=>false,
                    'msg'=>'当前优惠券已经被领完了哟！',
                    'data' => ''
                );
            }else{
                //此时给该用户发放此优惠券，减少一个优惠券可领取数量
                $data['status']=1;
                $data['datetime']=date('Y-m-d H:i:s',time());
                $add=$Table->add($data);
                $couponTable->where($map)->setDec('number');
                $result=array(
                    'success'=>true,
                    'msg'=>'领取成功',
                    'data' => $add
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //获取我的优惠券
    public function getMyCouponList(){
        //获取到当前日期，查看当前日期是否在优惠券设置的日期内
        //查找已过期优惠券：结束时间>当前时间
        $status=I('status');
        $Table=M('shop_coupon_record');
        $currentDate=date('Y-m-d',time());  //当前日期
        $map['wechat_shop_coupon_record.userid']=I('userid');
        if($status==1){
            //查找未使用优惠券：已过期优惠券不显示
            $map['wechat_shop_coupon_record.status']=$status;
            $map['wechat_shop_coupon.startdate'] = array('ELT', $currentDate);
            $map['wechat_shop_coupon.enddate'] = array('EGT', $currentDate);
        }else if($status==2){
            //查找已使用优惠券：已过期优惠券显示
            $map['wechat_shop_coupon_record.status']=$status;
        }else if($status==3){
            //查找已过期优惠券
            $map['wechat_shop_coupon_record.status'] = array('NEQ', 2);
            $map['wechat_shop_coupon.enddate'] = array('LT', $currentDate);
        }
        $map['_logic'] = 'AND';
        $List=$Table->join("wechat_shop_coupon ON wechat_shop_coupon_record.couponid = wechat_shop_coupon.id")->where($map)->field("wechat_shop_coupon_record.id, wechat_shop_coupon_record.status, wechat_shop_coupon_record.datetime, wechat_shop_coupon.title, wechat_shop_coupon.full, wechat_shop_coupon.reduce, wechat_shop_coupon.reduce, wechat_shop_coupon.startdate, wechat_shop_coupon.enddate")->order("wechat_shop_coupon_record.datetime desc")->select();
        if($List){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $List
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到相关优惠券哟！',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取当前可用于该商品的优惠券列表
    public function getIsUseCouponList(){
        //获取到当前日期，查看当前日期是否在优惠券设置的日期内
        //查找已过期优惠券：结束时间>当前时间
        $Table=M('shop_coupon_record');
        $currentDate=date('Y-m-d',time());  //当前日期
        $orderArr=json_decode($_POST['orderArr']);
        $shopTable=M('shop_commodity');
        $specsTable=M('shop_commodity_specs');
        //计算总价格
        $sumPrice=0;
        foreach ($orderArr as $k=>$v){
            $temp=$shopTable->where("id='{$orderArr[$k]->shopId}'")->field("id,name,thumbnail")->find();
            $temp['specs']=$specsTable->where("id='{$orderArr[$k]->specsId}'")->field("id,title,price")->find();
            $temp['number']=$orderArr[$k]->number;
            $sumPrice=$sumPrice+($temp['specs']['price']*$orderArr[$k]->number);
        }

        //优惠券属于此用户
        $map['wechat_shop_coupon_record.userid']=I('userid');
        //优惠券在有效期内
        $map['wechat_shop_coupon.startdate'] = array('ELT', $currentDate);
        $map['wechat_shop_coupon.enddate'] = array('EGT', $currentDate);
        //优惠券状态=1：未使用
        $map['wechat_shop_coupon_record.status']=1;
        $map['_logic'] = 'AND';
        $List=$Table->join("wechat_shop_coupon ON wechat_shop_coupon_record.couponid = wechat_shop_coupon.id")->where($map)->field("wechat_shop_coupon_record.id, wechat_shop_coupon_record.status, wechat_shop_coupon_record.datetime, wechat_shop_coupon.title, wechat_shop_coupon.full, wechat_shop_coupon.reduce, wechat_shop_coupon.reduce,wechat_shop_coupon.goodsid, wechat_shop_coupon.startdate, wechat_shop_coupon.enddate")->order("wechat_shop_coupon_record.datetime desc")->select();
        if($List){
            $temp=array();
            //查找优惠券是否可用于当前商品
            foreach ($List as $k=>$v){
                $List[$k]['goodsidList']=explode(",", $List[$k]['goodsid']);
                foreach ($orderArr as $key=>$value){
                    if(in_array($orderArr[$key]->shopId, $List[$k]['goodsidList'])){
                        //判断总支付金额大于优惠券使用金额
                        if($sumPrice>=$List[$k]['full']){
                            $temp[$k][$key]=$List[$k];
                            unset($List[$k]);
                        }
                    }
                }
            }
            //满足使用条件的优惠券
            $temp=array_reduce($temp, 'array_merge', array());
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $temp,
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到相关优惠券哟！',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }

}