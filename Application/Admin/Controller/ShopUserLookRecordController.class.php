<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class ShopUserLookRecordController extends BaseController {
    //获取用户浏览记录列表
    public function getUserLookRecordList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_case_record');
        $map['nickname']=array('like',"%$select_word%");
        $map['wechat_shop_case_record.tel']=array('like',"%$select_word%");
        $map['name']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->join("wechat_shop_user ON wechat_shop_case_record.userid=wechat_shop_user.id")->join("wechat_shop_case ON wechat_shop_case_record.caseid=wechat_shop_case.id")->where($map)->field("wechat_shop_case_record.id,wechat_shop_case_record.tel,wechat_shop_case_record.datetime,wechat_shop_user.nickname,wechat_shop_user.gender,wechat_shop_user.city,wechat_shop_user.province,wechat_shop_user.avatarUrl,wechat_shop_case.name")->order('wechat_shop_case_record.datetime desc')->page($pageIndex.",$number")->select();
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