using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace aliyun_api_gateway_sdk.Constant
{
    public class SystemHeader
    {
        //签名Header
        public  const String X_CA_SIGNATURE = "X-Ca-Signature";
        //所有参与签名的Header
        public  const String X_CA_SIGNATURE_HEADERS = "X-Ca-Signature-Headers";
        //请求时间戳
        public  const String X_CA_TIMESTAMP = "X-Ca-Timestamp";
        //请求放重放Nonce,15分钟内保持唯一,建议使用UUID
        public  const String X_CA_NONCE = "X-Ca-Nonce";
        //APP KEY
        public  const String X_CA_KEY = "X-Ca-Key";
        //请求API所属Stage
        public const String X_CA_STAGE = "X-Ca-Stage";
    }
}
