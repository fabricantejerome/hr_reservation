<section class="content rooms">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-info">
				<?php if ($this->session->userdata('user_type') == 'admin'): ?>
					<div class="box-header with-border">
						<?php echo $this->session->flashdata('success_message'); ?>
						<a href="<?php echo base_url('index.php/admin/user_form') ?>">
							<button class="btn btn-flat btn-danger pull-right">Add User <i class="fa fw fa-plus" aria-hidden="true"></i></button>
						</a>
					</div>
				<?php endif; ?>
					
				<div class="box-body">
					<!-- Room table -->
					<table id="users-tbl" class="table table-condensed table-striped table-bordered" >
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Fullname</th>
								<th>Email address</th>
								<th>User type</th>
							</tr>
						</thead>
						<tbody>
							<?php $counter = 1; ?>
							<?php foreach($users as $user): ?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td><?php echo $user->username; ?></td>
									<td><?php echo $user->fullname; ?></td>
									<td><?php echo $user->email ?></td>
									<td><?php echo $user->user_type; ?></td>
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
<script type="text/javascript">
	$(document).ready(function() {

		$('#users-tbl').DataTable();
		
	})
</script>
