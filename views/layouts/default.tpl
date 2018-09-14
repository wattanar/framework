<?php

use App\Auth\AuthAPI;

$auth = new AuthAPI;
$user_data = $auth->verifyToken();

if( !isset($home_url) ) $home_url = '/';?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo $this->e($title) . ' - ' . $this->e(APP_NAME); ?>
  </title>
  <link rel="icon" sizes="192x192" href="/assets/images/favicon.ico">
  <!-- CSS -->
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/bootstrap-navbar-custom.css">
  <link rel="stylesheet" href="/assets/bootstrap-navbar-dropdowns/css/navbar.css">
  <link rel="stylesheet" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" />
  <link rel="stylesheet" href="/assets/jqwidgets/styles/theme.css" />
  <link rel="stylesheet" href="/assets/jquery-ui-1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" href="/assets/select2/select2.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
  <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.js"></script>
  <![endif]-->
</head>

<body>
  <!-- Navigation -->
  <div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $this->e($home_url); ?>">
          <?php echo $this->e(APP_NAME);?>
        </a>
      </div>
      <div class="collapse navbar-collapse">
        <?php 
          if ( $user_data['result'] === true) {
            $this->insert('includes/navbar-left');
            $this->insert('includes/navbar-right'); 
          }
        ?>
      </div>
      <!--/.nav-collapse -->
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php echo $this->section('content'); ?>
      </div>
    </div>
  </div>

  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="/assets/bootstrap-navbar-dropdowns/js/navbar.js"></script>
  <script src="/assets/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="/assets/js/v8n.min.js"></script>
  <script src="/assets/select2/select2.min.js"></script>
  <script src="/assets/jqwidgets/jqxcore.js"></script>
  <script src="/assets/jqwidgets/jqxinput.js"></script>
  <script src="/assets/jqwidgets/jqxdata.js"></script>
  <script src="/assets/jqwidgets/jqxbuttons.js"></script>
  <script src="/assets/jqwidgets/jqxbuttongroup.js"></script>
  <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
  <script src="/assets/jqwidgets/jqxmenu.js"></script>
  <script src="/assets/jqwidgets/jqxlistbox.js"></script>
  <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.columnsresize.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.filter.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.sort.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.edit.js"></script>
  <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
  <script src="/assets/jqwidgets/jqxcalendar.js"></script>
  <script src="/assets/jqwidgets/jqxgrid.grouping.js"></script>
  <script src="/assets/jqwidgets/jqxwindow.js"></script>
  <script src="/assets/jqwidgets/jqxinput.js"></script>
  <script src="/assets/jqwidgets/jqxcheckbox.js"></script>
  <script src="/assets/jqwidgets/jqxpanel.js"></script>
  <script src="/assets/jqwidgets/jqxcombobox.js"></script>
  <script src="/assets/jqwidgets/jqxdropdownbutton.js"></script>
  <script src="/assets/jqwidgets/globalization/globalize.js"></script>
  <script src="/assets/js/app.js"></script>

  <?php echo $this->section('scripts')?>
</body>

</html>