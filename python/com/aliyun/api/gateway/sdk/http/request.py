from com.aliyun.api.gateway.sdk.common import constant

class Request:
    content_md5 = "Content-MD5"
    content_length = "Content-Length"
    content_type = "Content-Type"

    def __init__(self, host=None, protocol=constant.HTTP, headers={}, url=None, method=None, time_out=None):
        self.__host = host
        self.__url = url
        self.__method = method
        self.__time_out = time_out
        self.__headers = headers
        self.__body = None
        self.__content_type = None
        self.__query_str = None
        self.__protocol = protocol

    def get_protocol(self):
        return self.__protocol

    def set_protocol(self, protocol):
        self.__protocol = protocol

    def get_method(self):
        return self.__method

    def set_method(self, method):
        self.__method = method

    def get_host(self):
        return self.__host

    def set_host(self, host):
        self.__host = host

    def get_url(self):
        return self.__url

    def set_url(self, url):
        self.__url = url

    def get_time_out(self):
        return self.__time_out

    def set_time_out(self, time_out):
        self.__time_out = time_out

    def get_content_type(self):
        return self.__content_type

    def set_content_type(self, content_type):
        self.__content_type = content_type

    def get_headers(self):
        return self.__headers

    def set_headers(self, headers={}):
        self.__headers = headers

    def get_query_str(self):
        return self.__query_str

    def set_query_str(self, query_str=None):
        self.__query_str = query_str

    def set_body(self, body):
        self.__body = body

    def get_body(self):
        return self.__body
