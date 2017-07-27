<section class="content rooms">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-danger">
				<?php if ($this->session->userdata('user_type') == 'admin'): ?>
					<div class="box-header with-border">
						<?php echo $this->session->flashdata('success_message'); ?>
						<a href="<?php echo base_url('index.php/admin/room_form') ?>">
							<button class="btn btn-flat btn-danger pull-right">Add Room <i class="fa fw fa-plus" aria-hidden="true"></i></button>
						</a>
					</div>
				<?php endif; ?>
					
				<div class="box-body">
					<!-- Room table -->
					<table id="room-tbl" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room Name</th>
								<th>Capacity</th>
								<th>Combined Capacity</th>
								<th>Floor</th>
								<th>Available</th>
								<?php if ($this->session->userdata('user_type') == 'admin'): ?>
									<th></th>
									<th></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($rooms as $row): ?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td><?php echo $row->room_name; ?></td>
									<td><?php echo $row->capacity; ?></td>
									<td><?php echo $row->description; ?></td>
									<td><?php echo $row->floor; ?></td>
									<td><?php echo $row->available ? 'Yes' : 'No'; ?></td>
									<?php if ($this->session->userdata('user_type') == 'admin'): ?>
										<td>
											<a href="<?php echo base_url('index.php/admin/room_form/' . $row->id) ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										</td>
										<td>
											<a href="<?php echo base_url('index.php/admin/room_delete/' . $row->id) ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</td>
									<?php endif; ?>
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

		$('#room-tbl').DataTable();
		
	})
</script>
