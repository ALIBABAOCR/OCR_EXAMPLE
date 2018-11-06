using aliyun_api_gateway_sdk.Constant;
using aliyun_api_gateway_sdk.Util;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;


namespace appkey_demo
{
    class MainClass
    {
        static void Main(string[] args)
        {
            String url = "http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";

                String appKey = "你自己的AppKey";
                String appSecret = "你自己的AppSecret";
                String imgFile = "图片路径";

            //如果没有configure字段，configure设为''
            //String configure = '';
            String configure = "{\\\"side\\\":\\\"face\\\"}";


            FileStream fs = new FileStream(imgFile, FileMode.Open);
            BinaryReader br = new BinaryReader(fs);
            byte[] contentBytes = br.ReadBytes(Convert.ToInt32(fs.Length));
            String base64 = System.Convert.ToBase64String(contentBytes);
            String bodys;
            bodys = "{\"image\":\"" + base64 + "\"";
            if (config.Length > 0)
            {
                bodys += ",\"configure\" :\"" + configure + "\"";
            }
            bodys += "}";

            Dictionary<String, String> headers = new Dictionary<string, string>();
            Dictionary<String, String> querys = new Dictionary<string, string>();
            Dictionary<String, String> bodys_map = new Dictionary<string, string>();
            List<String> signHeader = new List<String>();

            //设定Content-Type，根据服务器端接受的值来设置
            headers.Add(HttpHeader.HTTP_HEADER_CONTENT_TYPE, ContentType.CONTENT_TYPE_JSON);
            //设定Accept，根据服务器端接受的值来设置
            headers.Add(HttpHeader.HTTP_HEADER_ACCEPT, ContentType.CONTENT_TYPE_JSON);

            //注意：如果有非Form形式数据(body中只有value，没有key)；如果body中是key/value形式数据，不要指定此行
            headers.Add(HttpHeader.HTTP_HEADER_CONTENT_MD5, MessageDigestUtil.Base64AndMD5(Encoding.UTF8.GetBytes(bodys)));

            //注意：业务body部分
            bodys_map.Add("", bodys);

            //指定参与签名的header            
            signHeader.Add(SystemHeader.X_CA_TIMESTAMP);

            Uri myUri = new Uri(url);
            using (HttpWebResponse response = HttpUtil.HttpPost(myUri.Scheme + "://" + myUri.Host, myUri.AbsolutePath, appKey, appSecret, 30000, headers, querys, bodys_map, signHeader))
            {
                if (response.StatusCode != HttpStatusCode.OK)
                {
                    Console.WriteLine("http error code: " + response.StatusCode);
                    Console.WriteLine("error in header: " + response.GetResponseHeader("X-Ca-Error-Message"));
                    Console.WriteLine("error in body: ");
                    Stream st = response.GetResponseStream();
                    StreamReader reader = new StreamReader(st, Encoding.GetEncoding("utf-8"));
                    Console.WriteLine(reader.ReadToEnd());
                }
                else
                {

                    Stream st = response.GetResponseStream();
                    StreamReader reader = new StreamReader(st, Encoding.GetEncoding("utf-8"));
                    Console.WriteLine(reader.ReadToEnd());
                    Console.WriteLine(Constants.LF);

                }

            }

        }


    }
}
