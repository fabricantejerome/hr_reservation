<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>

	<style type="text/css">
		table {
			border-collapse: collapse;
		}

		table, th, td {
		   border: 1px solid black;
		   padding: 5px;
		   text-align: center
		}

		p {
			line-height: 1.5em;
		}
	</style>
</head>
<body>
	<main class="container">
		<p>
			<?php if (isset($mail)): ?>
				<strong>From: </strong><?php echo $mail->From; ?> <br />
				<strong>To: </strong><?php echo implode('; ', array_filter(array_flatten($mail->getToAddresses(), array()))) ; ?><br />
			<?php endif; ?>
			<?php if (isset($mail) && count($mail->getCcAddresses())): ?>
				<strong>Cc: <?php echo implode('; ', array_filter(array_flatten($mail->getCcAddresses(), array()))) ; ?></strong>
			<?php endif; ?>
		</p>

		<p>/* This is a system-generated e-mail sent by HR Training Room Reservation System. Please do not reply. */</p>

		<p>
			<strong>Subject: </strong><?php echo isset($item['subject']) ? $item['subject'] : '' ?> <br />
			<strong>Sent: </strong> <?php echo date('l, F d, Y h:i A') ?> <br />
			<strong>Requesting Section: </strong> <?php echo isset($item['section']) ? $item['section'] : '' ?> <br />
		</p>

		<div class="table-request">
			<!--   Room table -->
			<table>
				<thead>
					<tr>
						<th>Room</th>
						<th>Purpose</th>
						<th>Capacity</th>
						<th>Floor</th>
						<th>Date</th>
						<th>Time Start</th>
						<th>Time End</th>
						<th>Reserved by</th>
						<?php if(isset($item['date_filed'])): ?>
							<th>Date Filed</th>
						<?php endif; ?>
						<?php if(isset($item['reason'])): ?>
							<th>Reason</th>
						<?php endif; ?>
						<?php if(isset($item['approver'])): ?>
							<th><?php echo $header; ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php if(isset($item['room_name'])): ?>
							<td><?php echo $item['room_name'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['purpose'])): ?>
							<td><?php echo $item['purpose'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['capacity'])): ?>
							<td><?php echo $item['capacity'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['floor'])): ?>
							<td><?php echo $item['floor'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['date_reserved'])): ?>
							<td><?php echo date('m/d/Y', strtotime($item['date_reserved'])); ?></td>
						<?php endif; ?>
						<?php if(isset($item['time_start'])): ?>
							<td><?php echo date('h:i A', strtotime($item['time_start'])) ?></td>
						<?php endif; ?>
						<?php if(isset($item['time_end'])): ?>
							<td><?php echo date('h:i A', strtotime($item['time_end'])) ?></td>
						<?php endif; ?>
						<?php if(isset($item['fullname'])): ?>
							<td><?php echo $item['fullname'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['date_filed'])): ?>
							<td><?php echo date('m/d/Y h:i A', strtotime($item['date_filed'])) ?></td>
						<?php endif; ?>
						<?php if(isset($item['reason'])): ?>
							<td><?php echo $item['reason'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['approver'])): ?>
							<td><?php echo $item['approver'] ?></td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table><!-- End of table -->
		</div>
		
		<?php if (isset($link) && $item['subject'] == 'Request Room Reservation'): ?>
			<p>
				<b>Approval link:</b>  < <a href="<?php echo $link; ?>">Click this to approve the request</a> > <br />
			
				* If the above link won't work, please copy and paste the link below on your browsers address bar * <br />
				<?php echo $link ?>
			</p>
		<?php endif; ?>

		<!-- Don't show the link to all CC addresses -->
		<?php if (isset($link) && $item['subject'] == 'Reminder'): ?>
			<p>
				<p><q>If your meeting was cancelled, kindly withdraw your reservation thru this link.</q></p>
				<b>Cancellation link:</b>  < <a href="<?php echo $link; ?>">Click this to cancel the reservation</a> > <br />
			
				* If the above link won't work, please copy and paste the link below on your browsers address bar * <br />
				<?php echo $link ?>
			</p>
		<?php endif; ?>

	</main>

	<p>/* If you have any questions, please contact HRS. */</p>
	
</body>
</html>