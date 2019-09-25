<?php
namespace Shop\Controller;
use Think\Controller;
class CaseController extends Controller {
    //获取日记菜单列表
    public function getCaseMenuList(){
        $caseMenuTable=M("shop_casemenu");
        $caseMenuList=$caseMenuTable->order("sort desc")->field("id,imgid,title")->select();
        if($caseMenuList){
            $images=A('Common/Images');
            $caseMenuList=$images->getImagesList($caseMenuList,'imgid', 'imgUrl');
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $caseMenuList
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
    //获取日记列表
    public function getCaseList(){
        $caseTable=M('shop_case');
        $casemenuid=I("casemenuid");  //类别id
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $searchValue=I("searchValue")?I("searchValue"):null;  //搜索内容
        $userid=I('userid');
        if(!empty($casemenuid)){
            $map['casemenuid']= $casemenuid;
        }
        $map['name']= array('like',"%$searchValue%");  //查找关键字
        $caseList=$caseTable->join("wechat_shop_casemenu ON wechat_shop_case.casemenuid = wechat_shop_casemenu.id")->where($map)->field("wechat_shop_case.id,wechat_shop_case.name,wechat_shop_casemenu.title,wechat_shop_case.project,wechat_shop_case.imglist,wechat_shop_case.looknumber,wechat_shop_case.fabulousnumber,wechat_shop_case.datetime")->order("wechat_shop_case.sort desc")->page($pageIndex.",$number")->select();
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
                //如果userid为空时则默认未点赞
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
    //根据日记id获取日记详情
    public function getCaseDetails(){
        $caseTable=M('shop_case');
        $caseId=I('id');
        $userid=I('userid');
        $order=I('order');
        $map['id']=$caseId;
        $caseDetails=$caseTable->where($map)->field("id,imglist,name,looknumber,fabulousnumber,shopids,datetime")->find();
        if($caseDetails){
            $caseTable->where($map)->setInc("looknumber");
            $caseDetails['looknumber']=$caseTable->where($map)->getField("looknumber");
            $images=A('Common/Images');
            //通过逗号分隔id从而查找图片列表
            $commodityDetails=$images->spliceIdGetImgList($caseDetails,'imglist', 'imglist');
            if(empty($userid)){
                $commodityDetails['isFabulous']=false;
                $commodityDetails['isCollection']=false;
            }else{
                $fabulousTable=M('shop_case_fabulous');
                $find=$fabulousTable->where("caseid='{$commodityDetails['id']}' and userid=$userid")->getField("id");
                if($find){
                    $commodityDetails['isFabulous']=true;
                }else {
                    $commodityDetails['isFabulous']=false;
                }
                //查看用户是否收藏此日记
                if(M('shop_case_collection')->where("caseid=$caseId and userid=$userid")->getField("id")){
                    $commodityDetails['isCollection']=true;
                }else{
                    $commodityDetails['isCollection']=false;
                }
            }
            //查找此日记下关联的商品
            $shopTable=M('shop_commodity');
            $specsTable=M('shop_commodity_specs');
            $tempArr=array();
            if(!empty($commodityDetails['shopids'])){
                $shopIdArr = explode(',',$commodityDetails['shopids']);
                foreach ($shopIdArr as $key => $value){
                    $temp=$shopTable->where("id='{$value}'")->field("id,name,thumbnail,beforeprice,salenumber")->find();  //查找商品
                    $temp=$images->getImagesList($temp,'thumbnail', 'thumbnailUrl');  //查找商品图片
                    unset($temp['thumbnail']);
                    //查找商品第一个规格
                    $temp['price']=$specsTable->where("cid='{$temp['id']}'")->getField("price");
                    array_push($tempArr,$temp);
                }
                $commodityDetails['linkGoods']=$tempArr;
            }
            $processTable=M('shop_case_process');
            $commodityDetails['processList']=$processTable->where("caseid='{$commodityDetails['id']}'")->order("day $order")->field("id,day,type,medialist,content")->select();
            //通过逗号分隔id从而查找图片列表
            $commodityDetails['processList']=$images->spliceIdGetImgList($commodityDetails['processList'],'medialist', 'medialist');
            //查看此用户是否授权过手机号码
            $caseRecordTable=M('shop_case_record');
            $find=$caseRecordTable->where("userid=$userid")->find();
            $find ? $telAuthor=true : $telAuthor=false;
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityDetails,
                'telAuthor' => $telAuthor
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
    //点赞
    public function addfabulous(){
        $data['userid']=I('userid');
        $data['caseid']=I('caseid');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $fabulousTable=M('shop_case_fabulous');
        $add=$fabulousTable->add($data);
        if($add){
            $caseTable=M("shop_case");
            $caseTable->where("id='{$data['caseid']}'")->setInc('fabulousnumber');
            $fabulousnumber=$caseTable->where("id='{$data['caseid']}'")->getField("fabulousnumber");
            $result=array(
                'success'=>true,
                'msg'=>'点赞成功',
                'data' => $fabulousnumber
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'点赞失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //取消点赞
    public function delfabulous(){
        $fabulousTable=M('shop_case_fabulous');
        $map['caseid']=I('caseid');
        $map['userid']=I('userid');
        $del=$fabulousTable->where($map)->delete();
        if($del){
            $caseTable=M("shop_case");
            $caseTable->where("id='{$map['caseid']}'")->setDec('fabulousnumber');
            $fabulousnumber=$caseTable->where("id='{$map['caseid']}'")->getField("fabulousnumber");
            $result=array(
                'success'=>true,
                'msg'=>'取消点赞成功',
                'data' => $fabulousnumber
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'取消点赞失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取此日记评论
    public function getCaseCommentList(){
        $caseid=I('caseid');
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_case_comment');
        $map['caseid']= $caseid;  //查找关键字
        $commentList=$Table->join("wechat_shop_user ON wechat_shop_case_comment.userid = wechat_shop_user.id")->where($map)->field("wechat_shop_case_comment.id,wechat_shop_case_comment.content,wechat_shop_case_comment.datetime,wechat_shop_user.nickName,wechat_shop_user.avatarUrl")->order("wechat_shop_case_comment.datetime desc")->page($pageIndex.",$number")->select();
        $count=$Table->join("wechat_shop_user ON wechat_shop_case_comment.userid = wechat_shop_user.id")->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($commentList){
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $commentList,
                    'count' => $count
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
    //添加评论操作
    public function addComment(){
        $data['userid']=I('userid');
        $data['caseid']=I('caseid');
        $data['content']=I('content');
        $data['datetime']=date('Y-m-d H:i:s',time());
        $Table=M('shop_case_comment');
        try {
            $Table->add($data);
            $result=array(
                'success'=>true,
                'msg'=>'评论成功',
                'data' => ''
            );
        } catch (Exception $e) {
            $result=array(
                'success'=>false,
                'msg'=>'评论失败',
                'data' => $e->getMessage()
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
        $session_key = S($userId);  //从缓存中取出session_key
        Vendor('weChat.wxBizDataCrypt');
        $pc = new \WXBizDataCrypt($APPID, $session_key);
        $pc->decryptData($encryptedData, $iv, $data);
        $data = json_decode($data);
        if($data){
            $caseRecordTable=M('shop_case_record');
            $find=$caseRecordTable->where("tel=$data->phoneNumber")->find();
            if(!$find) {
                $dataArr['userid']=$userId;
                $dataArr['caseid']=$_POST['caseid'];
                $dataArr['tel']=$data->phoneNumber;
                $dataArr['datetime']=date('Y-m-d H:i:s',time());
                $caseRecordTable->add($dataArr);
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