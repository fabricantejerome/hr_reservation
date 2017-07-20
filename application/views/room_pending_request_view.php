<?php //var_dump($rooms); ?>
<section class="content rooms">
	<div class="row">
		<div class="col-md-10">
			<div class="box box-danger">
					
				<div class="box-body">
					<!-- Room table -->
					<table id="room_pending_tbl" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Room No.</th>
								<th>Purpose</th>
								<th>Reservation Date</th>
								<th>Time Start</th>
								<th>Time End</th>
								<th>Date Filed</th>
								<th>Reserved by</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($rooms as $row): ?>
								<tr>
									<td><?php echo $counter ?></td>
									<td><?php echo $row->room_no ?></td>
									<td><?php echo $row->purpose ?></td>
									<td><?php echo $row->date_reserved ?></td>
									<td><?php echo $row->time_start ?></td>
									<td><?php echo $row->time_end ?></td>
									<td><?php echo $row->date_filed ?></td>
									<td><?php echo $row->fullname ?></td>
									<td>
										<a href="<?php echo base_url('index.php/admin/approved_request/') . $row->id ?>"><button class="btn btn-flat btn-success">Approve <i class="fa fa-check" aria-hidden="true"></i></button></a>
									</td>
									<td>
										<button class="btn btn-flat btn-danger">Deny <i class="fa fa-times" aria-hidden="true"></i></button>
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