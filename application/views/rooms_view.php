<section class="content rooms">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-info">
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
								<th>Room</th>
								<th>Capacity</th>
								<!-- <th style="border-right: 0; border-right-color: white">Combined </th>
								<th style="border-left: 0; border-left-color: white">Capacity</th> -->
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

									<?php //$description = explode('/', $row->description); ?>

									<?php //if(in_array(trim($row->room_name), array('Crosswind Room', 'Trooper Room', 'Alterra Room', 'mu-X Room A', 'mu-X Room B', 'Sportivo Room'))): ?>
										<!-- <td class="text-center" colspan="2"><?php //echo isset($description[0]) ? $description[0] : ''; ?></td>
										<td class="hidden"></td> -->
									<?php //else: ?>
										<!-- <td class="text-center"><?php //echo isset($description[0]) ? $description[0] : ''; ?></td>
										<td class="text-center"><?php //echo isset($description[1]) ? $description[1] : ''; ?></td>
									<?php //endif; ?> -->
									
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

		<!-- Table description -->
		<div class="col-md-6">
			<img src="<?php echo base_url('resources/images/room_description.png');?>" class="img-responsive">
		</div>	
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {

		$('#room-tbl').DataTable();
		
	})
</script>
