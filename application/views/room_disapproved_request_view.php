<?php //var_dump($rooms); ?>

<section class="content rooms">
	<div class="row">
		<div class="col-md-10">
			<div class="box box-danger">
					
				<div class="box-body">
					<!-- Room table -->
					<table id="disapproved-request" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room No.</th>
								<th>Purpose</th>
								<th>Date</th>
								<th>Time Start</th>
								<th>Time End</th>
								<th>Reserved by</th>
								<th>Date Denied</th>
								<th>Reason</th>
								<th>Denied by</th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($requests as $row): ?>
								<tr>
									<td><?php echo $counter ?></td>
									<td><?php echo $row->room_no ?></td>
									<td><?php echo $row->purpose ?></td>
									<td><?php echo date('m/d/Y', strtotime($row->date_reserved)) ?></td>
									<td><?php echo $row->time_start ?></td>
									<td><?php echo $row->time_end ?></td>
									<td><?php echo $row->fullname ?></td>
									<td><?php echo date('m/d/Y H:i:s', strtotime($row->denied_datetime)) ?></td>
									<td><?php echo $row->reason ?></td>
									<td><?php echo $row->approver ?></td>
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

		$('#disapproved-request').DataTable();
		
	})
</script>