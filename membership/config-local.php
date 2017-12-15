<?php
require_once ('payment/vendor/autoload.php');

/*
	first - clientID
	second - secret
*/

$paypal = new \PayPal\Rest\ApiContext(
	new \PayPal\Auth\OAuthTokenCredential(
		'AdAO4BLBGacF1T2rOOzSsWTWlolusHLIcmvUKtn_oU_74WGpR4uy9Krx8HUhX9rmao0ZitbVdBm2SbCQ',
		'EIqQbmW2HEPx3g0Rs00hPeyVn0EMDMG7D3Fzo3eyCkZNBb_cKFkTos5cPepPhoZuQktGRPwvyX3lQRq2'
	)
);

?>