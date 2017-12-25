using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace aliyun_api_gateway_sdk.Constant
{
    public class Constants
    {
        //签名算法HmacSha256
        public  const String HMAC_SHA256 = "HmacSHA256";
        //编码UTF-8
        public  const String ENCODING = "UTF-8";
        //UserAgent
        public  const String USER_AGENT = "demo/aliyun/net";
        //换行符
        public  const String LF = "\n";
        //分隔符1
        public const String SPE1 = ",";
        //分隔符2
        public const String SPE2 = ":";

        //默认请求超时时间,单位毫秒
        public  const int DEFAULT_TIMEOUT = 1000;

        //参与签名的系统Header前缀,只有指定前缀的Header才会参与到签名中
        public  const String CA_HEADER_TO_SIGN_PREFIX_SYSTEM = "X-Ca-";
    }
}
