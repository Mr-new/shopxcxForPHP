<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class ShopIntegralOrderController extends BaseController {
    //获取订单列表
    public function getOrderList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_integral_order');
        $map['ordernumber']=array('like',"%$select_word%");
        $map['wechat_shop_integral_order.tel']=array('like',"%$select_word%");
        $map['remarks']=array('like',"%$select_word%");
        $map['nickName']=array('like',"%$select_word%");
        $map['sumintegral']=array('like',"%$select_word%");
        $map['_logic']='OR';

        $list=$Table->join("wechat_shop_user ON wechat_shop_integral_order.userid = wechat_shop_user.id")->where($map)->field("wechat_shop_user.nickName,wechat_shop_integral_order.id,wechat_shop_integral_order.ordernumber,wechat_shop_integral_order.userid,wechat_shop_integral_order.integralgoodsid,wechat_shop_integral_order.tel,wechat_shop_integral_order.remarks,wechat_shop_integral_order.sumintegral,wechat_shop_integral_order.datetime")->order('wechat_shop_integral_order.datetime desc')->page($pageIndex.",$number")->select();
        $count=$Table->join("wechat_shop_user ON wechat_shop_integral_order.userid = wechat_shop_user.id")->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $commodityTable=M('shop_integral_goods');
            foreach ($list as $k=>$v){
                //查找相关商品
                $list[$k]['goods'][0]=$commodityTable->where("id='{$list[$k]['integralgoodsid']}'")->field("id,title,pic")->find();
                $list[$k]['goods'][0]['sumintegral']=$list[$k]['sumintegral'];
                //拼接图片路径
                $list[$k]['goods']=$images->getImagesList($list[$k]['goods'],'pic', 'thumbnail');
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
}