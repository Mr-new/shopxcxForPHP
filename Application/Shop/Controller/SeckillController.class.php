<?php
namespace Shop\Controller;
use Think\Controller;
class SeckillController extends Controller {
    //获取秒杀商品列表
    public function getSeckillCommodityList(){
        $commodityTable=M('shop_commodity');
        $currentDate=date('Y-m-d H:i:s',time());  //当前日期时间
        $status=I('status');
        if($status==2){
            //查找未开始的秒杀商品：当前时间>开始时间
            $map['startdatetime'] = array('GT', $currentDate);
        }else{
            //查找已开始的秒杀商品：当前时间<开始时间
            $map['startdatetime'] = array('ELT', $currentDate);
        }
        $map['isseckill'] = 1;
        $map['isup'] = 1;
        $map['enddatetime'] = array('EGT', $currentDate);
        $map['_logic'] = 'AND';
        $commodityList=$commodityTable->where($map)->field("id,name,thumbnail,beforeprice,seckillprice,startdatetime,enddatetime")->order("sort desc")->select();
        if($commodityList){
            $images=A('Common/Images');
            $commodityList=$images->getImagesList($commodityList,'thumbnail', 'thumbnailUrl');
            foreach ($commodityList as $k=>$v){
                $commodityList[$k]['seckillprice']=(int)$commodityList[$k]['seckillprice'];
                $commodityList[$k]['beforeprice']=(int)$commodityList[$k]['beforeprice'];
            }
            $result=array(
                'success'=>true,
                'msg'=>'查找成功',
                'data' => $commodityList
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

}