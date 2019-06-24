<?php
namespace Shop\Controller;
use Think\Controller;
class PayController extends Controller {
    //微信支付
    public function pay($openId, $shopName, $orderNumber, $sumPrice){
        $fee = $sumPrice;//举例支付0.01
        $appid = C('APPID');//appid.如果是公众号 就是公众号的appid
        $body = $shopName;
        $mch_id = '1509441511';//商户号
        $nonce_str = $this->nonce_str();//随机字符串
        $notify_url = 'https://xaxcx.17mall.cc/index.php/Shop/Pay/notify'; //回调的url【自己填写】
        $openid = $openId;
        $out_trade_no = $orderNumber;//商户订单号
        $spbill_create_ip = '127.0.0.1';//服务器的ip【自己填写】;
        $total_fee = $fee*100;// 微信支付单位是分，所以这里需要*100
        $trade_type = 'JSAPI';//交易类型 默认
        //这里是按照顺序的 因为下面的签名是按照顺序 排序错误 肯定出错
        $post['appid'] = $appid;
        $post['body'] = $body;
        $post['mch_id'] = $mch_id;
        $post['nonce_str'] = $nonce_str;//随机字符串
        $post['notify_url'] = $notify_url;
        $post['openid'] = $openid;
        $post['out_trade_no'] = $out_trade_no;
        $post['spbill_create_ip'] = $spbill_create_ip;//终端的ip
        $post['total_fee'] = $total_fee;//总金额 
        $post['trade_type'] = $trade_type;
        $sign = $this->sign($post);//签名
        $post_xml = '<xml>
           <appid>'.$appid.'</appid>
           <body>'.$body.'</body>
           <mch_id>'.$mch_id.'</mch_id>
           <nonce_str>'.$nonce_str.'</nonce_str>
           <notify_url>'.$notify_url.'</notify_url>
           <openid>'.$openid.'</openid>
           <out_trade_no>'.$out_trade_no.'</out_trade_no>
           <spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
           <total_fee>'.$total_fee.'</total_fee>
           <trade_type>'.$trade_type.'</trade_type>
           <sign>'.$sign.'</sign>
        </xml> ';
//        print_r($post_xml);die;
        //统一接口prepay_id
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $xml = $this->http_request($url,$post_xml);
        $array = $this->xmlToArray($xml);
//        print_r($xml);die;
        if($array['return_code'] == 'SUCCESS' && $array['result_code'] == 'SUCCESS'){
            $time = time();
            $tempArr=array(
                'appId' => $appid,
                'nonceStr' => $nonce_str,
                'package' => 'prepay_id='.$array['prepay_id'],
                'signType' => 'MD5',
                'timeStamp' => "$time"
            );
            $data['state'] = 200;
            $data['timeStamp'] = "$time";//时间戳
            $data['nonceStr'] = $nonce_str;//随机字符串
            $data['signType'] = 'MD5';//签名算法，暂支持 MD5
            $data['package'] = 'prepay_id='.$array['prepay_id'];//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
            $data['paySign'] = $this->sign($tempArr);//签名,具体签名方案参见微信公众号支付帮助文档;
            $data['out_trade_no'] = $out_trade_no;
            $data['orderNumber'] = $orderNumber;
            $data['sumPrice'] = $sumPrice;

        }else{
            $data['state'] = 0;
            $data['text'] = "错误";
            $data['returnArr'] = $array;
        }
        return $data;
    }
    //随机32位字符串
    private function nonce_str(){
        $result = '';
        $str = 'QWERTYUIOPASDFGHJKLZXVBNMqwertyuioplkjhgfdsamnbvcxz';
        for ($i=0;$i<32;$i++){
            $result .= $str[rand(0,48)];
        }
        return $result;
    }
    //签名 $data要先排好顺序
    private function sign($data){
        $stringA = '';
        foreach ($data as $key=>$value){
            if(!$value) continue;
            if($stringA) $stringA .= '&'.$key."=".$value;
            else $stringA = $key."=".$value;
        }
        $wx_key = 'P1ypDLpNBadDqr5q0JKHG9WUIYc8LaqH';//申请支付后有给予一个商户账号和密码，登陆后自己设置的key
        $stringSignTemp = $stringA.'&key='.$wx_key;
        return strtoupper(md5($stringSignTemp));
    }
    //curl请求
    public function http_request($url,$data = null,$headers=array()){
        $curl = curl_init();
        if( count($headers) >= 1 ){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    //xml转换成数组
    private function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }
    //小程序支付成功回调函数
    public function notify(){
        //获取接口数据，如果$_REQUEST拿不到数据，则使用file_get_contents函数获取
        $post = $_REQUEST;
        if ($post == null) {
            $post = file_get_contents("php://input");
        }
        if ($post == null) {
            $post = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        }
        if (empty($post) || $post == null || $post == '') {
            //阻止微信接口反复回调接口  文档地址 https://pay.weixin.qq.com/wiki/doc/api/H5.php?chapter=9_7&index=7，下面这句非常重要!!!
            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            echo $str;
            exit('Notify 非法回调');
        }
        /*****************微信回调返回数据样例*******************
        $post = '<xml><appid><![CDATA[wx42e35e9cba15a5fc]]></appid>
        <bank_type><![CDATA[CFT]]></bank_type>
        <cash_fee><![CDATA[1]]></cash_fee>
        <fee_type><![CDATA[CNY]]></fee_type>
        <is_subscribe><![CDATA[N]]></is_subscribe>
        <mch_id><![CDATA[1509441511]]></mch_id>
        <nonce_str><![CDATA[UtpvtBhXIqpROhTXNpJSafbcFuPXTprt]]></nonce_str>
        <openid><![CDATA[oYlJJ5Fs8VzKYE3xOKvKn-IuuMLM]]></openid>
        <out_trade_no><![CDATA[20190516155797735964]]></out_trade_no>
        <result_code><![CDATA[SUCCESS]]></result_code>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <sign><![CDATA[8DBFF1A0922A35B081CD09E59FB21EDF]]></sign>
        <time_end><![CDATA[20190516112925]]></time_end>
        <total_fee>1</total_fee>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        <transaction_id><![CDATA[4200000296201905161862992531]]></transaction_id>
        </xml>';
         *************************微信回调返回*****************/
        libxml_disable_entity_loader(true); //禁止引用外部xml实体
        $xml = simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);//XML转数组
        $post_data = (array)$xml;
        //将用户支付信息记录日志文件
        \Think\Log::record("用户openid：".$post_data['openid']);
        \Think\Log::record("appId：".$post_data['appid']);
        \Think\Log::record("订单编号：".$post_data['out_trade_no']);
        \Think\Log::record("支付金额：".$post_data['total_fee']/100);
        //将订单状态修改为已支付状态
        $out_trade_no=$post_data['out_trade_no'];  //订单编号
        $orderTable=M('shop_order');
        //修改订单状态为已支付
        $map['ordernumber']=$out_trade_no;
        $updateData['status']=2;
        $orderTable->where($map)->save($updateData);
        $shopidStr=$orderTable->where($map)->getField("shopid");
        $shopidArr=explode(",", $shopidStr);  //将字符串分割为数组
        $shopTable=M('shop_commodity');
        //增加商品销量
        \Think\Log::record("商品id字符串：".$shopidStr);
        foreach ($shopidArr as $k=>$v){
            \Think\Log::record("商品id：".$shopidArr[$k]);
            $shopTable->where("id='{$shopidArr[$k]}'")->setInc('salenumber');
        }
        //阻止微信接口反复回调接口  文档地址 https://pay.weixin.qq.com/wiki/doc/api/H5.php?chapter=9_7&index=7，下面这句非常重要!!!
        $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $str;
    }




}