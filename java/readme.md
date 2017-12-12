# 使用方法

## APPCODE方式
打开src/test/java/APPCodeDemo.java，填写appcode， url，文件路径，设置body格式，配置字符串, 执行即可

## APPKEY方式
打开src/test/java/APPKeyDemo.java，填写app_key, app_secret, url，文件路径，设置body格式，配置字符串, 执行即可

# 编译运行

## 命令行


```
mvn compile
mvn exec:java -Dexec.mainClass="com.alibaba.ocr.demo.APPKeyDemo"
mvn exec:java -Dexec.mainClass="com.alibaba.ocr.demo.APPCodeDemo"
```

## IDE
