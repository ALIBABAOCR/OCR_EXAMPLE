<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/**
*签名处理
*/
class SignUtil
{
	/**
     * 构建待签名
	 */
	public static function Sign($path, $method, $secret, &$headers, $querys, $bodys, $signHeaderPrefixList)
    {
		$signStr = self::BuildStringToSign($path, $method, $headers, $querys, $bodys, $signHeaderPrefixList);
		return base64_encode(hash_hmac('sha256', $signStr, $secret, true));
    }
	/**
     * 构建待签名path+(header+query+body)
	 */
	private static function BuildStringToSign($path, $method, &$headers, $querys, $bodys, $signHeaderPrefixList)
    {
		$sb = "";
		$sb.= strtoupper($method);
		$sb.= Constants::LF;
		if (array_key_exists(HttpHeader::HTTP_HEADER_ACCEPT, $headers) && null != $headers[HttpHeader::HTTP_HEADER_ACCEPT]) {
			$sb.= $headers[HttpHeader::HTTP_HEADER_ACCEPT];
		}
		$sb.= Constants::LF;
		if (array_key_exists(HttpHeader::HTTP_HEADER_CONTENT_MD5, $headers) && null != $headers[HttpHeader::HTTP_HEADER_ACCEPT]) {
			$sb.= $headers[HttpHeader::HTTP_HEADER_CONTENT_MD5];
		}
		$sb.= Constants::LF;
		if (array_key_exists(HttpHeader::HTTP_HEADER_CONTENT_TYPE, $headers) && null != $headers[HttpHeader::HTTP_HEADER_ACCEPT]) {
			$sb.= $headers[HttpHeader::HTTP_HEADER_CONTENT_TYPE];
		}
		$sb.= Constants::LF;
		if (array_key_exists(HttpHeader::HTTP_HEADER_DATE, $headers) && null != $headers[HttpHeader::HTTP_HEADER_ACCEPT]) {
			$sb.= $headers[HttpHeader::HTTP_HEADER_DATE];
		}
		$sb.= Constants::LF;		
		$sb.= self::BuildHeaders($headers, $signHeaderPrefixList);
		$sb.= self::BuildResource($path, $querys, $bodys);

		return $sb;
	}

	/**
     * 构建待签名Path+Query+FormParams
	 */
    private static function BuildResource($path, $querys, $bodys)
	{
		$sb = "";
		if (0 < strlen($path))
        {
			$sb.=$path;
		}
        $sbParam = "";
		$sortParams = array();

        //query参与签名
	    if (is_array($querys)) {
			foreach ($querys as $itemKey => $itemValue) {
				if (0 < strlen($itemKey)) {
					$sortParams[$itemKey] = $itemValue;
				}
			}
		}
        //body参与签名
	    if (is_array($bodys)) {
			foreach ($bodys as $itemKey => $itemValue) {
				if (0 < strlen($itemKey)) {
					$sortParams[$itemKey] = $itemValue;
				}
			}
		}
		//排序
		ksort($sortParams);
		//参数Key 
		foreach ($sortParams as $itemKey => $itemValue) {
			if (0 < strlen($itemKey)) {
				if (0 < strlen($sbParam)) {
					$sbParam.="&";
				}
				$sbParam.=$itemKey;
                if (null != $itemValue)
                {
					if (0 < strlen($itemValue)) {
						$sbParam.="=";
						$sbParam.=$itemValue;
					}
                 }
			}
		}
		if (0 < strlen($sbParam)) {
			$sb.="?";
			$sb.=$sbParam;
		}

		return $sb;
	}
	
	/**
     * 构建待签名Http头
	 *
     * @param headers              请求中所有的Http头
     * @param signHeaderPrefixList 自定义参与签名Header前缀
     * @return 待签名Http头
    */
    private static function BuildHeaders(&$headers, $signHeaderPrefixList)
	{
		$sb = "";

		if (null != $signHeaderPrefixList)
		{
			//剔除X-Ca-Signature/X-Ca-Signature-Headers/Accept/Content-MD5/Content-Type/Date
            unset($signHeaderPrefixList[SystemHeader::X_CA_SIGNATURE]);
			unset($signHeaderPrefixList[HttpHeader::HTTP_HEADER_ACCEPT]);
			unset($signHeaderPrefixList[HttpHeader::HTTP_HEADER_CONTENT_MD5]);
			unset($signHeaderPrefixList[HttpHeader::HTTP_HEADER_CONTENT_TYPE]);
			unset($signHeaderPrefixList[HttpHeader::HTTP_HEADER_DATE]);
			ksort($signHeaderPrefixList);
			
			if (is_array($headers)) {
				ksort($headers);
				$signHeadersStringBuilder = "";
				foreach ($headers as $itemKey => $itemValue)
				{
					if (self::IsHeaderToSign($itemKey, $signHeaderPrefixList))
                    {
						$sb.=$itemKey;
						$sb.=Constants::SPE2;
                        if (0 < strlen($itemValue)) {
							$sb.=$itemValue;
						}
						$sb.=Constants::LF;
                        if (0 < strlen($signHeadersStringBuilder))
                        {
                            $signHeadersStringBuilder.= Constants::SPE1;
                        }
                        $signHeadersStringBuilder.= $itemKey;
                    }
				}
				$headers[SystemHeader::X_CA_SIGNATURE_HEADERS] = $signHeadersStringBuilder;
			}

		} 

        return $sb;
     }	
	/**
	* Http头是否参与签名
    * return
    */
    private static function IsHeaderToSign($headerName, $signHeaderPrefixList) 
	{
		if (NULL == $headerName) {
			return false;
		}
		if (0 == strlen($headerName)) {
			return false;
		}
		if (1 == strpos("$".$headerName, Constants::CA_HEADER_TO_SIGN_PREFIX_SYSTEM)) {
			return true;
		}
		if (!is_array($signHeaderPrefixList) || empty($signHeaderPrefixList) ) {
			return false;
		}
		if (array_key_exists($headerName, $signHeaderPrefixList)) {
			return true;
		}

		return false;
	}
}