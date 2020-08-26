<?php

namespace App\Helper;

use Carbon\CarbonImmutable;

/**
 * Class DateTimeHelper
 */
class DateTimeHelper
{
    /**
     * 计算年龄
     *
     * @param string $birthday
     *
     * @return int
     */
    public static function age($birthday)
    {
        return formatDate('Y') - substr($birthday, 0, 4);
    }

    /**
     * 计算两个时间相差几个月
     *
     * @param int $date1 UNIX 时间戳
     * @param int $date2 UNIX 时间戳
     *
     * @return int
     */
    public static function monthDiff(int $date1, int $date2)
    {
        return CarbonImmutable::createFromTimestampUTC($date1)
            ->diffInMonths(CarbonImmutable::createFromTimestampUTC($date2));
    }

    /**
     * 当前月往过去、往将来 $num 个月
     *
     * @param int $num       <0，表示往过去；>0，表示往将来
     * @param int $timestamp UNIX 时间戳
     *
     * @return int UNIX 时间戳
     */
    public static function addMonths(int $num, int $timestamp = TIMENOW)
    {
        return CarbonImmutable::createFromTimestampUTC($timestamp)
            ->addMonths($num)->timestamp;
    }
}
