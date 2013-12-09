<?php

/**
 * Icepay_Order
 *
 * Contains all the order information and can generate it into XML for the extended checkout.
 *
 * @version 1.0.0
 *
 * @author Wouter van Tilburg
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order {

    private $_orderData;
    private $_consumerNode;
    private $_addressesNode;
    private $_productsNode;
    private static $instance;
    private $_debug = false;
    public $_data = Array();

    public function setData($id, $obj) {
        $this->_data[$id] = $obj;
    }

    /**
     * Sets the consumer information for the order
     *
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Consumer class
     * @return \Icepay_Order
     */
    public function setConsumer(Icepay_Order_Consumer $obj) {
        $this->setData("consumer", $obj);
        return $this;
    }

    /**
     * Sets the shipping address for the order
     *
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Address class
     * @return \Icepay_Order
     */
    public function setShippingAddress(Icepay_Order_Address $obj) {
        $this->setData("shippingAddress", $obj);
        return $this;
    }

    /**
     * Sets the billing address for the order
     *
     * @since 1.0.0
     * @param obj Object containing the Icepay_Order_Address class
     * @return \Icepay_Order
     */
    public function setBillingAddress(Icepay_Order_Address $obj) {
        $this->setData("billingAddress", $obj);
        return $this;
    }

    /**
     * Adds a product to the order
     *
     * @since 1.0.0
     * @param obj object containing the Icepay_Order_Product class
     * @return \Icepay_Order
     */
    public function addProduct(Icepay_Order_Product $obj) {
        if (!isset($this->_data["products"]))
            $this->_data["products"] = Array();
        array_push($this->_data["products"], $obj);
        return $this;
    }

    /**
     * Sets the order discount
     *
     * @since 1.0.0
     * @param string $amount Contains the discount amount in cents
     * @param string $name Contains the name of the discount
     * @param string $description Contains description of the discount
     * @return \Icepay_Order
     */
    public function setOrderDiscountAmount($amount, $name = 'Discount', $description = 'Order Discount') {
        $obj = Icepay_Order_Product::create()
            ->setProductID('02')
            ->setProductName($name)
            ->setDescription($description)
            ->setQuantity('1')
            ->setUnitPrice(-$amount)
            ->setVATCategory(Icepay_Order_VAT::getCategoryForPercentage(-1));

        $this->addProduct($obj);

        return $this;
    }

    /**
     * Sets the order shipping costs
     *
     * @since 1.0.0
     * @param string $amount Contains the shipping costs in cents
     * @param int $vat Contains the VAT category in percentages
     * @param string $name Contains the shipping name
     * @return \Icepay_Order
     */
    public function setShippingCosts($amount, $vat = -1, $name = 'Shipping Costs') {
        $obj = Icepay_Order_Product::create()
            ->setProductID('01')
            ->setProductName($name)
            ->setDescription('')
            ->setQuantity('1')
            ->setUnitPrice($amount)
            ->setVATCategory(Icepay_Order_VAT::getCategoryForPercentage($vat));

        $this->addProduct($obj);

        return $this;
    }

    /**
     * Validates the Order
     *
     * <p>Validates the order information based on the paymentmethod and country used</p>
     * <p>For example Afterpay, it will check the zipcodes and it makes sure that the billing and shipping address are in the same country</p>
     *
     * @param obj $paymentObj
     * @throws Exception
     */
    public function validateOrder($paymentObj) {
        switch (strtoupper($paymentObj->getPaymentMethod())) {
            case 'AFTERPAY':
                if ($this->_data['shippingAddress']->country !== $this->_data['billingAddress']->country)
                    throw new Exception('Billing and Shipping country must be equal in order to use Afterpay.');

                if (!Icepay_Order_Helper::validateZipCode($this->_data['shippingAddress']->zipCode, $this->_data['shippingAddress']->country))
                    throw new Exception('Zipcode format for shipping address is incorrect.');

                if (!Icepay_Order_Helper::validateZipCode($this->_data['billingAddress']->zipCode, $this->_data['billingAddress']->country))
                    throw new Exception('Zipcode format for billing address is incorrect.');

                if (!Icepay_Order_Helper::validatePhonenumber($this->_data['consumer']->phone))
                    throw new Exception('Phonenumber is incorrect.');

                break;
        }
    }

    private function array_to_xml($childs, $node = 'Order') {
        $childs = (array) $childs;

        foreach ($childs as $key => $value) {
            $node->addChild(ucfirst($key), $value);
        }

        return $node;
    }

    /**
     * Generates the XML for the webservice
     *
     * @since 1.0.0
     * @return XML
     */
    public function createXML() {

        $this->_orderData = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><Order></Order>");
        $this->_consumerNode = $this->_orderData->addChild('Consumer');
        $this->_addressesNode = $this->_orderData->addChild('Addresses');
        $this->_productsNode = $this->_orderData->addChild('Products');

        // Set Consumer
        $this->array_to_xml($this->_data['consumer'], $this->_consumerNode);

        // Set Addresses
        $shippingNode = $this->_addressesNode->addChild('Address');
        $shippingNode->addAttribute('id', 'shipping');

        $this->array_to_xml($this->_data['shippingAddress'], $shippingNode);

        $billingNode = $this->_addressesNode->addChild('Address');
        $billingNode->addAttribute('id', 'billing');

        $this->array_to_xml($this->_data['billingAddress'], $billingNode);

        // Set Products
        foreach ($this->_data['products'] as $product) {
            $productNode = $this->_productsNode->addChild('Product');
            $this->array_to_xml($product, $productNode);
        }

        if ($this->_debug == true) {
            header("Content-type: text/xml");
            echo $this->_orderData->asXML();
            exit;
        }

        return $this->_orderData->asXML();
    }

    public static function getInstance() {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

}
