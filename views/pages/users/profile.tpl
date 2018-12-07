<?php $this->layout('layouts/dashboard', ['title' => 'Profile']);?>

<?php 
use Core\Flash;
use App\User\UserAPI;

$user = new UserAPI;

$flash_status = Flash::getMessage('status');
$flash_message = Flash::getMessage('message'); ?>

<form id="formProfile" method="post" action="/user/profile" class="center-360">
  <?php if ($flash_status === 'error'): ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?php echo $flash_message; ?>
  </div>
  <?php elseif ($flash_status === 'success'): ?>
  <div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?php echo $flash_message; ?>
  </div>
  <?php endif; ?>

  <div class="form-group">
    <label for="user_login">Username</label>
    <input type="text" name="user_login" id="user_login" class="form-control" value="<?php echo $user_data[0]['user_login']; ?>"
      readonly>
  </div>

  <div class="form-group">
    <label for="user_email">Email</label>
    <input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo $user_data[0]['user_email']; ?>">
  </div>

  <div class="form-group">
    <label for="user_registered_date">Register Date</label>
    <input type="text" name="user_registered_date" id="user_registered_date" class="form-control" value="<?php echo $user_data[0]['user_registered']; ?>"
      readonly>
  </div>

  <div class="form-group">
    <label for="user_firstname">First Name</label>
    <input type="text" name="user_firstname" id="user_firstname" class="form-control" value="<?php echo $user_data[0]['user_firstname']; ?>">
  </div>

  <div class="form-group">
    <label for="user_lastname">Last Name</label>
    <input type="text" name="user_lastname" id="user_lastname" class="form-control" value="<?php echo $user_data[0]['user_lastname']; ?>">
  </div>

  <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $key['csrf_name']; ?>">
  <input type="hidden" name="<?php echo $value ?>" value="<?php echo $key['csrf_value']; ?>">

  <div class="form-group">
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {
    // code here
  });
</script>
<?php $this->end() ?>