<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class ShopOnlineController extends BaseController {
    //获取预约信息列表
    public function getOnlineList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_online');
        $map['name']=array('like',"%$select_word%");
        $map['sex']=array('like',"%$select_word%");
        $map['typename']=array('like',"%$select_word%");
        $map['content']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->where($map)->order('datetime desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
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
}