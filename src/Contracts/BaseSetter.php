<?php

namespace Hestalabs\Payments\Contracts;

/**
 * Base setter contract for all the implemented payment gateways
 */
interface BaseSetter{
	/**
	 * setter for payment method
	 * @param string
	 * @return object
	 */
	public function setPaymentMethod($method);

	/**
	 * setter for price
	 * @param number
	 * @return object
	 */
	public function setPrice($method);
	
	/**
	 * setter for total
	 * @param number
	 * @return object
	 */
	public function setTotal($method);
	
	/**
	 * setter for tax
	 * @param number
	 * @return object
	 */
	public function setTax($method);
	
	/**
	 * setter for quentity
	 * @param integer
	 * @return object
	 */
	public function setQuantity($method);
	
	/**
	 * setter for currency
	 * @param string
	 * @return object
	 */
	public function setCurrency($method);
	
	/**
	 * setter for decription
	 * @param string
	 * @return object
	 */
	public function setDescription($method);
	
	/**
	 * setter for invoice number
	 * @param integer
	 * @return object
	 */
	public function setInvoiceNumber($method);
	
	/**
	 * setter for item name
	 * @param string
	 * @return object
	 */
	public function	setItemName($method);
	
	/**
	 * setter for cancel url
	 * @param string
	 * @return object
	 */
	public function setCancelUrl($method);
	
	/**
	 * setter for extra parameters
	 * @param object
	 * @return object
	 */
	public function setExtraParam($extra_param);
	
	/**
	 * setter for reurn url
	 * @param string
	 * @return object
	 */
	public function setReturnUrl($method);

	/**
	 * getter for price
	 * @return object
	 */
	public function getPrice();
	
	/**
	 * getter for cancel url
	 * @return object
	 */
	public function getCancelUrl();
	
	/**
	 * getter for return url
	 * @return object
	 */
	public function getReturnUrl();
	
	/**
	 * getter for extra param
	 * @return object
	 */
	public function getExtraParam();
	
	/**
	 * getter for currency
	 * @return object
	 */
	public function getCurrency();
	
	/**
	 * getter for item name
	 * @return object
	 */
	public function getItemName();
	
	/**
	 * getter for quantity
	 * @return object
	 */
	public function getQuantity();
	
	/**
	 * getter for total
	 * @return object
	 */
	public function getTotal();
	
	/**
	 * getter for invoice number
	 * @return object
	 */
	public function getInvoiceNumber();
	
	/**
	 * getter for description
	 * @return object
	 */
	public function getDescription();
	
	/**
	 * getter for tax
	 * @return object
	 */
	public function getTax();	
}