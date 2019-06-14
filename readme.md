## 简介

这是云天河多年来整理来的通用工具库  
后续将会给出对应类库的使用方法  

目录如下  

    .
    ├── composer.json
    ├── readme.md
    ├── src
    │   └── HaleyLeoZhang
    │       ├── Cdn
    │       │   ├── BaseCdn.php
    │       │   └── SmCdn.php
    │       ├── Helpers
    │       │   ├── CommonTool.php
    │       │   ├── CurlRequest.php
    │       │   ├── ExpectValue.php
    │       │   ├── Location.php
    │       │   └── Token.php
    │       ├── Laravel
    │       │   ├── Caches
    │       │   │   ├── BaseCache.php
    │       │   │   └── CacheExample
    │       │   ├── Crypt
    │       │   │   ├── BaseCrypt.php
    │       │   │   └── RsaCrypt.php
    │       │   ├── Helpers
    │       │   │   └── DistributedLock.php
    │       │   ├── Jobs
    │       │   │   ├── EmailJob.php
    │       │   │   └── Job.php
    │       │   ├── Libs
    │       │   │   ├── HTMLPurifier
    │       │   │   └── Smtp
    │       │   └── Tool
    │       │       ├── Export.php
    │       │       ├── Filter.php
    │       │       ├── Log.php
    │       │       └── Smtp.php
    │       ├── OAuth2
    │       │   ├── BaseOAuth2.php
    │       │   ├── Github
    │       │   │   └── ApiService.php
    │       │   ├── GithubOAuth2.php
    │       │   ├── Qq
    │       │   │   └── ApiService.php
    │       │   ├── QqOAuth2.php
    │       │   ├── Sina
    │       │   │   └── ApiService.php
    │       │   ├── SinaOAuth2.php
    │       │   ├── Wechat
    │       │   │   └── ApiService.php
    │       │   └── WechatOAuth2.php
    │       └── ThirdApi
    │           ├── ExpressDeliveryApi.php
    │           ├── KugouMusicApi.php
    │           └── TuringRobotApi.php
    ├── tests
    │   └── test_upload.php


各个部分 使用方法，请稍候