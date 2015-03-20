<?php

/**
 * RedisにキャッシュされたAPIデータを取得表示
 * 
 * @author ichiyanagi
 * @create 2015/03/18
 */
class ApiView
{
    private $redis;
    private $table_line_format = <<<END
<tr>
    <th>%s</th>
    <td>%s</td>
</tr>
END;

    public function __construct()
    {
        $this->redis = new Predis\Client(REDIS_URL);
        $this->redis->auth(REDIS_PW);
        $this->redis->select(REDIS_DB_NUM); //接続DB選択0～n / 一柳が支持した番号に変更の上作業進めてください
    }

    public function __destruct()
    {
        $this->redis->quit();
    }

    public function output_table()
    {
        $table = "";
        $dow_list = ["日", "月", "火", "水", "木", "金", "土" ];
        foreach ($dow_list as $dow) {
            $view_data = $this->creta_dow_line($dow);
            $table .= sprintf($this->table_line_format, $dow, $view_data);
        }

        $table = <<<END
<h3>特定県の曜日ごと放送作品一覧</h3>
<table class="table table-bordered">{$table}</table>
END;
        return $table;
    }

    private function creta_dow_line($dow)
    {
        $item_hash = json_decode($this->get_dow_anime_list($dow), true);
        foreach ($item_hash as $item) {
            $view_data .= $item["title"] . "/" . $item["time"] . "<br>";
        }
        return $view_data;
    }

    private function get_dow_anime_list($field = "all")
    {
        $pref_key = "kyoto";
        if ($field === "all") {
            return $this->redis->hgetall($pref_key);
        } else {
            return $this->redis->hget($pref_key, $field);
        }
    }

}
