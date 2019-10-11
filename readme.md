## 简介

这是云天河多年来整理来的通用工具库  
下面是一些示例使用方法[完整测试代码在 `tests/test_file.php` 中]  
其中 `Larvarl` 目录,只针对这一款框架使用,已齐全注释,如需使用,请自行查看  

## 安装方法

~~~bash
composer require haleyleozhang/php_libary
~~~

## 部分示例

~~~php
<?php
require __DIR__ . '/../vendor/autoload.php';

use HaleyLeoZhang\Helpers\ArrayHelper;
use HaleyLeoZhang\Helpers\CurlRequest;
use HaleyLeoZhang\Helpers\ExpectValue;
use HaleyLeoZhang\Helpers\Filter;
use HaleyLeoZhang\Helpers\Location;
use HaleyLeoZhang\Helpers\Token;
use HaleyLeoZhang\ThirdApi\ExpressDeliveryApi;
use HaleyLeoZhang\ThirdApi\KugouMusicApi;
use HaleyLeoZhang\ThirdApi\TuringRobotApi;

class App
{
    public function run()
    {
        $this->token();
        $this->___gap();

        $this->expect_value();
        $this->___gap();

        $this->filter();
        $this->___gap();

        $this->get_ip();
        $this->___gap();

        $this->array_helper();
        $this->___gap();

        // ------------------- API类示例,请手动解开注释测试 -------------------

        // $this->curl(); // 示例上传图片到 sm.ms
        // $this->___gap();

        // $this->delivery(); // 快递查询
        // $this->___gap();

        // $this->kugou(); // 酷狗音乐
        // $this->___gap();

        // $this->turning(); // 图灵机器人
        // $this->___gap();

    }
    public function token()
    {
        echo "---- 测试随机数 ---- 即将输出: " . Token::rand_str();
    }
    public function expect_value()
    {
        $values = [5, 7, 9, 10, 6, 77, 1, 6, 33, 99, 1, 4, 7, 1, 5, 7, 20];
        $expect = ExpectValue::compute($values);
        echo "预期值: " . $expect;
    }
    public function filter()
    {
        $param = [
            'id'   => 1,
            'name' => 'I am the bone of my sword',
        ];
        try {
            Filter::request($param, ['field' => 'id', 'type' => 'int', 'min' => 1, 'require' => true]);
            Filter::request($param, ['field' => 'name', 'type' => 'string', 'min' => 1, 'max' => '10', 'require' => true]);
            $param = Filter::get_filter_data(); // 获取验证后的信息
        } catch (\Exception $exception) {
            echo '---入参验证---' . PHP_EOL;
            echo '验证信息: ' . $exception->getMessage() . PHP_EOL;
            echo '错误码: ' . $exception->getCode();
        }
    }
    public function get_ip()
    {
        $ip = Location::get_ip(); // 没有经过 SERVER 的情况下 获取不到
        echo '---当前IP---' . $ip . PHP_EOL;
    }
    public function array_helper()
    {
        $raw_array = [
            ["a" => 'value_1', "b" => '55'],
            ["a" => 'value_2', "b" => '66'],
        ];
        $target_array = ArrayHelper::group_by_key($raw_array, 'a');
        echo '---当前数组---' . PHP_EOL;
        var_dump($target_array);
    }
    // API 类
    public function curl()
    {
        echo '---正在上传---请稍候' . PHP_EOL;
        $image     = new \CURLFile(__DIR__ . '/for_upload.jpg');
        $base_api  = 'https://sm.ms/api/upload'; // 详见 API 文档 https://sm.ms/doc/
        $get_param = [
            'inajax' => 1,
            'ssl'    => 1,
        ];
        $post_param = [ // form-data 形式上传,不需要再 http_build_query 了
            'smfile' => $image,
        ];
        $header = [
            "referer: https://sm.ms/",
            "user-agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0",
            "x-requested-with: XMLHttpRequest",
            'x-forward-for: 154.34.6.54',
        ];
        $api     = $base_api . '?' . http_build_query($get_param);
        $content = CurlRequest::run($api, $post_param, $header);
        echo '---当前上传结果---' . PHP_EOL;
        echo $content;
    }
    public function delivery()
    {
        echo '---正在拉取快递信息---' . PHP_EOL;
        try {
            $track_number = 'YT4126633106250';
            $list         = ExpressDeliveryApi::run($track_number);
            echo '---快递信息如下---' . PHP_EOL;
            var_dump($list);
        } catch (\Exception $exception) {
            echo '---快递信息拉取.失败---' . PHP_EOL;
            echo '异常:' . $exception->getMessage() . PHP_EOL;
            echo '编号:' . $exception->getCode() . PHP_EOL;
            echo '文件:' . $exception->getFile() . PHP_EOL;
            echo '行数:' . $exception->getLine() . PHP_EOL;
        }
    }
    public function kugou()
    {
        echo '---正在获取歌曲信息---' . PHP_EOL;
        try {
            $keyword  = '刚好遇见你';
            $singer   = '曲肖冰';
            $play_url = KugouMusicApi::run($keyword, $singer);
            echo '---播放地址如下---' . PHP_EOL;
            echo $play_url;
        } catch (\Exception $exception) {
            echo '---获取歌曲信息.失败---' . PHP_EOL;
            echo '异常:' . $exception->getMessage() . PHP_EOL;
            echo '编号:' . $exception->getCode() . PHP_EOL;
            echo '文件:' . $exception->getFile() . PHP_EOL;
            echo '行数:' . $exception->getLine() . PHP_EOL;
        }
    }
    public function turning()
    {
        echo '---正在与图灵机器人对话---' . PHP_EOL;
        try {
            $trans_id = Token::uuid();
            $question = '你叫什么名字';
            $res      = TuringRobotApi::get_instance()
                ->set_trans_id($trans_id)
                ->set_sentence($question)
                ->request(TuringRobotApi::API_TYPE_PUBLIC);
            echo '---与图灵机器人对话结果如下---' . PHP_EOL;
            var_dump($res);
        } catch (\Exception $exception) {
            echo '---与图灵机器人对话.失败---' . PHP_EOL;
            echo '异常:' . $exception->getMessage() . PHP_EOL;
            echo '编号:' . $exception->getCode() . PHP_EOL;
            echo '文件:' . $exception->getFile() . PHP_EOL;
            echo '行数:' . $exception->getLine() . PHP_EOL;
        }
    }

    // ------------------------------------- Lib -------------------------------------

    public function __construct()
    {
        //
    }
    protected function ___gap()
    {
        echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
    }
}
$app = new App();
$app->run();
~~~
