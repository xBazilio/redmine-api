<?php
/**
 * @author: mix
 * @date: 01.05.14
 */
namespace RedmineApi\Api;

use \RedmineApi\HttpClient;
use \RedmineApi\MysqlClient;

/**
 * Class Base
 *
 * @package Redmine\Api
 */
abstract class Base
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MysqlClient
     */
    private $accelerator;

    public function __construct(HttpClient $client,MysqlClient $accelerator = null) {
        $this->client = $client;
        $this->accelerator = $accelerator;
    }

    /**
     * performs a request
     *
     * @param $method
     * @param $url
     * @param array $data
     * @return mixed
     * @throws \RedmineApi\Exception
     */
    protected function request($method, $url, $data = []) {
        return $this->client->request($method, $url, $data);
    }

    /**
     * enable debug
     */
    public function enableDebug(){
        $this->client->enableDebug();
    }

    protected function accelerate($table, array $ids, $field='id'){
        if($this->accelerator){
            return $this->accelerator->request($ids, $table, $field);
        }
        return false;
    }

    /**
     * @param int[] $ids
     * @param callable $call
     * @return array
     */
    public function multiQuery(array $ids, callable $call){
        $result = [];
        foreach($ids as $id){
            $user = $call($id);
            if($user){
                $result[$id] = $user;
            }
        }
        return $result;
    }

    public function multiAccelerate($table, array $ids, callable $call, $field="id"){
        $result = $this->accelerate($table, $ids, $field);

        if ($result === false) {
            $result = $this->multiQuery($ids, $call);
        }
        return $result;
    }
}