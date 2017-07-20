<style type="text/css">
	/* Control Button */
	.control-btn {
		padding: 10px;
	}
</style>
<section class="content rooms">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="control-btn">
					<a href="<?php echo base_url('index.php/admin/room_form') ?>">
						<button class="btn btn-flat btn-danger pull-right">Add Room <i class="fa fw fa-plus" aria-hidden="true"></i></button>
					</a>
				</div>
					
				<div class="box-body">
					<!-- Room table -->
					<table id="room-tbl" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room No.</th>
								<th>Room</th>
								<th>Capacity</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($rooms as $row): ?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td><?php echo $row->room_no; ?></td>
									<td><?php echo $row->room_name; ?></td>
									<td><?php echo $row->capacity; ?></td>
									<td>
										<a href="<?php echo base_url('index.php/admin/room_form/' . $row->id) ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
									</td>
									<td>
										<a href="<?php echo base_url('index.php/admin/room_delete/' . $row->id) ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

		$('#room-tbl').DataTable();
		
	})
</script>
