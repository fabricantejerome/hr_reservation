<?php 
	//var_dump($room);
?>
<link href="<?php echo base_url('resources/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" >

<!-- Tags resources -->
<link href="<?php echo base_url('resources/plugins/tags/css/jquery.tagit.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/tags/css/tagit.ui-zendesk.css') ?>" rel="stylesheet" >
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('resources/plugins/tags/js/tag-it.min.js'); ?>"></script>

<script>

	$(function() {
		var available_tags;
		var appUrl = "<?php echo base_url('index.php/admin/ajax_browse_rooms'); ?>";

		$.ajax({
			url: appUrl,
			success: function(data) {
				available_tags = JSON.parse(data);
				console.log(available_tags);

				$('#myTags').tagit({
					availableTags: available_tags,
				});
			}
		});
	});
</script>

<section class="content rooms">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-info">					
				<div class="box-body">
					<!-- Form -->
					<form action="<?php echo base_url('index.php/admin/room_store'); ?>" method="post">
						<div class="form-group hidden">
							<label for="room_no">Room No.</label>
							<input type="number" class="form-control" id="room_no" name="room_no" value="<?php echo isset($room['room_no']) ? $room['room_no'] : ''; ?>">
						</div>

						<div class="form-group">
							<label for="room_name">Room Name</label>
							<input type="text" class="form-control" id="room_name" name="room_name" value="<?php echo isset($room['room_name']) ? $room['room_name'] : ''; ?>" required>
						</div>

						<div class="form-group">
							<label for="capacity">Capacity</label>
							<input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo isset($room['capacity']) ? $room['capacity'] : ''; ?>" required>
						</div>

						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($room['description']) ? $room['description'] : ''; ?></textarea>

							<input type="text" class="form-control hidden" name="id" value="<?php echo isset($room['id']) ? $room['id'] : 0; ?>">
						</div>

						<div class="form-group">
							<label for="floor">Floor</label>
							<select name="floor" id="floor" class="form-control select2" data-live-search="true" required>
								<option></option>
								<?php $floors = array('1st', '2nd', '3rd'); ?>
								<?php foreach($floors as $floor): ?>
									<option value="<?php echo $floor; ?>" <?php echo isset($room['floor']) ? trim($floor) == trim($room['floor']) ? 'selected' : '' : ''; ?> ><?php echo $floor; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label for="available">Available</label>
							<input type="checkbox" name="available" id="available" <?php echo isset($room['available']) ? $room['available'] ? 'checked' : '' : ''; ?> >
						</div>

						<div class="form-group">
							<label for="tags">Combine Rooms</label>
							<input type="text" name="selected-tags" id="selected-tags" class="hidden">
							<ul id="myTags"></ul>
						</div>

						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-flat btn-danger" id="btn-submit">
						</div>
					</form><!-- End Form -->
				</div>
			</div>
		</div>	
	</div>
</section>
<script src="<?php echo base_url('resources/plugins/select2/js/select2.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("select").select2({ width: 'resolve' });
	});
</script>
