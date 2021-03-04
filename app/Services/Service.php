<?php


namespace App\Services;


use App\Traits\ExceptionTrait;

class Service
{
    use ExceptionTrait;

    static $instances = [];

    /**
     * 获取单例
     * @param  mixed  ...$args
     * @return  static
     */
    public static function instance(...$args)
    {
        if (!isset(self::$instances[static::class])) {
            self::$instances[static::class] = new static(...$args);
        }
        return self::$instances[static::class];
    }

    /**
     * 获取新的实例
     * @param  mixed  ...$args
     * @return static
     */
    public static function getOneInstance(...$args)
    {
        return new static(...$args);
    }
}
