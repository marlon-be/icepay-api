<?php

/**
 * @package API_Webservice_Paymentmethods
 */
class Icepay_Webservice_Paymentmethods extends Icepay_Webservice_Base {

    protected $_paymentMethod = null;
    protected $_paymentMethods = null;
    protected $_paymentMethodsArray;
    protected $_savedData = array();

    public function __construct()
    {
        $this->setupClient();
    }

    /**
     * Retrieve all payment methods
     *
     * @since 2.1.0
     * @access public
     *
     * @return \Icepay_Webservice_Paymentmethods
     */
    public function retrieveAllPaymentmethods()
    {
        if (isset($this->_paymentMethodsArray))
            return $this;

        $obj = new stdClass();
        // Must be in specific order for checksum ---------
        $obj->MerchantID = $this->_merchantID;
        $obj->SecretCode = $this->_secretCode;
        $obj->Timestamp = $this->getTimeStamp();
        // ------------------------------------------------
        $obj->Checksum = $this->generateChecksum($obj);
        $obj->SecretCode = null;

        $this->_paymentMethods = $this->client->GetMyPaymentMethods(array('request' => $obj));

        if (isset($this->_paymentMethods->GetMyPaymentMethodsResult->PaymentMethods->PaymentMethod)) {
            $this->_paymentMethodsArray = $this->clean($this->_paymentMethods);
        }

        return $this;
    }

    /**
     * Return clean array
     *
     * @since 2.1.0
     * @access protected
     * @param object $obj
     * @return array
     */
    protected function clean($obj)
    {
        $methods = array();

        foreach ($this->forceArray($obj->GetMyPaymentMethodsResult->PaymentMethods->PaymentMethod) as $value) {
            array_push($methods, array(
                    'PaymentMethodCode' => $value->PaymentMethodCode,
                    'Description' => $value->Description,
                    'Issuers' => $this->convertIssuers($this->forceArray($value->Issuers->Issuer))
                )
            );
        };
        return $methods;
    }

    /**
     * Convert Issuers
     *
     * @since 2.1.0
     * @access private
     * @param array $array
     * @return array
     */
    private function convertIssuers($array)
    {
        $return = array();
        foreach ($array as $value) {
            array_push($return, array(
                    'IssuerKeyword' => $value->IssuerKeyword,
                    'Description' => $value->Description,
                    'Countries' => $this->convertCountries($this->forceArray($value->Countries->Country))
                )
            );
        }
        return $return;
    }

    /**
     * Convert Countries
     *
     * @since 2.1.0
     * @access private
     * @param array $array
     * @return array
     */
    private function convertCountries($array)
    {
        $return = array();
        foreach ($array as $value) {
            array_push($return, array(
                'CountryCode' => $value->CountryCode,
                'MinimumAmount' => $value->MinimumAmount,
                'MaximumAmount' => $value->MaximumAmount,
                'Currencies' => $this->convertCurrencies($value->Currency)
            ));
        }
        return $return;
    }

    /**
     * Convert Currencies
     *
     * @since 2.1.0
     * @access private     *
     * @param string $string
     * @return string
     */
    private function convertCurrencies($string)
    {
        $return = explode(", ", $string);
        return $return;
    }

    /**
     * Returns paymentmethods as array
     *
     * @since 2.1.0
     * @access public
     * @return array
     */
    public function asArray()
    {
        return $this->_paymentMethodsArray;
    }

    /**
     * Returns paymentmethods as object
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function asObject()
    {
        return $this->_paymentMethods;
    }

    /**
     * get Webservice Data
     *
     * @since 2.1.0
     * @access public
     * @return string
     */
    public function exportAsString()
    {
        return urlencode(serialize($this->_paymentMethodsArray));
    }

    /**
     * Save ws to File
     *
     * @since 2.1.0
     * @access public
     * @param string $fileName
     * @param directory $directory
     * @return boolean
     * @throws Exception
     */
    public function saveToFile($fileName = "wsdata", $directory = "")
    {
        if ($directory == "")
            $directory = dirname(__FILE__);

        date_default_timezone_set("Europe/Paris");
        $line = sprintf("Paymentmethods %s,%s\r\n", date("H:i:s", time()), $this->exportAsString());

        $filename = sprintf("%s/%s.csv", $directory, $fileName);
        try {
            $fp = @fopen($filename, "w");
            @fwrite($fp, $line);
            @fclose($fp);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        };

        return true;
    }

}
