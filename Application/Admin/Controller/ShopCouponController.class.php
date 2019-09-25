<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class ShopCouponController extends BaseController {
    //获取优惠券列表
    public function getCouponList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_coupon');
        $map['title']=array('like',"%$select_word%");
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $shopTable=M('shop_commodity');
            foreach ($list as $k=>$v){
                if($list[$k]['isup']==1){
                    $list[$k]['isup']=true;
                }else{
                    $list[$k]['isup']=false;
                }
                if(!empty($list[$k]['goodsid'])){
                    $tempArr=array();
                    $goodsIdArr = explode(',',$list[$k]['goodsid']);
                    foreach ($goodsIdArr as $key => $value){
                        $temp=$shopTable->where("id='{$value}'")->field("id,name")->find();
                        array_push($tempArr,$temp);
                    }
                    $list[$k]['goodList']=$tempArr;
                }else{
                    $list[$k]['goodList']="";
                }

            }
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
    //获取商品列表
    public function getGoodsList(){
        $Table=M('shop_commodity');
        $goodsList=$Table->field("id,name")->select();
        if($goodsList){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $goodsList
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => '没有找到您需要的数据哟'
            );
        }
        $this->ajaxReturn($result);
    }
    //修改优惠券信息
    public function saveCoupon(){
        $Table=M('shop_coupon');
        $id=I('id')?I('id'):null;  //主键id
        $data['title']=I('title');
        $data['goodsid']=I('goodsid');
        $data['full']=I('full');
        $data['reduce']=I('reduce');
        $data['number']=I('number');
        $data['startdate']=I('startdate');
        $data['enddate']=I('enddate');
        $data['isup']=I('isup');
        $data['sort']=I('sort');
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $add
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'添加失败',
                    'data' => ''
                );
            }
        }else{
            //修改商品信息操作
            $save=$Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $save
            );
        }
        $this->ajaxReturn($result);
    }
    //删除优惠券
    public function deleteCoupon(){
        $id=I('id');
        $Table=M('shop_coupon');
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
}