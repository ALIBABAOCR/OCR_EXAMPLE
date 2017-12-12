#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys,os
import base64
import time
import json

from urlparse import urlparse
from com.aliyun.api.gateway.sdk import client
from com.aliyun.api.gateway.sdk.http import request
from com.aliyun.api.gateway.sdk.common import constant
import traceback
import urllib2
import base64

def get_img_base64(img_file):
    with open(img_file, 'rb') as infile:
        s = infile.read()
        return base64.b64encode(s) 


def predict(url, app_key, app_secret, img_base64, kv_config, old_format):
        statTime = time.time()
        cli = client.DefaultClient(app_key=app_key, app_secret=app_secret)
        if not old_format:
            param = {}
            param['image'] = img_base64
            if kv_config is not None:
                param['configure'] = json.dumps(kv_config)
            body = json.dumps(param)
        else:
            param = {}
            pic = {}
            pic['dataType'] = 50
            pic['dataValue'] = img_base64
            param['image'] = pic
    
            if kv_config is not None:
                conf = {}
                conf['dataType'] = 50
                conf['dataValue'] = json.dumps(kv_config) 
                param['configure'] = conf

    
            inputs = { "inputs" : [param]}
            body = json.dumps(inputs)

        url_ele = urlparse(url)
        host = 'http://' + url_ele.hostname
        path = url_ele.path 
        headers = {}
        req_post = request.Request(host=host, protocol=constant.HTTP, url=path, headers = headers, method="POST", time_out=6000)
        req_post.set_body(bytearray(source=body, encoding="utf8"))
        req_post.set_content_type(constant.CONTENT_TYPE_STREAM)
        stat,header, content = cli.execute(req_post)
        endTime = time.time()

        return stat, dict(header) if header is not None else {}, content


def demo():
    app_key = '你的APP_KEY'
    app_secret = '你的APP_SECRET'
    url = 'http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json'
    img_file = '图片文件路径'
    #如果输入带有inputs, 设置为True，否则设为False
    is_old_format = True  
    config = {'side':'face'}
    #如果没有configure字段，config设为None
    #config = None

    img_base64data = get_img_base64(img_file)
    stat, header, content = predict( url, app_key, app_secret, img_base64data, config, is_old_format)
    if stat != 200:
        print 'Http status code: ', stat
        print 'Error msg in header: ', header['x-ca-error-message'] if 'x-ca-error-message' in header else ''
        print 'Error msg in body: ', content
        exit()
    if is_old_format:
        result_str = json.loads(content)['outputs'][0]['outputValue']['dataValue']
    else:
        result_str = content

    print result_str;
    #result = json.loads(result_str)

if __name__ == '__main__':
    demo()
