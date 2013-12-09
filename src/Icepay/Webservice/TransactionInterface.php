<?php

interface Icepay_Webservice_TransactionInterface {

    public function setData($data);

    public function getPaymentScreenURL();

    public function getPaymentID();

    public function getProviderTransactionID();

    public function getTestMode();

    public function getTimestamp();

    public function getEndUserIP();
}
