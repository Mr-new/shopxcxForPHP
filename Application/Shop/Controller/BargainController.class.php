<?php
namespace Shop\Controller;
use Think\Controller;
class BargainController extends Controller {
    //获取砍价商品列表
    public function getBargainCommodityList(){
        $commodityTable=M('shop_commodity');
        $currentDate=date('Y-m-d H:i:s',time());  //当前日期时间
        $status=I('status');
        if($status==2){
            //查找未开始的秒杀商品：当前时间>开始时间
            $map['bargainstartdatetime'] = array('GT', $currentDate);
        }else{
            //查找已开始的秒杀商品：当前时间<开始时间
            $map['bargainstartdatetime'] = array('ELT', $currentDate);
        }
        $map['isbargain'] = 1;
        $map['isup'] = 1;
        $map['bargainenddatetime'] = array('EGT', $currentDate);
        $map['_logic'] = 'AND';
        $commodityList=$commodityTable->where($map)->field("id,name,thumbnail,defaultbargainprice,bargainprice,bargainstartdatetime,bargainenddatetime")->order("sort desc")->select();
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'thumbnail', 'thumbnailUrl');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityList
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
    //添加砍价订单
    public function addBargainOrder(){
        $Table=M('shop_bargain_order');
        $goodsTable=M('shop_commodity');
        $goodsid=I('goodsid');
        $goodsFind=$goodsTable->where("id=$goodsid")->field("bargainprice,defaultbargainprice")->find();
        $data['userid']=I('userid');
        $data['goodsid']=$goodsid;
        $data['price']=$goodsFind['defaultbargainprice'];
        $data['minprice']=$goodsFind['bargainprice'];
        $data['status']=1;
        $data['datetime']=date('Y-m-d H:i:s',time());
        $map['goodsid']=$goodsid;
        $map['userid']=$data['userid'];
        $bargainFind=$Table->where($map)->getField("id");
        if($bargainFind){
            $result=array(
                'success'=>false,
                'msg'=>'您已经有此商品的砍价订单了哟',
                'data' => $bargainFind
            );
        }else{
            $add=$Table->add($data);
            $result=array(
                'success'=>true,
                'msg'=>'添加砍价订单成功',
                'data' => $add
            );
        }
        $this->ajaxReturn($result);
    }
    //获取砍价订单信息详情
    public function getBargainDetails(){
        $Table=M('shop_bargain_order');
        $goodsTable=M('shop_commodity');
        $friendTable=M('shop_bargain_user');
        $orderid=I('orderid');
        $userid=I('userid');
        $details=$Table->where("id=$orderid")->find();
        if($details){
            //检测此砍价订单是帮砍还是此用户笨人
            if($details['userid']==$userid){
                $details['isuser']=true;
            }else{
                $details['isuser']=false;
            }
            //查找订单商品信息
            $details['goods']=$goodsTable->where("id='{$details['goodsid']}'")->field("id,name,thumbnail,defaultbargainprice,bargainenddatetime")->find();
            $images=A('Common/Images');
            $details['goods']=$images->getImagesList($details['goods'],'thumbnail', 'thumbnailUrl');
            //查找订单好友帮
            $details['friendsList']=$friendTable->where("orderid=$orderid")->field("id,userid,price")->order("datetime desc")->select();
            $userTable=M('shop_user');
            foreach ($details['friendsList'] as $k=>$v){
                $details['friendsList'][$k]['nickname']=$userTable->where("id='{$details['friendsList'][$k]['userid']}'")->getField("nickName");
                $details['friendsList'][$k]['avatarurl']=$userTable->where("id='{$details['friendsList'][$k]['userid']}'")->getField("avatarUrl");
            }
            $details['surplus']=round($details['price']-$details['minprice'],2);
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $details
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您的砍价订单信息哟!',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //好友帮忙砍价操作
    public function doBargain(){
        $orderTable=M('shop_bargain_order');
        $friendTable=M('shop_bargain_user');
        $userid=I('userid');
        $orderid=I('orderid');
        //1.查询是否为朋友砍过价
        $find=$friendTable->where("orderid=$orderid and userid=$userid")->find();
        if($find){
            $result=array(
                'success'=>false,
                'msg'=>'您已经帮Ta砍过价了哟!',
                'data' => ''
            );
        }else{
            //砍掉的价格
            $orderDetails=$orderTable->where("id=$orderid")->field("price,minprice")->find();
            //最低价格
            $minPrice=$orderDetails['minprice'];
            //当前价格
            $currentPrice=$orderDetails['price'];
            //2.判断砍价金额是否还能砍
            if($currentPrice==$minPrice){
                $result=array(
                    'success'=>false,
                    'msg'=>'此商品已经被砍到低价了哟!',
                    'data' => ''
                );
                $this->ajaxReturn($result);
            }
            //3.算法算出这一刀多少钱
            $maxRandPrice=$currentPrice-$minPrice;  //剩下还能砍的价格
            $min=0.01;
            $total = mt_rand($min * 100, $maxRandPrice * 100) / 100;
            $total = sprintf("%.2f", $total);   //砍掉的金额
            if($total==0){
                $total=$currentPrice-$minPrice;
            }
            //4.数据库数据添加
            $dec=$orderTable->where("id=$orderid")->setDec('price',$total);
            $data['orderid']=$orderid;
            $data['userid']=$userid;
            $data['price']=$total;
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$friendTable->add($data);
            if($dec && $add){
                $result=array(
                    'success'=>true,
                    'msg'=>'您帮Ta砍掉了'.round($total,2).'元',
                    'data' => ''
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'砍价失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }


}