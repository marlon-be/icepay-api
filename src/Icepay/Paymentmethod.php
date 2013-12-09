<?php

/**
 *  The Icepay_Paymentmethod is the base class for all payment method subclasses
 *
 *  @author Olaf Abbenhuis
 *  @since 2.1.0
 */
class Icepay_Paymentmethod implements Icepay_Basic_PaymentmethodInterface {

    public $_version = null;
    public $_method = null;
    public $_readable_name = null;
    public $_issuer = null;
    public $_country = null;
    public $_language = null;
    public $_currency = null;
    public $_amount = null;

    /**
     * Get the version of the API or the loaded payment method class
     * @since version 1.0.1
     * @access public
     * @return string
     */
    public function getCode()
    {
        return $this->_method;
    }

    /**
     * Get the version of the API or the loaded payment method class
     * @since version 1.0.1
     * @access public
     * @return string
     */
    public function getReadableName()
    {
        return $this->_readable_name;
    }

    /**
     * Get the supported issuers of the loaded paymentmethod
     * @since version 1.0.0
     * @access public
     * @return array The issuer codes of the paymentmethod
     */
    public function getSupportedIssuers()
    {
        return $this->_issuer;
    }

    /**
     * Get the supported countries of the loaded paymentmethod
     * @since version 1.0.0
     * @access public
     * @return array The country codes of the paymentmethod
     */
    public function getSupportedCountries()
    {
        return $this->_country;
    }

    /**
     * Get the supported currencies of the loaded paymentmethod
     * @since version 1.0.0
     * @access public
     * @return array The currency codes of the paymentmethod
     */
    public function getSupportedCurrency()
    {
        return $this->_currency;
    }

    /**
     * Get the supported languages of the loaded paymentmethod
     * @since version 1.0.0
     * @access public
     * @return array The Language codes of the paymentmethod
     */
    public function getSupportedLanguages()
    {
        return $this->_language;
    }

    /**
     * Get the general amount range of the loaded paymentmethod
     * @since version 1.0.0
     * @access public
     * @return array [minimum(uint), maximum(uint)]
     */
    public function getSupportedAmountRange()
    {
        return $this->_amount;
    }

}
