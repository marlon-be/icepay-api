<?php

/**
 *  Icepay_ProjectHelper class
 *  A helper for all-in-one solutions
 *
 *  @author Olaf Abbenhuis
 *  @since 1.0.0
 *
 */
class Icepay_ProjectHelper {

    private static $instance;
    private $_release = "2.4.0";
    private $_basic;
    private $_result;
    private $_postback;
    private $_validate;

    /**
     * Returns the Icepay_Basicmode class or creates it
     *
     * @since 1.0.0
     * @access public
     * @return \Icepay_Basicmode
     */
    public function basic()
    {
        if (!isset($this->_basic))
            $this->_basic = new Icepay_Basicmode();
        return $this->_basic;
    }

    /**
     * Returns the Icepay_Result class or creates it
     *
     * @since 1.0.0
     * @access public
     * @return \Icepay_Result
     */
    public function result()
    {
        if (!isset($this->_result))
            $this->_result = new Icepay_Result();
        return $this->_result;
    }

    /**
     * Returns the Icepay_Postback class or creates it
     *
     * @since 1.0.0
     * @access public
     * @return \Icepay_Postback
     */
    public function postback()
    {
        if (!isset($this->_postback))
            $this->_postback = new Icepay_Postback();
        return $this->_postback;
    }

    /**
     * Returns the Icepay_Paramater_Validation class or creates it
     *
     * @since 1.1.0
     * @access public
     * @return \Icepay_ParameterValidation
     */
    public function validate()
    {
        if (!isset($this->_validate))
            $this->_postback = new Icepay_ParameterValidation();
        return $this->_validate;
    }

    /**
     * Returns the current release version
     *
     * @since 1.1.0
     * @access public
     * @return string
     */
    public function getReleaseVersion()
    {
        return $this->_release;
    }

    /**
     * Create an instance
     * @since version 1.0.2
     * @access public
     * @return instance of self
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

}
