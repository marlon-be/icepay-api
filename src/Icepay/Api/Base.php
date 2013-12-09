<?php

/**
 *  Icepay_Api_Base class
 *  Basic Setters and Getters required in most API
 *
 *  @author Olaf Abbenhuis
 *  @author Wouter van Tilburg
 *  @package API_Base
 *  @since 1.0.0
 *  @version 1.0.2
 *
 */
class Icepay_Api_Base {

    private $_pinCode;
    protected $_merchantID;
    protected $_secretCode;
    protected $_method = null;
    protected $_issuer = null;
    protected $_country = null;
    protected $_language = null;
    protected $_currency = null;
    protected $_version = "1.0.2";
    protected $_doIPCheck = array();
    protected $_whiteList = array();
    protected $data;
    protected $_logger;

    public function __construct()
    {
        $this->_logger = Icepay_Api_Logger::getInstance();
        $this->data = new stdClass();
    }

    /**
     * Validate data
     * @since version 1.0.0
     * @access public
     * @param string $needle
     * @param array $haystack
     * @return boolean
     */
    public function exists($needle, $haystack = null)
    {
        $result = true;
        if ($haystack && $result && $haystack[0] != "00")
            $result = in_array($needle, $haystack);
        return $result;
    }

    /**
     * Use IP Check
     * @since 1.0.1
     * @access public
     * @param boolean $bool
     */
    public function doIPCheck($bool = true)
    {
        // IP Range of ICEPAY servers - Do not change unless asked to.
        $this->setIPRange('194.30.175.0', '194.30.175.255');
        $this->setIPRange('194.126.241.128', '194.126.241.191');

        $this->_doIPCheck = $bool;

        return $this;
    }

    /**
     * Add ip(s) to whitelist
     *
     * @example '1.1.1.1', '1.1.1.1-1.1.1.2'
     * @since 2.1.2
     * @access public
     * @param type $string
     */
    public function addToWhitelist($string)
    {
        // Remove whitespaces
        $string = str_replace(' ', '', $string);

        // Seperate ip's
        $ipRanges = explode(",", $string);

        foreach ($ipRanges as $ip) {
            // Explode for range
            $ip = explode("-", $ip);

            if (count($ip) > 1) {
                $this->setIPRange($ip[0], $ip[1]);
            } else {
                $this->setIPRange($ip[0], $ip[0]);
            }
        }
    }

    /**
     * Set the IP range
     * @since 1.0.1
     * @access public
     * @param string $start
     * @param string $end
     */
    public function setIPRange($start, $end)
    {
        $start = str_replace(' ', '', $start);
        $end = str_replace(' ', '', $end);
        $this->_whiteList[] = array('start' => $start, 'end' => $end);

        return $this;
    }

    /**
     * Get the version of the API or the loaded payment method class
     * @since 1.0.0
     * @access public
     * @return string Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the Merchant ID field
     * @since 1.0.0
     * @access public
     * @param (int) $merchantID
     */
    public function setMerchantID($merchantID)
    {
        if (!Icepay_ParameterValidation::merchantID($merchantID))
            throw new Exception('MerchantID not valid');

        $this->_merchantID = (int) $merchantID;

        return $this;
    }

    /**
     * Get the Merchant ID field
     * @since 1.0.0
     * @access public
     * @return (int) MerchantID
     */
    public function getMerchantID()
    {
        return $this->_merchantID;
    }

    /**
     * Set the Secret Code field
     * @since 1.0.0
     * @access public
     * @param (string) $secretCode
     */
    public function setSecretCode($secretCode)
    {
        if (!Icepay_ParameterValidation::secretCode($secretCode))
            throw new Exception('Secretcode not valid');

        $this->_secretCode = (string) $secretCode;
        return $this;
    }

    /**
     * Get the Secret Code field
     * @since 1.0.0
     * @access protected
     * @return (string) Secret Code
     */
    protected function getSecretCode()
    {
        return $this->_secretCode;
    }

    /**
     * Set the Pin Code field
     * @since 1.0.1
     * @access public
     * @param (int) $pinCode
     */
    public function setPinCode($pinCode)
    {
        if (!Icepay_ParameterValidation::pinCode($pinCode))
            throw new Exception('Pincode not valid');

        $this->_pinCode = (string) $pinCode;

        return $this;
    }

    /**
     * Get the Pin Code field
     * @since 1.0.0
     * @access protected
     * @return (int) PinCode
     */
    protected function getPinCode()
    {
        return $this->_pinCode;
    }

    /**
     * Set the success url field (optional)
     * @since version 1.0.1
     * @access public
     * @param string $url
     */
    public function setSuccessURL($url = "")
    {
        if (!isset($this->data))
            $this->data = new stdClass();

        $this->data->ic_urlcompleted = $url;

        return $this;
    }

    /**
     * Set the error url field (optional)
     * @since version 1.0.1
     * @access public
     * @param string $url
     */
    public function setErrorURL($url = "")
    {
        if (!isset($this->data))
            $this->data = new stdClass();

        $this->data->ic_urlerror = $url;
        return $this;
    }

    /**
     * Get the success URL
     * @since version 2.1.0
     * @access public
     * @return string $url
     */
    public function getSuccessURL()
    {
        return (isset($this->data->ic_urlcompleted)) ? $this->data->ic_urlcompleted : "";
    }

    /**
     * Get the error URL
     * @since version 2.1.0
     * @access public
     * @return string $url
     */
    public function getErrorURL()
    {
        return (isset($this->data->ic_urlerror)) ? $this->data->ic_urlerror : "";
    }

    /**
     * Check if the ip is in range
     * @since version 2.1.0
     * @access protected
     * @param string $ip IP used within the request
     * @param string $range Allowed range
     * @return boolean
     */
    protected function ip_in_range($ip, $range)
    {
        if (strpos($range, '/') !== false) {
            // $range is in IP/NETMASK format
            list($range, $netmask) = explode('/', $range, 2);
            if (strpos($netmask, '.') !== false) {
                // $netmask is a 255.255.0.0 format
                $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);
                return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
            } else {
                // $netmask is a CIDR size block
                // fix the range argument
                $x = explode('.', $range);
                while (count($x) < 4)
                    $x[] = '0';
                list($a, $b, $c, $d) = $x;
                $range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);

                # Strategy 1 - Using substr to chop up the range and pad it with 1s to the right
                $broadcast_dec = bindec(substr($this->decbin32($range_dec), 0, $netmask)
                    . str_pad('', 32 - $netmask, '1'));

                # Strategy 2 - Use math to OR the range with the wildcard to create the Broadcast address
                $wildcard_dec = pow(2, (32 - $netmask)) - 1;
                $broadcast_dec = $range_dec | $wildcard_dec;

                return (($ip_dec & $broadcast_dec) == $ip_dec);
            }
        } else {
            // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
            if (strpos($range, '*') !== false) { // a.b.*.* format
                // Just convert to A-B format by setting * to 0 for A and 255 for B
                $lower = str_replace('*', '0', $range);
                $upper = str_replace('*', '255', $range);
                $range = "$lower-$upper";
            }

            if (strpos($range, '-') !== false) { // A-B format
                list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = ip2long($lower);
                $upper_dec = ip2long($upper);
                $ip_dec = ip2long($ip);
                return ( ($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec) );
            }

            return false;
        }

        $ip_dec = ip2long($ip);
        return (($ip_dec & $netmask_dec) == $ip_dec);
    }

    public function getTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s\Z");
    }

}
