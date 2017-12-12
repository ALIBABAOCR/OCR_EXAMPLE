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
 * 集合对象的元素处理
 */
class DictionaryUtil 
{
	public static function Add($dic, $key, $value) {
		if (null == $value) {
			return;
		}
		if (null == $dic)
		{
			$dic = Array();
		}
		foreach($dic as $itemKey=>$itemValue)
		{
			//区分大小写
			if ($itemKey == $key) {
				$dic[$key] = $itemValue;
				return;
			}
		}
		$dic[$key] = $value;

	}

	public static function Get($dic, $key)
	{
		if (array_key_exists($key, $dic)) {
			return $dic[$key];
		}

		return null;
	}

	public static function Pop(&$dic, $key)
	{
		$value = null;
		if (array_key_exists($key, $dic)) {
			$value = $dic[$key];
			unset($dic[$key]);
		}

		return $value;
	}
}