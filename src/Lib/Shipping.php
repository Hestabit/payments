<?php

namespace Hestalabs\Payments\Lib;

/*
|--------------------------------------------------------------------------
| This class is used to set and get various shipping details related to payment
| Created By- Hestalabs
|--------------------------------------------------------------------------
*/
class Shipping{
	public $city;
	public $line1;
	public $phone;
	public $line2;
	public $state;
	public $postal_code;
	public $country_code;
	public $recipient_name;

	/**
	 * setter for line1
	 * @param string
	 * @return  object
	 */
	public function setLine1($line1){
		$this->line1 = $line1;

		return $this;
	}

	/**
	 * setter for line2
	 * @param string
	 * @return  object
	 */
	public function setLine2($line2){
		$this->line2 = $line2;

		return $this;
	}

	/**
	 * setter for city
	 * @param string
	 * @return  object
	 */
	public function setCity($city){
		$this->city = $city;

		return $this;
	}

	/**
	 * setter for state
	 * @param string
	 * @return  object
	 */
	public function setState($state){
		$this->state = $state;

		return $this;
	}

	/**
	 * setter for postal code
	 * @param integer
	 * @return  object
	 */
	public function setPostalCode($postal_code){
		$this->postal_code = $postal_code;

		return $this;
	}

	/**
	 * setter for country code
	 * @param integer
	 * @return  object
	 */
	public function setCountryCode($country_code){
		$this->country_code = $country_code;

		return $this;
	}

	/**
	 * setter for phone
	 * @param string
	 * @return  object
	 */
	public function setPhone($phone){
		$this->phone = $phone;

		return $this;
	}

	/**
	 * setter for recipient name
	 * @param string
	 * @return  object
	 */
	public function setRecipientName($recipient_name){
		$this->recipient_name = $recipient_name;

		return $this;
	}

	/**
	 * getter for line1
	 * @return string
	 */
	public function getLine1(){
		return $this->line1;
	}

	/**
	 * getter for line2
	 * @return string
	 */
	public function getLine2(){
		return $this->line2;
	}

	/**
	 * getter for city
	 * @return string
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * getter for state
	 * @return string
	 */
	public function getState(){
		return $this->state;
	}

	/**
	 * getter for postal code
	 * @return integer
	 */
	public function getPostalCode(){
		return $this->postal_code;
	}

	/**
	 * getter for country code
	 * @return integer
	 */
	public function getCountryCode(){
		return $this->country_code;
	}

	/**
	 * getter for phone
	 * @return string
	 */
	public function getPhone(){
		return $this->phone;
	}

	/**
	 * getter for recipient name
	 * @return string
	 */
	public function getRecipientName(){
		return $this->recipient_name;
	}
}