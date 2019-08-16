<?php
namespace Admin\Controller;
use Common\Controller\BaseController;
class RefundController extends BaseController {
    //通过微信api进行退款流程
    public function wxRefundApi($out_trade_no,$total_fee){
        $parma = array(
            'appid'         => C('APPID'),  //appid
            'mch_id'        => C('MCHID'),  //商户id
            'nonce_str'     => $this->createNoncestr(),  //随机字符串
            'out_refund_no' => $out_trade_no.rand('1111,9999'), //由后端生成的退款单号，需要保证唯一，因为多个同样的退款单号只会退款一次。
            'out_trade_no'  => $out_trade_no,                   //退款订单在支付时生成的订单号
            'total_fee'     => $total_fee*100,  //支付金额：此处单位是分所以需要*100
            'refund_fee'    => $total_fee*100,  //退款金额：此处单位是分所以需要*100
            'op_user_id'    => C('MCHID'),          //操作员 op_user_id .与商户号相同即可
        );

        $parma['sign'] = $this->getSign($parma);
        $xmldata = $this->arrayToXml($parma);
        $xmlresult = $this->postXmlSSLCurl($xmldata,'https://api.mch.weixin.qq.com/secapi/pay/refund');
        $result = $this->xmlToArray($xmlresult);
//        setlog($parma,$result,__METHOD__);
        if (!$result){
            $result_arr = array(
                'num'     =>      0,
                'desc'   =>      '退款失败',
            );
            return $result_arr;
        }

        if ($result['result_code'] != 'SUCCESS'){
            $result_arr = array(
                'num'     =>      -1,
                'desc'    =>      $result['err_code_des']
            );
        } else {
            $result_arr = array(
                'num'     =>      1,
                'desc'    =>      '退款成功',
                'data'    =>      $result['refund_id']
            );
        }

        return $result_arr;
    }


    /*
     * 生成随机字符串方法
     */
    protected function createNoncestr($length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ ) {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }


    /*
    * 对要发送到微信统一下单接口的数据进行签名
    */
    protected function getSign($Obj){
        foreach ($Obj as $k => $v){
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".C('WEIXIN_PAY_KEY');  //此处是商户key
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }


    /*
      *排序并格式化参数方法，签名时需要使用
    */
    protected function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }

        $reqPar = '';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }


    //数组转字符串方法
    protected function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    protected function xmlToArray($xml){
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }


    //需要使用证书的请求
    function postXmlSSLCurl($xml,$url,$second=30)
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLCERT, C('SSLCERT_PATH'));  //此处是证书存放地址，例：/home/apiclient_cert.pem
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLKEY, C('SSLKEY_PATH'));    //此处是证书存放地址，例：/home/apiclient_key.pem
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            curl_close($ch);
            return false;
        }
    }
}