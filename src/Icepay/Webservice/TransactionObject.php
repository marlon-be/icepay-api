<?php

/**
 *  The Transaction Object is returned when making a payment using the webservices
 *
 *  @author Olaf Abbenhuis
 *  @since 2.1.0
 */
class Icepay_Webservice_TransactionObject implements Icepay_Webservice_TransactionInterface {

    protected $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getPaymentScreenURL()
    {
        return $this->data->PaymentScreenURL;
    }

    public function getPaymentID()
    {
        return $this->data->PaymentID;
    }

    public function getProviderTransactionID()
    {
        return $this->data->ProviderTransactionID;
    }

    public function getTestMode()
    {
        return $this->data->TestMode;
    }

    public function getTimestamp()
    {
        return $this->data->Timestamp;
    }

    public function getEndUserIP()
    {
        return $this->data->EndUserIP;
    }

}
