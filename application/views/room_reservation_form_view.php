<?php 
	//var_dump($this->session->userdata());
?>
<style type="text/css">
	/* Control Button */
	.control-btn > .btn.btn-flat {
		margin: 10px;
	}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<section class="content rooms">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-danger">					
				<div class="box-body">
					<!-- Form -->
					<form action="<?php echo base_url('index.php/requestor/reservation_submit'); ?>" method="post">
						<div class="form-group">
							<label for="room_id">Room No.</label>
							<select name="room_id" id="room_id" class="form-control select2" data-live-search="true" required>
								<option></option>
								<?php foreach($rooms as $row): ?>
									<option value="<?php echo $row->id; ?>"><?php echo $row->room_no; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div claxss="form-group">
							<label for="date_reserved">Reservation Date</label>
							<div class="input-group date">
							    <input type="text" class="form-control datepicker" name="date_reserved" value="<?php echo date('m/d/Y'); ?>" required>
							    <div class="input-group-addon">
							        <span class="glyphicon glyphicon-th"></span>
							    </div>
							</div>
						</div>

						<div class="form-group">
							<label for="time_start">Time Start</label>
							<input type="text" name="time_start" id="time_start" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="time_end">Time End</label>
							<input type="text" name="time_end" id="time_end" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="purpose">Purpose</label>
							<textarea class="form-control" id="purpose" name="purpose" rows="3"><?php echo isset($room['purpose']) ? $room['purpose'] : ''; ?></textarea>
						</div>

						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-flat btn-danger">
						</div>
					</form><!-- End Form -->
				</div>
			</div>
		</div>

		<div class="col-md-9" >
			<div id="taken_slot" class="hidden box box-danger">
				<!-- Room table -->
				<table id="room_tbl" class="table table-condensed table-striped table-bordered" >
					<thead>
						<tr>
							<th>Purpose</th>
							<th>Date Reserved</th>
							<th>Time Start</th>
							<th>Time End</th>
							<th>Date Filed</th>
							<th>Reserved by</th>
						</tr>
					</thead>
					<tbody id="content-area">

					</tbody>
				</table><!-- End of table -->
			</div>
		</div>
	</div>
</section>
<script src="<?php echo base_url('resources/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#time_start").inputmask("h:s",{ "placeholder": "hh/mm" });

		$("#time_end").inputmask("h:s",{ "placeholder": "hh/mm" });

		$('.datepicker').datepicker({
			dateFormat: 'mm/dd/yy'
		});

		$('#room_id').on('change', function() {

			var $self = $(this);

			$.ajax({
				type: 'GET',
				url: '<?php echo base_url('index.php/requestor/show_room_reserved/') ;?>' + $self.val(),
				success: function(data) 
				{
					
					if ($.isEmptyObject(data))
					{
						$("#taken_slot").addClass('hidden');
					}
					else 
					{
						var data = $.parseJSON(data);

						console.log(data);

						var markup = '';

						for (item of data)
						{
							markup += '<tr>\n'; 
							markup += '<td>' + item.purpose + '</td>\n';
							markup += '<td>' + item.date_reserved + '</td>\n';
							markup += '<td>' + item.time_start + '</td>\n';
							markup += '<td>' + item.time_end + '</td>\n';
							markup += '<td>' + item.date_filed + '</td>\n';
							markup += '<td>' + item.fullname + '</td>\n';
							markup += '<tr>';
						}


						$('#content-area').html(markup);
						$("#taken_slot").removeClass('hidden');
					}
					
				}
			});

		});
	});
</script>