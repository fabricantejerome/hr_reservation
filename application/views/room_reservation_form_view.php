<link href="<?php echo base_url('resources/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" >
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/timepicker/jquery.timepicker.css'); ?>">
<section class="content rooms">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-info">					
				<div class="box-body">
					<!-- Form -->
					<?php echo $this->session->flashdata('success_message'); ?>
					<?php echo $this->session->flashdata('error_message'); ?>
					<form action="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/reservation_submit') : base_url('index.php/requestor/reservation_submit'); ?>" method="post" id="form">
						<div class="form-group">
							<label for="room_id">Room Name</label>
							<select name="room_id" id="room_id" class="form-control select2" data-live-search="true" required>
								<option></option>
								<?php foreach($rooms as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($item['room_id']) ? $row->id == $item['room_id'] ? 'selected' : '' : ''; ?> ><?php echo $row->room_name . ' | Capacity  (' . $row->capacity . ') | ' . $row->floor . ' floor' ; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label for="capacity">Capacity</label>
							<input type="text" name="capacity" id="capacity" class="form-control" disabled>
						</div>

						<div class="form-group">
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

							<input type="text" name="agree" id="agree" class="hidden" value="<?php echo $agree ?>" >
						</div>

						<?php if ($this->session->userdata('user_type') == 'admin'): ?>
							<div class="form-group">
								<label for="requestor">Requestor</label>
								<select name="emp_id" id="emp_id" class="form-control select2" data-live-search="true">
									<option></option>
									<?php foreach($users as $row): ?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['fullname']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>

						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-flat btn-danger" id="btn-submit">
						</div>
					</form><!-- End Form -->
				</div>
			</div>
		</div>

		<div class="col-md-9" >
			<div id="taken_slot" class="hidden box box-info">
				<!-- Room table -->
				<table id="room_tbl" class="table table-condensed table-striped table-bordered" >
					<thead>
						<tr>
							<th>Room</th>
							<th>Purpose</th>
							<th>Capacity</th>
							<th>Date Reserved</th>
							<th>Time</th>
							<th>Reserved by</th>
							<th>Section</th>
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Guidelines for Using Training/Meeting Rooms in HR Building</h4>
			</div>

			<div class="modal-body">
				<ol type="I">
					<li class="list-header">Scheduling</li>
					<ol>
						<li>All reservations must be done online thru the Training Reservations System (Pls. refer to the IPC Centralized Portal, under HRS).</li>
						<li>It is the responsibility of the requestor to make sure HRS PIC has approved or declined the reservation.</li>
					</ol>

					<li class="list-header">Conference Room Etiquette</li>
					<ol>
						<li>General Cleanliness</li>

						<ol type="a">
							<li class="list-title">No food or drinks are allowed inside the training/meeting rooms. Coffee/snacks must be served in the pantry.</li>
							<p>The requestor will be responsible for ensuring the reserved room is cleaned of any trash used during the meeting. Glass boards should be erased and cleaned to be ready for the next user. Any remaining handouts, agendas, etc. be removed from the room.</p>

							<li>The requestor is responsible for:</li>
							<ul>
								<li>Making sure the meeting is on the calendar for applicable conference room</li>
								<li>Only using the allotted time scheduled for set meeting</li>
								<li>Avoid exceeding the maximum seating capacity</li>
								<li>If additional seat(s) are borrowed, making sure to return to their original location/placement</li>
								<li>Ensuring training/conference room is returned to its original state</li>
								<li>Aircon, projector and lights are turned off</li>
								<li>Closing the door when meeting is in progress as noise and/or conversations could be interruptive to nearby rooms.</li>
							</ul>

							<li>Reporting</li>
							<p>If an item is found to be broken or nonfunctioning; it will be the responsibility of the requestor to report to HRS.</p>

							<li>Lost and Found</li>
							<p>All items found or items lost within a conference/training room should be turned in or reported to HRS.</p>
						</ol>
					</ol>

					<li class="list-header">Meeting Time</li>
					<ol>
						<li>When scheduling a meeting, please schedule enough time to avoid meeting overrun.</li>
						<li>If your meeting extends beyond the allotted time and another meeting is scheduled to begin, the requesting section must adjourn the meeting or find another room to complete the meeting extension. Those reservations listed on the calendar will have priority over any impromptu meeting</li>
						<li>HRS reserves the right to cancel other meetings scheduled when there are urgent meetings by Executives or top management.</li>
					</ol>

					<li class="list-header">Computer Training Rm. Usage</li>
					<ol>
						<li>Do not eat or drink near a computer or while utilizing.</li>
						<li>Do not push the on and off switch on the computer</li>
						<li>Do not remove any equipment from the training room.</li>
						<li>Do not download or install software, games, or chat programs onto any computers.</li>
						<li>Place your items (purses, jackets, bags, etc.) under the chairs to keep the aisles clear for walking.</li>
						<li>Log off your computer, push in your chair and make sure the area is neat for the next trainees when leaving the room.</li>
						<li>Report all computer malfunctions to HRS or MIS immediately.</li>
						<li>Respect users who want to be in a quiet environment free of interruptions.</li>
						<li>To request software installation for instruction or training, please inform HRS one(1) week before the training.</li>
						<li>TMs and Trainers are responsible for taking reasonable safety precautions in using computer equipment. They will be held responsible for damage to such equipment arising out of their negligence.</li>
					</ol>
				</ol>

				<div class="form-check">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" id="cb-agree">
						I Agree
					</label>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal" id="modal-close">Close</button>
				<button type="submit" class="btn btn-success" disabled id="modal-submit">Submit</button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('resources/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('resources/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script src="<?php echo base_url('resources/plugins/select2/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/timepicker/jquery.timepicker.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/daterangepicker/moment.js');?>"></script>
<script src="<?php echo base_url('resources/js/lodash/lodash.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var $time_start   = $('#time_start');
		var $time_end     = $('#time_end');
		var $form         = $('#form');
		var today         = new Date();
		var $modal_submit = $('#modal-submit');
		var $modal_close  = $('#modal-close');
		var $agree        = $('#agree');

		console.log(today);

		$("select").select2({ width: 'resolve' });

		$("#date_reserved").datepicker({ 
			startDate: today,
			autoclose: true,
			orientation: 'bottom'
		}).on('change', function()
		{
			var $self    = $(this).val();
			var $room_id = $('#room_id');
			var $date = $self.split('/');

			/*
			 * Data for blocking time
			 * Unfortunately time picker does not support it
			 */
			$date = $date[2] + '-' + $date[0] + '-' + $date[1];

			/*$.ajax({
				type: 'GET',
				url: '<?php //echo base_url('index.php/requestor/ajax_block_time/') ?>' + $room_id.val() + '/' + $date,
				success: function(data){
					var data = JSON.parse(data);
					var time_start = JSON.stringify(data[0]);

					time_start = time_start.substring(1, time_start.length-1);
					
					console.log(data[0][0].join());

					$('#time_start').timepicker( {
						disableTimeRanges: [
							[data[0][0].join()]
						]			 	
					});

					

					$('#time_end').timepicker({
						'disableTimeRanges': JSON.stringify(data[1])
					});
				}
			})*/
		});

		$('#time_start').timepicker({
			minTime: '07:00'
		});

		$('#time_end').timepicker({
			minTime: '07:00'
		});

		$('#room_id').on('change', function() {

			var $self = $(this);

			$.ajax({
				type: 'GET',
				url: '<?php echo base_url('index.php/requestor/ajax_room_details/') ?>' + $self.val(),
				success: function(data)
				{
					var data = $.parseJSON(data);
					$('#capacity').val(data.capacity);
				}
			})

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

						var markup = '';

						for (item of data)
						{
							markup += '<tr>'; 
							markup += '<td>' + item.room_name + '</td>';
							markup += '<td>' + item.purpose + '</td>';
							markup += '<td>' + item.capacity + '</td>';
							markup += '<td>' + item.date_reserved + '</td>';
							markup += '<td>' + convertTo12HourFormat(item.time_start) + ' - ' + convertTo12HourFormat(item.time_end) + '</td>';
							markup += '<td>' + _.chain(item.fullname).toLower().startCase() + '</td>';
							markup += '<td>' + item.section + '</td>';
							markup += '<td>' + _.chain(item.approver).toLower().startCase() + '</td>';
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

			var start_time = convertToSec(moment($time_start.val(), ["h:mma"]).format("HH:mm"));
			var end_time = convertToSec(moment($time_end.val(), ["h:mma"]).format("HH:mm"));

			if (start_time >= end_time) {
				e.preventDefault();
				alert('End Time must be greater than start time.');
			}

			if ($agree.val() == 0)
			{
				e.preventDefault();
				$('#myModal').modal({
				    backdrop: 'static',
				    keyboard: false
				});

				$('#myModal').modal('show');
			}

			$('#btn-submit').attr("disabled", true);
		
		});

		$('#cb-agree').on('click', function() {
			if ($(this).is(':checked'))
			{
				$modal_submit.removeAttr('disabled');
			}
			else
			{
				$modal_submit.attr('disabled', true);
			}
		});

		$modal_close.on('click', function() {
			$('#btn-submit').attr("disabled", false);
		})

		$modal_submit.on('click', function() {
			$agree.val(1);
			$form.submit();
			$(this).attr('disabled', true);
			$modal_close.attr('disabled', true);
		});

		function convertToSec(t)
		{
			var time = t.split(':');

			return (time[0] * 60 * 60) + (time[1] * 60);
		}

		function convertTo12HourFormat(t) {
			var time   = t.split(':');
			var h      = time[0] % 12;
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