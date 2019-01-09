```
v0.0.3
1、添加setOptions配置（swoole官方timeout设置"超时时间, 默认为全局的协程socket_timeout(-1, 永不超时)"。在使用brPop函数时，发现好似并未生效，timeout的设置必须大于brPop超时，否则会出现"Resource temporarily unavailable"）
2、添加断线重连功能
```