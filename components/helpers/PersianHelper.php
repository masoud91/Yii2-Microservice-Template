<?php

namespace micro\components\helpers;

use yii\base\Component;

/**
 * Class MongoCastHelper
 * @package common\components
 */
class PersianHelper extends Component
{
    /**
     * @param $string
     * @return mixed
     */
    public static function faDigitToEnDigit($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨','٩'];

        $num = range(0, 9);
        $convertedPersianDigits = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianDigits);

        return $englishNumbersOnly;
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function EnDigitToFaDigit($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);

        $convertedEnglishDigits = str_replace($num, $persian, $string);
        return $convertedEnglishDigits;
    }

    /**
     * convert mobile format
     * valid formats: 09-------- and 913-------
     *
     *
     * @param $mobile
     * @return bool|string
     */
    public static function phoneFormat($mobile){

        $mobile = str_replace('+', '', $mobile);

        if (!self::isInternationalMobileFormat($mobile)) {
            if (preg_match('/^09[0-9]{9}/', $mobile)) {
                return '98' . substr($mobile, 1);
            } else if (preg_match('/^9[0-9]{9}/', $mobile)) {
                return '98' . $mobile;
            }
        } else {
            return $mobile;
        }

        return false;
    }

    public static function phoneDisplayFormat($mobile) {
        if (self::isInternationalMobileFormat($mobile)) {
            return '0' . substr($mobile, 2);
        }

        return false;
    }

    /**
     * @param $mobile
     * @return bool|string
     */
    public static function InternationalFormatPlus($mobile){
        if( !$formattedMobile = self::phoneFormat($mobile) ) {
            return false;
        }

        return '+' . $formattedMobile;
    }

    /**
     * @param $mobile
     * @return false|int
     */
    public static function isInternationalMobileFormat($mobile){
        $mobile = str_replace('+', '', $mobile);
        return preg_match('/989[0-9]{1}[0-9]{8}$/', $mobile);
    }

    /**
     * @param $mobile
     * @return mixed
     */
    public static function MobileObfuscate($mobile){
        $mobile = self::InternationalFormatPlus($mobile);
        $mobile = substr_replace($mobile, '09', 0, 4);
        return substr_replace($mobile, '***', 6, 3);
    }

    /**
     * @param $month
     * @return string
     */
    public static function getMonthName($month) {
        $name = '';
        switch ($month) {
            case '۰۱':
                $name = 'فروردین';
                break;
            case '۰۲':
                $name = 'اردیبهشت';
                break;
            case '۰۳':
                $name = 'خرداد';
                break;
            case '۰۴':
                $name = 'تیر';
                break;
            case '۰۵':
                $name = 'مرداد';
                break;
            case '۰۶':
                $name = 'شهریور';
                break;
            case '۰۷':
                $name = 'مهر';
                break;
            case '۰۸':
                $name = 'آبان';
                break;
            case '۰۹':
                $name = 'آذر';
                break;
            case '۱۰':
                $name = 'دی';
                break;
            case '۱۱':
                $name = 'بهمن';
                break;
            case '۱۲':
                $name = 'اسفند';
                break;
        }

        return $name;
    }
}