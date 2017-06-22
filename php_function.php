 <?php
/**
 * json_encode避免中文转码
 * @author huangdongxi(huangdongxi@baidu.com)
 * @date   2016-07-27
 * @param string        $label 标签 默认为空
 * @return void|string
 */
function encode_json($str) {
    return urldecode(json_encode(url_encode($str)));
}

/**
 * json_decode 转义
 * @author huangdongxi(huangdongxi@baidu.com)
 * @date   2016-07-28
 * @param string        $label 标签 默认为空
 * @return void|string
 */
function decode_json($json)
{
  return json_decode(trim($json,chr(239).chr(187).chr(191)),true);
}
/**
 * 读取文件后几行
 * @author huangdongxi(huangdongxi@baidu.com)
 * @date   2016-08-09
 * @param string        $label 标签 默认为空
 * @return void|string
 */
function tail($file,$num)
{
    $fp = fopen($file,"r");
    if ($fp === false){
        return array();
    }
    $pos = 0;
    $eof = "";
    $head = false;   //当总行数小于Num时，判断是否到第一行了
    $lines = array();
    while($num>0){
        while($eof != "\n"){
            if(fseek($fp, $pos, SEEK_END)==0){    //fseek成功返回0，失败返回-1
                $eof = fgetc($fp);
                $pos--;
            }else{                               //当到达第一行，行首时，设置$pos失败
                fseek($fp,0,SEEK_SET);
                $head = true;                   //到达文件头部，开关打开
                break;
            }

        }
        array_unshift($lines,fgets($fp));
        if($head){ break; }                 //这一句，只能放上一句后，因为到文件头后，把第一行读取出来再跳出整个循环
        $eof = "";
        $num--;
    }
    fclose($fp);
    return $lines;
}
/**
 * [httpRequest description]
 * @param  string  $url    [description]
 * @param  string  $params [description]
 * @param  string  $method [description]
 * @param  array   $header [description]
 * @param  boolean $multi  [description]
 * @return void          [description]
 */
function httpRequest($url, $params, $method = 'GET', $header = array(), $multi = false) {
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $header,
        );
    switch(strtoupper($method)) {
        case 'GET' :
                $opts[CURLOPT_URL] = $url . '&' . http_build_query($params);
                break;
        case 'POST' :
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
        default :
                throw new Exception('unsupport');
    }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    if ($error){
            throw new Exception('req error'. $error);
        }
        return $data;
}
function post($url,$postdata){
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, false);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    $data = curl_exec($curl);
   // $data = json_decode($data,true);
    return $data;
    //关闭URL请求
    curl_close($curl);
}
function curl_request($url,$data=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT,60);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/json" ));
    $res = curl_exec ( $ch );
    curl_close ( $ch );
    return $res;
}
function http_post_data($url, $data_string) {  
  
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
            'Content-Type: application/json; charset=utf-8',  
            'Content-Length: ' . strlen($data_string))  
        );  
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();  
  
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        return array($return_code, $return_content);  
    }  

function init_curl($url,$data=array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec ( $ch );
    curl_close ( $ch );
    $res = json_decode($res,true);
    return $res;
}

?>
