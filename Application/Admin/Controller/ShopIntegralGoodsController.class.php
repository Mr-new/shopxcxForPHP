<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopIntegralGoodsController extends BaseController {
    //获取积分商品列表
    public function getCommodityList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_integral_goods');
        $map['title']=array('like',"%$select_word%");
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $list=$images->getImagesList($list,'pic', 'b_image');
            foreach ($list as $k=>$v){
                if($list[$k]['isup']==1){
                    $list[$k]['isup']=true;
                }else{
                    $list[$k]['isup']=false;
                }

                $temp=$images->spliceIdGetImgList($list[$k],'imglist','swiperimgList');
                $tempArr=explode(",", $list[$k]['imglist']);
                foreach ($temp['swiperimgList'] as $key=>$value){
                    $list[$k]['swiperimgList'][$key]['id']=$tempArr[$key];
                    $list[$k]['swiperimgList'][$key]['url']=$value;
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
    //修改商品
    public function saveCommodity(){
        $Table=M('shop_integral_goods');
        $id=I('id')?I('id'):null;  //主键id
        $data['title']=I('title');
        $data['pic']=I('pic')?I('pic'):null;
        $data['imglist']=I('imglist');
        $data['integral']=I('integral');
        $data['salesvolume']=I('salesvolume');
        $data['isup']=I('isup');
        $data['details']=$_POST['details'];
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
            //修改商品信息操作
            $save=$Table->where("id=$id")->save($data);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $save
            );
        }
        $this->ajaxReturn($result);
    }
    //删除商品
    public function deleteCommodity(){
        $id=I('id');
        $Table=M('shop_integral_goods');
        $imgId=$Table->where("id=$id")->getField('pic');
        $images=A('Common/Images');
        if($imgId){
            $images->deleteImage($imgId);
        }
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
        $Table=M('shop_integral_goods');
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
}