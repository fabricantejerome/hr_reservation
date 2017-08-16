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
					<form action="<?php echo base_url('index.php/requestor/reservation_submit'); ?>" method="post" id="form">
						<div class="form-group">
							<label for="room_id">Room Name</label>
							<select name="room_id" id="room_id" class="form-control select2" data-live-search="true" required>
								<option></option>
								<?php foreach($rooms as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($item['room_id']) ? $row->id == $item['room_id'] ? 'selected' : '' : ''; ?> ><?php echo $row->room_name . ' | Capacity  (' . $row->capacity . ') | Floor  ' . $row->floor ; ?></option>
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
				<h4 class="modal-title">Terms and Conditions</h4>
			</div>

			<div class="modal-body">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Istam voluptatem, inquit, Epicurus ignorat? Quasi ego id curem, quid ille aiat aut neget. Quamquam ab iis philosophiam et omnes ingenuas disciplinas habemus; Quodcumque in mentem incideret, et quodcumque tamquam occurreret. Duo Reges: constructio interrete. Hi curatione adhibita levantur in dies, valet alter plus cotidie, alter videt. Dat enim intervalla et relaxat. Cur deinde Metrodori liberos commendas? Qua tu etiam inprudens utebare non numquam. Id et fieri posse et saepe esse factum et ad voluptates percipiendas maxime pertinere. </p>

				<p>Tecum optime, deinde etiam cum mediocri amico. Non autem hoc: igitur ne illud quidem. Aliter homines, aliter philosophos loqui putas oportere? Septem autem illi non suo, sed populorum suffragio omnium nominati sunt. </p>

				<p>At certe gravius. Ut in geometria, prima si dederis, danda sunt omnia. Superiores tres erant, quae esse possent, quarum est una sola defensa, eaque vehementer. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Nihil opus est exemplis hoc facere longius. Nunc vides, quid faciat. An hoc usque quaque, aliter in vita? Cum id quoque, ut cupiebat, audivisset, evelli iussit eam, qua erat transfixus, hastam. Quo studio Aristophanem putamus aetatem in litteris duxisse? </p>

				<p>Quare ad ea primum, si videtur; At hoc in eo M. Qui non moveatur et offensione turpitudinis et comprobatione honestatis? At quanta conantur! Mundum hunc omnem oppidum esse nostrum! Incendi igitur eos, qui audiunt, vides. Sumenda potius quam expetenda. Ut placet, inquit, etsi enim illud erat aptius, aequum cuique concedere. </p>

				<p>Quo studio Aristophanem putamus aetatem in litteris duxisse? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Sed ad haec, nisi molestum est, habeo quae velim. </p>

				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Istam voluptatem, inquit, Epicurus ignorat? Quasi ego id curem, quid ille aiat aut neget. Quamquam ab iis philosophiam et omnes ingenuas disciplinas habemus; Quodcumque in mentem incideret, et quodcumque tamquam occurreret. Duo Reges: constructio interrete. Hi curatione adhibita levantur in dies, valet alter plus cotidie, alter videt. Dat enim intervalla et relaxat. Cur deinde Metrodori liberos commendas? Qua tu etiam inprudens utebare non numquam. Id et fieri posse et saepe esse factum et ad voluptates percipiendas maxime pertinere. </p>

				<p>Tecum optime, deinde etiam cum mediocri amico. Non autem hoc: igitur ne illud quidem. Aliter homines, aliter philosophos loqui putas oportere? Septem autem illi non suo, sed populorum suffragio omnium nominati sunt. </p>

				<p>At certe gravius. Ut in geometria, prima si dederis, danda sunt omnia. Superiores tres erant, quae esse possent, quarum est una sola defensa, eaque vehementer. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Nihil opus est exemplis hoc facere longius. Nunc vides, quid faciat. An hoc usque quaque, aliter in vita? Cum id quoque, ut cupiebat, audivisset, evelli iussit eam, qua erat transfixus, hastam. Quo studio Aristophanem putamus aetatem in litteris duxisse? </p>

				<p>Quare ad ea primum, si videtur; At hoc in eo M. Qui non moveatur et offensione turpitudinis et comprobatione honestatis? At quanta conantur! Mundum hunc omnem oppidum esse nostrum! Incendi igitur eos, qui audiunt, vides. Sumenda potius quam expetenda. Ut placet, inquit, etsi enim illud erat aptius, aequum cuique concedere. </p>

				<p>Quo studio Aristophanem putamus aetatem in litteris duxisse? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Sed ad haec, nisi molestum est, habeo quae velim. </p>

				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Istam voluptatem, inquit, Epicurus ignorat? Quasi ego id curem, quid ille aiat aut neget. Quamquam ab iis philosophiam et omnes ingenuas disciplinas habemus; Quodcumque in mentem incideret, et quodcumque tamquam occurreret. Duo Reges: constructio interrete. Hi curatione adhibita levantur in dies, valet alter plus cotidie, alter videt. Dat enim intervalla et relaxat. Cur deinde Metrodori liberos commendas? Qua tu etiam inprudens utebare non numquam. Id et fieri posse et saepe esse factum et ad voluptates percipiendas maxime pertinere. </p>

				<p>Tecum optime, deinde etiam cum mediocri amico. Non autem hoc: igitur ne illud quidem. Aliter homines, aliter philosophos loqui putas oportere? Septem autem illi non suo, sed populorum suffragio omnium nominati sunt. </p>

				<p>At certe gravius. Ut in geometria, prima si dederis, danda sunt omnia. Superiores tres erant, quae esse possent, quarum est una sola defensa, eaque vehementer. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Nihil opus est exemplis hoc facere longius. Nunc vides, quid faciat. An hoc usque quaque, aliter in vita? Cum id quoque, ut cupiebat, audivisset, evelli iussit eam, qua erat transfixus, hastam. Quo studio Aristophanem putamus aetatem in litteris duxisse? </p>

				<p>Quare ad ea primum, si videtur; At hoc in eo M. Qui non moveatur et offensione turpitudinis et comprobatione honestatis? At quanta conantur! Mundum hunc omnem oppidum esse nostrum! Incendi igitur eos, qui audiunt, vides. Sumenda potius quam expetenda. Ut placet, inquit, etsi enim illud erat aptius, aequum cuique concedere. </p>

				<p>Quo studio Aristophanem putamus aetatem in litteris duxisse? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Sed ad haec, nisi molestum est, habeo quae velim. </p>

				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Istam voluptatem, inquit, Epicurus ignorat? Quasi ego id curem, quid ille aiat aut neget. Quamquam ab iis philosophiam et omnes ingenuas disciplinas habemus; Quodcumque in mentem incideret, et quodcumque tamquam occurreret. Duo Reges: constructio interrete. Hi curatione adhibita levantur in dies, valet alter plus cotidie, alter videt. Dat enim intervalla et relaxat. Cur deinde Metrodori liberos commendas? Qua tu etiam inprudens utebare non numquam. Id et fieri posse et saepe esse factum et ad voluptates percipiendas maxime pertinere. </p>

				<p>Tecum optime, deinde etiam cum mediocri amico. Non autem hoc: igitur ne illud quidem. Aliter homines, aliter philosophos loqui putas oportere? Septem autem illi non suo, sed populorum suffragio omnium nominati sunt. </p>

				<p>At certe gravius. Ut in geometria, prima si dederis, danda sunt omnia. Superiores tres erant, quae esse possent, quarum est una sola defensa, eaque vehementer. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Nihil opus est exemplis hoc facere longius. Nunc vides, quid faciat. An hoc usque quaque, aliter in vita? Cum id quoque, ut cupiebat, audivisset, evelli iussit eam, qua erat transfixus, hastam. Quo studio Aristophanem putamus aetatem in litteris duxisse? </p>

				<p>Quare ad ea primum, si videtur; At hoc in eo M. Qui non moveatur et offensione turpitudinis et comprobatione honestatis? At quanta conantur! Mundum hunc omnem oppidum esse nostrum! Incendi igitur eos, qui audiunt, vides. Sumenda potius quam expetenda. Ut placet, inquit, etsi enim illud erat aptius, aequum cuique concedere. </p>

				<p>Quo studio Aristophanem putamus aetatem in litteris duxisse? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Sed ad haec, nisi molestum est, habeo quae velim. </p>

				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Istam voluptatem, inquit, Epicurus ignorat? Quasi ego id curem, quid ille aiat aut neget. Quamquam ab iis philosophiam et omnes ingenuas disciplinas habemus; Quodcumque in mentem incideret, et quodcumque tamquam occurreret. Duo Reges: constructio interrete. Hi curatione adhibita levantur in dies, valet alter plus cotidie, alter videt. Dat enim intervalla et relaxat. Cur deinde Metrodori liberos commendas? Qua tu etiam inprudens utebare non numquam. Id et fieri posse et saepe esse factum et ad voluptates percipiendas maxime pertinere. </p>

				<p>Tecum optime, deinde etiam cum mediocri amico. Non autem hoc: igitur ne illud quidem. Aliter homines, aliter philosophos loqui putas oportere? Septem autem illi non suo, sed populorum suffragio omnium nominati sunt. </p>

				<p>At certe gravius. Ut in geometria, prima si dederis, danda sunt omnia. Superiores tres erant, quae esse possent, quarum est una sola defensa, eaque vehementer. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Nihil opus est exemplis hoc facere longius. Nunc vides, quid faciat. An hoc usque quaque, aliter in vita? Cum id quoque, ut cupiebat, audivisset, evelli iussit eam, qua erat transfixus, hastam. Quo studio Aristophanem putamus aetatem in litteris duxisse? </p>

				<p>Quare ad ea primum, si videtur; At hoc in eo M. Qui non moveatur et offensione turpitudinis et comprobatione honestatis? At quanta conantur! Mundum hunc omnem oppidum esse nostrum! Incendi igitur eos, qui audiunt, vides. Sumenda potius quam expetenda. Ut placet, inquit, etsi enim illud erat aptius, aequum cuique concedere. </p>

				<p>Quo studio Aristophanem putamus aetatem in litteris duxisse? Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Sed ad haec, nisi molestum est, habeo quae velim. </p>

				<div class="form-check">
				    <label class="form-check-label">
				      <input type="checkbox" class="form-check-input" id="cb-agree">
				      I Agree
				    </label>
				  </div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
<script type="text/javascript">
	$(document).ready(function() {
		var $time_start = $('#time_start');
		var $time_end   = $('#time_end');
		var $form       = $('#form');
		var today       = new Date();

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
					//console.log(data);
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

						console.log(data);

						var markup = '';

						for (item of data)
						{
							markup += '<tr>'; 
							markup += '<td>' + item.room_name + '</td>';
							markup += '<td>' + item.purpose + '</td>';
							markup += '<td>' + item.capacity + '</td>';
							markup += '<td>' + item.date_reserved + '</td>';
							markup += '<td>' + convertTo12HourFormat(item.time_start) + ' - ' + convertTo12HourFormat(item.time_end) + '</td>';
							markup += '<td>' + item.fullname + '</td>';
							markup += '<td>' + item.section + '</td>';
							markup += '<td>' + item.approver + '</td>';
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

			/*$('.modal-content').html('');
			$('#myModal').modal('show');*/

			if (start_time >= end_time) {
				e.preventDefault();
				alert('End Time must be greater than start time.');
			}

			$('#btn-submit').attr("disabled", true);
		
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