<?php

return [
	'payment_type' => env('PAYMENT_TYPE', 'paypal'),
    'stripe' => [
        'key'       => env('STRIPE_KEY'),
        'secret'    => env('STRIPE_SECRET'),
    ],
    'paypal_payment' => [
    	# Define your application mode here
	    'mode' => env('PAYPAL_MODE', 'sandbox'),  //"sanbox" for testing and "live" for production

	    # Account credentials from developer portal
	    'account' => [
	        'client_id'		=> env('PAYPAL_CLIENT_ID', ''),
	        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
	    ],

	    # Connection Information
	    'http' => [
	        'retry' => 1,
	        'connection_time_out' => 30,
	    ],

	    # Logging Information
	    'log' => [
	        'log_enabled' => true,

	        # When using a relative path, the log file is created
	        # relative to the .php file that is the entry point
	        # for this request. You can also provide an absolute
	        # path here
	        'file_name' => '../PayPal.log',

	        # Logging level can be one of FINE, INFO, WARN or ERROR
	        # Logging is most verbose in the 'FINE' level and
	        # decreases as you proceed towards ERROR
	        'log_level' => 'FINE',
	    ],
    ],
];