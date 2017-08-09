<?php //var_dump($rooms); ?>

<section class="content rooms">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<!-- Room table -->
					<table id="cancelled-request" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room</th>
								<th>Purpose</th>
								<th>Date Reserved</th>
								<th>Time Start</th>
								<th>Time End</th>
								<th>Reserved by</th>
								<th>Section</th>
								<th>Date Cancelled</th>
								<th>Cancelled by</th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($requests as $row): ?>
								<tr>
									<td><?php echo $counter ?></td>
									<td><?php echo $row['room_name'] ?></td>
									<td><?php echo $row['purpose'] ?></td>
									<td><?php echo date('m/d/Y', strtotime($row['date_reserved'])); ?></td>
									<td><?php echo date('h:i A', strtotime($row['time_start'])) ?></td>
									<td><?php echo date('h:i A', strtotime($row['time_end'])) ?></td>
									<td><?php echo $row['fullname'] ?></td>
									<td><?php echo $row['section'] ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($row['cancelled_datetime'])) ?></td>
									<td><?php echo $row['approver'] ?></td>
								</tr>
								<?php $counter++; ?>
							<?php endforeach; ?>
						</tbody>
					</table><!-- End of table -->
				</div>
			</div>
		</div>	
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {

		$('#cancelled-request').DataTable();
		
	})
</script>