<?php

/**
 *  Interfaces
 *
 *  @author Olaf Abbenhuis
 *  @since 2.1.0
 */
interface Icepay_PaymentObjectInterface {

    public function setData($data);

    public function getData();

    public function setIssuer($issuer);

    public function getIssuer();

    public function setPaymentMethod($paymentmethod);

    public function getPaymentMethod();

    public function setCountry($country);

    public function getCountry();

    public function setCurrency($currency);

    public function getCurrency();

    public function setLanguage($lang);

    public function getLanguage();

    public function setAmount($amount);

    public function getAmount();

    public function setOrderID($id = "");

    public function getOrderID();

    public function setReference($reference = "");

    public function getReference();

    public function setDescription($info = "");

    public function getDescription();
}
