<?php
    include_once 'api-gateway-demo-sign-php/Util/Autoloader.php';
    
    
    function doPost($url, $appKey, $appSecret, $bodyContent) {
        //域名后、query前的部分
        $urlEles = parse_url($url);
        $host = $urlEles["scheme"] . "://" . $urlEles["host"];
        $path = $urlEles["path"];
        $request = new HttpRequest($host, $path, HttpMethod::POST, $appKey, $appSecret);
    
        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
    
        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
    
        //注意：业务body部分，不能设置key值，只能有value
        if (0 < strlen($bodyContent)) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
            $request->setBodyString($bodyContent);
        }
    
        //指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        $response = HttpClient::execute($request);
        return $response;
    }
    
    $appKey = "你的APPKEY";
    $appSecret = "你的APPSECRET";
    $url = "http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
    $file = "/Users/zhouwenmeng/ubuntu_sync/test/idcard.jpg"; // 文件路径 
    
    //如果输入带有inputs, 设置为True，否则设为False
    $is_old_format = false;
    
    //如果没有configure字段，config设为空
    $config = array(
        "side" => "face"
    );
    //$config = array()

    if($fp = fopen($file, "rb", 0)) { 
        $binary = fread($fp, filesize($file)); // 文件读取
        fclose($fp); 
        $base64 = base64_encode($binary); // 转码
    }

    if($is_old_format == TRUE){
        $request = array();
        $request["image"] = array(
                "dataType" => 50,
                "dataValue" => "$base64"
        );

        if(count($config) > 0){
            $request["configure"] = array(
                    "dataType" => 50,
                    "dataValue" => json_encode($config) 
                );
        }
        $body = json_encode(array("inputs" => array($request)));
    }else{
        $request = array(
            "image" => "$base64"
        );
        if(count($config) > 0){
            $request["configure"] = json_encode($config);
        }
        $body = json_encode($request);
    }
    $response = doPost($url, $appKey, $appSecret, $body);
    $stat = $response->getHttpStatusCode();
    if($stat == 200){
        if($is_old_format){
            $output = json_decode($response->getBody(), true);
            $result_str = $output["outputs"][0]["outputValue"]["dataValue"];
        }else{
            $result_str = $response->getBody();
        }
        printf("result is :\n %s\n", $result_str);

    }else{
        printf("Http error code: %d\n", $stat);
        printf("Error msg in body: %s\n", $response->getBody());
        printf("header: %s\n", $response->getHeader());
}
    //var_dump($response);
