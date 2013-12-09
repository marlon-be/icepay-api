<?php

/**
 *  ICEPAY API
 *
 *  @version 2.4.0
 *  @author Olaf Abbenhuis
 *  @author Wouter van Tilburg
 *  @copyright Copyright (c) 2012, ICEPAY
 *
 */

interface Icepay_Basic_PaymentmethodInterface {

    public function getCode();

    public function getReadableName();

    public function getSupportedIssuers();

    public function getSupportedCountries();

    public function getSupportedCurrency();

    public function getSupportedLanguages();

    public function getSupportedAmountRange();
}
