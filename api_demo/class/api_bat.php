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
    /*
     * 都道府県別/形式別のURLデータ / 毎日更新有り
     * http://animemap.net/pages/api/
     */
    //const API_URL = 'http://animemap.net/api/table/hokkaidou.json';
    const API_URL = 'http://animemap.net/api/table/kyoto.json';

    public function __construct()
    {
        $this->redis = new Predis\Client(REDIS_URL);
        $this->redis->auth(REDIS_PW);
        $this->redis->select(REDIS_DB_NUM);//接続DB選択0～n / 一柳が指示した番号に変更の上作業進めてください
    }

    public function __destruct()
    {
        $this->redis->quit();
    }

    public function fetch_api_data()
    {
        include_once "{$_SERVER['DOCUMENT_ROOT']}/api_data/api_json.php";

        $pref_key = "kyoto";
        //$this->redis->del($pref_key);
        
        $json = file_get_contents($api_url_hash[$pref_key]);
        $json_hash = json_decode($json, true);

        if(! is_array($json_hash)) throw new Exception ("APIデータ未取得" . $api_url_hash[$pref_key]);

        //itemが複数ある場合と、単一の場合でデータ構造が異なるので整える
        if(isset($json_hash["response"]["item"]["title"])){
            $item_ary[0] = $json_hash["response"]["item"];//番組が単一のとき
        }else{
            $item_ary = $json_hash["response"]["item"];//番組が複数のとき
        }

        $week = [];
        foreach($item_ary as $item){
            $dow = mb_substr($item["week"], 0, 1);
            $week[$dow][] = $item;
        }

        if(count($week) === 0) throw new Exception("曜日ごとデータ未生成");

        foreach($week as $key => $hash){
            $json = json_encode($hash);
            $this->redis->hset($pref_key, $key, $json);
        }
        
    }

    public function confirm_redis()
    {
        print_r($this->redis->hgetall("kyoto"));
    }

}
