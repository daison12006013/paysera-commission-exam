<?php

declare(strict_types=1);

namespace Daison\Paysera\Services;

class Math
{
    /**
     * Undocumented function.
     *
     * @param string|float $num1
     * @param string|float $num2
     *
     * @return string
     */
    public static function add($num1, $num2, int $precision = 10)
    {
        return bcadd((string) $num1, (string) $num2, $precision);
    }

    /**
     * Undocumented function.
     *
     * @param string|float $num1
     * @param string|float $num2
     *
     * @return string
     */
    public static function sub($num1, $num2, int $precision = 10)
    {
        return bcsub((string) $num1, (string) $num2, $precision);
    }

    /**
     * Undocumented function.
     *
     * @param string|float $num1
     * @param string|float $num2
     *
     * @return string
     */
    public static function mul($num1, $num2, int $precision = 10)
    {
        return bcmul((string) $num1, (string) $num2, $precision);
    }

    /**
     * Undocumented function.
     *
     * @param string|float $num1
     * @param string|float $num2
     *
     * @return string
     */
    public static function div($num1, $num2, int $precision = 10)
    {
        return bcdiv((string) $num1, (string) $num2, $precision);
    }

    /**
     * Undocumented function.
     *
     * @param string|float $number
     *
     * @return string}float
     */
    public static function roundUp($number, int $precision = 2)
    {
        return number_format(
            ceil($number * pow(10, $precision)) / pow(10, $precision),
            2
        );
    }
}
