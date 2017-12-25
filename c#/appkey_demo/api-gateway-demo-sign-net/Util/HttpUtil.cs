using aliyun_api_gateway_sdk.Constant;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Security;
using System.Web;
using System.Security.Cryptography.X509Certificates;
using System.Text;
using System.Threading.Tasks;

namespace aliyun_api_gateway_sdk.Util
{
    public class HttpUtil
    {
        public static HttpWebResponse HttpPost(String host, String path, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, Dictionary<String, String> bodys, List<String> signHeaderPrefixList)
        {
            return DoHttp(host, path, HttpMethod.POST, appKey, appSecret, timeout, headers, querys, bodys, signHeaderPrefixList);
        }

        public static HttpWebResponse HttpPut(String host, String path, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, Dictionary<String, String> bodys, List<String> signHeaderPrefixList)
        {
            return DoHttp(host, path, HttpMethod.PUT, appKey, appSecret, timeout, headers, querys, bodys, signHeaderPrefixList);
        }

        public static HttpWebResponse HttpGet(String host, String path, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, List<String> signHeaderPrefixList)
        {
            return DoHttp(host, path, HttpMethod.GET, appKey, appSecret, timeout, headers, querys, null, signHeaderPrefixList);
        }

        public static HttpWebResponse HttpHead(String host, String path, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, List<String> signHeaderPrefixList)
        {
            return DoHttp(host, path, HttpMethod.HEAD, appKey, appSecret, timeout, headers, querys, null, signHeaderPrefixList);
        }

        public static HttpWebResponse HttpDelete(String host, String path, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, List<String> signHeaderPrefixList)
        {
            return DoHttp(host, path, HttpMethod.DELETE, appKey, appSecret, timeout, headers, querys, null, signHeaderPrefixList);
        }





        private static HttpWebResponse DoHttp(String host, String path, String method, String appKey, String appSecret, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys, Dictionary<String, String> bodys, List<String> signHeaderPrefixList)
        {
            headers = InitialBasicHeader(path, appKey, appSecret, method, headers, querys, bodys, signHeaderPrefixList);
            HttpWebRequest httpRequest = InitHttpRequest(host, path, method, timeout, headers, querys);

            if (null != bodys && 0 < bodys.Count)
            {
                StringBuilder sb = new StringBuilder();
                foreach (var param in bodys)
                {
                    if (0 < sb.Length)
                    {
                        sb.Append("&");
                    }
                    if (null != param.Value && 0 == param.Key.Length)
                    {
                        sb.Append(param.Value);
                    }
                    if (0 < param.Key.Length)
                    {
                        sb.Append(param.Key).Append("=");
                        if (null != param.Value)
                        {
                            sb.Append(HttpUtility.UrlEncode(param.Value, Encoding.UTF8));
                        }
                    }
                }
                byte[] data = Encoding.UTF8.GetBytes(sb.ToString());
                using (Stream stream = httpRequest.GetRequestStream())
                {
                    stream.Write(data, 0, data.Length);
                }
            }

            HttpWebResponse httpResponse = GetResponse(httpRequest);
            return httpResponse;
        }

        private static HttpWebResponse GetResponse(HttpWebRequest httpRequest)
        {
            HttpWebResponse httpResponse = null;
            try
            {
                WebResponse response = httpRequest.GetResponse();
                httpResponse = (HttpWebResponse)response;

            }
            catch (WebException ex)
            {
                httpResponse = (HttpWebResponse)ex.Response;
            }
            return httpResponse;
        }

        private static HttpWebRequest InitHttpRequest(String host, String path, String method, int timeout, Dictionary<String, String> headers, Dictionary<String, String> querys)
        {
            HttpWebRequest httpRequest = null;
            String url = host;
            if (null != path)
            {
                url = url + path;
            }

            if (null != querys && 0 < querys.Count)
            {
                StringBuilder sb = new StringBuilder();
                foreach (var param in querys)
                {
                    if (0 < sb.Length)
                    {
                        sb.Append("&");
                    }
                    if (null != param.Value && null == param.Key)
                    {
                        sb.Append(param.Value);
                    }
                    if (null != param.Key)
                    {
                        sb.Append(param.Key).Append("=");
                        if (null != param.Value)
                        {
                            sb.Append(HttpUtility.UrlEncode(param.Value, Encoding.UTF8));
                        }
                    }
                }
                if (0 < sb.Length)
                {
                    url = url + "?" + sb.ToString();
                }
            }

            if (host.Contains("https://"))
            {
                ServicePointManager.ServerCertificateValidationCallback = new RemoteCertificateValidationCallback(CheckValidationResult);
                httpRequest = (HttpWebRequest)WebRequest.CreateDefault(new Uri(url));
            }
            else
            {
                httpRequest = (HttpWebRequest)WebRequest.Create(url);
            }
            httpRequest.ServicePoint.Expect100Continue = false;
            httpRequest.Method = method;
            httpRequest.KeepAlive = true;
            httpRequest.Timeout = timeout;

            if (headers.ContainsKey("Accept"))
            {
                httpRequest.Accept = DictionaryUtil.Pop(headers, "Accept");
            }
            if (headers.ContainsKey("Date"))
            {
                httpRequest.Date = Convert.ToDateTime(DictionaryUtil.Pop(headers, "Date"));
            }
            if (headers.ContainsKey(HttpHeader.HTTP_HEADER_CONTENT_TYPE))
            {
                httpRequest.ContentType = DictionaryUtil.Pop(headers, HttpHeader.HTTP_HEADER_CONTENT_TYPE);
            }

            foreach (var header in headers)
            {
                httpRequest.Headers.Add(header.Key, header.Value);
            }
            return httpRequest;
        }


        private static Dictionary<String, String> InitialBasicHeader(String path, String appKey, String appSecret, String method, Dictionary<String, String> headers, Dictionary<String, String> querys, Dictionary<String, String> bodys, List<String> signHeaderPrefixList)
        {
            if (headers == null)
            {
                headers = new Dictionary<String, String>();
            }

            StringBuilder sb = new StringBuilder();
            //时间戳
            headers.Add(SystemHeader.X_CA_TIMESTAMP, DateUtil.ConvertDateTimeInt(DateTime.Now).ToString());
            //防重放，协议层不能进行重试，否则会报NONCE被使用；如果需要协议层重试，请注释此行
            headers.Add(SystemHeader.X_CA_NONCE, Guid.NewGuid().ToString());
            headers.Add(SystemHeader.X_CA_KEY, appKey);
            headers.Add(SystemHeader.X_CA_SIGNATURE, SignUtil.Sign(path, method, appSecret, headers, querys, bodys, signHeaderPrefixList));

            return headers;
        }


        public static bool CheckValidationResult(object sender, X509Certificate certificate, X509Chain chain, SslPolicyErrors errors)
        {
            return true;
        }
    }
}
