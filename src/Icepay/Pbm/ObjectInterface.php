<?php

/**
 * PBM Object Interface
 *
 * @since 1.0.0
 * @author Wouter van Tilburg
 * @copyright Copyright (c) 2013, ICEPAY
 */
interface Icepay_Pbm_ObjectInterface {

    public function setAmount($amount);

    public function getAmount();

    public function setCurrency($currency);

    public function getCurrency();

    public function setCountry($country);

    public function getCountry();

    public function setLanguage($language);

    public function getLanguage();

    public function setOrderID($orderID);

    public function getOrderID();

    public function setReference($reference);

    public function getReference();

    public function setDescription($description);

    public function getDescription();
}
