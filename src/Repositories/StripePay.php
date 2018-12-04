<?php

namespace Hestalabs\Payments\Repositories;

use Validator;
use Cartalyst\Stripe\Stripe;
use Hestalabs\Payments\Payment;
use Hestalabs\Payments\Handlers\Setter;

/*
|--------------------------------------------------------------------------
| Handling all the payment behaviours via Stripe.
|--------------------------------------------------------------------------|
| Written By- Hestalabs
*/
class StripePay extends Setter implements Payment{
	/*
	|--------------------------------------------------------------------------
	| Payment via Stripe with a given credit card
	| Written By- Hestalabs
	|--------------------------------------------------------------------------
	| @var number 			: integer (Required)
	| @var expiry_year 		: integer (Required)
	| @var expiry_month		: integer (Required)
	| @var cvv				: integer (Required)
	| @var amount			: integer (Required)
	| @var description		: string  (Optional) (Default : No description avaliable.)
	|--------------------------------------------------------------------------
	| Below is the example, how you can set the objects
	| //initialising the card object
	| $card = \Payment::card();
	| $card->setNumber(4242424242424242) 	: integer (Required)
	| ->setExpireYear(2020) 				: integer (Required)
	| ->setExpireMonth(06) 					: integer (Required)
	| ->setCvv(314);						: integer (Required)
	|	
	| //making payments
	| $pay = \Payment::setAmount(1) 		: integer (Required)
	| ->setDescription('for whatever') 		: string  (Optional)
	| ->setCard($card)						: object  (Required)
	| ->pay();
	*/
    public function pay(){
    	try{
	    	$rules = !empty($this->rules) ? $this->getRules($this->rules) : [];

		    $validator = Validator::make($rules, [
				'cvv2' 			=> 'required|integer|digits:3',
				"number" 		=> 'required|integer|min:1',
				'amount' 		=> 'required|numeric|min:0.1',
				'expire_year'	=> 'required|integer|min:1|digits:4',
				'expire_month' 	=> 'required|numeric|min:1|max:12|digits_between:1, 2',
				'description'	=> 'string'
			], [
				'number.min'		=> 'Card number must be postive.',
				'expire_year.min'	=> 'Expire year must be postive.',
				'expire_month.min'	=> 'Expire month must be postive.',
				'expire_month.digits_between' => 'Expire month must be two digits.',
			]);

			/**
			 * If validator fails
			 */
			if($validator->fails()){
				return dd($validator->messages()->toArray());
			}

			$stripe = Stripe::make(config('payments.stripe.secret'));

			try {
				$token = $stripe->tokens()
				->create([
					'card' => [
						'number' 	=> $this->getCard()->number,
						'exp_month' => $this->getCard()->expire_month,
						'exp_year' 	=> $this->getCard()->expire_year,
						'cvc' 		=> $this->getCard()->cvv2
					],
				]);
				
				if (!isset($token['id'])) {
					return 'There are some technical issues, transaction not able to take place';
				}

				$charge = $stripe->charges()
				->create([
					'card' 			=> $token['id'],
					'currency' 		=> $this->getCurrency(),
					'amount' 		=> $this->getAmount(),
					'description' 	=> $this->getDescription(),
				]);

				if($charge['status'] == 'succeeded') {					
					return $charge;
				} 
				else {
					return 'Not able to connect to Stripe.';
				}
			} 
			catch (\Exception $e) {
				return $e;
			} 
			catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
				return $e;
			} 
			catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
				return $e;
			}
		}
		catch(\Exception $e){
			return $e;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Getting the details of any given transaction
	| Written By- Hestalabs
	|--------------------------------------------------------------------------
	| @var transaction_id	: integer (Required)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects
	| $payment_id = 'ch_1DK0jWB108n83JtHxlqSCqrK';  : Required(fetch this id, when you are making transaction and store it safe)
	| 	
    | $payment_details = \Payment::invoice($payment_id);
	*/
	public function invoice($payment_id){
		try{
			$stripe = Stripe::make(config('payments.stripe.secret'));

			$charge = $stripe->charges()
			->find($payment_id);

			return $charge;
		}
		catch(\Exception $e){
			return $e;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| To refund the paid amount
	| Written By- Hestalabs
	|--------------------------------------------------------------------------
	| @var reason 			: string  (Optional) (e.g. duplicate, fraudulent, requested_by_customer) (default : requested_by_customer)
	| @var amount 			: numeric (Required)
	| @var transaction_id	: integer (Required)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects
	|  
	| $transaction_id = 'ch_1DK0j1B108n83JtH236d8aH5'; : Required(fetch this id, when you are making transaction and store)
	| $refund = \Payment::setAmount(1)
    | ->setReason('duplicate')
    | ->refund($transaction_id); 
	*/
	public function refund($transaction_id){
		try{
			$stripe = Stripe::make(config('payments.stripe.secret'));

			$refund = $stripe->refunds()
			->create($transaction_id, $this->getAmount(), [
				'reason' => !empty($this->getReason()) ? $this->getReason() : 'requested_by_customer'
			]);

			return $refund;
		}
		catch(\Exception $e){
			return $e;
		}
	}
}