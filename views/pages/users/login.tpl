<?php $this->layout('layouts/dashboard', ['title' => 'Login']);?>

<?php use Core\Flash; 

$flash_status = Flash::getMessage('status');
$flash_message = Flash::getMessage('message');?>

<form role="form" id="formLogin" action="/user/auth" method="POST" class="center-360">

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
		<input class="form-control" placeholder="Username" name="user_login" id="user_login" type="text" autofocus autocomplete="off"
		 required>
	</div>
	<div class="form-group">
		<label for="user_pass">Password</label>
		<input class="form-control" placeholder="Password" name="user_pass" id="user_pass" type="password" value="" required>
	</div>

	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $key['csrf_name']; ?>">
	<input type="hidden" name="<?php echo $value ?>" value="<?php echo $key['csrf_value']; ?>">

	<button type="submit" class="btn btn-lg btn-success btn-block">Submit</button>
</form>

<?php $this->push('scripts'); ?>
<script>
	jQuery(document).ready(function ($) {
		$('#formLogin').submit(function (e) {

			var user_login = $('#user_login').val();
			var user_pass = $("#user_pass").val();

			if ($.trim(user_login) === '') {
				alert('Please fill username!');
				$('#formLogin').trigger('reset');
				$('#user_login').focus();
				e.preventDefault();
				return false;
			}

			if ($.trim(user_pass) === '') {
				alert('Please fill password!');
				$('#formLogin').trigger('reset');
				$('#user_login').focus();
				e.preventDefault();
				return false;
			}

			return true;
		});
	});
</script>
<?php $this->end(); ?>