<?php

/**
 * Icepay_Order_Product
 *
 * The product object contains all information about the customers address
 * You can add as many products as you want, just remember that the total amount for the products must match the total amount of the Icepay Payment Object
 *
 * @version 1.0.0
 *
 * @author Wouter van Tilburg
 * @author Olaf Abbenhuis
 * @copyright Copyright (c) 2011-2012, ICEPAY
 */
class Icepay_Order_Product {

    public $productID = '00';
    public $productName = '';
    public $description = '';
    public $quantity = '1';
    public $unitPrice = '0';
    public $VATCategory = 'standard';

    /**
     * Creates and returns a new Icepay_Order_Product
     *
     * @since 1.0.0
     * @return \Icepay_Order_Product
     */
    public static function create() {
        return new self();
    }

    /**
     * Sets the product ID
     *
     * @since 1.0.0
     * @param string Contains the product ID
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setProductID($productID) {
        if (empty($productID))
            throw new Exception('Product ID must be set and cannot be empty.');

        $this->productID = trim($productID);

        return $this;
    }

    /**
     * Sets the product name
     *
     * @since 1.0.0
     * @param string Contains the product name
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setProductName($productName) {
        if (empty($productName))
            throw new Exception('Product name must be set and cannot be empty.');

        $this->productName = trim($productName);

        return $this;
    }

    /**
     * Sets the product description
     *
     * @since 1.0.0
     * @param string Contains the product discription
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setDescription($description) {
        $this->description = trim($description);

        return $this;
    }

    /**
     * Sets the product quantity
     *
     * @since 1.0.0
     * @param string Contains the quantity of the product
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setQuantity($quantity) {
        if (empty($quantity))
            throw new Exception('Quantity must be set and cannot be empty.');

        $this->quantity = trim($quantity);

        return $this;
    }

    /**
     * Sets the product unit price
     *
     * @since 1.0.0
     * @param string Contains the unitprice in cents
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setUnitPrice($unitPrice) {
        $this->unitPrice = trim($unitPrice);

        return $this;
    }

    /**
     * Sets the product's VAT Category
     *
     * @since 1.0.0
     * @param string Contains the VAT Category (Choices are: zero, reduced-low, reduced-middle, standard)
     * @return \Icepay_Order_Product
     * @throws Exception when empty
     */
    public function setVATCategory($vatCategory) {
        if (empty($vatCategory))
            throw new Exception('VAT Category must be set and cannot be empty.');

        $this->VATCategory = $vatCategory;

        return $this;
    }

}
