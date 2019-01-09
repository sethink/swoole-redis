```
v0.0.3
1、添加setOptions配置（swoole官方timeout设置"超时时间, 默认为全局的协程socket_timeout(-1, 永不超时)"。在使用brPop函数时，发现好似并未生效，timeout的设置必须大于brPop超时，否则会出现"Resource temporarily unavailable"）
2、添加断线重连功能
3、添加instance()函数，如果有特殊需求扩展无法实现，又想共用连接池时，譬如事务处理，此时可以通过instance获取一个连接
4、添加put()函数，配合instance使用，使用完连接后，将连接put回连接池里
5、添加异常处理
```