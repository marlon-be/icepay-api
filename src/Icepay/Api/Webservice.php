<?php

/**
 * @package API_Webservice
 */
class Icepay_Api_Webservice extends Icepay_Api_Base {

    private static $instance;
    private $_service_reporting;
    private $_service_pay;
    private $_service_paymentMethods;
    private $_service_refunds;
    private $_service_autoCapture;
    private $_filtering;
    private $_single;
    protected $version = "1.1.0";

    /**
     * Create an instance
     *
     * @since 2.1.0
     * @access public
     * @return instance of self
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Returns class or creates the Auto Capture class
     *
     * @since 2.4.0
     * @access public
     * @return type
     */
    public function autoCaptureService()
    {
        if (!$this->_service_autoCapture)
            $this->_service_autoCapture = new Icepay_Webservice_AutoCapture();
        return $this->_service_autoCapture;
    }

    /**
     * Returns class or creates the Payment Methods class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function paymentMethodService()
    {
        if (!$this->_service_paymentMethods)
            $this->_service_paymentMethods = new Icepay_Webservice_Paymentmethods();
        return $this->_service_paymentMethods;
    }

    /**
     * Returns class or creates the Filtering class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function filtering()
    {
        if (!$this->_filtering)
            $this->_filtering = new Icepay_Webservice_Filtering();
        return $this->_filtering;
    }

    /**
     * Returns class or creates the Paymentmethod class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function singleMethod()
    {
        if (!$this->_single)
            $this->_single = new Icepay_Webservice_Paymentmethod();
        return $this->_single;
    }

    /**
     * Returns class or creates the Pay class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function paymentService()
    {
        if (!$this->_service_pay)
            $this->_service_pay = new Icepay_Webservice_Pay();
        return $this->_service_pay;
    }

    /**
     * Returns class or creates the Reporting class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function reportingService()
    {
        if (!$this->_service_reporting)
            $this->_service_reporting = new Icepay_Webservice_Reporting();
        return $this->_service_reporting;
    }

    /**
     * Returns class or creates the Refund class
     *
     * @since 2.1.0
     * @access public
     * @return object
     */
    public function refundService()
    {
        if (!$this->_service_refunds)
            $this->_service_refunds = new Icepay_Webservice_Refunds();
        return $this->_service_refunds;
    }

}
