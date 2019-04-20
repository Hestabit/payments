<?php 

namespace Hetsabit\Payments\Handlers;

use Hetsabit\Payments\Lib\Card;
use Hetsabit\Payments\Lib\Details;
use Hetsabit\Payments\Lib\Shipping;
use Hetsabit\Payments\Contracts\BaseSetter;

/*
|--------------------------------------------------------------------------
| This class is used to set and get various data related to payment
| Created By- Hetsabit
|--------------------------------------------------------------------------
*/
class Setter implements BaseSetter{
	protected $tax;
	protected $type;
	protected $card;
	protected $rules;
	protected $price;
	protected $number;
	protected $intent;
	protected $amount;
	protected $reason;
	protected $details;
	protected $quantity;
	protected $currency;
	protected $item_name;
	protected $last_name;
	protected $cancel_url;
	protected $first_name;
	protected $return_url;
	protected $extra_param;
	protected $expire_year;
	protected $description;
	protected $expire_month;
	protected $payment_method;
	protected $shipping_details;
	protected $unique_invoice_num;


	/**
     * @return Hetsabit\Payments\Lib\Shipping
     */
	public function shipping(){
	 	return new Shipping;
	}

	/**
     * @return Hetsabit\Payments\Lib\Card
     */
	public function card(){
	 	return new Card;
	}

	/**
     * @return Hetsabit\Payments\Lib\Details
     */
	public function details(){
	 	return new Details;
	}

	/**
	 * setter for shipping details
	 * @param object
	 * @return object
	 */
	public function setShippingDetails($shipping_details){
		$this->shipping_details = $shipping_details;
		$this->rules['shipping_details'] = $shipping_details;

		return $this;
	}

	/**
	 * setter for card
	 * @param object
	 * @return object
	 */
	public function setCard($card){
		$this->card = $card;
		$this->rules['card'] = $card;

		return $this;
	}

	/**
	 * setter for amount
	 * @param number
	 * @return object
	 */
	public function setAmount($amount){
		$this->amount = $amount;
		$this->rules['amount'] = $amount;

		return $this;
	}

	/**
	 * setter for reason
	 * @param string
	 * @return object
	 */
	public function setReason($reason){
		$this->reason = $reason;
		$this->rules['reason'] = $reason;

		return $this;
	}

	/**
	 * setter for details
	 * @param object
	 * @return object
	 */
	public function setDetails($details){
		$this->details = $details;
		$this->rules['details'] = $details;

		return $this;
	}

	/**
	 * setter for payment method
	 * @param string
	 * @return object
	 */
	public function setPaymentMethod($method){
		$this->payment_method = $method;
		$this->rules['payment_method'] = $method;

		return $this;
	}
	
	/**
	 * setter for return url
	 * @param string
	 * @return object
	 */
	public function setReturnUrl($return_url){
		$this->return_url = $return_url;
		$this->rules['return_url'] = $return_url;

		return $this;
	}

	/**
	 * setter for cancel url
	 * @param string
	 * @return object
	 */
	public function setCancelUrl($cancel_url){
		$this->cancel_url = $cancel_url;
		$this->rules['cancel_url'] = $cancel_url;

		return $this;
	}

	/**
	 * setter for extra parameters
	 * @param object
	 * @return object
	 */
	public function setExtraParam($param){
		$this->extra_param = $param;
		return $this;
	}

	/**
	 * setter for item name
	 * @param string
	 * @return object
	 */
	public function setItemName($item_name){
		$this->item_name = $item_name;
		$this->rules['item_name'] = $item_name;

		return $this;
	}

	/**
	 * setter for invoice number
	 * @param  alpha numeric
	 * @return object
	 */
	public function setInvoiceNumber($unique_invoice_num){
		$this->unique_invoice_num = $unique_invoice_num;
		$this->rules['invoice_num']	= $unique_invoice_num;

		return $this;
	}

	/**
	 * setter for description
	 * @param string
	 * @return object
	 */
	public function setDescription($description){
		$this->description = $description;
		$this->rules['description'] = $description;

		return $this;
	}

	/**
	 * setter for currency
	 * @param string
	 * @return object
	 */
	public function setCurrency($currency){
		$this->currency = $currency;
		$this->rules['currency'] = $currency;

		return $this;
	}

	/**
	 * setter for quantity
	 * @param integer
	 * @return object
	 */
	public function setQuantity($quantity){
		$this->quantity = $quantity;
		$this->rules['quantity'] = $quantity;

		return $this;
	}

	/**
	 * setter for tax
	 * @param number
	 * @return object
	 */
	public function setTax($tax){
		$this->tax = $tax;
		$this->rules['tax'] = $tax;

		return $this;
	}

	/**
	 * setter for payment intent
 	 * Valid Values: ["sale", "authorize", "order"] : default -> sale
	 * @param  string
	 * @return object
	 */
	public function setIntent($intent = 'sale'){
		$this->intent = $intent;
		$this->rules['intent'] = $intent;

		return $this;
	}

	/**
	 * setter for total
	 * @param number
	 * @return object
	 */
	public function setTotal($total){
		$this->total = $total;
		$this->rules['total'] = $total;

		return $this;
	}

	/**
	 * setter for sub-total
	 * @param number
	 * @return object
	 */
	public function setSubTotal($subtotal){
		$this->subtotal = $subtotal;
		$this->rules['subtotal'] = $subtotal;

		return $this;
	}

	/**
	 * setter for price
	 * @param number
	 * @return object
	 */
	public function setPrice($price){
		$this->price = $price;
		$this->rules['price'] = $price;

		return $this;
	}

	/**
	 * getter for tax
	 * @return object
	 */
	public function getTax(){
		return !empty($this->tax) ? $this->tax : 0.0;
	}

	/**
	 * getter for intent
	 * @return object
	 */
	public function getIntent(){
		return !empty($this->intent) ? $this->intent : 'sale';
	}

	/**
	 * getter for description
	 * @return object
	 */
	public function getDescription(){
		return !empty($this->description) ? $this->description : 'Default description.';
	}

	/**
	 * getter for invoice number
	 * @return object
	 */
	public function getInvoiceNumber(){
		return !empty($this->unique_invoice_num) ? $this->unique_invoice_num : uniqid();
	}

	/**
	 * getter for total
	 * @return object
	 */
	public function getTotal(){
		return !empty($this->total) ? $this->total : $this->price;
	}

	/**
	 * getter for subtotal
	 * @return object
	 */
	public function getSubTotal(){
		return !empty($this->subtotal) ? $this->subtotal : $this->price;
	}

	/**
	 * getter for payment method
	 * @return object
	 */
	public function getPaymentMethod(){
		return $this->payment_method;
	}

	/**
	 * getter for quantity
	 * @return object
	 */
	public function getQuantity(){
		return !empty($this->quantity) ? $this->quantity : 1;
	}

	/**
	 * getter for item name
	 * @return object
	 */
	public function getItemName(){
		return $this->item_name;
	}

	/**
	 * getter for currency
	 * @return object
	 */
	public function getCurrency(){
		return !empty($this->currency) ? $this->currency : 'USD';
	}

	/**
	 * getter for price
	 * @return object
	 */
	public function getPrice(){
		return $this->price;
	}

	/**
	 * getter for reason
	 * @return object
	 */
	public function getReason(){
		return $this->reason;
	}

	/**
	 * getter for cancel url
	 * @return object
	 */
	public function getCancelUrl(){
		return $this->cancel_url;
	}

	/**
	 * getter for return url
	 * @return object
	 */
	public function getReturnUrl(){
		return $this->return_url;
	}
	
	/**
	 * getter for type
	 * @return object
	 */
	public function getType(){
		return !empty($this->type) ? $this->type : 'visa';
	}

	/**
	 * getter for extra param
	 * @return object
	 */
	public function getExtraParam(){
		return !empty($this->extra_param) ? $this->extra_param : false;
	}

	/**
	 * getter for extra param
	 * @return object
	 */
	public function getShippingDetails(){
		return $this->shipping_details;
	}

	/**
	 * getter for card
	 * @return object
	 */
	public function getCard(){
		return $this->card;
	}

	/**
	 * getter for amount
	 * @return object
	 */
	public function getAmount(){
		return $this->amount;
	}

	/**
	 * getter for details
	 * @return object
	 */
	public function getDetails(){
		return $this->details;
	}


	/**
	 * setter for rules
	 * used to get all the set parameters
	 */
	public function setRules(){
		$this->rules = [
			'total'				=> $this->getTotal(),
			'price'				=> $this->getPrice(),
			'currency'			=> $this->getCurrency(),
			'quantity'			=> $this->getQuantity(),
			'item_name'			=> $this->getItemName(),
			'return_url'		=> $this->getReturnUrl(),
			'cancel_url'		=> $this->getCancelUrl(),
			'invoice_num'		=> $this->getInvoiceNumber(),
			'description'		=> $this->getDescription(),
			'payment_method'	=> $this->getPaymentMethod(),
		];
	}

	/**
	 * getter for rules
	 * @return object
	 */
	public function getRules(){
		$var = !empty($this->rules) ? $this->rules : [];

    	foreach ($var as $key1 => $val1) {
    		if(is_object($val1)){
    			foreach ($this->rules[$key1] as $key2 => $val2) {
    				$this->rules[$key2] = $val2;
    			}
    		}

    		if(!is_object($val1)){
    			$this->rules[$key1] = $val1;
    		}
    	}

    	return $this->rules;
	}
}