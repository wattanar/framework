<?php $menu = new \App\Menu\MenuController; ?>
<ul class="nav navbar-nav">
  <?php echo $menu->generateMenu(); ?>
</ul>