<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class InCodeController extends BaseController {
    //获取内部码列表
    public function getInCodeList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $codeRecordTable=M('code_record');
        $map['title']=array('like',"%$select_word%");
        $list=$codeRecordTable->where($map)->order('startdatetime desc')->page($pageIndex.",$number")->select();
        for($i=0;$i<count($list);$i++){
            if($list[$i]['status']==1){
                $list[$i]['status']="未使用";
            }else if($list[$i]['status']==2){
                $list[$i]['status']="已使用";
            }
        }
        $count=$codeRecordTable->where($map)->count();// 查询满足要求的总记录数
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
    //修改内部码信息
    public function saveInCode(){
        $codeRecordTable=M('code_record');
        $id=I('id')?I('id'):null;  //主键id
        $title=I('title');
        $number=I('number');
        $InCodeData['title']=$title;
        $InCodeData['content']=$this->rand(6);
        $InCodeData['number']=$number;
        $InCodeData['status']=1;
        $InCodeData['startdatetime']=date('Y-m-d H:i:s',time());
        if(empty($id)){
            //添加
            $add=$codeRecordTable->add($InCodeData);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $add,
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'添加失败',
                    'data' => ''
                );
            }
        }else{
            //修改
            $data['title']=$title;
            $data['number']=$number;
            $data['status']=1;
            $data['startdatetime']=date('Y-m-d H:i:s',time());
            $save=$codeRecordTable->where("id=$id")->save($data);
            if($save){
                $result=array(
                    'success'=>true,
                    'msg'=>'修改成功',
                    'data' => $id
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'修改失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //删除内部码
    public function deleteInCode(){
        $id=I('id');
        $codeRecordTable=M('code_record');
        $delSql=$codeRecordTable->where("id=$id")->delete();
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
    //生成不重复字符串
    function rand($len=6){
        return substr(md5(microtime(true)), 0, $len);
    }
}