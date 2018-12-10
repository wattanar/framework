<?php $this->layout('layouts/dashboard', ['title' => 'Demo Page']);
?>

<div>
  <h1>Hello World!</h1>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function($) {
    console.log("document rendered.");
  });
</script>
<?php $this->end() ?>
