<?php

namespace Hestalabs\Payments;

use Illuminate\Http\Request;

/**
 |-------------------------------------
 |	Contract for all the implemented payment gateways
 | 	Written by : Hestalabs
 |-------------------------------------
 */
interface Payment{
	/**
	 * payment via gateways
	 */
	public function pay();

	/**
	 * invoice of payement made
	 */
	public function invoice($payment_id);

	/**
	 * refund of transaction made
	 */
	public function refund($transaction_id);
}