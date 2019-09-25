<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopCaseController extends BaseController {
    //获取日记分类列表
    public function getCaseMenuList(){
        $Table=M('shop_casemenu');
        $list=$Table->field("id,title")->order("sort desc")->select();
        if($list){
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => $list
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'请求失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //获取日记列表
    public function getCaseList(){
        $imagesTable=M('images');
        $imgurl=C('imgurl');
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_case');
        $map['name']=array('like',"%$select_word%");
        $map['remarks']=array('like',"%$select_word%");
        $map['doctor']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $menuTable=M('shop_casemenu');
            $shopTable=M('shop_commodity');
            $processTable=M('shop_case_process');
            foreach ($list as $k=>$v){
                $list[$k]['casemenu']=$menuTable->where("id='{$list[$k]['casemenuid']}'")->getField("title");
                if($list[$k]['ishome']==1){
                    $list[$k]['ishome']=true;
                }else{
                    $list[$k]['ishome']=false;
                }
                $temp=$images->spliceIdGetImgList($list[$k],'imglist','imglistUrl');
                $tempArr=explode(",", $list[$k]['imglist']);
                foreach ($temp['imglistUrl'] as $key=>$value){
                    $list[$k]['imglistUrl'][$key]['id']=$tempArr[$key];
                    $list[$k]['imglistUrl'][$key]['url']=$value;
                }
                if(!empty($list[$k]['shopids'])){
                    $tempArr=array();
                    $goodsIdArr = explode(',',$list[$k]['shopids']);
                    foreach ($goodsIdArr as $key => $value){
                        $temp=$shopTable->where("id='{$value}'")->field("id,name")->find();
                        array_push($tempArr,$temp);
                    }
                    $list[$k]['goodList']=$tempArr;
                }else{
                    $list[$k]['goodList']="";
                }
                //查找变美过程列表
                $list[$k]['processList']=$processTable->where("caseid='{$list[$k]['id']}'")->field("id,day,type,medialist,content,datetime")->order("datetime asc")->select();
                //查找变美过程图片路径
                foreach ($list[$k]['processList'] as $kk=>$vv){
                    if($list[$k]['processList'][$kk]['type']==2){
                        $list[$k]['processList'][$kk]['Video']=$imgurl.$imagesTable->where("id='{$list[$k]['processList'][$kk]['medialist']}'")->getField("image");
                        $list[$k]['processList'][$kk]['VideoId']=$list[$k]['processList'][$kk]['medialist'];
                        $list[$k]['processList'][$kk]['medialist'] ? $list[$k]['processList'][$kk]['medialist'] = explode(",",$list[$k]['processList'][$kk]['medialist']) : $list[$k]['processList'][$kk]['medialist'] = array();
                    }else{
                        $list[$k]['processList'][$kk]=$images->spliceVueIdGetImgList( $list[$k]['processList'][$kk],'medialist','mediaListUrl');
                        $list[$k]['processList'][$kk]['Video']="";
                        $list[$k]['processList'][$kk]['VideoId']=null;
                        $list[$k]['processList'][$kk]['medialist'] ? $list[$k]['processList'][$kk]['medialist'] = explode(",",$list[$k]['processList'][$kk]['medialist']) : $list[$k]['processList'][$kk]['medialist'] = array();
                    }
                }
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
    //添加或修改日记
    public function saveCase(){
        $Table=M('shop_case');
        $id=I('id')?I('id'):null;  //主键id
        $processids=I('processids') ? I('processids') : null;
        $data['name']=I('name');
        $data['project']=I('project');
        $data['imglist']=I('imglist');
        $data['casemenuid']=I('casemenuid');
        $data['looknumber']=I('looknumber');
        $data['fabulousnumber']=I('fabulousnumber');
        $data['shopids']=I('shopids');
        $data['sort']=I('sort')?I('sort'):0;
        $data['ishome']=I('ishome');
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                $processTable=M('shop_case_process');
                if(!empty($processids)){
                    $tempArr=explode(",", $processids);
                    foreach ($tempArr as $k=>$v){
                        $processTable->where("id='{$tempArr[$k]}'")->setField('caseid',$add);
                    }
                }
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
            //修改日记信息操作
            $save=$Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $save
            );
        }
        $this->ajaxReturn($result);
    }
    //新增或修改变美过程
    public function addOrSaveProcess(){
        $Table=M('shop_case_process');
        $id=I('id')?I('id'):null;  //主键id
        $data['caseid']=I('caseid') ? I('caseid') : null;
        $data['day']=I('day');
        $data['type']=I('type');
        $data['medialist']=I('medialist')?I('medialist'):null;
        $data['content']=I('content');
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'保存成功',
                    'data' => $add
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'保存失败',
                    'data' => ''
                );
            }
        }else{
            //修改日记信息操作
            $Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $id
            );
        }
        $this->ajaxReturn($result);
    }
    //删除变美过程
    public function delProcess(){
        $id=I('id');
        $Table=M('shop_case_process');
        $images=A('Common/Images');
        $swiperImgId=$Table->where("id=$id")->getField("medialist");
        if($swiperImgId){
            $swiperImgId=explode(",", $swiperImgId);
            foreach ($swiperImgId as $k=>$v){
                $images->deleteImage($v);
            }
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
    //删除日记
    public function deleteCase(){
        $id=I('id');
        $Table=M('shop_case');
        $images=A('Common/Images');
        $swiperImgId=$Table->where("id=$id")->getField("imglist");
        if($swiperImgId){
            $swiperImgId=explode(",", $swiperImgId);
            foreach ($swiperImgId as $k=>$v){
                $images->deleteImage($v);
            }
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
    //删除图片：vue删除图片
    public function delImage(){
        $imgId=I('imgId');
        $id=I('id');
        $Table=M('shop_case');
        $images=M('images');
        if(!empty($imgId)){
            if(!empty($id)){
                $imgStr=$Table->where("id=$id")->getField("imglist");
                $imgArr=explode(",", $imgStr);
                foreach ($imgArr as $k=>$v){
                    if($imgId==$v){
                        unset($imgArr[$k]);
                    }
                }
                $saveData['imglist']=implode(",", $imgArr);
                $Table->where("id=$id")->save($saveData);
            }
            $imgName=$images->where("id=$imgId")->getField('image');
            if($imgName){
                unlink('./Public/uploadImages/'.$imgName);  //删除图片文件
            }
            $delImage=$images->where("id=$imgId")->delete();
            if($delImage){
                $result=array(
                    'success'=>true,
                    'msg'=>'删除成功',
                    'data' => $delImage
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'删除失败',
                    'data' => ''
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //删除图片：vue删除变美过程图片
    public function delProcessImage(){
        $imgId=I('imgId');
        $id=I('id');
        $Table=M('shop_case_process');
        $images=M('images');
        if(!empty($imgId)){
            if(!empty($id)){
                $imgStr=$Table->where("id=$id")->getField("medialist");
                $imgArr=explode(",", $imgStr);
                foreach ($imgArr as $k=>$v){
                    if($imgId==$v){
                        unset($imgArr[$k]);
                    }
                }
                $saveData['medialist']=implode(",", $imgArr);
                $Table->where("id=$id")->save($saveData);
            }
            $imgName=$images->where("id=$imgId")->getField('image');
            if($imgName){
                unlink('./Public/uploadImages/'.$imgName);  //删除图片文件
            }
            $delImage=$images->where("id=$imgId")->delete();
            if($delImage){
                $result=array(
                    'success'=>true,
                    'msg'=>'删除成功',
                    'data' => $delImage
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'删除失败',
                    'data' => ''
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}