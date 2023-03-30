<?php
require('stripe/stripe-php/init.php');

// $publishableKey="pk_test_51MRxg8BVXH98GRBW3K1OH8z6j6WTJe37RCDk9UWznyYOS9KiiKwaWbrtSNiGXeNh93Whee28sa8x9GTIaCH30X4z00JPLqmlfC";


// $secretKey="sk_test_51MqvJ6DaZYlKuMDpvrzhuUjMRN4UTBs31kkFKk8xpk39Hyx6MMedix6BZZ2HQxLxOWNWg8x7TUozPD94CffQD3No00iWphPala";


$secretKey = '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';

\Stripe\Stripe::setApiKey($secretKey);









?>