<?php
namespace Shop\Controller;
use Think\Controller;
class AskAnswerController extends Controller {
    //获取问答列表数据
    public function getAskAnswerList(){
        $Table=M('shop_ask_answer');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $casemenuid=I("casemenuid");  //类别id
        $searchValue=I("searchValue")?I("searchValue"):null;  //搜索内容
        $map['ask']= array('like',"%$searchValue%");  //查找关键字
        if($casemenuid!=0){
            $map['casemenuid']= $casemenuid;
        }
        $list=$Table->where($map)->order("sort desc")->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $list
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
}