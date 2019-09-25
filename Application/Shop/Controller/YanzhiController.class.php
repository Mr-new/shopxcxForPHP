<?php
namespace Shop\Controller;
use Think\Controller;
class YanzhiController extends Controller {
    //获取日记列表
    public function getCaseList(){
        $caseTable=M('shop_case');
        $typename=I("typename");  //类别id
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $searchValue=I("searchValue")?I("searchValue"):null;  //搜索内容
        $userid=I('userid');
        $map['name']= array('like',"%$searchValue%");  //查找关键字
        if($typename=="yanbu"){
            $map['casemenuid']=1;
            $order="wechat_shop_case.sort desc";
        }else if($typename=="tuijian"){
            $map['ishome']=1;
            $order="wechat_shop_case.sort desc";
        }else if($typename=="kandian"){
            $order="wechat_shop_case.sort asc";
        }else{
            $order="wechat_shop_case.sort desc";
        }
        $caseList=$caseTable->join("wechat_shop_casemenu ON wechat_shop_case.casemenuid = wechat_shop_casemenu.id")->where($map)->field("wechat_shop_case.id,wechat_shop_case.name,wechat_shop_casemenu.title,wechat_shop_case.project,wechat_shop_case.imglist,wechat_shop_case.looknumber,wechat_shop_case.fabulousnumber,wechat_shop_case.datetime")->order($order)->page($pageIndex.",$number")->select();
        $count=$caseTable->join("wechat_shop_casemenu ON wechat_shop_case.casemenuid = wechat_shop_casemenu.id")->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        //检测用户是否授权手机号码
        $userTable=M('shop_user');
        $findTel=$userTable->where("id=$userid")->getField("tel");
        if($findTel){
            $telAuthor=true;
        }else{
            $telAuthor=false;
        }
        if($caseList){
            $images=A('Common/Images');
            $fabulousTable=M('shop_case_fabulous');
            $processTable=M('shop_case_process');
            foreach ($caseList as $k=>$v){
                //查询最后一次添加的变美记录
                $processList=$processTable->where("caseid='{$caseList[$k]['id']}' and type=1")->order("day desc")->select();
                $temp=$images->spliceIdGetImgList($processList[0],'medialist', 'medialist');
                $caseList[$k]['afterImgUrl']=$temp['medialist'][0];
                $caseList[$k]['title']=$temp['content'];
                if(empty($userid)){
                    $caseList[$k]['isFabulous']=false;
                }else{
                    $find=$fabulousTable->where("caseid='{$caseList[$k]['id']}' and userid=$userid")->getField("id");
                    if($find){
                        $caseList[$k]['isFabulous']=true;
                    }else {
                        $caseList[$k]['isFabulous']=false;
                    }
                }

            }
            //通过逗号分隔id从而查找图片列表
            $caseList=$images->spliceIdGetImgList($caseList,'imglist', 'imglist');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'isTelAuthor' => $telAuthor,
                    'list' => $caseList
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到所需数据',
                'isTelAuthor' => $telAuthor,
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取问答列表数据
    public function getAskAnswerList(){
        $Table=M('shop_ask_answer');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $searchValue=I("searchValue")?I("searchValue"):null;  //搜索内容
        $map['ask']= array('like',"%$searchValue%");  //查找关键字
        $map['answer']= array('like',"%$searchValue%");  //查找关键字
        $map['_logic'] = 'OR';
        $list=$Table->where($map)->order("datetime desc")->page($pageIndex.",$number")->select();
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
    //获取问答详情数据
    public function getAskDetails(){
        $Table=M('shop_ask_answer');
        $id=I('id');
        $map['id']=$id;
        $find=$Table->where($map)->find();
        if($find){
            $Table->where($map)->setInc("looknum");
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $find
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
    //获取用户手机号
    public function getUserTel(){
        $APPID = C('APPID');
        $encryptedData = $_POST['encryptedData'];
        $iv = $_POST['iv'];
        $userId=$_POST['userId'];
        $consultationid=$_POST['consultationid'];  //咨询id
        $session_key = S($userId);  //从缓存中取出session_key
        Vendor('weChat.wxBizDataCrypt');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $pc->decryptData($encryptedData, $iv, $data);
        $data = json_decode($data);
        if($data){
            //往用户表记录用户手机号码
            $userTable=M('shop_user');
            $findTel=$userTable->where("id=$userId")->getField("tel");
            if(!$findTel){
                //当用户tel不为空时更新用户tel
                $savaData['tel']=$data->phoneNumber;
                $savaData['countryCode']=$data->countryCode;
                $userTable->where("id=$userId")->save($savaData);
            }
            //记录用户与咨询关系
            if($consultationid){
                $fissionTable=M('shop_fission');
                $find=$fissionTable->where("userid=$userId")->find();
                if(!$find) {
                    $dataArr['userid']=$userId;
                    $dataArr['cid']=$consultationid;
                    $dataArr['datetime']=date('Y-m-d H:i:s',time());
                    $fissionTable->add($dataArr);
                }
            }
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $data
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'获取手机号码失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}