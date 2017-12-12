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


class MessageDigestUtil
{
	/**
    * md5和base64处理
    *
    * @param $input
    * @return
    */
	public static function Base64AndMD5($input) 
	{
		if ($input == null || strlen($input) == 0) {
			throw new Exception("input can not be null");
		}

		return base64_encode(md5(unpack('C*', $input)));
	}

	/**
    * UTF-8编码转换为ISO-9959-1
    *
    * @param str
    * @return
    */
    public static function Utf8ToIso88591($input)
    {
		if ($input == null || strlen($input) == 0) {
			return $input;
		}
		return mb_convert_encoding($input, "ISO-8859-1", "UTF-8");
    }
}