<?php
/**
 * API取得バッチ処理
 * 
 * @author ichiyanagi
 * @create 2015/03/18
 */

class ApiBat
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Predis\Client(REDIS_URL);
        $this->redis->auth(REDIS_PW);
        $this->redis->select(0);//接続DB選択0～n / 一柳が支持した番号に変更の上作業進めてください
    }

    public function __destruct()
    {
        $this->redis->quit();
    }

}
