<?php

/**
 * ICEPAY API - Pay By Mail
 *
 * @version 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 *
 */
class Icepay_Api_Pbm extends Icepay_Api_Base {

    private $url = 'http://pbm.icepay.com/api';
    private static $instance;

    /**
     * Create an instance
     *
     * @since version 1.0.0
     * @access public
     * @return instance of self
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Create a PBM link
     *
     * @since 1.0.0
     * @param Icepay_Pbm_Object $pbmObject
     * @return string
     */
    public function createLink(Icepay_Pbm_Object $pbmObject)
    {
        $this->validateSettings();

        $linkObj = new StdClass();
        $linkObj->merchantid = $this->getMerchantID();
        $linkObj->timestamp = $this->getTimestamp();
        $linkObj->amount = $pbmObject->getAmount();
        $linkObj->currency = $pbmObject->getCurrency();
        $linkObj->language = $pbmObject->getLanguage();
        $linkObj->orderid = $pbmObject->getOrderID();
        $linkObj->country = $pbmObject->getCountry();
        $linkObj->description = $pbmObject->getDescription();
        $linkObj->reference = $pbmObject->getReference();
        $linkObj->checksum = $this->generateChecksum($linkObj);

        $result = $this->generateURL($linkObj);

        return json_decode($result);
    }

    /**
     * Calls PBM platform and returns generated PBM link
     *
     * @since 1.0.0
     * @param object $parameters
     * @return string
     */
    private function generateURL($parameters)
    {
        $ch = curl_init();

        $parameters = http_build_query($parameters);

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * Generates PBM checksum
     *
     * @since 1.0.0
     * @param obj $linkObj
     * @return string
     */
    private function generateChecksum($linkObj)
    {
        $arr = (array)$linkObj;
        $arr[] = $this->getSecretCode();

        return sha1(implode("|", $arr));
    }

    /**
     * Validate the merchant settings
     *
     * @since 1.0.0
     * @throws Exception
     */
    private function validateSettings()
    {
        // Validate Merchant ID
        if (!Icepay_ParameterValidation::merchantID($this->getMerchantID()))
            throw new Exception('Merchant ID not set, use the setMerchantID() method', 1001);

        // Validate SecretCode
        if (!Icepay_ParameterValidation::secretCode($this->getSecretCode()))
            throw new Exception('Secretcode ID not set, use the setSecretCode() method', 1002);
    }

}
