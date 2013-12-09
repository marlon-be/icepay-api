<?php

/**
 * Icepay_ParameterValidation class
 * Validates parameters
 *
 * @author Olaf Abbenhuis
 * @since 2.1.0
 */
class Icepay_ParameterValidation {

    protected $_version = "1.0.0";

    /**
     * Check if Merchant ID is valid
     *
     * @since 2.1.0
     * @access public
     * @param string $string
     * @return bool
     */
    public static function merchantID($string)
    {
        return (strlen($string) == 5 && is_numeric($string));
    }

    /**
     * Check if Secret Code is valid
     *
     * @since 2.1.0
     * @access public
     * @param sring $string
     * @return bool
     */
    public static function secretCode($string)
    {
        return (strlen($string) == 40 && !is_numeric($string));
    }

    /**
     * Check if Pin Code is valid
     *
     * @since 2.1.0
     * @access public
     * @param string $string
     * @return bool
     */
    public static function pinCode($string)
    {
        return (strlen($string) == 8 && is_numeric($string));
    }

    /**
     * Check if Country is valid
     *
     * @since 2.1.0
     * @access public
     * @param string $string
     * @return bool
     */
    public static function country($string)
    {
        return (strlen($string) == 2);
    }

    /**
     * Check if Language is valid
     *
     * @since 2.1.0
     * @access public
     * @param string $string
     * @return bool
     */
    public static function language($string)
    {
        return (strlen($string) == 2 && !is_numeric($string));
    }

    /**
     * Check if Currency is valid
     *
     * @since 2.1.0
     * @access public
     * @param string $string
     * @return bool
     */
    public static function currency($string)
    {
        return (strlen($string) == 3 && !is_numeric($string));
    }

    /**
     * Check if Amount is valid
     *
     * @since 2.1.0
     * @access public
     * @param int $number
     * @return bool
     */
    public static function amount($number)
    {
        return (is_numeric($number));
    }

    public static function orderID($orderID)
    {
        return (strlen($orderID) < 11);
    }

}
