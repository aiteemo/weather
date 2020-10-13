<?php
/**
 * Created by PhpStorm.
 * User: jinfengchen
 * Date: 2020/10/10
 * Time: 2:45 PM
 */

namespace Aiteemo\Weather;

use Aiteemo\Weather\Exceptions\Exception;
use GuzzleHttp\Client;
use Aiteemo\Weather\Exceptions\HttpException;
use Aiteemo\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    protected $key;
    protected $guzzleHttpOptions = [];

    public function __construct($key)
    {
        $this->key = $key;
    }

    // get client
    public function getHttpClient()
    {
        return new Client($this->guzzleHttpOptions);
    }

    /**
     * 设置：guzzleHttpOptions
     *
     * @param array $options
     */
    public function setGuzzleHttpOptions(array $options)
    {
        $this->guzzleHttpOptions = $options;
    }

    /**
     * 获取天气
     *
     * @param string $city       城市名/adcode ，比如：“深 圳” 或者（adcode：440300)
     * @param string $extensions 可选值：base/all base:返回实况天气 all:返回预报天气
     * @param string $output     返回格式 可选值：JSON,XML
     * @throws
     * @return json $result;
     */
    public function getWeather($city, $extensions = 'base', $output = 'JSON')
    {
        $uri = 'https://restapi.amap.com/v3/weather/weatherInfo';

        // 1、检查 $extensions 与 $ouput 参数
        if(!in_array(strtoupper($output), [ 'XML', 'JSON' ])) {
            throw new InvalidArgumentException('Invalid output value(JSON/XML):' . $output);
        }
        if(!in_array(strtolower($extensions), [ 'base', 'all' ])) {
            throw new InvalidArgumentException('Invalid extensions value(base/all):' . $extensions);
        }

        //  2、封装 query 参数，并对空值进行过滤。
        $query = array_filter([
            'key'        => $this->key,
            'city'       => $city,
            'extensions' => strtolower($extensions),
            'output'     => strtoupper($output)
        ]);

        try {
            // 3、调用getHttpClient获取接口内容
            $response = $this->getHttpClient()->get($uri, [
                'query' => $query,
            ])->getBody()->getContents();
            return $response;
        } catch(\Exception $Exception) {
            // 4、调用异常时抛出
            throw new HttpException($Exception->getMessage(), $Exception->getCode(), $Exception);
        }
    }
}

/*require "/data/docker/www/test2/section_4_composer/weather/vendor/autoload.php";
$m = new Weather('1d6d10fc03d935042ceeee9eda128802');
echo $m->getWeather('北京');*/
