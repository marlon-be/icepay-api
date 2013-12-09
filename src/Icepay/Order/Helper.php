<?php

/**
 * Icepay_Order_Helper
 *
 * The Order Helper class contains handy fuctions to validate the input, such as a telephonenumber and zipcode check
 *
 * @version 1.0.0
 *
 * @author Wouter van Tilburg
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Helper {

    private static $street;
    private static $houseNumber;
    private static $houseNumberAddition;

    /**
     * Sets and explodes the streetaddress
     *
     * @since 1.0.0
     * @param string Contains the street address
     * @return Icepay_Order_Helper
     */
    public static function setStreetAddress($streetAddress) {
        self::explodeStreetAddress($streetAddress);

        return new self;
    }

    /**
     * Get the street from address
     *
     * @since 1.0.0
     * @param string Contains the street address
     * @return Icepay_Order_Helper
     */
    public static function getStreetFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$street;
    }

    /**
     * Get the housenumber from address
     *
     * @since 1.0.0
     * @param string Contains the street address
     * @return Icepay_Order_Helper
     */
    public static function getHouseNumberFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$houseNumber;
    }

    /**
     * Get the housenumber addition from address
     *
     * @since 1.0.0
     * @param string Contains the street address
     * @return Icepay_Order_Helper
     */
    public static function getHouseNumberAdditionFromAddress($streetAddress = null) {
        if ($streetAddress)
            self::explodeStreetAddress($streetAddress);

        return self::$houseNumberAddition;
    }

    /**
     * Validates a zipcode based on country
     *
     * @since 1.0.0
     * @param string $zipCode A string containing the zipcode
     * @param string $country A string containing the ISO 3166-1 alpha-2 code of the country
     * @example validateZipCode('1122AA', 'NL')
     * @return boolean
     */
    public static function validateZipCode($zipCode, $country) {
        switch (strtoupper($country)) {
            case 'NL':
                if (preg_match('/^[1-9]{1}[0-9]{3}[A-Z]{2}$/', $zipCode))
                    return true;
                break;
            case 'BE':
                if (preg_match('/^[1-9]{4}$/', $zipCode))
                    return true;
                break;
            case 'DE':
                if (preg_match('/^[1-9]{5}$/', $zipCode))
                    return true;
                break;
        }

        return false;
    }

    /**
     * Validates a phonenumber
     *
     * @since 1.0.0
     * @param string Contains the phonenumber
     * @return boolean
     */
    public static function validatePhonenumber($phoneNumber) {
        if (strlen($phoneNumber) < 10) {
            return false;
        }

        if (preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $phoneNumber)) {
            return true;
        }

        return false;
    }

    private static function explodeStreetAddress($streetAddress) {
        $pattern = '#^(.+\D+){1} ([0-9]{1,})\s?([\s\/]?[0-9]{0,}?[\s\S]{0,}?)?$#i';

        $aMatch = array();

        if (preg_match($pattern, $streetAddress, $aMatch)) {
            array_shift($aMatch);

            self::$street = array_shift($aMatch);
            self::$houseNumber = array_shift($aMatch);
            self::$houseNumberAddition = array_shift($aMatch);
        }
    }

}
