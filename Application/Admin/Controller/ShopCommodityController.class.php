<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class ShopCommodityController extends BaseController {
    //获取商品分类列表
    public function getCategoryList(){
        $Table=M('shop_commodity_category');
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
    //获取商品列表
    public function getCommodityList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $Table=M('shop_commodity');
        $map['name']=array('like',"%$select_word%");
        $map['title']=array('like',"%$select_word%");
        $map['_logic']='OR';
        $list=$Table->where($map)->order('sort desc')->page($pageIndex.",$number")->select();
        $count=$Table->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=A('Common/Images');
            $list=$images->getImagesList($list,'thumbnail', 'b_image');
            $categoryTable=M('shop_commodity_category');
            $specsTable=M('shop_commodity_specs');
            foreach ($list as $k=>$v){
                $list[$k]['category']=$categoryTable->where("id='{$list[$k]['categoryid']}'")->getField("title");
                $list[$k]['isup']==1 ? $list[$k]['isup']=true : $list[$k]['isup']=false;  //是否上架
                $list[$k]['hot']==1 ? $list[$k]['hot']=true : $list[$k]['hot']=false;  //是否热门商品
                $list[$k]['isrecomd']==1 ? $list[$k]['isrecomd']=true : $list[$k]['isrecomd']=false;  //是否是商家推荐
                $list[$k]['isseckill']==1 ? $list[$k]['isseckill']=true : $list[$k]['isseckill']=false;  //是否是秒杀商品
                $temp=$images->spliceIdGetImgList($list[$k],'swiperimg','swiperimgList');
                $tempArr=explode(",", $list[$k]['swiperimg']);
                foreach ($temp['swiperimgList'] as $key=>$value){
                    $list[$k]['swiperimgList'][$key]['id']=$tempArr[$key];
                    $list[$k]['swiperimgList'][$key]['url']=$value;
                }
//                $list[$k]['temp']=$temp;
                $list[$k]['specs']=$specsTable->where("cid='{$list[$k]['id']}'")->field("id,title,price")->order("price desc")->select();
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
        $Table=M('shop_commodity');
        $id=I('id')?I('id'):null;  //主键id
        $data['name']=I('name');
        $data['title']=I('title');
        $data['thumbnail']=I('thumbnail')?I('thumbnail'):null;
        $data['swiperimg']=I('swiperimg');
        $data['beforeprice']=I('beforeprice');
        $data['salenumber']=I('salenumber');
        $data['categoryid']=I('categoryid');
        $data['isup']=I('isup');
        $data['hot']=I('hot');
        $data['isseckill']=I('isseckill');
        $data['seckillprice']=I('seckillprice')?I('seckillprice'):null;
        $data['startdatetime']=I('startdatetime')?I('startdatetime'):null;
        $data['enddatetime']=I('enddatetime')?I('enddatetime'):null;
        $data['isrecomd']=I('isrecomd');
        $data['details']=$_POST['details'];
        $data['sort']=I('sort')?I('sort'):0;
        $specs=json_decode($_POST['specs']);
        $specsTable=M('shop_commodity_specs');
        if(empty($id)){
            $data['datetime']=date('Y-m-d H:i:s',time());
            $add=$Table->add($data);
            if($add){
                $specsData=array();
                foreach ($specs as $k=>$v){
                    array_push($specsData,array('title'=>$specs[$k]->title, 'cid'=>$add, 'price'=>$specs[$k]->price,'datetime'=>date('Y-m-d H:i:s',time())));
                }
                $specsTable->addAll($specsData);
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $specsData
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
            $Table->where("id=$id")->save($data);
            //修改规格：先将该商品所有规格删除，然后重新添加规格
            $specsId=$specsTable->where("cid=$id")->getField("id",true);
            $specsId=implode(',',$specsId);
            if($specsId){
                $specsTable->delete($specsId);
            }
            $specsData=array();
            foreach ($specs as $k=>$v){
                array_push($specsData,array('title'=>$specs[$k]->title, 'cid'=>$id, 'price'=>$specs[$k]->price,'datetime'=>date('Y-m-d H:i:s',time())));
            }
            $specsTable->addAll($specsData);
            $result=array(
                'success'=>true,
                'msg'=>'修改成功',
                'data' => $specsId
            );
        }
        $this->ajaxReturn($result);
    }
    //删除商品
    public function deleteCommodity(){
        $id=I('id');
        $Table=M('shop_commodity');
        $imgId=$Table->where("id=$id")->getField('thumbnail');
        $images=A('Common/Images');
        if($imgId){
            $images->deleteImage($imgId);
        }
        $swiperImgId=$Table->where("id=$id")->getField("swiperimg");
        if($swiperImgId){
            $swiperImgId=explode(",", $swiperImgId);
            foreach ($swiperImgId as $k=>$v){
                $images->deleteImage($v);
            }
        }
        $delSql=$Table->where("id=$id")->delete();
        if($delSql){
            $specsTable=M('shop_commodity_specs');
            $specsTable->where("cid=$id")->delete();
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
        $Table=M('shop_commodity');
        $images=M('images');
        if(!empty($imgId)){
            if(!empty($id)){
                $imgStr=$Table->where("id=$id")->getField("swiperimg");
                $imgArr=explode(",", $imgStr);
                foreach ($imgArr as $k=>$v){
                    if($imgId==$v){
                        unset($imgArr[$k]);
                    }
                }
                $saveData['swiperimg']=implode(",", $imgArr);
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