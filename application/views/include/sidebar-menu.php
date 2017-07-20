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
<!-- 				<p>	<?php echo $this->session->get_userdata()['fullname']; ?></p> -->
			</div>
		</div>
		<form action="../history/search" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search cs number here...">
				<span class="input-group-btn">
					<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>
		<!-- Sidebar Menu -->
			<ul class="sidebar-menu">
				<li class="header">MAIN NAVIGATION</li>
				
				<?php 
				//BUYOFF
				if(in_array($this->session->userdata('user_type'),array('administrator','manufacturing 1','manufacturing 2') )){
				?>
					<li class="<?php echo ($this->uri->uri_string() == 'buyoff/list_') ? 'active' : ''; ?>"><a href="<?php echo base_url('buyoff/list_'); ?>"><i class="fa fa-truck"></i><span>Buyoff Units</span></a></li>
				<?php 
				}
				?>	
				
				<li class=""><a href="<?php echo base_url('index.php/admin/rooms'); ?>"><i class="fa fa-table"></i><span>Rooms</span></a></li>

				<li class=""><a href="<?php echo base_url('index.php/requestor/reservation_form'); ?>"><i class="fa fa-wpforms"></i><span>File Room Reservation</span></a></li>

				<li class=""><a href="<?php echo base_url('index.php/admin/get_pending_request'); ?>"><i class="fa fa-wpforms"></i><span>Pending Request</span></a></li>

				

			</ul><!-- /.sidebar-menu -->
	</section>
<!-- /.sidebar -->
</aside>

