<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class TestController extends BaseController {
    //递归查找子级菜单
    private function getMenuSub($data, $id=0) {
        $list = array();
        foreach($data as $v) {
            if($v['pid'] == $id) {
                $v['sub'] = $this->getMenuSub($data, $v['id']);
                if(empty($v['sub'])) {
                    unset($v['sub']);
                }
                array_push($list, $v);
            }
        }
        return $list;
    }
    public function test(){
        $menuTable=M('admin_menu');
        $list=$menuTable->order("id asc")->select();
        $slist=$this->getMenuSub($list);
        $result=array(
            'success' => true,
            'msg' => "请求成功",
            'data' => $slist
        );
        $this->ajaxReturn($result);
    }
    //获取角色列表
    public function index(){
        //拼装奖项数组
        // 奖项id，奖品，概率
        $prizeTable=M('prize');
        $prize_arr=$prizeTable->order("id asc")->select();
//        $prize_arr = array(
//            '0' => array('id'=>1,'prize'=>'平板电脑','v'=>0),
//            '1' => array('id'=>2,'prize'=>'数码相机','v'=>0),
//            '2' => array('id'=>3,'prize'=>'音箱设备','v'=>0),
//            '3' => array('id'=>4,'prize'=>'4G优盘','v'=>5),
//            '4' => array('id'=>5,'prize'=>'10Q币','v'=>0),
//            '5' => array('id'=>6,'prize'=>'空奖','v'=>5),
//        );
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }

        $rid = $this->get_rand($arr); //根据概率获取奖项id

        $res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项
        unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项
        shuffle($prize_arr); //打乱数组顺序
        for($i=0;$i<count($prize_arr);$i++){
            $pr[] = $prize_arr[$i]['prize'];
        }
        $res['no'] = $pr;
        echo '<pre>';
        print_r($res);
    }
    function get_rand($proArr) {
        $result = '';

        //概率数组的总概率精度
        $proSum = array_sum($proArr);

        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);

        return $result;
    }
    //修改角色信息
    public function saveAdminRole(){
        $adminRoleTable=M('admin_role');
        $id=I('id')?I('id'):null;  //主键id
        $title=I('title');
        $roleData['title']=$title;
        $roleData['datetime']=date('Y-m-d H:i:s',time());
        if(empty($id)){
            //添加
            $add=$adminRoleTable->add($roleData);
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
            $save=$adminRoleTable->where("id=$id")->save($roleData);
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
    //删除角色
    public function deleteAdminRole(){
        $id=I('id');
        $adminRoleTable=M('admin_role');
        $delSql=$adminRoleTable->where("id=$id")->delete();
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