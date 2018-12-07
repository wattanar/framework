<?php if( !isset($home_url) ) $home_url = '/';?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
	<title><?php echo $this->e($title) . ' - ' . $this->e(APP_NAME); ?></title>
	<!-- Load CSS -->
	<?php	$this->insert("includes/load-css"); ?>
	<!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-green sidebar-mini">
	<!-- wrapper -->
	<div class="wrapper">
		<?php $this->insert("includes/main-header", ["home_url" => $home_url]); ?>
		<?php	$this->insert("includes/main-sidebar"); ?>
		<div class="content-wrapper">
			<!-- Main content -->
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<!-- Title -->
						<h3 class="box-title"><?php echo $title; ?></h3>
					</div>
					<!-- Content -->
					<div class="box-body">
						<?php echo $this->section('content'); ?>
					</div>
				</div>
			</section>
		</div>

		<?php $this->insert('includes/main-footer'); ?>
	</div>
	<?php $this->insert('includes/load-js'); ?>
	<?php echo $this->section('scripts')?>
</body>
</html>