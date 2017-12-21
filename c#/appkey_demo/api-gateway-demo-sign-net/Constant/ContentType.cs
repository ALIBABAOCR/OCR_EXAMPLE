using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace aliyun_api_gateway_sdk.Constant
{
    public class ContentType
    {
        //表单类型Content-Type
        public  const String CONTENT_TYPE_FORM = "application/x-www-form-urlencoded; charset=utf-8";
        // 流类型Content-Type
        public  const String CONTENT_TYPE_STREAM = "application/octet-stream; charset=utf-8";
        //JSON类型Content-Type
        public  const String CONTENT_TYPE_JSON = "application/json; charset=utf-8";
        //XML类型Content-Type
        public  const String CONTENT_TYPE_XML = "application/xml; charset=utf-8";
        //文本类型Content-Type
        public  const String CONTENT_TYPE_TEXT = "application/text; charset=utf-8";

    }
}
