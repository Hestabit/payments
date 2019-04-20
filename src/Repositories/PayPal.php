<?php

namespace Hetsabit\Payments\Repositories;

use Validator;	
use PayPal\Api\Sale;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Refund;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Api\RedirectUrls;
use Illuminate\Http\Request;
use Hetsabit\Payments\Handlers\Setter;
use Anouar\Paypalpayment\PaypalPayment;
use Hetsabit\Payments\Payment as PPPayment;

/*
|--------------------------------------------------
| Handling all the payment behaviours via PayPal.
|--------------------------------------------------
| Written By- Hetsabit
*/
class PayPal extends Setter implements PPPayment{
	/**
	 * Initialising the class with PatPal credentials
	 */
	public function __construct(){
		$config = [
            'mode' => config('payments.paypal_payment.mode'),

            'account' => [
                'client_id' => config('payments.paypal_payment.account.client_id'),
                'client_secret' => config('payments.paypal_payment.account.client_secret'),
            ],

            'http' => [
                'connection_time_out' => config('payments.paypal_payment.http.connection_time_out'),
            ],

            'log' => [
                'log_enabled' => config('payments.paypal_payment.log.log_enabled'),
                'file_name' => config('payments.paypal_payment.log.file_name'),
                'log_level' => config('payments.paypal_payment.log.log_level'),
            ],
        ];

		//setup PayPal api context
		$this->paypal = new Paypalpayment($config);

		$this->_api_context = $this->paypal->apiContext(config('payments.paypal_payment.account.client_id'), config('payments.paypal_payment.account.client_secret'));
	}

	/*
	|------------------------------------------------------
	| For paying via paypal(PayPal Express)
	| Written By- Hetsabit
	|------------------------------------------------------
	| @var line1 			: apha_numeric
	| @var line2 			: apha_numeric
	| @var city 			: string
	| @var state			: string
	| @var postal_code		: integer
	| @var country_code 	: integer
	| @var phone 			: integer
	| @var recipient_name	: string
	| @var type				: string
	| @var number			: integer
	| @var expire_month 	: integer
	| @var expire_year 		: integer
	| @var cvv				: integer
	| @var first_name		: string
	| @var last_name		: string
	| @var shipping_charge 	: numeric
	| @var shipping_tax 	: numeric
	| @var subtotal			: numeric
	| @var tax				: numeric
	| @var price			: numeric
	| @var quantity			: integer
	| @var currency 		: string  (Default : USD)
	| @var item_name 		: string
	| @var return_url		: string
	| @var cancel_url		: string
	| @var intent 			: string  (Default : sale)
	| @var description		: string  (Default : No description avaliable.)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects	|
	| //for shipping details 					: (All are optional fields in shipping)
    | $shipping = \Payment::shipping();
    | $shipping->setLine1("3909 Witmer Road")
    | ->setLine2("Niagara Falls")
    | ->setCity("Niagara Falls")
    | ->setState("NY")
    | ->setPostalCode("14305")
    | ->setCountryCode("US")
    | ->setPhone("716-298-1822")
    | ->setRecipientName("Jhone");
	|	
	| //if user wants to add some extra details  	: (All fields are optional in details)
	| $details = \Payment::details();
    | $details//>setShippingCharge(1.0)				: numeric (Optional)
    | ->setShippingTax(1.0)							: numeric (Optional)
    | ->setSubtotal(2.0);							: numeric (Optional)
	|
	| //making payment
	| $pay = \Payment::setTax(0.2)					: numeric (Optional)
    | ->setPrice(1)                           		: numeric (Required)
    | ->setQuantity(1)								: numeric (Optional)(Default : 1)
    | ->setSubTotal(1)								: string  (Optional, If no tax provided)(Default : equals to total)
    | ->setTotal(1.0)                           	: numeric (Required)
    | ->setCurrency('USD')							: string  (Optional)(Default : 'USD')(e.g USD, AUD)
    | ->setDescription('New description')			: string  (Optional)(Default : 'Default description.')
    | ->setItemName('New Items for shopping') 		: string  (Required)
    | ->setShippingDetails($shipping)				: object  (Optional)(If shipping initialised, then Required)
	| ->setReturnUrl(url('/payment?success=true'))	: string  (Optional)(if payment_method is paypal, then Required)(Must be a full and clean URl)
    | ->setCancelUrl(url('/payment?success=false'))	: string  (Optional)(if payment_method is paypal, then Required)(Must be a full and clean URl)
    | ->setExtraParam([		
    |		'url' => true 							: object  (Optional)(If user want only redirection-link of payment, then mark it true)
    |  ])
    |  ->setIntent('sale')							: string  (Optional)(Default : sale)(e.g. sale, order, authorize)
    |  ->setDetails($details)						: object  (Optional)(If details are initialised, then Required)
    |  ->pay();
	*/
	public function pay(){
		try{
			$rules = !empty($this->rules) ? $this->getRules($this->rules) : [];

			$required_arr = [
				'tax'				=> 'numeric|min:0.0',
				'city'				=> 'string',
				'total' 			=> 'required|numeric|min:0.1',
				'line1'				=> 'string',
				'line2'				=> 'string',
				'state'				=> 'string',
				'price'				=> 'required|numeric|min:0.1',	
				'intent'			=> 'string',
				'subtotal'			=> 'numeric|min:0.0',
				'quantity'			=> 'integer|min:1',
				'currency'			=> 'string|min:3',
				'last_name'			=> 'string',
				'item_name'			=> 'required|string',				
				'first_name'		=> 'string',	
				'description'		=> 'string',
				'postal_code'		=> 'integer',		
				'country_code'		=> 'string',
				'shipping_tax'		=> 'numeric|min:0.0',	
				'recipient_name'	=> 'string',		
				'shipping_charge'	=> 'numeric|min:0.0',		
			];

			//if payment via credit card
			if(!empty($this->getCard())){
				return $this->payWithCard();
			}

			$required_arr = [
				'return_url'	=> 'required|url',
				'cancel_url'	=> 'required|url',
			];

			$validator = Validator::make($rules, $required_arr);

			/**
			 * If validator fails
			 */
			if($validator->fails()){
				return dd($validator->messages()->toArray());
			}

	        $payer = $this->paypal
	        ->payer();

	        $payer->setPaymentMethod('paypal');

	        /**
	         * Collecting item's details
	         * @var object
	         */
	        $item = $this->paypal
	        ->item();

	        $item->setName($this->getItemName())
            ->setDescription($this->getDescription())
            ->setCurrency($this->getCurrency())
            ->setQuantity($this->getQuantity())
            ->setTax($this->getTax())
            ->setPrice($this->getPrice());

	        $itemList = $this->paypal
	        ->itemList();

	        $itemList->setItems([ $item ]);
	        
	        //if shipping details are provided
	        if(!empty($this->getShippingDetails())){
	        	$itemList->setShippingAddress($this->getShippingDetails());
	        }

			//Payment Amount
	        $amount = $this->paypal
	        ->amount();

	        $amount->setCurrency($this->getCurrency())
            ->setTotal($this->getTotal());
            
            //if details are provided
            if(!empty($this->getDetails())){
            	$amount->setDetails($this->getDetails());
            }

            /**
             * Transaction details
             * @var object
             */
	        $transaction = $this->paypal->transaction();
	        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->getDescription())
            ->setInvoiceNumber($this->getInvoiceNumber());

	        $payment =  $this->paypal->payment();

	        /**
	         * If user have Non-US based account, then the redirect URLs must be given
	         * 
	         */
        	$redirect_urls = new RedirectUrls();

	        $redirect_urls->setReturnUrl($this->getReturnUrl()) //Specify return URL
	        ->setCancelUrl($this->getCancelUrl());				//Specify cancel URL

	        $payment->setPayer($payer)
	        ->setIntent($this->getIntent())            
            ->setTransactions([$transaction]);

            /**
             * In case of paypal-checkout
             * setting the redirect URLs
             */
            $payment->setRedirectUrls($redirect_urls);

	        try {
	        	/**
	        	 * making final payment
	        	 */
	            $payment->create($this->_api_context);
	            
	            /**
	             * if user want only URL of redirectiction
	             * In case of implementation of API
	             * provide $this->extra_param[
	             * 		'url' => true
	             * ]
	             */
	            if($this->extra_param['url']){
	            	/**
	            	 * returns the approval link of payment
	            	 */
	            	return $payment->getApprovalLink();
	            }	            
	        } 
	        catch (\PPConnectionException $ex) {
	            return $ex->getMessage();
	        }

	        /**
	         * redirecting the user to PayPal checkout
	         * @var string
	         */
	        $approval_url = $payment->getApprovalLink();
	        header("Location: {$approval_url}");
	        
	        exit();
		}
		catch(\Exception $e){
			return $e;
		}
	}

	/*
	|------------------------------------------------------
	| For paying via credit_card
	| Written By- Hetsabit
	|------------------------------------------------------
	| @var line1 			: apha_numeric
	| @var line2 			: apha_numeric
	| @var city 			: string
	| @var state			: string
	| @var postal_code		: integer
	| @var country_code 	: integer
	| @var phone 			: integer
	| @var recipient_name	: string
	| @var type				: string
	| @var number			: integer
	| @var expire_month 	: integer
	| @var expire_year 		: integer
	| @var cvv				: integer
	| @var first_name		: string
	| @var last_name		: string
	| @var shipping_charge 	: numeric
	| @var shipping_tax 	: numeric
	| @var subtotal			: numeric
	| @var tax				: numeric
	| @var price			: numeric
	| @var quantity			: integer
	| @var currency 		: string  (Default : USD)
	| @var item_name 		: string
	| @var intent 			: string  (Default : sale)
	| @var description		: string  (Default : No description avaliable.)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects	|
	| //for shipping details 					: (All are optional fields in shipping)
    | $shipping = \Payment::shipping();
    | $shipping->setLine1("3909 Witmer Road")
    | ->setLine2("Niagara Falls")
    | ->setCity("Niagara Falls")
    | ->setState("NY")
    | ->setPostalCode("14305")
    | ->setCountryCode("US")
    | ->setPhone("716-298-1822")
    | ->setRecipientName("Jhone");
	|	
	| //if making payment via credit_card 			: (Required, when you select payment_method as credit_card)
	| $card = \Payment::card();
    | $card->setType('visa')						: string  (Optional)
    | ->setNumber(4032038634486363)					: longInt (Required)
    | ->setExpireMonth(11)							: integer (Required)
    | ->setExpireYear(2023)							: integer (Required)
    | ->setCvv(123)									: integer (Required)
    | ->setFirstName('Abhishek')					: string  (Optional)
    | ->setLastName('Rana');						: string  (Optional)
	|	
	| //if user wants to add some extra details  	: (All fields are optional in details)
	| $details = \Payment::details();
    | $details//>setShippingCharge(1.0)				: numeric (Optional)
    | ->setShippingTax(1.0)							: numeric (Optional)
    | ->setSubtotal(2.0);							: numeric (Optional)
	|
	| //making payment
	| $pay = \Payment::setTax(0.2)					: numeric (Optional)
	| ->setPrice(1)									: numeric (Required)
    | ->setAmount(1)                           		: numeric (Required)
    | ->setQuantity(1)								: numeric (Optional)(Default : 1)
    | ->setSubTotal(1)								: string  (Optional, If no tax provided)(Default : equals to total)
    | ->setCurrency('USD')							: string  (Optional)(Default : 'USD')(e.g USD, AUD)
    | ->setDescription('New description')			: string  (Optional)(Default : 'Default description.')
    | ->setItemName('New Items for shopping') 		: string  (Required)
    | ->setShippingDetails($shipping)				: object  (Optional)(If shipping initialised, then Required)
    | ->setCard($card)								: object  (Optional)(If payment_method is credit_card, then Required)
 	| ->setIntent('sale')							: string  (Optional)(Default : sale)(e.g. sale, order, authorize)
    | ->setDetails($details)						: object  (Optional)(If details are initialised, then Required)
    | ->pay();
	*/
	public function payWithCard(){
		try{
			$rules = !empty($this->rules) ? $this->getRules($this->rules) : [];

			$required_arr = [
				'tax'				=> 'numeric|min:0.0',
				'cvv2' 				=> 'required|integer|digits:3',
				'city'				=> 'string',
				'line1'				=> 'string',
				'line2'				=> 'string',
				'state'				=> 'string',
				'price'				=> 'required|numeric|min:0.1',	
				'intent'			=> 'string',
				'amount' 			=> 'required|numeric',
				'number' 			=> 'required|integer|min:1',
				'subtotal'			=> 'numeric|min:0.0',
				'quantity'			=> 'integer|min:1',
				'currency'			=> 'string|min:3',
				'last_name'			=> 'string',
				'item_name'			=> 'required|string',			
				'first_name'		=> 'string',	
				'description'		=> 'string',
				'postal_code'		=> 'integer',		
				'expire_year'		=> 'required|integer|min:1|digits:4',
				'expire_month' 		=> 'required|numeric|min:1|max:12|digits_between:1, 2',
				'country_code'		=> 'string',
				'shipping_tax'		=> 'numeric|min:0.0',	
				'recipient_name'	=> 'string',		
				'shipping_charge'	=> 'numeric|min:0.0',		
			];
			
			$validator = Validator::make($rules, $required_arr , [
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

			$payer = $this->paypal
	        ->payer();

	        $payer->setPaymentMethod('credit_card');

	        $funding_instrument = $this->paypal
		    ->fundingInstrument();

		    $funding_instrument->setCreditCard($this->getCard());	//setting card details
			$payer->setFundingInstruments([$funding_instrument]);

			$item = $this->paypal
	        ->item();

	        $item->setName($this->getItemName())
            ->setDescription($this->getDescription())
            ->setCurrency($this->getCurrency())
            ->setQuantity($this->getQuantity())
            ->setTax($this->getTax())
            ->setPrice($this->getPrice());

	        $itemList = $this->paypal
	        ->itemList();

	        $itemList->setItems([ $item ]);
	        
	        //if shipping details are provided
	        if(!empty($this->getShippingDetails())){
	        	$itemList->setShippingAddress($this->getShippingDetails());
	        }

	        //Payment Amount
	        $amount = $this->paypal
	        ->amount();

	        $amount->setCurrency($this->getCurrency())
            ->setTotal($this->getAmount());
            
            //if details are provided
            if(!empty($this->getDetails())){
            	$amount->setDetails($this->getDetails());
            }

            /**
             * Transaction details
             * @var object
             */
	        $transaction = $this->paypal->transaction();//Paypalpayment::transaction();
	        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->getDescription())
            ->setInvoiceNumber($this->getInvoiceNumber());

	        $payment =  $this->paypal->payment();
	        $payment->setPayer($payer)
	        ->setIntent($this->getIntent())            
            ->setTransactions([$transaction]);

            try {
	        	/**
	        	 * making final payment
	        	 */
	            $payment->create($this->_api_context);	          
	           
	            return $payment;	
	        } 
	        catch (\PPConnectionException $ex) {
	            return $ex->getMessage();
	        }
		}
		catch(\Exception $e){
			return $e;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Getting the details of given transaction
	| Written By- Hetsabit
	|--------------------------------------------------------------------------
	| @var payment_id 	: integer
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects
	|
	| $payment_id = 'PAY-1JK339012V705711ALPC3QSI';  	: Required(fetch this id, when you are making transaction and store it safe)	
    | $payment_details = \Payment::invoice($payment_id);
	*/
	public function invoice($payment_id){
		try{			
			return  $this->paypal
			->getById($payment_id, $this->_api_context);	
		}
		catch(\Exception $e){
			return $e;
		}
	}

    /*
	|--------------------------------------------------------
	| To refund the paid amount
	| Written By- Hetsabit
	|--------------------------------------------------------
	| @var reason 			: string 
	| @var amount 			: numeric
	| @var transaction_id	: integer
	|--------------------------------------------------------
	| Below are the example how you can set the objects
	|
	| $transaction_id = '3S7574554G079694D'; : Required(fetch this id, when you are making transaction and store)
	| $refund = \Payment::setAmount(0.11)
    | ->setCurrency('USD')
    | ->setReason('to refund the amount reason')
    | ->refund($transaction_id);  
	*/
	public function refund($transaction_id){
		try{
	        $amt = new Amount();
			$amt->setTotal($this->getAmount()) 		//in paypal its 'total', but we are using 'amount' for the sake of simplicity
			->setCurrency($this->getCurrency());

			$refund = new Refund();
			$refund->setAmount($amt)
			->setReason($this->getReason());

			$sale = new Sale();
			$sale->setId($transaction_id);

			return $sale->refund($refund, $this->_api_context);
		}
		catch(\Exception $e){
			return $e;
		}
	}
}