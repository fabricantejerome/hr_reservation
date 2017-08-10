<?php //var_dump($rooms); ?>

<section class="content rooms">
	<div class="row">
		<div class="col-md-10">
			<?php echo $this->session->flashdata('success_message'); ?>
		</div>
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<!-- Room table -->
					<table id="approved-request" class="table table-condensed table-striped table-bordered" >
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
								<th>Date Approved</th>
								<th>Approved by</th>
								<th></th>
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
									<td><?php echo ucwords(strtolower($row['fullname'])) ?></td>
									<td><?php echo $row['section'] ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($row['approved_datetime'])) ?></td>
									<td><?php echo ucwords(strtolower($row['approver'])) ?></td>
									<td>
										<a href="<?php echo base_url('index.php/admin/cancel_request/') . $row['room_res_id'] ?>">
											<button class="btn btn-flat btn-warning">Cancel <i class="fa fa-times" aria-hidden="true"></i></button>
										</a>
									</td>
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

		$('#approved-request').DataTable();
		
	})
</script>