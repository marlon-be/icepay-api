<?php

/**
 * ICEPAY API - Pay By Mail Object
 *
 * @version 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 *
 */
class Icepay_Pbm_Object implements Icepay_Pbm_ObjectInterface {

    private $amount;
    private $currency = 'EUR';
    private $country = 'NL';
    private $language = 'NL';
    private $orderID;
    private $description = '';
    private $reference = '';

    /**
     * Set Amount
     *
     * @since 1.0.0
     * @param int $amount
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setAmount($amount)
    {
        if (!Icepay_ParameterValidation::amount($amount))
            throw new Exception('Please enter a valid amount (in cents)', 1003);

        $this->amount = $amount;
        return $this;
    }

    /**
     * Get Amount
     *
     * @since 1.0.0
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set Currency
     *
     * @since 1.0.0
     * @param string $currency
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setCurrency($currency)
    {
        if (!Icepay_ParameterValidation::currency($currency))
            throw new Exception('Please enter a valid currency format (ISO 4217)', 1004);

        $this->currency = $currency;
        return $this;
    }

    /**
     * Get Currency
     *
     * @since 1.0.0
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set Country
     *
     * @since 1.0.0
     * @param string $countryCode
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setCountry($countryCode)
    {
        if (!Icepay_ParameterValidation::country($countryCode))
            throw new Exception('Please enter a valid country format (ISO 3166-1)', 1005);

        $this->country = $countryCode;
        return $this;
    }

    /**
     * Get Country
     *
     * @since 1.0.0
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set Language
     *
     * @since 1.0.0
     * @param string $language
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setLanguage($language)
    {
        if (!Icepay_ParameterValidation::language($language))
            throw new Exception('Please enter a valid language (ISO 639-1)', 1006);

        $this->language = $language;
        return $this;
    }

    /**
     * Get Language
     *
     * @since 1.0.0
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set OrderID
     *
     * @param string $orderID
     * @return \Icepay_Pbm_Object
     * @throws Exception
     */
    public function setOrderID($orderID)
    {
        if (!Icepay_ParameterValidation::orderID($orderID))
            throw new Exception('The Order ID cannot be longer than 10 characters', 1007);

        $this->orderID = $orderID;
        return $this;
    }

    /**
     * Get Order ID
     *
     * @since 1.0.0
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set Description
     *
     * @since 1.0.0
     * @param string $description
     * @return \Icepay_Pbm_Object
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get Description
     *
     * @since 1.0.0
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set Reference
     *
     * @since 1.0.0
     * @param string $reference
     * @return \Icepay_Pbm_Object
     */
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Get Reference
     *
     * @since 1.0.0
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

}
