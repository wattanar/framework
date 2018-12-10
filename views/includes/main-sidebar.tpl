<?php $user_data = getUserData(); ?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">



	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<?php if ( $user_data["result"] === true ): ?>
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
					<button type="submit" name="search" id="search-btn" class="btn btn-flat">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>
		<?php endif; ?>
			
		<?php if ( $user_data["result"] === true ): ?>
			<?php	$this->insert("includes/admin-sidebar"); ?>
			<?php echo getSidebarMenu("Menu"); ?>
		<?php else: ?>
		<ul class="sidebar-menu" data-widget="tree"></ul>
			<li><a href="/user/login"><i class="fa fa-circle-o"></i> <span>Login</span></a></li>
			<li><a href="/user/register"><i class="fa fa-circle-o"></i> <span>Register</span></a></li>
		</ul>
		<?php endif; ?>
		
		<!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->
</aside>