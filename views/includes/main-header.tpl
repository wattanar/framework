<?php $user_data = getUserData(); ?>

<!-- Main Header -->
<header class="main-header">
	<!-- Logo -->
	<a href="<?php echo $this->e($home_url); ?>" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><?php echo $this->e(substr(APP_NAME, 0, 3)); ?></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><?php echo $this->e(APP_NAME); ?></span>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="javascript:void(0);" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<?php if ( $user_data["result"] === true ): ?>
		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li><a href="#">Administrator</a></li>
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="/assets/images/avatar.png" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><?php echo $user_data['payload']['user_data']->username; ?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header">
							<img src="/assets/images/avatar.png" class="img-circle" alt="User Image">

							<p>
								Web Developer
								<small>Awesome Company</small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<a href="/user/profile" class="btn btn-default btn-block btn-flat">Profile</a>
							<a href="/user/change_password" class="btn btn-default btn-block btn-flat">Change Password</a>
							<a href="/user/logout" class="btn btn-default btn-block btn-flat">Sign out</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<?php endif; ?>
	</nav>
</header>