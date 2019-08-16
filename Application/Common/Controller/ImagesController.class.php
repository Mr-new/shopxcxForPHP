<?php
namespace Common\Controller;
use Think\Controller;
class ImagesController extends Controller {
    public function index(){
        echo 1;
    }
    //上传图片
    public function upload(){
        $id=I('id')?I('id'):null;  //图片id
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/uploadImages/'; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['image']);
        if(!$info) {// 上传错误提示错误信息
            $result=array(
                'success'=>false,
                'msg'=>'上传失败,请重新上传图片',
                'data' => ''
            );
        }else{// 上传成功 获取上传文件信息
            //压缩图片
            $data['image']=$info['savepath'].$info['savename'];
            $image = new \Think\Image();
            $image->open("./Public/uploadImages/{$data['image']}");
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
            $image->thumb(1920, 1920)->save("./Public/uploadImages/{$data['image']}");//直接把缩略图覆盖原图
            $images=M('images');
            $data['datetime']=date('Y-m-d H:i:s',time());
            //如果id不为null则是更改图片，如果id为null则是上传图片
            $beforeImg=$images->where("id=$id")->getField('image');
            if($beforeImg){
                unlink('./Public/uploadImages/'.$beforeImg);  //删除原来的图片
                $change=$images->where("id=$id")->save($data);  //替换数据库图片路径
                if($change){
                    $result=array(
                        'success'=>true,
                        'msg'=>'上传成功',
                        'data' => $id
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'替换失败，请重新上传图片',
                        'data' => ''
                    );
                }
            }else{
                $add=$images->add($data);
                if($add){
                    $result=array(
                        'success'=>true,
                        'msg'=>'上传成功',
                        'data' => $add,
                        'id' => ''
                    );
                }else{
                    $result=array(
                        'success'=>false,
                        'msg'=>'数据库写入错误，请稍后重试',
                        'data' => ''
                    );
                }
            }
        }
        $this->ajaxReturn($result);
    }
    //上传富文本编辑器图片
    public function uploadEditorImage(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/uploadImages/'; // 设置附件上传根目录
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['image']);
        if(!$info) {// 上传错误提示错误信息
            $result=array(
                'success'=>false,
                'msg'=>'上传失败,请重新上传图片',
                'data' => ''
            );
        }else{// 上传成功 获取上传文件信息
            //压缩图片
            $data['image']=$info['savepath'].$info['savename'];
            $image = new \Think\Image();
            $image->open("./Public/uploadImages/{$data['image']}");
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
            $image->thumb(5000, 5000)->save("./Public/uploadImages/{$data['image']}");//直接把缩略图覆盖原图
            $url=C('imgurl');
            $imgUrl=$url.$data['image'];
            $result=array(
                'success'=>true,
                'msg'=>'上传成功',
                'data' => $imgUrl
            );
        }
        $this->ajaxReturn($result);
    }
    //获取图片列表:$list->数据列表，$name->图片id字段名称，$newName->存放图片地址的字段名称
    public function getImagesList($list,$name,$newName){
        $images=M('images');
        $imgurl=C('imgurl');
        if (count($list) == count($list, 1)) {
            //一维数组处理
            if($list[$name]){
                $list[$newName]=$images->where("id={$list[$name]}")->getField('image');  //获取图片名称
                $list[$newName]=$imgurl.$list[$newName];  //拼接图片url
            }else{
                $list[$newName]="";
            }

        } else {
            //二维数组处理
            for($i=0;$i<count($list);$i++){
                if($list[$i][$name]){
                    $list[$i][$newName]=$images->where("id={$list[$i][$name]}")->getField('image');  //获取图片名称
                    $list[$i][$newName]=$imgurl.$list[$i][$newName];  //拼接图片url
                }else{
                    $list[$i][$newName]="";
                }

            }
        }
        return $list;
    }
    //以逗号分割id查找图片列表:$list->数据列表，$name->图片id字段名称，$newName->存放图片地址的字段名称
    public function spliceIdGetImgList($list,$name,$newName){
        $imagesTable=M('images');
        $imgurl=C('imgurl');
        if (count($list) == count($list, 1)) {
            //一维数组处理
            if($list[$name]){
                $tempArr=array();
                $imgIdArr = explode(',',$list[$name]);
                foreach ($imgIdArr as $k => $v){
                    $temp=$imagesTable->where("id='{$v}'")->getField("image");
                    array_push($tempArr,$imgurl.$temp);
                }
                $list[$newName]=$tempArr;
            }else{
                $list[$newName]="";
            }

        }else{
            //二维数组处理
            foreach ($list as $k=>$v){
                if($list[$k][$name]){
                    $tempArr=array();
                    $imgIdArr = explode(',',$list[$k][$name]);
                    foreach ($imgIdArr as $key => $value){
                        $temp=$imagesTable->where("id='{$value}'")->getField("image");
                        array_push($tempArr,$imgurl.$temp);
                    }
                    $list[$k][$newName]=$tempArr;
                }else{
                    $list[$k][$newName]="";
                }
            }
        }
        return $list;
    }
    //删除图片操作:$imgId->图片id
    public function deleteImage($imgId){
        $images=M('images');
        if(!empty($imgId)){
            $imgName=$images->where("id=$imgId")->getField('image');
            if($imgName){
                unlink('./Public/uploadImages/'.$imgName);  //删除图片文件
            }
            $delImage=$images->where("id=$imgId")->delete();
            if($delImage){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}