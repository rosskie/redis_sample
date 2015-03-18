<?php
/**
 * redis操作デモクラス
 * 
 * 基本的にredisのコマンド名と同じメソッド名になっている
 * redisコマンドはリファレンス参照
 * http://redis.shibu.jp/commandreference/
 *
 * @author ichiyanagi
 * @create 2015/03/18
 */
class RedisDemo
{
    //パラメータ
    private $redis;

    /* パブリック */
    public function __construct()
    {
        $this->redis = new Predis\Client(REDIS_URL);
        $this->redis->auth(REDIS_PW);
        $this->redis->select(0);//接続DB選択0～n
    }

    public function __destruct()
    {
        $this->redis->quit();
    }

    /**
     * 文字列型の動作サンプル
     * @return string
     */
    public function runString()
    {
        $key = "sting_key";
        $this->redis->del($key);

        //データ入力
        $this->redis->set($key, "あ");

        //データ参照
        $get = $this->redis->get($key);
        $this->redis->append($key, "いうえお");
        $append = $this->redis->get($key);

        return sprintf(
            "get<br/>%s<hr>append後<br/>%s<hr>",
            $get, $append
            );
    }

    /**
     * リスト型の動作サンプル
     * @return string
     */
    public function runList()
    {
        $key = "list_key";
        $this->redis->del($key);

        //データセット
        $this->redis->rpush($key, "あ");
        $this->redis->rpush($key, "い");
        $this->redis->rpush($key, "う");

        //データ取り出し
        $length = $this->redis->llen($key);
        $range = $this->redis->lrange($key, 0, $length);

        $list = "";
        for ($i = 0; $i < $length; $i++) {
            // 先頭から値を取り出す
            $list .= $this->redis->lindex($key, $i) . "<br>";
        }

        return sprintf(
            "長さ<br/>%s<hr>全要素<br/>%s<hr>要素取り出し<br>%s",
            $length, print_r($range, true), $list
            );
    }

    /**
     * セット型の動作サンプル
     * @return string
     */
    public function runSet()
    {
        $key_a = "set_a_key";
        $key_b = "set_b_key";
        $this->redis->del($key_a);
        $this->redis->del($key_b);
        
        //データセット
        //グループA
        $this->redis->sadd($key_a, "あ");
        $this->redis->sadd($key_a, "あ");//重複要素は登録されない
        $this->redis->sadd($key_a, "い");
        $this->redis->sadd($key_a, "う");

        //グループB
        $this->redis->sadd($key_b, "あ");
        $this->redis->sadd($key_b, "か");
        $this->redis->sadd($key_b, "さ");

        //データ取り出し
        $card = $this->redis->scard($key_a);
        $members = $this->redis->smembers($key_a);
        $inter = $this->redis->sinter($key_a, $key_b);//積集合
        $union = $this->redis->sunion($key_a, $key_b);//和集合
        $diff = $this->redis->sdiff($key_a, $key_b);//差集合

        return sprintf(
            "要素数<br>%s<hr>グループAの全要素<br>%s<hr>積集合<br>%s<hr>和集合<br>%s<hr>差集合<br>%s<hr>",
            $card,
            print_r($members, true),
            print_r($inter, true),
            print_r($union,true),
            print_r($diff, true)
            );
    }

    /**
     * ソート済みセットの動作サンプル
     * @return string
     */
    public function runSorted()
    {
        $z_key = "sorted_key";
        $this->redis->del($z_key);

        //データセット
        $array = ["あ","い","う","え","お","か","き","く","け","こ"];
        foreach($array as $key=> $val){
            $this->redis->zadd($z_key, $key, $val);
        }
        
        //データ取り出し
        $range = $this->redis->zrevrange($z_key, 0, 2);
        $score = $this->redis->zscore($z_key, "こ");
        $rank = $this->redis->zrevrank($z_key, "こ");
        $this->redis->zadd($z_key, $key, $val);

        $this->redis->zadd($z_key, 10, "あ");//スコア変更
        $change_range = $this->redis->zrevrange($z_key, 0, 2);

        return sprintf(
            "「こ」のスコア<br>%s<hr>「こ」のランク<br>%s<hr>スコア順ベスト3<br>%s<hr>変更後のスコア順<br>%s<hr>",
            $score,
            $rank,
            print_r($range, true),
            print_r($change_range, true)
            );
    }

    /**
     * ハッシュ型の動作サンプル
     * @return string
     */
    public function runHash()
    {
        $key = "hash_key";
        $this->redis->del($key);

        //データセット
        $this->redis->hset($key, "aaa", "あ");
        $this->redis->hset($key, "bbb", "い");
        $this->redis->hset($key, "ccc", "う");
        
        //データ取り出し
        $length = $this->redis->hlen($key);
        $get = $this->redis->hget($key, "aaa");
        $mget = $this->redis->hmget($key, "aaa", "bbb");
        $keys = $this->redis->hkeys($key);
        $vals = $this->redis->hvals($key);

        return sprintf(
            "length<br>%s<hr>get<br>%s<hr>mget<br>%s<hr>keys<br>%s<hr>vals<br>%s<hr>",
            $length,
            $get,
            print_r($mget,true),
            print_r($keys, true),
            print_r($vals, true)
            );
    }

    /* プライベート */
}
