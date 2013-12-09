<?php

class Icepay_Order_VAT {

    private static $categories = array();

    public static function setDefaultCategories() {
        $ranges = array(
            'zero' => 0,
            'reduced-low' => array('1', '6'),
            'reduced-middle' => array('7', '12'),
            'standard' => array('13', '100')
        );

        self::setCategories($ranges);
    }

    public static function setCategories($array) {
        self::$categories = $array;
    }

    public static function getCategories() {
        return self::$categories;
    }

    public static function getCategory($name) {
        return self::$categories[$name];
    }

    public static function getCategoryForPercentage($number = null, $default = "exempt") {
        if (!self::$categories)
            self::setDefaultCategories();

        foreach (self::getCategories() as $category => $value) {
            if (!is_array($value)) {
                if ($value == $number)
                    return $category;
            }

            if ($number >= $value[0] && $number <= $value[1])
                return $category;
        }

        return $default;
    }

}
