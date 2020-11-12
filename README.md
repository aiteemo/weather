<h1 align="center"> weather </h1>

<p align="center"> 基于高德开放平台的PHP天气信息组件。</p>
[![Build Status](https://travis-ci.org/aiteemo/weather.svg?branch=main)

## 🏷Installing

```shell
$ composer require aiteemo/weather dev-main
```

## 🏷配置
在使用本扩展之前，你需要去 注册账号，然后创建应用，获取应用的 API Key。
## 🏷使用

```php
<?php
use Aiteemo\Weather\Weather;
$key        = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$weather    = new Weather($key);
```
### 获取实时天气
```php
<?php
use Aiteemo\Weather\Weather;
$key      = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$weather  = new Weather($key);

$response = $weather->getWeather('深圳');

// 或者直接调用：getLiveWeather（version >= 0.0.2）
// $response = $weather->getLiveWeather('深圳'); 
```

### 获取近期天气预报
```php
<?php
use Aiteemo\Weather\Weather;
$key      = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$weather  = new Weather($key);

$response = $weather->getWeather('深圳', 'all');

// 或者直接调用：getForecastsWeather（version >= 0.0.2）
// $response = $weather->getForecastsWeather('深圳'); 
```
### 返回格式
第三个参数为返回值类型，可选 json 与 xml ，默认json ：
```php
<?php
use Aiteemo\Weather\Weather;
$key      = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$weather  = new Weather($key);
$response = $weather->getWeather('深圳', 'all', 'xml');
```

### 参数说明
<li>key         请求服务权限标识用户在高德地图官网申请web服务API类型KEY,必填</li>
<li>city        城市编码输入城市的adcode，adcode信息可参考城市编码表,必填</li>
<li>extensions  气象类型,可选值：base/all,base:返回实况天气,all:返回预报天气,可选</li>
<li>output      返回格式,可选值：JSON,XML,可选</li>

## 🏷参考
高德开放平台天气接口

## 🏷License
MIT
