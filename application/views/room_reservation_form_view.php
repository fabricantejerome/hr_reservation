<?php 
	//var_dump($this->session->userdata());
?>
<link href="<?php echo base_url('resources/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" >
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/timepicker/jquery.timepicker.css'); ?>">
<section class="content rooms">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-danger">					
				<div class="box-body">
					<!-- Form -->
					<?php echo $this->session->flashdata('success_message'); ?>
					<?php echo $this->session->flashdata('error_message'); ?>
					<form action="<?php echo base_url('index.php/requestor/reservation_submit'); ?>" method="post" id="form">
						<div class="form-group">
							<label for="room_id">Room Name</label>
							<select name="room_id" id="room_id" class="form-control select2" data-live-search="true" required>
								<option></option>
								<?php foreach($rooms as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($item['room_id']) ? $row->id == $item['room_id'] ? 'selected' : '' : ''; ?> ><?php echo $row->room_name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div claxss="form-group">
							<label for="date_reserved">Reservation Date</label>
							<div class="input-group date">
							    <input type="text" class="form-control datepicker" name="date_reserved" id="date_reserved" value="<?php echo isset($item['date_reserved']) ? date('m/d/Y', strtotime($item['date_reserved'])) :  date('m/d/Y'); ?>" required>
							    <div class="input-group-addon">
							        <span class="glyphicon glyphicon-th"></span>
							    </div>
							</div>
						</div>

						<div class="form-group">
							<label for="time_start">Time Start</label>
							<input type="text" name="time_start" id="time_start" class="form-control time ui-timepicker-input" value="<?php echo isset($item['time_start']) ? $item['time_start'] : ''; ?>" required>
						</div>

						<div class="form-group">
							<label for="time_end">Time End</label>
							<input type="text" name="time_end" id="time_end" class="form-control time ui-timepicker-input" value="<?php echo isset($item['time_end']) ? $item['time_end'] : ''; ?>" required>
						</div>

						<div class="form-group">
							<label for="purpose">Purpose</label>
							<textarea class="form-control" id="purpose" name="purpose" rows="3" required><?php echo isset($item['purpose']) ? $item['purpose'] : ''; ?></textarea>

							<input type="text" name="id" id="id" class="hidden" value="<?php echo isset($item['id']) ? $item['id'] : 0; ?>" required>
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
							<th>Reserved by</th>
							<th>Approved by</th>
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
<script src="<?php echo base_url('resources/plugins/select2/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/timepicker/jquery.timepicker.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var $time_start = $('#time_start');
		var $time_end   = $('#time_end');
		var $form       = $('#form');
		var today       = new Date();

		console.log(today);

		$("select").select2({ width: 'resolve' });

		$("#date_reserved").datepicker({ 
			startDate: today
		}).on('change', function()
		{
			var $self    = $(this);
			var $room_id = $('#room_id');
			//alert($self.val() + $room_id.val());
		});

		$('#time_start').timepicker({
			/*timeFormat: 'H:i',
			'disableTimeRanges': [
		        ['1:00:00', '2:00:00'],
		        ['3:00:00', '4:00:01']
		    ]*/
		});

		$('#time_end').timepicker({
			//timeFormat: 'H:i'
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
							markup += '<td>' + convertTo12HourFormat(item.time_start) + '</td>\n';
							markup += '<td>' + convertTo12HourFormat(item.time_end) + '</td>\n';
							markup += '<td>' + item.fullname + '</td>\n';
							markup += '<td>' + item.approver + '</td>\n';
							markup += '<tr>';
						}


						$('#content-area').html(markup);
						$("#taken_slot").removeClass('hidden');
					}
					
				}
			});

		});

		$time_start.on('change', function() {
			$time_end.val($(this).val());
		});

		$form.on('submit', function(e) {

			var start_time = convertToSec($time_start.val());
			var end_time = convertToSec($time_end.val())

			if (start_time >= end_time) {
				e.preventDefault();
				alert('End Time must be greater than start time.');
			}
		
		});

		function convertToSec(t)
		{
			var time = t.split(':');

			return (time[0] * 60 * 60) + (time[1] * 60);
		}

		function convertTo12HourFormat(t) {
			var time = t.split(':');

			var h = time[0] % 12;

			var format = '';
			if (time[0] == 12)
			{
				format = time[0] + ':' +  time[1] + ' ' + 'PM';
			}
			else if(time[0] > 12)
			{
				format = h + ':' +  time[1] + ' ' + 'PM';
			}
			else {
				format = h + ':' +  time[1] + ' ' + 'AM';
			}

			return format;
		}

	});
</script>