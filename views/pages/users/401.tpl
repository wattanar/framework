<?php $this->layout('layouts/default', ['title' => 'Unauthorized']); ?>

<div style="text-align: center;">
  <p style="font-size: 5em; margin: 0 auto;">401</p>
  <p>
    Your are not unauthorized to access this section.
  </p>
  <p>
    <a href="<?php echo APP_ROOT;?>">Go back</a>
  </p>
</div>

<?php $this->push('scripts') ?>
<script>
  jQuery(document).ready(function ($) {
    console.log('document rendered.');
  });
</script>
<?php $this->end() ?>