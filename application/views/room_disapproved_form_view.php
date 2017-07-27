<section class="content rooms">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-info">					
				<div class="box-body">
					<!-- Form -->
					<form action="<?php echo base_url('index.php/admin/disapproved_request'); ?>" method="post">

						<div class="form-group">
							<label for="reason">Reason</label>
							<textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>

							<input type="text" class="form-control hidden" name="room_res_id" value="<?php echo $room_res_id ?>">
						</div>

						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-flat btn-danger">
						</div>
					</form>
					<!-- End Form -->
				</div>
			</div>
		</div>	
	</div>
</section>
