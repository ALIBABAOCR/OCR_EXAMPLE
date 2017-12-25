using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace aliyun_api_gateway_sdk.Util
{
    public class MessageDigestUtil
    {
        public static string Base64AndMD5(string input) {
            if (input == null || input.Length == 0)
            {
                throw new Exception("input can not be null");
            }
            byte[] bytes = Encoding.UTF8.GetBytes(input);
            return Base64AndMD5(bytes);
        }

        public static String Base64AndMD5(byte[] bytes) {
            MD5 md5 = new MD5CryptoServiceProvider();
            byte[] data = md5.ComputeHash(bytes);

            return Convert.ToBase64String(data);
        }

        /**
         * UTF-8编码转换为ISO-9959-1
         *
         * @param str
         * @return
         */
        public static String Utf8ToIso88591(String input)
        {
            if (input == null)
            {
                return input;
            }

            try
            {
                return Encoding.Default.GetString(Encoding.Convert(Encoding.UTF8, Encoding.GetEncoding("ISO-8859-1"), Encoding.UTF8.GetBytes(input)));
            }
            catch (Exception e)
            {
                return input;
            }
        }

    }
}
