<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopAskController extends BaseController {
    //获取问答列表
    public function getDoctorList(){
        $select_word=I('select_word'); //搜索关键词saveAsk
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_ask_answer');
        $map['ask']=array('like',"%$select_word%");
        $map['answer']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $caseMenuTable=M('shop_casemenu');
            foreach ($list as $k=>$v){
                $list[$k]['casemenu']=$caseMenuTable->where("id='{$list[$k]['casemenuid']}'")->getField("title");
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
    //修改问答信息
    public function saveAsk(){
        $Table=M('shop_ask_answer');
        $id=I('id')?I('id'):null;  //主键id
        $data['casemenuid']=I('casemenuid');
        $data['ask']=I('ask');
        $data['answer']=I('answer');
        $data['looknum']=I('looknum');
        $data['sort']=I('sort')?I('sort'):0;
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
            //修改问答操作
            $save=$Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $save
            );
        }
        $this->ajaxReturn($result);
    }
    //删除问答
    public function deleteAsk(){
        $id=I('id');
        $Table=M('shop_ask_answer');
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