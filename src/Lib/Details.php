<?php

namespace Hestalabs\Payments\Lib;

/*
|--------------------------------------------------------------------------
| This class is used to set and get various details related to payment
| Created By- Hestalabs
|--------------------------------------------------------------------------
*/
class Details{
	/**
	 * setter for shipping charge
	 * @param number
	 * @return object
	 */
	public function setShippingCharge($shipping_charge){
		$this->shipping = $shipping_charge;

		return $this;
	}

	/**
	 * setter for shipping tax
	 * @param number
	 * @return object
	 */
	public function setShippingTax($shipping_tax){
		$this->tax = $shipping_tax;

		return $this;
	}

	/**
	 * setter for sub total
	 * @param number
	 * @return object
	 */
	public function setSubtotal($subtotal){
		$this->subtotal = $subtotal;

		return $this;
	}

	/**
	 * getter for shipping charge
	 * @return number
	 */
	public function getShippingCharge(){
		return $this->shipping;
	}

	/**
	 * getter for shipping tax
	 * @return number
	 */
	public function getShippingTax(){
		return $this->tax;
	}

	/**
	 * getter for sub total
	 * @return number
	 */
	public function getSubtotal(){
		return $this->subtotal;
	}
}