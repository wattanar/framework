<?php 

use App\User\UserAPI;

$user = new UserAPI;

$user_data = $user->verifyToken(); ?>

<ul class="nav navbar-nav navbar-right">
  <li>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <?php echo $user_data['payload']['user_data']->username; ?>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li>
        <a href="/user/profile">
          Profile
        </a>
      </li>
      <li>
        <a href="/user/change_password">
          Change password
        </a>
      </li>
      <li>
        <a href="/user/logout">
          Logout
        </a>
      </li>
    </ul>
  </li>
</ul>