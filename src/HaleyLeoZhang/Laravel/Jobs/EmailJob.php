<?php

namespace HaleyLeoZhang\Laravel\Jobs;

// ----------------------------------------------------------------------
// 邮件队列服务
// ----------------------------------------------------------------------
// Link  : http://www.hlzblog.top/
// GITHUB: https://github.com/HaleyLeoZhang
// ----------------------------------------------------------------------

use HaleyLeoZhang\LaravelTool\Smtp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// use Log;

class EmailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * 队列名，常用启动命令如下
     * php artisan queue:listen --queue=队列名（多个，以逗号隔开） --tries=3 --delay=5 --timeout=120
     * 最好是通过 supervisor 去运行 并且保持 与 运行 nginx 的用户相同
     */
    const QUEUE_NAME = 'email_job';

    /**
     * 任务类型声明
     */
    const TYPE_SEND_TEXT = 1; // 发送普通文本内容
    const TYPE_SEND_HTML = 2; // 发送HTML内容

    protected $type;
    protected $object;

    /**
     * 数据注入
     * @param string $type 发送类型
     * @param string $json_str json字符串，默认为 null
     * @return void
     */
    public function __construct($type, $json_str = null)
    {
        $this->type   = $type;
        $this->object = json_decode($json_str);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log::info('EmailJob.type.doing');
        switch ($this->type) {
            case self::TYPE_SEND_HTML:
                $this->send_html_content();
                break;
            case self::TYPE_SEND_TEXT:
                $this->send_to_one();
                break;
            default:
                // Log::info('EmailJob.queue.empty');
                break;
        }
    }

    /**
     * 发送HTML内容
     * HTML 内容，得先从json字符串中解码
     */
    public function send_html_content()
    {
        $this->object->content = htmlspecialchars_decode($this->object->content);
        $this->send_to_one();
    }

    /**
     * 邮件发送给一个人
     */
    public function send_to_one()
    {
        $to            = $this->object->to;
        $title         = $this->object->title;
        $content       = $this->object->content;
        $path          = $this->object->path ?? '';
        $set_file_name = $this->object->set_file_name ?? '';
        $send_status   = Smtp::run($to, $title, $content, $path, $set_file_name);
        // Log::info('EmailJob.result.', [
        //     // 'data'        => $this->object,
        //     'send_status' => $send_status,
        // ]);
    }

}
