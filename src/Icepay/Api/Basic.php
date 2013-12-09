<?php

/**
 *  Icepay_Api_Basic class
 *  Loads and filters the paymentmethod classes
 *  @author Olaf Abbenhuis
 *
 * @var $instance Instance Class object
 * @var string $_content The contents of the files for the fingerprint
 * @var string $_folderPaymentMethods Folder of paymentmethod classes
 * @var array $paymentMethods List of all classes
 * @var array $_paymentMethods Filtered list
 * @package API_Basicmode
 *
 */
class Icepay_Api_Basic extends Icepay_Api_Base {

    private static $instance;
    private $version = "1.0.2";
    private $_folderPaymentMethods;
    private $paymentMethods = null; // Classes
    private $_paymentMethodsObject = null; // Loaded classes
    private $_paymentMethod = null; // Filtered list

    /**
     * Create an instance
     * @since version 1.0.0
     * @access public
     * @return instance of self
     */

    public static function getInstance() {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Set the folder where the paymentmethod classes reside
     * @since version 1.0.0
     * @access public
     * @param string $dir Folder of the paymentmethod classes
     */
    public function setPaymentMethodsFolder($dir) {
        $this->_folderPaymentMethods = $dir;
        return $this;
    }

    /**
     * Store the paymentmethod class names in the paymentmethods array.
     * @since version 1.0.0
     * @access public
     * @param string $dir Folder of the paymentmethod classes
     */
    public function readFolder($dir = null) {
        $this->setPaymentMethodsFolder(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Paymentmethods');

        if ($dir)
            $this->setPaymentMethodsFolder($dir);

        $this->paymentMethods = array();
        try {
            $folder = $this->_folderPaymentMethods;
            $handle = is_dir($folder) ? opendir($folder) : false;

            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && $file != ".svn") {
                        require_once (sprintf("%s/%s", $this->_folderPaymentMethods, $file));
                        $name = strtolower(substr($file, 0, strlen($file) - 4));
                        $className = "Icepay_Paymentmethod_" . ucfirst($name);
                        $this->paymentMethods[$name] = $className;
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $this;
    }

    /**
     * Returns a single class based on payment method code
     * @since version 2.1.0
     * @access public
     * @param string pmcode
     */
    public function getClassByPaymentMethodCode($pmcode) {
        return new $this->paymentMethods[strtolower($pmcode)]();
    }

    /**
     * Load all the paymentmethod classes and store these in the filterable paymentmethods array.
     * @since version 1.0.0
     * @access public
     */
    public function prepareFiltering() {
        foreach ($this->paymentMethods as $name => $class) {
            $this->_paymentMethod[$name] = new $class();
        }
        return $this;
    }

    /**
     * Filter the paymentmethods array by currency
     * @since version 1.0.0
     * @access public
     * @param string $currency Language ISO 4217 code
     */
    public function filterByCurrency($currency) {
        foreach ($this->_paymentMethod as $name => $class) {
            if (!in_array($currency, $class->getSupportedCurrency()) && !in_array('00', $class->getSupportedCurrency()))
                unset($this->paymentMethods[$name]);
        }
        return $this;
    }

    /**
     * Filter the paymentmethods array by country
     * @since version 1.0.0
     * @access public
     * @param string $country Country ISO 3166-1-alpha-2 code
     */
    public function filterByCountry($country) {
        foreach ($this->_paymentMethod as $name => $class) {
            if (!in_array(strtoupper($country), $class->getSupportedCountries()) && !in_array('00', $class->getSupportedCountries()))
                unset($this->paymentMethods[$name]);
        }
        return $this;
    }

    /**
     * Filter the paymentmethods array by language
     * @since version 1.0.0
     * @access public
     * @param string $language Language ISO 639-1 code
     */
    public function filterByLanguage($language) {
        foreach ($this->_paymentMethod as $name => $class) {
            if (!in_array(strtoupper($language), $class->getSupportedLanguages()) && !in_array('00', $class->getSupportedLanguages()))
                unset($this->paymentMethods[$name]);
        }
        return $this;
    }

    /**
     * Filter the paymentmethods array by amount
     * @since version 1.0.0
     * @access public
     * @param int $amount Amount in cents
     */
    public function filterByAmount($amount) {
        foreach ($this->_paymentMethod as $name => $class) {
            $amountRange = $class->getSupportedAmountRange();
            if (intval($amount) >= $amountRange["minimum"] &&
                intval($amount) <= $amountRange["maximum"]) {

            } else
                unset($this->paymentMethods[$name]);
        }
        return $this;
    }

    /**
     * Return the filtered paymentmethods array
     * @since version 1.0.0
     * @access public
     * @return array Paymentmethods
     */
    public function getArray() {
        return $this->paymentMethods;
    }

    public function loadArray() {
        if ($this->_paymentMethodsObject != null)
            return $this->_paymentMethodsObject;

        $this->_paymentMethodsObject = new stdClass();

        foreach ($this->getArray() as $key => $value) {
            $this->_paymentMethodsObject->$key = new $value();
        }

        return $this->_paymentMethodsObject;
    }

    public function getObject() {
        return $this->loadArray();
    }

}
