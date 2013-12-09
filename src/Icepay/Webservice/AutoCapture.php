<?php

/**
 * Icepay AutoCapture Webservice class
 *
 * @version 1.0.0
 * @author Wouter van Tilburg <wouter@icepay.eu>
 * @copyright Copyright (c) 2012, ICEPAY
 */
class Icepay_Webservice_AutoCapture extends Icepay_Webservice_Base {

    protected $service = 'https://connect.icepay.com/webservice/APCapture.svc?wsdl';

    /**
     * AutoCapture Webservice class constructer
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->setupClient();
    }

    /**
     * Capture an authorized AfterPay payment
     *
     * @since 1.0.0
     *
     * @param string $paymentID
     * @param int $amount
     * @param string $currency
     * @return object
     */
    public function captureFull($paymentID, $amount = 0, $currency = '')
    {
        $obj = new stdClass();

        $obj->MerchantID = $this->getMerchantID();
        $obj->Timestamp = $this->getTimestamp();
        $obj->amount = $amount;
        $obj->currency = $currency;
        $obj->PaymentID = $paymentID;

        // Generate checksum for the request
        $obj->Checksum = $this->generateChecksum($obj, $this->getSecretCode());

        // Make the request
        $request = $this->client->CaptureFull($obj);

        // Fetch the result
        $result = $request->CaptureFullResult;

        // Store result checksum
        $resultChecksum = $result->Checksum;

        // Remove result checksum from object
        unset($result->Checksum);

        // Create result checksum
        $checkSum = $this->generateChecksum($result, $this->getSecretCode());

        // Compare generated checksum and result checksum
        if ($resultChecksum !== $checkSum)
            throw new Exception('Data could not be verified');

        // Return result
        return $result;
    }

}