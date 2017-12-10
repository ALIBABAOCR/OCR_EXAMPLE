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
class HttpRequest
{
	protected  $host;
	protected  $path;
	protected  $method;
	protected  $appKey;
	protected  $appSecret;
	protected  $headers = array();
	protected  $signHeaders = array();
	protected  $querys = array();
	protected  $bodys = array();

	function  __construct($host, $path, $method, $appKey, $appSecret)
	{
	    $this->host = $host;
	    $this->path = $path;
	    $this->method = $method;
		$this->appKey = $appKey;
		$this->appSecret = $appSecret;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function setHeader($key, $value)
	{
		if (null == $this->headers) {
			$this->headers = array();
		}
		$this->headers[$key] = $value;
	}

	public function getHeader($key)
	{
		return $this->headers[$key];
	}

	public function removeHeader($key)
	{
		unset($this->headers[$key]);
	}

	public function getQuerys()
	{
		return $this->querys;
	}

	public function setQuery($key, $value)
	{
		if (null == $this->querys) {
			$this->querys = array();
		}
		$this->querys[$key] = $value;
	}

	public function getQuery($key)
	{
		return $this->querys[$key];
	}

	public function removeQuery($key)
	{
		unset($this->querys[$key]);
	}

	public function getBodys()
	{
		return $this->bodys;
	}

	public function setBody($key, $value)
	{
		if (null == $this->bodys) {
			$this->bodys = array();
		}
		$this->bodys[$key] = $value;
	}

	public function getBody($key)
	{
		return $this->bodys[$key];
	}

	public function removeBody($key)
	{
		unset($this->bodys[$key]);
	}

	public function setBodyStream($value)
	{
		if (null == $this->bodys) {
			$this->bodys = array();
		}
		$this->bodys[""] = $value;
	}

	public function setBodyString($value)
	{
		if (null == $this->bodys) {
			$this->bodys = array();
		}
		$this->bodys[""] = $value;
	}


	public function getSignHeaders()
	{
		return $this->signHeaders;
	}

	public function setSignHeader($value)
	{
		if (null == $this->signHeaders) {
			$this->signHeaders = array();
		}
		if (!in_array($value, $this->signHeaders)) {
			array_push($this->signHeaders, $value);
		}
	}

	public function removeSignHeader($value)
	{
		unset($this->signHeaders[$value]);
	}

	public function getHost()
	{
		return $this->host;
	}
	
	public function setHost($host)
	{
		$this->host = $host;
	}

	public function getPath()
	{
		return $this->path;
	}
	
	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getMethod()
	{
		return $this->method;
	}
	
	public function setMethod($method)
	{
		$this->method = $method;
	}

	public function getAppKey()
	{
		return $this->appKey;
	}
	
	public function setAppKey($appKey)
	{
		$this->appKey = $appKey;
	}

	public function getAppSecret()
	{
		return $this->appSecret;
	}
	
	public function setAppSecret($appSecret)
	{
		$this->appSecret = $appSecret;
	}
}