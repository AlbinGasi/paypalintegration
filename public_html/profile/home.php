<?php
if(!defined('PROFILE')) die('ERROR');

if(isset($_POST['user_submit'])){
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
	$date = trim($_POST['date']);
	$security_code = trim($_POST['security_code']);

	if($users->editUser($first_name,$last_name,$date,$email,$security_code)){
	  echo "<p class='cfe-text-center success-message'>Profil is updated!</p>";
  }
	$user = $users->get_user_by_id($_SESSION[ID]['user_id'],'none');
}else if (isset($_POST['new_password_submit'])){
	$newPassword = trim($_POST['newPassword']);
	$newPassword2 = trim($_POST['newPassword2']);
	if(!empty($newPassword) && !empty($newPassword2)){
		if($newPassword == $newPassword2){
			$users->updateUser(['set'=>'password','valueset'=>$newPassword,'where'=>'user_id','id'=>$_SESSION[ID]['user_id']]);
			echo "<p class='success-message cfe-text-center'>Success</p>";
		}else{
			echo "<p class='error-message cfe-text-center'>Passwords do not match</p>";
		}
  }else{
		echo "<p class='error-message cfe-text-center'>Can not be empty</p>";
  }
}
?>
<div id="membership-plan">
	<?php
	use PayPal\Api\Payment;
	use PayPal\Api\PaymentExecution;
	if (isset($_GET['membership'], $_GET['paymentId'], $_GET['PayerID'], $_GET['currency'], $_GET['paid']) && $_GET['membership'] == "success") {
		$status = 1;
		$paid = $_GET['paid'];
		$currency = $_GET['currency'];
		$duration = $_GET['d'];

		require 'membership/config-local.php';

		$paymentId = $_GET['paymentId'];
		$payerId = $_GET['PayerID'];

		$payment = Payment::get($paymentId, $paypal);

		$execute = new PaymentExecution();
		$execute->setPayerId($payerId);

		try {
			$result = $payment->execute($execute, $paypal);
		} catch (Exception $e) {
			$data = json_decode($e->getData());
			$status = 0;
		}

		if($status){
			$users->updateUserMembership('Premium membership',$paid,$currency);
			$users->usersMembershipDuration($duration);
			echo '<p class="cfe-text-center success-message">Membership is upgraded</p>';
		}else{
			echo '<p class="cfe-text-center error-message">'.$data->message.'</p>';
		}
	}

	?>
</div>
<div class="col col-12">
  <div id="get-user-details">
<?php
if(isset($_GET['newpassword'])){
?>
  <form action="" method="post">
    <table>
      <tr>
        <td>New password</td>
        <td><input type="password" name="newPassword" placeholder="New password"></td>
      </tr>
      <tr>
        <td>New password again</td>
        <td> <input type="password" name="newPassword2" placeholder="New password again"></td>
      </tr>
      <tr>
        <td></td>
        <td> <input type="submit" class="cfe-list-btn-standard" name="new_password_submit" value="Save"></td>
      </tr>
    </table>
  </form>
<?php
	echo "<p class='cfe-text-center'><a class='cfe-list-btn-standard' href='index.php?sel=profile'>Go back</a></p>";
	echo '<div class="cfe-m-b-40"></div>';
}else {
	?>
  <form action="" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td>Username</td>
        <td><input type="text" name="first_name" value="<?php echo $user['username'] ?>" disabled></td>
      </tr>
      <tr>
        <td>First name</td>
        <td><input type="text" name="first_name" value="<?php echo $user['first_name'] ?>"></td>
      </tr>
      <tr>
        <td>Last name</td>
        <td><input type="text" name="last_name" value="<?php echo $user['last_name'] ?>"></td>
      </tr>
      <tr>
        <td>Email</td>
        <td><input type="email" name="email" value="<?php echo $user['email'] ?>"></td>
      </tr>
      <tr>
        <td>Birthday</td>
        <td><input type="date" name="date" value="<?php echo date( 'Y-m-d', strtotime( $user['born'] ) ) ?>"></td>
      </tr>
      <tr>
        <td>Security code</td>
        <td><input type="password" name="security_code" value="<?php echo $user['security_code'] ?>"></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><a href="index.php?sel=profile&newpassword">Set new password</a></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" class="cfe-list-btn-standard" name="user_submit" value="Save"></td>
      </tr>
    </table>
  </form>
<?php
}
?>
	</div>

	<?php
	$users->is_premium();
	$user = $users->get_user_by_id($_SESSION[ID]['user_id'],'none');
	if($user['user_status'] == 2){
		$profilePlanStatus = "Free membership";
		$profilePlanStatus2 = "Upgrade your membership to premium";
		$button = updateToPremium("UPGRADE");
		$extraMessage = "";
	}else{
		$profilePlanStatus = "Premium memebership";
		$profilePlanStatus2 = "Extend your premium membership";
		$button = updateToPremium("EXTEND");
		if($user['user_status'] != 4){
			$profilePlanStatus = "Advanced user";
			$profilePlanStatus2 = "You don't need to update memebership";
			$button = "";
		}
		$extraMessage = "";
		$extraMessage = $users->membershipExpires();
		$daysLeft = strtotime($extraMessage['duration']) - time();
		$daysLeft = floor($daysLeft / (60 * 60 * 24)+1);

		if(strtotime(explode(" ", $extraMessage['duration'])[0]) == strtotime(date('Y-m-d'))){
			if(strtotime(explode(" ", $extraMessage['duration'])[1]) > strtotime(date('H:i:s'))){
				$daysLeft = dateDiff($extraMessage['duration']);
			}
		}
	}
	?>

	<div id="profile-plan">
		<p class="profile-plan-status"><?php echo $profilePlanStatus ?></p>
		<?php
		if($extraMessage != ""){
			?>
			<table>
				<tr>
					<td>Last Update</td>
					<td><?php echo date("d.m.Y \i\\n H:i", strtotime($extraMessage['dateupdate'])) ?></td>
				</tr>
				<tr>
					<td>Expires</td>
					<td><?php echo date("d.m.Y \i\\n H:i", strtotime($extraMessage['duration'])) ?></td>
				</tr>
				<tr>
					<td>Days left</td>
					<td><?php echo $daysLeft ?></td>
				</tr>
			</table>

		<?php } ?>
		<p class="profile-plan-status"><?php echo $profilePlanStatus2 ?></p>
		<p class="cfe-text-center"></p>
		<?php echo $button ?>

	</div>

	<script>
    function priceVal(val){
      var price;
      var cost = 10;
      val = parseInt(val);
      switch (val) {
        case 1:
          price = cost;
          break;
        case 3:
          price = cost * 3 - 5;
          break;
        case 6:
          price = cost * 6 - 8;
          break;
        case 12:
          price = cost * 12 - 12;
          break;
        default:
          price = "/"
      }

      if(price != "/"){
        document.getElementById("price-val").value = price + " USD";
      }
    }
    priceVal(1);
	</script>
</div>