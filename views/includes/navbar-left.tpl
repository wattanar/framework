<?php $menu = new \App\Menu\MenuController; ?>
<ul class="nav navbar-nav">

  <?php echo $menu->generateMenu(); ?>

  <li>
    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
      Settings
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li>
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
          Users
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="/user/all">All Users</a></li>
          <li><a href="/user/roles">Roles</a></li>
          <li><a href="/user/capabilities">Capabilities</a></li>
        </ul>
      </li>
      <li><a href="/menu">Menus</a></li>
    </ul>
  </li>
</ul>