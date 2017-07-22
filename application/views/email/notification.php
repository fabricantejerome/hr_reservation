<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>

	<style type="text/css">
		body {
			font-family: "Helvetica Nue", sans-serif;
		}

		.container {
			width: 960px;
			margin: auto;
			color: #77787A;
		}

		h2 {
			text-align: center;
			padding: 20px;
			border-top: 2px solid #85a2ff;
			border-bottom: 2px solid #85a2ff;
		}

		footer {
			text-align: center;
			color: #fff;
			background: #85a2ff;
			padding: 15px;
		}
		.box {
			margin: 50px 0;
		}
	</style>

	<link href="<?php echo base_url('resources/templates/bootstrap-3.3.7/css/bootstrap.min.css');?>" rel="stylesheet" >
</head>
<body>
	<main class="container">
		<h2>HR Training Room Reservation</h2>

		<div class="box box-danger">
				
			<div class="box-body">
				<!-- Room table -->
				<table class="table table-condensed table-striped table-bordered" >
					<thead>
						<tr>
							<th>Room No.</th>
							<th>Purpose</th>
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
								<th><?php echo isset($header) ? $header : 'Approved by'?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $item['room_no'] ?></td>
							<td><?php echo $item['purpose'] ?></td>
							<td><?php echo $item['date_reserved'] ?></td>
							<td><?php echo $item['time_start'] ?></td>
							<td><?php echo $item['time_end'] ?></td>
							<td><?php echo $item['fullname'] ?></td>
							<?php if(isset($item['date_filed'])): ?>
								<td><?php echo $item['date_filed'] ?></td>
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
		</div>
		
		<p>This is an automatically generated message sent by HR Traning Room Reservation System.</p>

	</main>

	<div class="container">
		<footer>
			<small>&copy; 2017 HR Training Room Reservation. All Rights Reserved.</small>
		</footer>
	</div>
	
</body>
</html>