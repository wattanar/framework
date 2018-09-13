<?php $this->layout('layouts/default', ['title' => 'Demo Page']); ?>

<div>
  <legend>This is title!</legend>
  <p>
    <button class="btn btn-primary">Click!</button>
    Content here!
  </p>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {
    console.log('document rendered.');
  });
</script>
<?php $this->end() ?>