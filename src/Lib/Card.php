<?php

namespace Hestalabs\Payments\Lib;

/*
|--------------------------------------------------------------------------
| This class is used to set and get various card details related to payment
| Created By- Hestalabs
|--------------------------------------------------------------------------
*/
class Card{
	/**
	 * setter for first name
	 * @param string
	 * @return object
	 */
	public function setFirstname($first_name){
		$this->first_name = $first_name;

		return $this;
	}

	/**
	 * setter for last name
	 * @param string
	 * @return object
	 */
	public function setLastname($last_name){
		$this->last_name = $last_name;

		return $this;
	}

	/**
	 * setter for cvv
	 * @param integer
	 * @return object
	 */
	public function setCvv($cvv){
		$this->cvv2 = $cvv;

		return $this;
	}

	/**
	 * setter for expiry year
	 * @param integer
	 * @return object
	 */
	public function setExpireYear($expire_year){
		$this->expire_year = $expire_year;

		return $this;
	}

	/**
	 * setter for expiry month
	 * @param integer
	 * @return object
	 */
	public function setExpireMonth($expire_month){
		$this->expire_month = $expire_month;

		return $this;
	}

	/**
	 * setter for card number
	 * @param number
	 * @return object
	 */
	public function setNumber($number){
		$this->number = $number;

		return $this;
	}

	/**
	 * setter for type
	 * visa, mastercard : default -> visa
	 * @param string
	 * @return object
	 */
	public function setType($type){
		$this->type = $type;

		return $this;
	}


	/**
	 * getter for first name
	 * @return string
	 */
	public function getFirstname(){
		return $this->first_name;
	}
	
	/**
	 * getter for last name
	 * @return string
	 */
	public function getLastname(){
		return $this->last_name;
	}
	
	/**
	 * getter for cvv
	 * @return integer
	 */
	public function getCvv(){
		return $this->cvv2;
	}
	
	/**
	 * getter for expiry year
	 * @return integer
	 */
	public function getExpireYear(){
		return $this->expire_year;
	}
	
	/**
	 * getter for expiry month
	 * @return integer
	 */
	public function getExpireMonth(){
		return $this->expire_month;
	}
	
	/**
	 * getter for card number
	 * @return integer
	 */
	public function getNumber(){
		return $this->number;
	}
}