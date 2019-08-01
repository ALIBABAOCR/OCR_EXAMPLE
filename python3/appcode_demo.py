#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys,os
import base64
import json

from urllib.parse import urlparse
import urllib.request
import base64

ENCODING = 'utf-8'

def get_img_base64(img_file):
    with open(img_file, 'rb') as infile:
        s = infile.read()
        return base64.b64encode(s).decode(ENCODING)



def predict(url, appcode, img_base64, kv_configure):
        param = {}
        param['image'] = img_base64
        if kv_configure is not None:
            param['configure'] = json.dumps(kv_configure)
        body = json.dumps(param)
        data = bytes(body, "utf-8")

        headers = {'Authorization' : 'APPCODE %s' % appcode}
        request = urllib.request.Request(url = url, headers = headers, data = data)
        try:
            response = urllib.request.urlopen(request, timeout = 10)
            return response.code, response.headers, response.read()
        except urllib.request.HTTPError as e:
            return e.code, e.headers, e.read()


def demo():
    appcode = '你的APPCODE'
    url = 'http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json'
    img_file = '图片文件路径/图片url'
    configure = {'side':'face'}
    #如果没有configure字段，configure设为None
    #configure = None

    img_base64data = get_img_base64(img_file)
    stat, header, content = predict( url, appcode, img_base64data, configure)
    if stat != 200:
        print('Http status code: ', stat)
        print('Error msg in header: ', header['x-ca-error-message'] if 'x-ca-error-message' in header else '')
        print('Error msg in body: ', content)
        exit()
    result_str = content

    print(result_str.decode(ENCODING))
    #result = json.loads(result_str)

if __name__ == '__main__':
    demo()
