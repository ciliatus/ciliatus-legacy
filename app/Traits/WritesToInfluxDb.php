<?php

namespace App\Traits;


use Carbon\Carbon;
use InfluxDB\Client as InfluxDbClient;
use InfluxDB\Database;
use InfluxDB\Point as InfluxDbPoint;

/**
 * Trait WritesToInfluxDb
 * @package App\Traits
 */
trait WritesToInfluxDb
{

    /**
     * @var null
     */
    private $influx_client = null;

    /**
     * @var null
     */
    private $influx_database = null;

    /**
     * @var array
     */
    private $config = null;

    /**
     * @param $metric_name
     * @param $value
     * @param array $tags
     * @param array $additional_fields
     * @param null $time
     * @return bool
     */
    public function writeToInfluxDb($metric_name, $value, $tags = [], $additional_fields = [], $time = null)
    {

        if (is_null($this->config)) {
            $this->loadInfluxConfig();
        }

        if (!$this->isInfluxDbEnabled()) {
            return false;
        }

        if (is_null($this->influx_client)) {
            $this->connectInfluxDb();
        }

        if (is_null($time)) {
            $time = Carbon::now()->timestamp;
        }

        $point = new InfluxDbPoint(
            $metric_name,
            $value,
            $tags,
            $additional_fields,
            $time
        );

        return $this->influx_database->writePoints([$point], Database::PRECISION_SECONDS);
    }

    /**
     * @return bool
     */
    protected function isInfluxDbEnabled()
    {
        return !is_null($this->config['host'])
            && !is_null($this->config['db']);
    }

    /**
     * @return array
     */
    protected function loadInfluxConfig()
    {
        $this->config = self::getInfluxConfig();
    }

    /**
     *
     */
    protected function connectInfluxDb()
    {
        $this->influx_client = self::getInfluxClient($this->config);

        $this->influx_database = self::getInfluxDatabase($this->influx_client, $this->config);
    }

    /**
     * @return array
     */
    protected static function getInfluxConfig()
    {
        return [
            'host'  => env('INFLUX_HOST', null),
            'port'  => env('INFLUX_PORT', 8086),
            'db'    => env('INFLUX_DB', null),
            'user'  => env('INFLUX_USER', null),
            'pass'  => env('INFLUX_PASS', null)
        ];
    }

    /**
     * @param array $config
     * @return InfluxDbClient
     */
    protected static function getInfluxClient(array $config)
    {
        return new InfluxDbClient(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['pass'],
            true
        );
    }

    /**
     * @param InfluxDbClient $influx_client
     * @param array $config
     * @return Database
     */
    protected  static function getInfluxDatabase(InfluxDbClient $influx_client, array $config)
    {
        return $influx_client->selectDB($config['db']);
    }

}