<?php

/**
 * @package API_Webservice_Reporting
 */
class Icepay_Webservice_Reporting extends Icepay_Webservice_Base {

    protected $service = 'https://connect.icepay.com/webservice/report.svc?wsdl';
    protected $_autokill = false;
    protected $_sessionName = "icepay_api_webservice_reportingsession";
    protected $_session;
    protected $_username;
    protected $_useragent;
    protected $_cookie;
    protected $_phpsession;

    public function __construct()
    {
        $this->setupClient();
    }

    public function __destruct()
    {
        if ($this->_autokill)
            $this->killSession();
    }

    /**
     * Set the Session ID field
     * @since version 2.1.0
     * @access public
     * @param string $val
     */
    public function setSessionID($val)
    {
        $this->_session = $val;
        return $this;
    }

    /**
     * Get the SessionID field
     * @since version 2.1.0
     * @access public
     * @return (string)session
     */
    public function getSessionID()
    {
        return $this->_session;
    }

    /**
     * Set the Username field
     * @since version 2.1.0
     * @access public
     * @param string $val
     */
    public function setUsername($val)
    {
        $this->_username = $val;
        return $this;
    }

    /**
     * Get the Username field
     * @since version 2.1.0
     * @access private
     * @return (string)username
     */
    private function getUsername()
    {
        return $this->_username;
    }

    /**
     * Set autokill to true or false
     * @since version 2.1.0
     * @access public
     * @param bool $bool
     */
    public function autoKill($bool)
    {
        $this->_autokill = $bool;
        return $this;
    }

    /**
     * Set the User Agent field
     * @since version 2.1.0
     * @access public
     * @param string $val
     */
    public function setUserAgent($val)
    {
        $this->_useragent = $val;
        return $this;
    }

    /**
     * Get the User Agent field
     * @since version 2.1.0
     * @access public
     * @return (string)useragent
     */
    private function getUserAgent()
    {
        return $this->_useragent;
    }

    /*
     * Make use of Cookies
     *
     * @since 2.1.0
     * @access public
     * @param bool $bool
     */

    public function useCookie($bool = true)
    {
        $this->_cookie = $bool;
        return $this;
    }

    /*
     * Make use of PHP Sessions
     *
     * @since 2.1.0
     * @access public
     * @param bool $bool
     */

    public function usePHPSession($bool = true)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_phpsession = $bool;
        return $this;
    }

    /*
     * Creates the PHP Session
     *
     * @since 2.1.0
     * @access public
     * @param bool $sessionID
     */

    public function createPHPSession($sessionID = true)
    {
        if ($sessionID) {
            $_SESSION[$this->_sessionName] = $this->_session;
        }
        return $this;
    }

    /*
     * Read the PHP Session
     *
     * @since 2.1.0
     * @access private
     * @param bool $sessionID
     * @return bool
     */

    private function readFromPHPSession($sessionID = true)
    {
        if ($sessionID) {
            if (isset($_SESSION[$this->_sessionName]) && $_SESSION[$this->_sessionName] != "") {
                $this->_session = $_SESSION[$this->_sessionName]->SessionID;
                return true;
            }
        }
        return false;
    }

    /*
     * Unsets the php Session
     *
     * @since 2.1.0
     * @access private
     */

    private function unsetPHPSession()
    {
        unset($_SESSION[$this->_sessionName]);
    }

    /**
     * Create Cookie
     *
     * @since 2.1.0
     * @access public
     * @return obj this
     */
    public function createCookie($cookie = true)
    {
        if ($cookie) {
            $cookietime = time() + (60 * 60 * 24 * 365);
            setcookie($this->_sessionName . "_SessionID", $this->_session->SessionID, $cookietime);
            setcookie($this->_sessionName . "_Timestamp", $this->_session->Timestamp, $cookietime);
        }

        return $this;
    }

    /**
     * Read Cookie
     *
     * @since 2.1.0
     * @access public
     * @return bool
     */
    private function readFromCookie($cookie = true)
    {
        if ($cookie) {
            if (isset($_COOKIE[$this->_sessionName . "_SessionID"])) {
                $this->_session = $_COOKIE[$this->_sessionName . "_SessionID"];
                return true;
            }
        }

        return false;
    }

    /*
     * Unset cookie
     *
     * @since 2.1.0
     * @access public     *
     */

    public function unsetCookie()
    {
        setcookie("icepay_api_webservice_reportingsession_SessionID", '', time() - 1000);
        setcookie("icepay_api_webservice_reportingsession_Timestamp", '', time() - 1000);
    }

    public function initSession()
    {
        if ($this->_cookie && $this->readFromCookie())
            return true;
        if ($this->_phpsession && $this->readFromPHPSession())
            return true;
        return $this->createSession();
    }

    /**
     * Create Session
     *
     * @since 2.1.0
     * @access public
     * @return (array)session
     */
    public function createSession()
    {
        $obj = new stdClass();

        $obj->Timestamp = $this->getTimeStamp();
        $obj->Username = $this->getUsername();
        $obj->PinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();

        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // PinCode only used for the Checksum
        unset($obj->PinCode);

        // Create the session and get the response
        $response = $this->client->CreateSession($obj);
        $result = $response->CreateSessionResult;

        // Verify response data by making a new Checksum
        $res = new stdClass();
        // Must be in specific order for checksum -----
        $res->Timestamp = $result->Timestamp;
        $res->SessionID = $result->SessionID;
        $res->PinCode = $this->getPinCode();
        $res->Success = $result->Success;
        $res->Description = $result->Description;
        // --------------------------------------------
        $checkSum = $this->generateChecksum($res);

        // Compare Checksums
        if ($result->Checksum != $checkSum)
            throw new Exception("Data could not be verified");

        // Assign Session
        $this->_session = $result;

        $this->createCookie(true);
        $this->createPHPSession(true);

        // Return Respsonse
        return (array) $response;
    }

    /*
     * Set the session name
     *
     * @since 2.1.0
     * @access public
     */

    public function setSessionName($name = "icepay_api_webservice_reportingsession")
    {
        $this->_sessionName = $name;
    }

    /*
     * Get the Session Timestamp
     *
     * @since 2.1.0
     * @access private
     * @return string $timestamp
     */

    private function getSessionTimestamp()
    {
        if ($this->_phpsession && isset($_SESSION[$this->_sessionName]->SessionID))
            $timestamp = $_SESSION[$this->_sessionName]->SessionID;


        if ($this->_cookie && isset($_COOKIE[$this->_sessionName . "_Timestamp"]))
            $timestamp = $_COOKIE[$this->_sessionName . "_Timestamp"];

        return $timestamp;
    }

    /*
     * Kill the current session
     *
     * @since 2.1.0
     * @access public
     * @return array $session
     */

    public function killSession()
    {
        $obj = new stdClass();

        $obj->Timestamp = $this->getSessionTimestamp();
        $obj->SessionID = $this->getSessionID();
        $obj->PinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();

        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // PinCode only used for the Checksum
        unset($obj->Pincode);

        $session = $this->client->KillSession($obj);

        $this->unsetCookie();
        $this->unsetPHPSession();

        $this->_session = null;
        return (array) $session;
    }

    /**
     * The MonthlyTurnoverTotals web method returns the sum of the turnover of all the transactions according
     * to the provided criteria: month, year and currency.
     *
     * @since version 2.1.0
     * @access public
     * @param int $month !required
     * @param int $year !required
     * @param string $currency
     */
    public function monthlyTurnoverTotals($month, $year, $currency = "EUR")
    {
        if ($month == "" || !is_numeric($month))
            throw new Exception('Please enter a valid month');
        if ($year == "" || !is_numeric($year))
            throw new Exception('Please enter a valid year');

        $obj = new stdClass();

        // Must be in specific order for checksum ------
        $obj->Timestamp = $this->getTimeStamp();
        $obj->SessionID = $this->getSessionID();
        $obj->ReportingPinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();
        $obj->MerchantID = $this->getMerchantID();
        $obj->CurrencyCode = $currency;
        $obj->Year = $year;
        $obj->Month = $month;
        // ---------------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // PinCode only used for the Checksum
        unset($obj->PinCode);

        // Ask for MonthlyTurnoverTotals and get response
        $result = $this->client->MonthlyTurnoverTotals($obj);
        $result = $result->MonthlyTurnoverTotalsResult;
        $dayStats = $result->Days->DayStatistics;

        $obj->Timestamp = $result->Timestamp;

        // Assign all properties of the DayStatistics object as property of mainObject
        $obj = $this->parseForChecksum($obj, $dayStats, true, array("Year", "Month", "Day", "Duration", "TransactionsCount", "Turnover"));

        // Unset properties for new Checksum
        unset($obj->MerchantID, $obj->Checksum);

        // Verify response data by making a new Checksum
        $Checksum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $Checksum)
            throw new Exception('Data could not be verified');

        return (array) $dayStats;
    }

    /**
     * The getMerchant web method returns a list of merchants that belong to your ICEPAY account.
     *
     * @since version 2.1.0
     * @access public
     * @return array
     */
    public function getMerchants()
    {
        $obj = new stdClass();

        // Must be in specific order for checksum --
        $obj->Timestamp = $this->getTimeStamp();
        $obj->SessionID = $this->getSessionID();
        $obj->PinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();
        // -----------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Ask for getMerchants and get response
        $result = $this->client->getMerChants($obj);
        $result = $result->GetMerchantsResult;
        $merchants = isset($result->Merchants->Merchant) ? $result->Merchants->Merchant : null;

        $obj->Timestamp = $result->Timestamp;

        if (!is_null($merchants)) {
            // Assign all properties of the Merchants object as property of mainObject
            $obj = $this->parseForChecksum($obj, $merchants, true, array("MerchantID", "Description", "TestMode"));
        }

        // Unset properties for new Checksum
        unset($obj->Checksum);

        // Verify response data by making a new Checksum
        $Checksum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $Checksum)
            throw new Exception('Data could not be verified');

        return (array) $merchants;
    }

    /**
     * The getPaymentMethods web method returns a list of  all  supported payment methods by ICEPAY.
     *
     * @since version 2.1.0
     * @access public
     */
    public function getPaymentMethods()
    {
        $obj = new stdClass();

        // Must be in specific order for checksum --
        $obj->Timestamp = $this->getTimeStamp();
        $obj->SessionID = $this->getSessionID();
        $obj->PinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();
        // -----------------------------------------
        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Ask for GetPaymentMethods and get response
        $result = $this->client->GetPaymentMethods($obj);
        $result = $result->GetPaymentMethodsResult;
        $methods = isset($result->PaymentMethods->PaymentMethod) ? $result->PaymentMethods->PaymentMethod : null;

        $obj->Timestamp = $result->Timestamp;

        if (!is_null($methods)) {
            // Assign all properties of the PaymentMethods object as property of mainObject
            $obj = $this->parseForChecksum($obj, $methods);
        }

        // Unset properties for new Checksum
        unset($obj->Checksum);

        // Verify response data by making a new Checksum
        $CheckSum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $CheckSum)
            throw new Exception('Data could not be verified');

        return (array) $methods;
    }

    /**
     * The searchPayments web method allows you to search for payments linked to your ICEPAY account. There are
     * several filters which you can employ for a more detailed search.
     *
     * @since version 2.1.0
     * @access public
     * @param array searchOptions
     * @return array
     */
    public function searchPayments($searchOptions = array())
    {

        $obj = new stdClass();
        // Must be in specific order for checksum ----------
        $obj->Timestamp = $this->getTimeStamp();
        $obj->SessionID = $this->getSessionID();
        $obj->PinCode = $this->getPinCode();
        $obj->UserAgent = $this->getUserAgent();
        $obj->MerchantID = null;
        $obj->PaymentID = null;
        $obj->OrderID = null;
        $obj->Reference = null;
        $obj->Description = null;
        $obj->Status = null;
        $obj->OrderTime1 = null;
        $obj->OrderTime2 = null;
        $obj->PaymentTime1 = null;
        $obj->PaymentTime2 = null;
        $obj->CountryCode = null;
        $obj->CurrencyCode = null;
        $obj->Amount = null;
        $obj->PaymentMethod = null;
        $obj->ConsumerAccountNumber = null;
        $obj->ConsumerName = null;
        $obj->ConsumerAddress = null;
        $obj->ConsumerHouseNumber = null;
        $obj->ConsumerPostCode = null;
        $obj->ConsumerCity = null;
        $obj->ConsumerCountry = null;
        $obj->ConsumerEmail = null;
        $obj->ConsumerPhoneNumber = null;
        $obj->ConsumerIPAddress = null;
        $obj->Page = (int) 1;
        // ------------------------------------------------

        if (!empty($searchOptions)) {
            foreach ($searchOptions as $key => $filter) {
                $obj->$key = $filter;
            }
        }

        // Generate Checksum
        $obj->Checksum = $this->generateChecksum($obj);

        // Properties only used for the Checksum
        unset($obj->PinCode);

        // Ask for SearchPayments and get response
        $result = $this->client->SearchPayments($obj);
        $result = $result->SearchPaymentsResult;

        $searchResults = isset($result->Payments->Payment) ? $result->Payments->Payment : null;

        $obj = new stdClass();
        $obj->Timestamp = $result->Timestamp;
        $obj->SessionID = $this->getSessionID();
        $obj->ReportingPinCode = $this->getPinCode();

        if (!is_null($searchResults)) {
            // Assign all properties of the sub object(s) as property of mainObject
            $obj = $this->parseForChecksum($obj, $searchResults, true, array(
                "Amount", "ConsumerAccountNumber", "ConsumerAddress", "ConsumerHouseNumber", "ConsumerName",
                "ConsumerPostCode", "CountryCode", "CurrencyCode", "Duration", "MerchantID", "OrderTime",
                "PaymentID", "PaymentMethod", "PaymentTime", "Status", "StatusCode", "TestMode"
            ));
        }

        // Verify response data by making a new Checksum
        $CheckSum = $this->generateChecksum($obj);

        // Compare Checksums
        if ($result->Checksum != $CheckSum)
            throw new Exception('Data could not be verified');

        return (array) $searchResults;
    }

}
