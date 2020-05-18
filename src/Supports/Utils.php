<?php
/**
 * Created by PhpStorm.
 * Filename: Utils.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 12:02 上午
 */

namespace JwtLibrary\Supports;

use Carbon\Carbon;

class Utils
{
    /**
     * Application current time zone
     *
     * @var
     */
    public static $tz = 'PRC';

    /**
     * Set the Application current time zone
     *
     * @param string $tz
     *
     * @return Utils
     */
    public static function setCurrentTimeZone(string $tz): Utils
    {
        static::$tz = $tz;
        return new static();
    }

    /**
     * Get the Carbon instance for the current time.
     *
     * @return Carbon
     */
    public static function now(): Carbon
    {
        return Carbon::now(static::$tz);
    }

    /**
     * Get the Carbon instance for the timestamp.
     *
     * @param int $timestamp
     *
     * @return Carbon
     */
    public static function timestamp(int $timestamp): Carbon
    {
        return Carbon::createFromTimestamp($timestamp, static::$tz);
    }

    /**
     * Checks if a timestamp is in the past.
     *
     * @param int $timestamp
     * @param int $leeway
     *
     * @return bool
     */
    public static function isPast(int $timestamp, int $leeway = 0): bool
    {
        $timestamp = static::timestamp($timestamp);

        return $leeway > 0
            ? $timestamp->addSeconds($leeway)->isPast()
            : $timestamp->isPast();
    }

    /**
     * Checks if a timestamp is in the future.
     *
     * @param int $timestamp
     * @param int $leeway
     *
     * @return bool
     */
    public static function isFuture(int $timestamp, int $leeway = 0): bool
    {
        $timestamp = static::timestamp($timestamp);

        return $leeway > 0
            ? $timestamp->subSeconds($leeway)->isFuture()
            : $timestamp->isFuture();
    }
}