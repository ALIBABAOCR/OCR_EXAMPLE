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
class HttpResponse
{
	private $content;
	private $body;
	private $header;
	private $requestId;
	private $errorMessage;
	private $contentType;
	private $httpStatusCode;
	
	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setHeader($header)
	{
		$this->header = $header;
	}

	public function getHeader()
	{
		return $this->header;
	}

	public function setBody($body)
	{
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function getRequestId()
	{
		return $this->requestId;
	}

	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	public function getHttpStatusCode()
	{
		return $this->httpStatusCode;
	}
	
	public function setHttpStatusCode($httpStatusCode)
	{
		$this->httpStatusCode  = $httpStatusCode;
	}

	public function getContentType()
	{
		return $this->contentType;
	}
	
	public function setContentType($contentType)
	{
		$this->contentType  = $contentType;
	}
	
	public function getSuccess()
	{
		if(200 <= $this->httpStatusCode && 300 > $this->httpStatusCode)
		{
			return true;
		}
		return false;
	}

	/**
	*根据headersize大小，区分返回的header和body
	*/
	public function setHeaderSize($headerSize) {
		if (0 < $headerSize && 0 < strlen($this->content)) {
			$this->header = substr($this->content, 0, $headerSize);
			self::extractKey();
		}
		if (0 < $headerSize && $headerSize < strlen($this->content)) {
			$this->body = substr($this->content, $headerSize);
		}
	}

	/**
	*提取header中的requestId和errorMessage
	*/
	private function extractKey() {
		if (0 < strlen($this->header)) {
			$headers = explode("\r\n", $this->header);
			foreach ($headers as $value) {
				if(strpos($value, "X-Ca-Request-Id:") !== false) 
				{
					$this->requestId = trim(substr($value, strlen("X-Ca-Request-Id:")));
				}
				if(strpos($value, "X-Ca-Error-Message:") !== false) 
				{
					$this->errorMessage = trim(substr($value, strlen("X-Ca-Error-Message:")));
				}
			}
		}
	}
}