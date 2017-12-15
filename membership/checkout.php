<?php
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

require 'config.php';

if (isset($_POST['btn_membership']) && !empty($_POST['period'])) {
	$period = $_POST['period'];
	$currency = "USD";
	$availablePreiod = [1, 3, 6, 12];

	if(!in_array($period, $availablePreiod)) die("Period not support");
	$cost = 10.00;
	switch ($period) {
		case 1:
			$price = $cost;
			break;
		case 3:
			$price = $cost * 3 - 5;
			break;
		case 6:
			$price = $cost * 6 - 8;
			break;
		case 12:
			$price = $cost * 12 - 12;
			break;
		default:
			$price = 10;
	}

	$shipping = 0.00;
	$total = $price + $shipping;

	$payer = new Payer();
	$payer->setPaymentMethod('paypal');

	$item = new Item();
	$item->setName('Membership')
	     ->setCurrency($currency)
	     ->setQuantity(1)
	     ->setPrice($price);

	$itemList = new ItemList();
	$itemList->setItems([$item]);

	$details = new Details();
	$details->setShipping($shipping)
	        ->setSubtotal($price);

	$amount = new Amount();
	$amount->setCurrency($currency)
	       ->setTotal($total)
	       ->setDetails($details);

	$transaction = new Transaction();
	$transaction->setAmount($amount)
	            ->setItemList($itemList)
	            ->setDescription('Premium membership')
	            ->setInvoiceNumber(uniqid());

	$redirectUrls = new RedirectUrls();
	$redirectUrls->setReturnUrl(ABSPATH . '/index.php?sel=profile&membership=success&paid='.$total.'&currency='.$currency.'&d='.$period)
	             ->setCancelUrl(ABSPATH . '/index.php?sel=profile&membership=error');

	$payment = new Payment();
	$payment->setIntent('sale')
	        ->setPayer($payer)
	        ->setRedirectUrls($redirectUrls)
	        ->setTransactions([$transaction]);

	try{
		$payment->create($paypal);
	} catch (PayPal\Exception\PayPalConnectionException $ex) {
		echo $ex->getCode(); // Prints the Error Code
		echo $ex->getData(); // Prints the detailed error message
		die($ex);
	} catch (Exception $e) {
		die($e);
	}

	$approvalUrl = $payment->getApprovalLink();

	header("Location: {$approvalUrl}");

}