<?php

/**
 *  ICEPAY Basicmode API 2
 *  SofortBanking library
 *
 *  @version 1.0.1
 *  @author Olaf Abbenhuis
 *  @copyright Copyright (c) 2011, ICEPAY
 *
 */

class Icepay_Paymentmethod_Directebank extends Icepay_Paymentmethod {
    public      $_version       = "1.3.4";
    public      $_method        = "DIRECTEBANK";
    public      $_readable_name = "Sofort banking";
    public      $_issuer        = array('DIGITAL', 'RETAIL', 'ADULT');
    public      $_country       = array('AT','BE','CH','DE','GB','ES','FR','IT');
    public      $_language      = array('DE','EN','NL','FR','ES');
    public      $_currency      = array('EUR', 'GBP');
    public      $_amount        = array(
                                    'minimum'   => 30,
                                    'maximum'   => 1000000
                                    );
}


?>
