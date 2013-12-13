<?php

class Icepay_Paymentmethod_Giftcard extends Icepay_Paymentmethod {
    public      $_version       = "1.3.4";
    public      $_method        = "GIFTCARD";
    public      $_readable_name = "Giftcard";
    public      $_issuer        = array('YOURGIFT');
    public      $_country       = array('00');
    public      $_language      = array('NL', 'EN');
    public      $_currency      = array('EUR','USD','GBP', 'AED', 'ARS',
                                        'AUD', 'BGN', 'BRL', 'CAD',
                                        'CHF', 'CLP', 'CNY', 'CZK',
                                        'DKK', 'EEK', 'HKD', 'HRK',
                                        'HUF', 'IDR', 'ILS', 'INR', 'ISK',
                                        'JPY', 'KRW', 'LTL', 'LVL', 'MXN',
                                        'MYR', 'NOK', 'NZD', 'PHP',
                                        'PLN', 'RON', 'RUB', 'SEK',
                                        'SGD', 'SKK', 'THB', 'TRY',
                                        'TWD', 'UAH', 'VND', 'ZAR');
    public      $_amount        = array(
        'minimum'   => 1,
        'maximum'   => 1000000000
    );
}

?>
