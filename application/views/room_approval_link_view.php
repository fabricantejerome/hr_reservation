<section class="content rooms">
	<div class="row">
		<div class="col-md-10">
			<?php echo $this->session->flashdata('success_message'); ?>
		</div>
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body">
					<!-- Room table -->
					<table id="pending-request" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room</th>
								<th>Purpose</th>
								<th>Reservation Date</th>
								<th>Time Start</th>
								<th>Time End</th>
								<th>Date Filed</th>
								<th>Reserved by</th>
								<th>Section</th>
								<?php if ($this->session->userdata('user_type') == 'admin'): ?>
									<th></th>
								<?php endif; ?>
								<?php if ($this->session->userdata('user_type') == 'requestor'): ?>
									<th></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php if (is_array($row)) : ?>
								<tr>
									<td>1</td>
									<td><?php echo $row['room_name'] ?></td>
									<td><?php echo $row['purpose'] ?></td>
									<td><?php echo date('m/d/Y', strtotime($row['date_reserved'])) ?></td>
									<td><?php echo date('h:i A', strtotime($row['time_start'])) ?></td>
									<td><?php echo date('h:i A', strtotime($row['time_end'])) ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($row['date_filed'])) ?></td>
									<td><?php echo ucwords(strtolower($row['fullname'])); ?></td>
									<td><?php echo $row['section'] ?></td>
									<?php if ($this->session->userdata('user_type') == 'admin'): ?>
										<td>
											<a href="<?php echo base_url('index.php/admin/approved_request/') . $row['id'] ?>">
												<button class="btn btn-flat btn-success">Approve <i class="fa fa-check" aria-hidden="true"></i></button>
											</a>
										</td>
									<?php endif; ?>
									<?php if($this->session->userdata('user_type') == 'requestor'): ?>
										<td>
											<a href="<?php echo base_url('index.php/requestor/reservation_form/') . $row['id'] ?>">
												<button class="btn btn-flat btn-warning">Update</button>
											</a>
										</td>
									<?php endif; ?>
								</tr>
							<?php endif; ?>
						</tbody>
					</table><!-- End of table -->
				</div>
			</div>
		</div>	
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {

		$('#pending-request').DataTable();

	})
</script>