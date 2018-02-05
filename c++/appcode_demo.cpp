#include <curl/curl.h>
#include <stdio.h>
#include <fstream>
#include <string>
#include "cppcodec/base64_default_rfc4648.hpp"
#include "json.hpp"

using json = nlohmann::json;

struct MemoryStruct {
  char *memory;
  size_t size;
};

static size_t writeMemoryCallback(void *contents, size_t size, size_t nmemb,
                                  void *userp) {
  size_t realsize = size * nmemb;
  struct MemoryStruct *mem = (struct MemoryStruct *)userp;

  mem->memory = realloc(mem->memory, mem->size + realsize + 1);
  if (mem->memory == NULL) {
    /* out of memory! */
    printf("not enough memory (realloc returned NULL)\n");
    return 0;
  }

  memcpy(&(mem->memory[mem->size]), contents, realsize);
  mem->size += realsize;
  mem->memory[mem->size] = 0;

  return realsize;
}

static std::string composeJson(const std::string &image_code,
                               const std::string &side, bool is_new_format) {
  json body;
  if (is_new_format) {
    body["configure"] = "{\"side\":\"" + side + "\"}";
    body["image"] = image_code;
  } else {
    json configure, image, combine;
    image["dataType"] = 50;
    image["dataValue"] = image_code;
    configure["dataType"] = 50;
    configure["dataValue"] = "{\"side\":\"" + side + "\"}";
    combine["configure"] = configure;
    combine["image"] = image;
    body["inputs"] = json::array({combine});
  }
  return body.dump();
}

static std::string encode(const std::string &file) {
  std::ifstream ifs(file, std::ios::binary | std::ios::in | std::ios::ate);
  std::string image_code;
  if (ifs.is_open()) {
    size_t image_size = ifs.tellg();
    char *image_data = new char[image_size];
    ifs.seekg(0, std::ios::beg);
    ifs.read(image_data, image_size);
    ifs.close();
    image_code = base64::encode(image_data, image_size);
    delete[] image_data;
  } else {
    printf("read file %s failed\n", file.c_str());
  }
  return image_code;
}

int main() {
  // 常规配置
  std::string url =
      "https://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
  std::string appcode = "你的Appcode";
  std::string image_file = "图片文件位置";
  // 新老json格式标记，如果json格式中含有inputs字段，则为老格式，默认使用新格式，请置为true
  bool is_new_format = true;

  // 身份证配置
  std::string side = "face";  // face or back

  std::string image_code = encode(image_file);
  std::string body = composeJson(image_code, side, is_new_format);

  // http post
  CURL *curl;
  CURLcode res;
  curl = curl_easy_init();
  if (curl) {
    struct MemoryStruct response;
    response.memory = malloc(1);
    response.size = 0;

    struct curl_slist *custom_header = NULL;
    custom_header = curl_slist_append(
        custom_header, std::string("Authorization:APPCODE " + appcode).c_str());
    custom_header = curl_slist_append(
        custom_header, "Content-Type:application/json; charset=UTF-8");
    res = curl_easy_setopt(curl, CURLOPT_HTTPHEADER, custom_header);

    curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
    curl_easy_setopt(curl, CURLOPT_POSTFIELDS, body.c_str());
    curl_easy_setopt(curl, CURLOPT_POSTFIELDSIZE, body.size());
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writeMemoryCallback);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, (void *)&response);
    // comment this line to ignore reponse header
    curl_easy_setopt(curl, CURLOPT_HEADER, 1L);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0);

    res = curl_easy_perform(curl);
    long http_code = 0;
    curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &http_code);
    if (res != CURLE_OK) {
      printf("post failed %s\n", curl_easy_strerror(res));
    } else {
      printf("http code: %d\n", http_code);
      printf("%s\n", response.memory);
    }

    curl_easy_cleanup(curl);
    free(response.memory);
    curl_slist_free_all(custom_header);
    curl_global_cleanup();
  }

  return 0;
}
