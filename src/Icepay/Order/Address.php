<?php

/**
 * Icepay_Order_Address
 *
 * The address class contains all information about the consumer's address
 *
 * @version 1.0.0
 *
 * @author Wouter van Tilburg
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Address {

    public $initials = '';
    public $prefix = '';
    public $lastName = '';
    public $street = '';
    public $houseNumber = '';
    public $houseNumberAddition = '';
    public $zipCode = '';
    public $city = '';
    public $country = '';

    /**
     * Creates and returns a new Icepay_Order_Address
     *
     * @since 1.0.0
     * @return \Icepay_Order_Address
     */
    public static function create() {
        return new self();
    }

    /**
     * Sets the initials
     *
     * @since 1.0.0
     * @param string A string containing the initials
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setInitials($initials) {
        if (empty($initials))
            throw new Exception('Initials must be set and cannot be empty.');

        $this->initials = trim($initials);

        return $this;
    }

    /**
     * Sets the prefix
     *
     * @since 1.0.0
     * @param string A string containing the prefix
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setPrefix($prefix) {
        $this->prefix = trim($prefix);

        return $this;
    }

    /**
     * Sets the last name
     *
     * @since 1.0.0
     * @param string A string containing the family name
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setLastName($lastName) {
        if (empty($lastName))
            throw new Exception('Lastname must be set and cannot be empty.');

        $this->lastName = trim($lastName);

        return $this;
    }

    /**
     * Sets the streetname
     *
     * @since 1.0.0
     * @param string A string containing the streetname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setStreet($street) {
        if (empty($street))
            throw new Exception('Streetname must be set and cannot be empty.');

        $this->street = trim($street);

        return $this;
    }

    /**
     * Sets the housenumber
     *
     * @since 1.0.0
     * @param string A string containing the housenumber
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setHouseNumber($houseNumber) {
        if (empty($houseNumber))
            throw new Exception('Housenumber must be set and cannot be empty.');

        $this->houseNumber = trim($houseNumber);

        return $this;
    }

    /**
     * Sets the housenumberaddition
     *
     * @since 1.0.0
     * @param string A string containing the housenumber addition
     * @return \Icepay_Order_Address
     */
    public function setHouseNumberAddition($houseNumberAddition) {
        $this->houseNumberAddition = trim($houseNumberAddition);

        return $this;
    }

    /**
     * Sets the address zipcode
     *
     * @since 1.0.0
     * @param string A string containing the zipcode
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setZipCode($zipCode) {
        if (empty($zipCode))
            throw new Exception('Zipcode must be set and cannot be empty.');

        $zipCode = str_replace(' ', '', $zipCode);

        $this->zipCode = trim($zipCode);

        return $this;
    }

    /**
     * Sets the address city
     *
     * @since 1.0.0
     * @param string A string containing the cityname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setCity($city) {
        if (empty($city))
            throw new Exception('City must be set and cannot be empty.');

        $this->city = trim($city);

        return $this;
    }

    /**
     * Sets the country
     *
     * @since 1.0.0
     * @param string A string containing the countryname
     * @return \Icepay_Order_Address
     * @throws Exception when empty
     */
    public function setCountry($country) {
        if (empty($country))
            throw new Exception('Country must be set and cannot be empty.');

        $this->country = trim($country);

        return $this;
    }

}
