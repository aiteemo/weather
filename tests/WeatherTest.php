<?php
/**
 * Created by PhpStorm.
 * User: jinfengchen
 * Date: 2020/10/12
 * Time: 11:44 AM
 */

namespace Aiteemo\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use Aiteemo\Weather\Exceptions\HttpException;
use Aiteemo\Weather\Exceptions\InvalidArgumentException;
use Aiteemo\Weather\Weather;
use PHPUnit\Framework\TestCase;
use Mockery\Exception\NoMatchingExpectationException;

class WeatherTest extends TestCase
{
    public function testGetWeatherWithInvalidExtensions()
    {
        $weather = new Weather('');

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为：''
        $this->expectExceptionMessage('Invalid extensions value(base/all):foo');

        $weather->getWeather('北京', 'foo');

        $this->fail('Failed to assert getWeather throw Invalid extensions');
    }

    public function testGetWeatherWithInvalidOutput()
    {
        $weather = new Weather('');

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为：''
        $this->expectExceptionMessage('Invalid output value(JSON/XML):array');

        $weather->getWeather('北京', 'base', 'array');

        $this->fail('Failed to assert getWeather throw Invalid output');
    }

    public function testGetWeather()
    {
        /********* output:json ***************/
        // 创建模拟接口响应值
        $response = new Response(200, [], '{"status":"1"}');

        // 创建模拟 http client
        $client = \Mockery::mock(Client::class);

        $uri = 'https://restapi.amap.com/v3/weather/weatherInfo';

        //  2、封装 query 参数，并对空值进行过滤。
        $query = array_filter([
            'key'        => 'mk',
            'city'       => '北京',
            'extensions' => 'base',
            'output'     => 'JSON',
        ]);

        // 指定将会产生的行为
        $client->allows()->get($uri, [ 'query' => $query ])->andReturn($response);
        //$client->shouldReceive('get')->with($uri, [ 'query' => $query ])->andReturn($response);

        $weather = \Mockery::mock(Weather::class, [ 'mk' ])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);
        $this->assertSame('{"status":"1"}', $weather->getWeather('北京', 'base', 'JSON'));

        /********* output:xml ***************/
        $response = new Response(200, [], '<status>1</status>');

        // 创建模拟 http client
        $client = \Mockery::mock(Client::class);

        $uri = 'https://restapi.amap.com/v3/weather/weatherInfo';

        //  2、封装 query 参数，并对空值进行过滤。
        $query = array_filter([
            'key'        => 'mk',
            'city'       => '北京',
            'extensions' => 'all',
            'output'     => 'XML',
        ]);

        // 指定将会产生的行为
        $client->allows()->get($uri, [ 'query' => $query ])->andReturn($response);

        $weather = \Mockery::mock(Weather::class, [ 'mk' ])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);
        $this->assertSame('<status>1</status>', $weather->getWeather('北京', 'all', 'XML'));
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        // 创建模拟 http client
        $client = \Mockery::mock(Client::class);
        // 指定产生行为
        $client->allows()->get(new AnyArgs())->andThrow(new \Exception('request timeout'));
        // 创建模拟 weather
        $weather = \Mockery::mock(Weather::class, [ 'mk' ])->makePartial();
        // 指定 getHttpClient 返回信息
        $weather->allows()->getHttpClient()->andReturn($client);
        // 断言
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');
        $weather->getWeather('深圳');
    }
}
