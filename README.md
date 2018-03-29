## 简介

 是一个免费开源的，快速、简单的面向对象的 轻量级PHP开发框架 。

## nginx伪静态配置
    location / {
        if ( !-e $request_filename ) {
            #rewrite .*\..* /404.html last;
            rewrite ^(.*)/?$ /index.php?$1 last;
        }
    }
