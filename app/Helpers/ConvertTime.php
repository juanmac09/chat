<?php

namespace App\Helpers;

class ConvertTime
{
    /**
     * Convert a given number of years into seconds.
     *
     * @param int $years The number of years to be converted.
     *
     * @return int The number of seconds equivalent to the given years.
     */
    public static function convertYearToSeconds(int $years)
    {
        return $years * 31536000;
    }

    /**
     * Convert a given number of months into seconds.
     *
     * @param int $months The number of months to be converted.
     *
     * @return int The number of seconds equivalent to the given months.
     */
    public static function convertMonthToSeconds(int $months)
    {
        return $months * 2629746;
    }

    /**
     * Convert a given number of weeks into seconds.
     *
     * @param int $weeks The number of weeks to be converted.
     *
     * @return int The number of seconds equivalent to the given weeks.
     */
    public static function convertWeekToSeconds(int $weeks)
    {
        return $weeks * 604800;
    }

    /**
     * Convert a given number of days into seconds.
     *
     * @param int $days The number of days to be converted.
     *
     * @return int The number of seconds equivalent to the given days.
     */
    public static function convertDayToSeconds(int $days)
    {
        return $days * 86400;
    }

    /**
     * Convert a given number of hours into seconds.
     *
     * @param int $hours The number of hours to be converted.
     *
     * @return int The number of seconds equivalent to the given hours.
     */
    public static function convertHourToSeconds(int $hours)
    {
        return $hours * 3600;
    }

    /**
     * Convert a given number of time units into seconds.
     *
     * @param int $type The type of time unit to be converted.
     *                    1 - Years
     *                    2 - Months
     *                    3 - Weeks
     *                    4 - Days
     *                    5 - Hours
     * @param int $amount The number of time units to be converted.
     *
     * @return int The number of seconds equivalent to the given time units.
     */
    public static  function convertToSeconds(int $type, int $amount)
    {
        switch ($type) {
            case 1:
                return self::convertYearToSeconds($amount);
                break;

            case 2:
                return self::convertMonthToSeconds($amount);
                break;

            case 3:
                return self::convertWeekToSeconds($amount);
                break;

            case 4:
                return self::convertDayToSeconds($amount);
                break;

            case 5:
                return self::convertHourToSeconds($amount);
                break;
        }
    }
}
