<?php $this->layout('layouts/default', ['title' => 'Demo Page']); ?>

<div>
  <legend>This is title!</legend>

  <p>This is content.</p>

  <?php if (userCan('cap_1')) :?> <button>Show Me!</button> <?php endif; ?>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function($) {
    console.log("document rendered.");
  });
</script>
<?php $this->end() ?>
