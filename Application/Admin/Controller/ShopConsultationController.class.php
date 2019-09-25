<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopConsultationController extends BaseController {
    //获取咨询用户列表
    public function getConsultationList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_consultation');
        $map['name']=array('like',"%$select_word%");
        $list=$Table->where($map)->order('datetime desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $fissionTable=M('shop_fission');
            $userTable=M('shop_user');
            foreach ($list as $k=>$v){
                $list[$k]['userList']=$fissionTable->where("cid='{$list[$k]['id']}'")->field("userid,datetime")->order("datetime desc")->select();
                foreach ($list[$k]['userList'] as $key=>$val){
                    $temp=$userTable->where("id='{$list[$k]['userList'][$key]['userid']}'")->field("nickname,gender,tel,province,city,avatarurl")->find();
                    $list[$k]['userList'][$key]['nickname']=$temp['nickname'];
                    $list[$k]['userList'][$key]['gender']=$temp['gender'];
                    $list[$k]['userList'][$key]['tel']=$temp['tel'];
                    $list[$k]['userList'][$key]['province']=$temp['province'];
                    $list[$k]['userList'][$key]['city']=$temp['city'];
                    $list[$k]['userList'][$key]['avatarurl']=$temp['avatarurl'];
                }
            }
            $images=A('Common/Images');
            $list=$images->getImagesList($list,'imgid', 'imgurl');
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
    //添加咨询或修改咨询
    public function saveConsultation(){
        $Table=M('shop_consultation');
        $id=I('id')?I('id'):null;  //主键id
        $data['name']=I('name');
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                $APPID = C('APPID');
                $APPSECRET =  C('AppSecret');
                //获取access_token
                $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
                $tokenResult=json_decode($this->httpRequest($access_token));
                $ACCESS_TOKEN=$tokenResult->access_token;
                $qcode ="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$ACCESS_TOKEN";
                $param = json_encode(array("scene"=>$add,"page"=>"pages/case/case","width"=> 150));
                //POST参数
                $resultPost = $this->httpRequest( $qcode, $param,"POST");
                //生成二维码
                $res=file_put_contents("Public/uploadImages/cqecode/$add.png", $resultPost);
                if($res){
                    $imageTable=M('images');
                    $imageData['image']='cqecode/'.$add.'.png';
                    $imageData['datetime']=date('Y-m-d H:i:s',time());
                    $imageAdd=$imageTable->add($imageData);
                    $saveData['imgid']=$imageAdd;
                    $Table->where("id=$add")->save($saveData);
                }
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
            $save=$Table->where("id=$id")->save($data);
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
    //删除咨询
    public function deleteConsultation(){
        $id=I('id');
        $Table=M('shop_consultation');
        $imgId=$Table->where("id=$id")->getField('imgid');
        if($imgId){
            $images=A('Common/Images');
            $images->deleteImage($imgId);
        }
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
    //把请求发送到微信服务器换取二维码
    public function httpRequest($url, $data='', $method='GET'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}