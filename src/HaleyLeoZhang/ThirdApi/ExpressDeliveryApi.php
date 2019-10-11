<?php
namespace HaleyLeoZhang\ThirdApi;

// ----------------------------------------------------------------------
// 快递查询
// ----------------------------------------------------------------------
// Link  : http://www.hlzblog.top/
// GITHUB: https://github.com/HaleyLeoZhang
// ----------------------------------------------------------------------

use HaleyLeoZhang\Helpers\CurlRequest;

// use Log;

// 需要引入 云天河写的对应类

class ExpressDeliveryApi
{
    /**
     * @var 快递100 - 这家的可能不准,所以部分需要自己整合
     */
    const API_GET_COMPANY_NAME = 'https://www.kuaidi100.com/autonumber/autoComNum'; // 查询快递所属物流公司
    const TIMER                = 3; // 设置超时时间.单位,秒

    /**
     * 快递公司映射调用函数表
     */
    static $reflect_map = [
        'yuantong' => 'yuantong', // 圆通
    ];

    /**
     * @var 快递公司查询API
     */
    const SEARCH_KUAIDI100 = 'https://www.kuaidi100.com/query';
    const SEARCH_YUANTONG  = 'http://wap.yto.net.cn/api/trace/waybill';

    /**
     * 程序入口
     * @param string $tracking_number  快递单号
     * @return array
     */
    public static function run($tracking_number)
    {
        static $_ins = null;
        if (null === $_ins) {
            $_ins = new self();
        }
        CurlRequest::set_timeout_second(self::TIMER);

        $company = $_ins->get_tracking_company($tracking_number);

        $track_history = $_ins->check_map($company) ? 
            $_ins->dispatch_search($company, $tracking_number) :
            $_ins->search_kuaidi100($company, $tracking_number);
        return $track_history;
    }

    /**
     * 查看单号所属公司，拼接为 GET 参数
     * @return string
     */
    protected function get_tracking_company($tracking_number)
    {
        $get_param = [
            'text' => $tracking_number,
        ];
        // 参数过滤
        $api = self::API_GET_COMPANY_NAME . '?' . http_build_query($get_param); // 组装链接
        // 请求接口
        $content = CurlRequest::run($api);
        // 调试接口
        // Log::debug('get_status' . $content);
        $res = json_decode($content, true);
        if (count($res['auto'])) {
            $company = $res['auto'][0]['comCode'] ?? '';
            return $company;
        } else {
            throw new \Exception('暂无查询记录,请稍候再试');
        }
    }

    protected function check_map($company)
    {
        if (in_array($company, self::$reflect_map)) {
            return true;
        }
        return false;
    }

    protected function dispatch_search($company, $tracking_number)
    {
        $callable = 'search_' . $company;
        return $this->$callable($tracking_number);
    }

    // 快递100
    protected function search_kuaidi100($company, $tracking_number)
    {
        $query_param = [
            'type'   => $company, // 快递名
            'postid' => $tracking_number, // 快递单号
            'temp'   => microtime(true), // 防缓存参数
            'phone'  => '',
        ];

        $header = [
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
            'Cookie: csrftoken=v7EVYIIhFXZ7SLi-CcDpAzXPLcqctR9YoS3V71pXoyU; WWWID=WWW560EC8B537D0FF00FDBD31899A67D217; Hm_lvt_22ea01af58ba2be0fec7c11b25e88e6c=1570777201; Hm_lpvt_22ea01af58ba2be0fec7c11b25e88e6c=1570777201',
            'Host: www.kuaidi100.com',
            'Referer: https://www.kuaidi100.com/',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36',
            'X-Requested-With: XMLHttpRequest',
        ];

        $api = self::SEARCH_KUAIDI100 . '?' . http_build_query($query_param); // 拼接第一个接口

        $detail = CurlRequest::run($api);
        $detail = json_decode($detail, true);

        $message = $detail['message'] ?? 'failed';

        if ($message == 'ok') {
            $list = $detail['data'];
            return $list;
        }
        return [];
    }

    // 圆通
    protected function search_yuantong($tracking_number)
    {
        $api = self::SEARCH_YUANTONG; // 拼接第一个接口

        $post_data = [
            'waybillNo' => $tracking_number,
        ];

        $header = [
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Host: wap.yto.net.cn',
            'Origin: http://wap.yto.net.cn',
            'Referer: http://wap.yto.net.cn/tracesimple.html/',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36',
            'X-Requested-With: XMLHttpRequest',
        ];

        $content = CurlRequest::run($api, http_build_query($post_data), $header);
        $detail  = json_decode($content, true);

        $list = $detail['data'][0]['traces'] ?? [];
        return $list;
    }
}

/* 成功时，输出如下

[
{
"time": "2017-03-02 02:21:16",
"ftime": "2017-03-02 02:21:16",
"context": "\u6c5f\u95e8\u8f6c\u8fd0\u4e2d\u5fc3 \u5df2\u53d1\u51fa,\u4e0b\u4e00\u7ad9 \u6d4e\u5357\u8f6c\u8fd0\u4e2d\u5fc3",
"location": null
}, {
"time": "2017-03-02 01:41:59",
"ftime": "2017-03-02 01:41:59",
"context": "\u6c5f\u95e8\u8f6c\u8fd0\u4e2d\u5fc3 \u5df2\u6536\u5165",
"location": null
}
]

 */
