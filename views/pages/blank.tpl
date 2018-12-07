<?php $this->layout('layouts/dashboard', ['title' => 'Blank Page']);?>

<h1>Hello, World!</h1>

<?php $this->push('scripts'); ?>
<script>
  jQuery(document).ready(function ($) {
    // code here
  });
</script>
<?php $this->end(); ?>