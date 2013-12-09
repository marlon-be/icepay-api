<?php

/**
 * @package API_Webservice_Refund
 */
class Icepay_Webservice_Refunds extends Icepay_Webservice_Base {

    protected $service = 'https://connect.icepay.com/webservice/refund.svc?wsdl';

    public function __construct()
    {
        $this->setupClient();
    }

    /**
     * The RequestRefund web method allows you to initiate a refund request for a payment. You can request
     * the entire amount to be refunded or just a part of it. If you request only a partial amount to be refunded
     * then you  are allowed to perform refund requests for the same payment until you have reached its full
     * amount. After that you cannot request refunds anymore for that payment.
     *
     * @since version 2.1.0
     * @access public
     * @param int $paymentID
     * @param int $refundAmount Amount in cents
     * @param string $refundCurrency
     */
    public function requestRefund($paymentID, $refundAmount, $refundCurrency)
    {
        $obj = new stdClass();

        // Must be in specific order for checksum --
        $obj->Secret = $this->getSecretCode();
        $obj->MerchantID = $this->getMerchantID();
        $obj->Timestamp = $this->getTimeStamp();
        $obj->PaymentID = $paymentID;
        $obj->RefundAmount = $refundAmount;
        $obj->RefundCurrency = $refundCurrency;
        // -----------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Ask for getPaymentRefunds and get response
        $result = $this->client->requestRefund($obj);
        $result = $result->RequestRefundResult;

        $obj = new StdClass();

        // Must be in specific order for checksum -------------------
        $obj->Secret = $this->getSecretCode();
        $obj->MerchantID = $this->getMerchantID();
        $obj->Timestamp = $result->Timestamp;
        $obj->RefundID = $result->RefundID;
        $obj->PaymentID = $paymentID;
        $obj->RefundAmount = $refundAmount;
        $obj->RemainingRefundAmount = $result->RemainingRefundAmount;
        $obj->RefundCurrency = $refundCurrency;
        // ----------------------------------------------------------
        // Verify response data by making a new Checksum
        $Checksum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $Checksum)
            throw new Exception('Data could not be verified');

        return (array) $result;
    }

    /**
     * The CancelRefund web method allows you to cancel a refund request if it has not already been processed.
     *
     * @since version 2.1.0
     * @access public
     * @param int $refundID
     * @param int $paymentID
     */
    public function cancelRefund($refundID, $paymentID)
    {
        $obj = new stdClass();

        // Must be in specific order for checksum --
        $obj->Secret = $this->getSecretCode();
        $obj->MerchantID = $this->getMerchantID();
        $obj->Timestamp = $this->getTimeStamp();
        $obj->RefundID = $refundID;
        $obj->PaymentID = $paymentID;
        // -----------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Ask for cancelRefunt and get response
        $result = $this->client->CancelRefund($obj);
        $result = $result->CancelRefundResult;

        $obj->Timestamp = $result->Timestamp;
        $obj->Success = $result->Success;

        // Unset properties for new Checksum
        unset($obj->RefundID, $obj->PaymentID, $obj->Checksum);

        // Verify response data by making a new Checksum
        $Checksum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $Checksum)
            throw new Exception('Data could not be verified');

        return (array) $result;
    }

    /**
     * The GetPaymentRefunds web method allows you to query refund request information that belongs to the payment.
     *
     * @since version 2.1.0
     * @access public
     * @param int $paymentID
     */
    public function getPaymentRefunds($paymentID)
    {
        $obj = new stdClass();

        // Must be in specific order for checksum --
        $obj->Secret = $this->getSecretCode();
        $obj->MerchantID = $this->getMerchantID();
        $obj->Timestamp = $this->getTimeStamp();
        $obj->PaymentID = $paymentID;
        // -----------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Ask for getPaymentRefunds and get response
        $result = $this->client->getPaymentRefunds($obj);

        $result = $result->GetPaymentRefundsResult;
        $refunds = isset($result->Refunds->Refund) ? $result->Refunds->Refund : null;

        $obj->Timestamp = $result->Timestamp;

        if (!is_null($refunds)) {
            // Assign all properties of the DayStatistics object as property of mainObject
            $obj = $this->parseForChecksum($obj, $refunds, true, array("RefundID", "DateCreated", "RefundAmount", "RefundCurrency", "Status"));
        }

        // Unset properties for new Checksum
        unset($obj->Checksum);

        // Verify response data by making a new Checksum
        $Checksum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $Checksum)
            throw new Exception('Data could not be verified');

        return (!is_null($refunds)) ? $this->forceArray($refunds) : array();
    }

}
