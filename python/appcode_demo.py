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


def predict(url, appcode, img_base64, kv_configure):
        param = {}
        param['image'] = img_base64
        if kv_configure is not None:
            param['configure'] = json.dumps(kv_configure)
        body = json.dumps(param)

        headers = {'Authorization' : 'APPCODE %s' % appcode}
        request = urllib2.Request(url = url, headers = headers, data = body)
        try:
            response = urllib2.urlopen(request, timeout = 10)
            return response.code, response.headers, response.read()
        except urllib2.HTTPError as e:
            return e.code, e.headers, e.read()


def demo():
    appcode = '你的APPCODE'
    url = 'http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json'
    img_file = '图片文件路径/图片url'
    configure = {'side':'face'}
    #如果没有configure字段，configure设为None
    #configure = None

    if img_file[:4] == 'http':
        img_base64data = img_file
    else:
        img_base64data = get_img_base64(img_file)
    stat, header, content = predict( url, appcode, img_base64data, configure)
    if stat != 200:
        print 'Http status code: ', stat
        print 'Error msg in header: ', header['x-ca-error-message'] if 'x-ca-error-message' in header else ''
        print 'Error msg in body: ', content
        exit()
    result_str = content

    print result_str;
    #result = json.loads(result_str)

if __name__ == '__main__':
    demo()
