<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo base_url('resources/images/default.png');?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>	<?php echo $this->session->userdata('fullname'); ?></p>
			</div>
		</div>
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
					<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>
		<!-- Sidebar Menu -->
			<ul class="sidebar-menu">
				<li class="header">MAIN NAVIGATION</li>
				
				<?php $menu =  explode("/", $this->uri->uri_string()); ?>
				<?php $menu =  end($menu) ?>

				<li class="<?php echo $menu == 'rooms' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/rooms') : base_url('index.php/requestor/rooms'); ?>"><i class="fa fa-table"></i><span>Rooms</span></a></li>

				<li class="<?php echo $menu == 'reservation_form' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/reservation_form') : base_url('index.php/requestor/reservation_form') ; ?>"><i class="fa fa-wpforms"></i><span>File Reservation</span></a></li>

				<li class="<?php echo $menu == 'display_pending_request' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/display_pending_request') : base_url('index.php/requestor/display_pending_request'); ?>"><i class="fa fa-wpforms"></i><span>Pending Request</span></a></li>

				<li class="<?php echo $menu == 'display_approved_request' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/display_approved_request') : base_url('index.php/requestor/display_approved_request'); ?>"><i class="fa fa-wpforms"></i><span>Approved Request</span></a></li>

				<li class="<?php echo $menu == 'display_disapproved_request' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/display_disapproved_request') : base_url('index.php/requestor/display_disapproved_request'); ?>"><i class="fa fa-wpforms"></i><span>Denied Request</span></a></li>

				<li class="<?php echo $menu == 'display_cancelled_request' ? 'active' : ''; ?>"><a href="<?php echo $this->session->userdata('user_type') == 'admin' ? base_url('index.php/admin/display_cancelled_request') : base_url('index.php/requestor/display_cancelled_request'); ?>"><i class="fa fa-wpforms"></i><span>Cancelled Request</span></a></li>

				<?php if($this->session->userdata('user_type') == 'admin'): ?>
					<li class="<?php echo $menu == 'users' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/admin/users') ?>"><i class="fa fa-wpforms"></i><span>Users</span></a></li>
				<?php endif; ?>
			</ul><!-- /.sidebar-menu -->
	</section>
<!-- /.sidebar -->
</aside>

