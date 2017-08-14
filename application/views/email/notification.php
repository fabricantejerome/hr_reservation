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
			border-top: 2px solid #a095ee;
			border-bottom: 2px solid #a095ee;
		}

		footer {
			text-align: center;
			color: #fff;
			background: #a095ee;
			padding: 15px;
		}

		body * {
		  box-sizing: border-box;
		}

		.header {
		  background-color: #a095ee;
		  color: white;
		  font-size: 1.5em;
		  padding: 1rem;
		  text-align: center;
		}

		img {
		  border-radius: 50%;
		  height: 60px;
		  width: 60px;
		}

		.table-request {
		  max-width: calc(100% - 2em);
		  margin: 3em auto;
		  overflow: hidden;
		}

		table {
		  width: 100%;
		}
		table td, table th {
		  color: #2b686e;
		  padding: 10px;
		}
		table td {
		  text-align: center;
		  vertical-align: middle;
		  font-size: 85%;
		}
		table td:last-child {
		  font-size: 0.95em;
		  line-height: 1.4;
		  text-align: left;
		}
		table th {
		  background-color: #efedfc;
		  font-weight: 300;
		}
		table tr:nth-child(2n) {
		  background-color: white;
		}
		table tr:nth-child(2n+1) {
		  background-color: #f7f5fe;
		}

		@media screen and (max-width: 700px) {
		  table, tr, td {
		    display: block;
		  }

		  td:first-child {
		    position: absolute;
		    top: 50%;
		    -webkit-transform: translateY(-50%);
		            transform: translateY(-50%);
		    width: 100px;
		  }
		  td:not(:first-child) {
		    clear: both;
		    margin-left: 100px;
		    padding: 4px 20px 4px 90px;
		    position: relative;
		    text-align: left;
		  }
		  td:not(:first-child):before {
		    color: #91ced4;
		    content: '';
		    display: block;
		    left: 0;
		    position: absolute;
		  }

		  tr {
		    padding: 10px 0;
		    position: relative;
		  }
		  tr:first-child {
		    display: none;
		  }
		}
		@media screen and (max-width: 500px) {
		  .header {
		    background-color: transparent;
		    color: white;
		    font-size: 2em;
		    font-weight: 700;
		    padding: 0;
		    text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
		  }

		  img {
		    border: 3px solid;
		    border-color: #daeff1;
		    height: 100px;
		    margin: 0.5rem 0;
		    width: 100px;
		  }

		  td:first-child {
		    background-color: #c8e7ea;
		    border-bottom: 1px solid #91ced4;
		    border-radius: 10px 10px 0 0;
		    position: relative;
		    top: 0;
		    -webkit-transform: translateY(0);
		            transform: translateY(0);
		    width: 100%;
		  }
		  td:not(:first-child) {
		    margin: 0;
		    padding: 5px 1em;
		    width: 100%;
		  }
		  td:not(:first-child):before {
		    font-size: .8em;
		    padding-top: 0.3em;
		    position: relative;
		  }
		  td:last-child {
		    padding-bottom: 1rem !important;
		  }

		  tr {
		    background-color: white !important;
		    border: 1px solid #6cbec6;
		    border-radius: 10px;
		    box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
		    margin: 0.5rem 0;
		    padding: 0;
		  }

		  .table-request {
		    border: none;
		    box-shadow: none;
		    overflow: visible;
		  }
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
				<strong>To: </strong><?php echo rtrim(implode('; ', array_flatten($mail->getToAddresses(), array())), '; ') ; ?><br />
			<?php endif; ?>
			<?php if (isset($mail) && count($mail->getCcAddresses())): ?>
				<strong>Cc: <?php echo rtrim(implode('; ', array_flatten($mail->getCcAddresses(), array())), '; ') ; ?></strong>
			<?php endif; ?>
		</p>

		<p>/* This is a system-generated e-mail sent by HR Training Room Reservation System. Please do not reply. */</p>

		<p>
			<strong>Subject: </strong><?php echo isset($item['subject']) ? $item['subject'] : '' ?> <br />
			<strong>Sent: </strong> <?php echo date('l, F d, Y h:i A') ?> <br />
			<strong>Section: </strong> <?php echo isset($item['section']) ? $item['section'] : '' ?> <br />
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
							<td><?php echo ucwords(strtolower($item['fullname'])) ?></td>
						<?php endif; ?>
						<?php if(isset($item['date_filed'])): ?>
							<td><?php echo date('m/d/Y h:i A', strtotime($item['date_filed'])) ?></td>
						<?php endif; ?>
						<?php if(isset($item['reason'])): ?>
							<td><?php echo $item['reason'] ?></td>
						<?php endif; ?>
						<?php if(isset($item['approver'])): ?>
							<td><?php echo ucwords(strtolower($item['approver'])) ?></td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table><!-- End of table -->
		</div>
		
		<?php if (isset($link)): ?>
			<p>
				<b>Approval link:</b>  < <a href="<?php echo $link; ?>">Click this to approve the request</a> > <br />
			
				* If the above link won't work, please copy and paste the link below on your browsers address bar * <br />
				<?php echo $link ?>
			</p>
		<?php endif; ?>

	</main>

	<p>/* If you have any questions, please contact HRS Department. */</p>
	
</body>
</html>