<?php


    $url = "https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
    $appcode = "你的APPCODE";
    $file = "你的文件路径"; 
    //如果输入带有inputs, 设置为True，否则设为False
    $is_old_format = true;
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
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    //根据API的要求，定义相对应的Content-Type
    array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
    $querys = "";
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
    $method = "POST";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    if (1 == strpos("$".$url, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $result = curl_exec($curl);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); 
    $rheader = substr($result, 0, $header_size); 
    $rbody = substr($result, $header_size);

    $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    if($httpCode == 200){
        if($is_old_format){
            $output = json_decode($rbody, true);
            $result_str = $output["outputs"][0]["outputValue"]["dataValue"];
        }else{
            $result_str = $rbody;
        }
        printf("result is :\n %s\n", $result_str);
    }else{
        printf("Http error code: %d\n", $httpCode);
        printf("Error msg in body: %s\n", $rbody);
        printf("header: %s\n", $rheader);
    }
?>
