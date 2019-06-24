<?php
namespace Shop\Controller;
use Think\Controller;
class CartController extends Controller {
    //加入购物车
    public function addShopCart(){
        $cartTable=M("shop_cart");
        $data['shopid']=I("shopid");
        $data['specsid']=I("specsid");
        $data['userid']=I("userid");
        $data['number']=I("number");
        $data['checked']=1;
        $data['datetime']=date('Y-m-d H:i:s',time());
        $map['userid']=$data['userid'];
        $map['shopid']=$data['shopid'];
        $map['specsid']=$data['specsid'];
        $find=$cartTable->where($map)->getField("id");
        if($find){
            //此时该用户购物车已有相同商品相同规格存在：数量加1
            $save=$cartTable->where("id=$find")->setInc('number');
            if($save){
                $result=array(
                    'success'=>true,
                    'msg'=>'加入购物车成功',
                    'data' => $save
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'加入购物车失败',
                    'data' => ''
                );
            }
        }else{
            //此时该用户购物车没有该商品：添加一条商品数据
            $add=$cartTable->add($data);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'加入购物车成功',
                    'data' => $add
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'加入购物车失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //获取用户购物车数据
    public function getShopCartList(){
        $userid=I('userid');
        $cartTable=M("shop_cart");
        $map['userid']=$userid;
        $cartList=$cartTable->join("wechat_shop_commodity ON wechat_shop_cart.shopid = wechat_shop_commodity.id")->join("wechat_shop_commodity_specs ON wechat_shop_cart.specsid = wechat_shop_commodity_specs.id")->where($map)->field("wechat_shop_cart.id,wechat_shop_cart.number,wechat_shop_commodity.id as shopid,wechat_shop_commodity.name,wechat_shop_commodity.thumbnail,wechat_shop_commodity_specs.id as specsid,wechat_shop_commodity_specs.title,wechat_shop_commodity_specs.price")->order("wechat_shop_cart.datetime desc")->select();
        if($cartList){
            $images=A('Common/Images');
            //拼接图片路径
            $cartList=$images->getImagesList($cartList,'thumbnail', 'thumbnail');
            foreach ($cartList as $k=>$v){
                $cartList[$k]['checked']=true;
            }
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $cartList
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
    //删除购物车数据
    public function deleteCartList(){
        $ids=I("ids");
        implode(',',$ids);
        $cartTable=M("shop_cart");
        $delete=$cartTable->delete($ids);
        if($delete){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $delete
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'删除失败',
                'data' => ""
            );
        }
        $this->ajaxReturn($result);
    }
}