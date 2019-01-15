<?php
namespace App\Serializer;

class NumericAmountToDecimalString
{
    public static function convert($value) : string
    {
        $formattedValue = '';
        if($value >= 100) {
            $formattedValue = self::formatValueBiggerThanOne($value);
        } else {
            $formattedValue = self::formatValueLesserThanOne($value);
        }
        return $formattedValue;
    }

    private static function formatValueBiggerThanOne($value)
    {
        $firstPart = substr($value, 0, strlen($value)-2);
        $lastPart = substr($value, strlen($value)-2);
        return $firstPart.'.'.$lastPart;
    }

    private static function formatValueLesserThanOne($value)
    {
        $lastPart = $value;
        if(strlen($lastPart) == 1) {
            $lastPart .= '0';
        }
        return '0.'.$lastPart;
    } 

}
