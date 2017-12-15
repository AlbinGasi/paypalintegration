<?php if(!defined('PROFILE')) die('ERROR');
if(!$users->is_admin()) die("<p class='error-message cfe-text-center'>You don't have privileges!</p>")
?>
<div class="col col-12">
	<table>
		<thead>
		<tr>
			<th>Pay</th>
			<th>Payment name</th>
			<th>Paid</th>
			<th>Currency</th>
			<th>Date</th>

		</tr>
		</thead>
		<tbody>
		<?php

		foreach ($transactions->getAllTransactions() as $transaction){
			echo "<tr>";
			echo "<td>" . $transaction['full_name'] . "</td>";
			echo "<td>" . $transaction['payment_name'] . "</td>";
			echo "<td>" . $transaction['paid'] . "</td>";
			echo "<td>" . $transaction['currency'] . "</td>";
			echo "<td>" . date('d.m.Y \i\\n H:i',strtotime($transaction['date'])) . "</td>";
			echo "</tr>";
		}
		?>
		</tbody>
	</table>
</div>
