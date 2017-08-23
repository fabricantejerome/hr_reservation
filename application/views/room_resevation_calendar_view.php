<link href="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/fullcalendar/fullcalendar.min.css');?>" rel="stylesheet">
<link href="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/fullcalendar/fullcalendar.print.css');?>" rel="stylesheet" media="print">
<link href="<?php echo base_url('resources/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" >
<style>
	.fc-day-grid-event > .fc-content {
	    white-space: normal;
	}
</style>
<!-- Main content -->
<section class="content calendar">
	<div class="row">
		<!-- .col -->
		<div class="col-md-12">
			<!-- box -->
			<div class="box box-info">
				<div class="box-header">
					<!-- Inner row -->
					<div class="row">
						<div class="col-md-4">
							<!-- Form group -->
							<div class="form-group">
								<label for="room_id">Room Name</label>
								<select name="room_id" id="room_id" class="form-control select2" data-live-search="true" required>
									<option></option>
									<?php foreach($rooms as $row): ?>
										<option value="<?php echo $row->id; ?>" <?php echo isset($item['room_id']) ? $row->id == $item['room_id'] ? 'selected' : '' : ''; ?> ><?php echo $row->room_name . ' | Capacity  (' . $row->capacity . ') | ' . $row->floor . ' floor' ; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<!-- End of form group -->
						</div>
					</div>
					<!-- End of Inner row -->
				</div>

				<div class="box-body">
					<!-- Calendar block -->
					<div id="calendar"></div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /. box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- /.content -->

<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/jQueryUI/jquery-ui.js');?>"></script>
<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/slimScroll/jquery.slimscroll.min.js');?>" ></script>
<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/fastclick/fastclick.js');?>" ></script>
<script src="<?php echo base_url('resources/plugins/select2/js/select2.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/daterangepicker/moment.js');?>"></script>
<script src="<?php echo base_url('resources/templates/AdminLTE-2.3.5/plugins/fullcalendar/fullcalendar.min.js');?>" ></script>

<!-- Calendar Script -->
<script>
	$(function () {
		$("select").select2({ width: 'resolve' });

		// Show all the room reservations on calendar
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('index.php/admin/ajax_approved_request/') ?>',
			success: function(data)
			{
				var response = $.parseJSON(data);
				$('#calendar').css('font-size', '1.1em');
				$('#calendar').fullCalendar('removeEvents');
				$('#calendar').fullCalendar('addEventSource', response);
				$('#calendar').fullCalendar({
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay'
					},
					buttonText: {
						today: 'today',
						month: 'month',
						week: 'week',
						day: 'day'
					},
					events: response,
					displayEventEnd: true
				});
			}
		});

		// Show the reservations on selected rooms
		$('#room_id').on('change', function() {
			var $self = $(this);

			$.ajax({
				type: 'GET',
				url: '<?php echo base_url('index.php/admin/ajax_approved_request/') ?>' + $self.val(),
				success: function(data)
				{
					var response = $.parseJSON(data);

					$('#calendar').fullCalendar('removeEvents');
					$('#calendar').fullCalendar('addEventSource', response);
					$('#calendar').fullCalendar({
						header: {
							left: 'prev,next today',
							center: 'title',
							right: 'month,agendaWeek,agendaDay'
						},
						buttonText: {
							today: 'today',
							month: 'month',
							week: 'week',
							day: 'day'
						},
						events: response,
					});
				}
			});
		});
	});
</script>
