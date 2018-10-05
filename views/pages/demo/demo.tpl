<?php $this->layout('layouts/default', ['title' => 'Demo Page']); ?>

<div>
  <legend>This is title!</legend>

  <p>
    This is content.
  </p>

  <button>Click Me</button>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {
    console.log('document rendered.');
  });
</script>
<?php $this->end() ?>