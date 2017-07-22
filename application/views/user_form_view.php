<?php 
	//var_dump($room);
?>
<link href="<?php echo base_url('resources/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" >
<section class="content users">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-danger">					
				<div class="box-body">
					<?php echo $this->session->flashdata('message'); ?>
					<!-- Form -->
					<form action="<?php echo base_url('index.php/admin/store_user'); ?>" method="post">
						<div class="form-group">
							<label for="emp_no">Employee No.</label>
							<input type="number" class="form-control" id="emp_no" name="emp_no" required>
						</div>

						<div class="form-group">
							<label for="fullname">Fullname</label>
							<input type="text" class="form-control" id="fullname" name="fullname" required>
						</div>

						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email" name="email" required>
						</div>

						<div class="form-group">
							<label for="supervisor_email">Supervisor Email</label>
							<input type="email" class="form-control" id="supervisor_email" name="supervisor_email">

							<input type="text" class="form-control hidden" name="emp_id" id="emp_id" >
						</div>

						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" name="username" required>
						</div>

						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" required>
						</div>

						<div class="form-group">
							<label for="role_id">User type</label>
							<select name="role_id" class="form-control select2" data-live-search="true">
								<option value="0"></option>
								<?php foreach ($roles as $row): ?>
									<option value="<?php echo $row->id; ?>" ><?php echo $row->user_type; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-flat btn-danger">
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

		$('#emp_no').on('keyup', function() {
			var $self             = $(this);
			var $length           = $self.val().length;
			var $fullname         = $('#fullname');
			var $email            = $('#email');
			var $emp_id           = $('#emp_id');
			var $supervisor_email = $('#supervisor_email');
			var $username         = $('#username');

			if (typeof($length) === 'number' && $length === 6) 
			{
				$.ajax({
					type: 'GET',
					url: '<?php echo base_url('index.php/ipc/ajax_personal_info/') ;?>' + $self.val(),
					success: function(data) 
					{
						var $data = $.parseJSON(data);

						$fullname.val($data.fullname);
						$email.val($data.requestor_email);
						$emp_id.val($data.id);
						$username.val($self.val());

						//console.log($data);
					}
				});

				$.ajax({
					type: 'GET',
					url: '<?php echo base_url('index.php/ipc/ajax_dept_head_info/') ;?>' + $self.val(),
					success: function(data) 
					{
						var $data = $.parseJSON(data);

						$supervisor_email.val($data.supervisor_email)

						//console.log($data);
					}
				});
			}
		});	
	});
</script>
