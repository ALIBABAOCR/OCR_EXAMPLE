using System;
using System.IO;
using System.Text;
using System.Net;
using System.Net.Security;
using System.Security.Cryptography.X509Certificates;

class Demo{
    static void Main(string[] args)
    {
        String url = "http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
        String appcode = "你自己的AppCode";
        String img_file = "图片路径";
        
        //如果输入带有inputs, 设置为True，否则设为False
        bool is_old_format = false;

        //如果没有configure字段，config设为''
        //String config = '';
        String config = "{\\\"side\\\":\\\"face\\\"}";
        
        String method = "POST";

        String querys = "";

        FileStream fs = new FileStream(img_file, FileMode.Open);
        BinaryReader br = new BinaryReader(fs);
        byte[] contentBytes = br.ReadBytes(Convert.ToInt32(fs.Length));
        String base64 = System.Convert.ToBase64String(contentBytes);
        String bodys;
        if(is_old_format){
            bodys = "{\"inputs\" :" +
                                "[{\"image\" :" + 
                                    "{\"dataType\" : 50," + 
                                     "\"dataValue\" :\"" + base64 + "\"" +
                                     "}";
            if(config.Length > 0){
                bodys += ",\"configure\" :" + 
                                "{\"dataType\" : 50," +
                                 "\"dataValue\" : \"" + config + "\"}" +
                                 "}";
            } 
            bodys +=  "]}";
        }else{
            bodys = "{\"image\":\"" + base64 + "\"";
            if(config.Length > 0){
                bodys += ",\"configure\" :\"" + config + "\"";
            } 
            bodys += "}";
        }
        HttpWebRequest httpRequest = null;
        HttpWebResponse httpResponse = null;

        if (0 < querys.Length)
        {
            url = url + "?" + querys;
        }

        if (url.Contains("https://"))
        {
            ServicePointManager.ServerCertificateValidationCallback = new RemoteCertificateValidationCallback(CheckValidationResult);
            httpRequest = (HttpWebRequest)WebRequest.CreateDefault(new Uri(url));
        }
        else
        {
            httpRequest = (HttpWebRequest)WebRequest.Create(url);
        }
        httpRequest.Method = method;
        httpRequest.Headers.Add("Authorization", "APPCODE " + appcode);
        //根据API的要求，定义相对应的Content-Type
        httpRequest.ContentType = "application/json; charset=UTF-8";
        if (0 < bodys.Length)
        {
            byte[] data = Encoding.UTF8.GetBytes(bodys);
            using (Stream stream = httpRequest.GetRequestStream())
            {
                stream.Write(data, 0, data.Length);
            }
        }
        try
        {
            httpResponse = (HttpWebResponse)httpRequest.GetResponse();
        }
        catch (WebException ex)
        {
            httpResponse = (HttpWebResponse)ex.Response;
        }

        Console.WriteLine(httpResponse.StatusCode);
        Console.WriteLine(httpResponse.Method);
        Console.WriteLine(httpResponse.Headers);
        Stream st = httpResponse.GetResponseStream();
        StreamReader reader = new StreamReader(st, Encoding.GetEncoding("utf-8"));
        Console.WriteLine(reader.ReadToEnd());
        Console.WriteLine("\n");

    }

    public static bool CheckValidationResult(object sender, X509Certificate certificate, X509Chain chain, SslPolicyErrors errors)
    {
        return true;
    }
}