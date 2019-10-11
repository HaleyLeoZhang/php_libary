<?php

namespace HaleyLeoZhang\Laravel\Caches;

/**
 * 大事记
 */
class MemorabiliaCache extends BaseCache
{

    /**
     * 缓存键值前缀
     */
    public static $cache_prefix = 'memorabilia_bg';

    /**
     * 缓存时间，单位，秒
     */
    public static $cache_ttl = 60 * 6;

    /**
     * 缓存值类型
     */
    public static $cache_type = parent::CACHE_TYPE_STRING;
}



// public static function ini_memorabilia_cache()
// {
//     $keyword = '刚好遇见你';
//     $singer  = '曲肖冰';
//     $bg_url  = KugouMusicApiSerivce::run($keyword, $singer);
//     $res     = MemorabiliaCache::set_cache_info('gang_hao_yu_jian_ni', $bg_url);
//     \LogService::info('刚好遇见你-曲肖冰-----', compact('bg_url', 'res'));
//     return $bg_url;
// }


// public static function get_memorabilia_cache()
// {
//     $bg_url = MemorabiliaCache::get_cache_info('gang_hao_yu_jian_ni');
//     return $bg_url;
// }
