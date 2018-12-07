<?php $this->layout('layouts/dashboard', ['title' => 'Change Password']);?>

<?php use Core\Flash; 

$flash_status = Flash::getMessage('status');
$flash_message = Flash::getMessage('message');?>

<form id="formChangePassword" action="/user/change_password" method="post" class="center-360">

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
    <label for="old_pass">Old password</label>
    <input type="password" name="old_pass" id="old_pass" class="form-control" autofocus required>
  </div>

  <div class="form-group">
    <label for="new_pass">New password</label>
    <input type="password" name="new_pass" id="new_pass" class="form-control" required>
  </div>

  <div class="form-group">
    <label for="confirm_new_pass">Confirm new password</label>
    <input type="password" name="confirm_new_pass" id="confirm_new_pass" class="form-control" required>
  </div>

  <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $key['csrf_name']; ?>">
  <input type="hidden" name="<?php echo $value ?>" value="<?php echo $key['csrf_value']; ?>">

  <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Submit">
  </div>
</form>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {

    $('#formChangePassword').submit(function (e) {
      var old_pass = $.trim($('#old_pass').val());
      var new_pass = $.trim($('#new_pass').val());
      var confirm_new_pass = $.trim($('#confirm_new_pass').val());

      if (old_pass === '' ||
        new_pass === '' ||
        confirm_new_pass === '') {
        e.preventDefault();
        alert('Pease fill data!');
      } else if (new_pass !== confirm_new_pass) {
        e.preventDefault();
        alert('Password not match!');
      } else if (v8n().string().minLength(8).test(new_pass) === false) {
        e.preventDefault();
        alert('Password must more than 8 charecter');
      } else {
        $('#formChangePassword').submit();
      }
    });
  });
</script>
<?php $this->end() ?>