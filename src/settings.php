<?php

header_remove("X-Powered-By");

return [
  'settings' => [
    'debug' => true,
    'whoops.editor' => 'sublime',
    'whoops.page_title' => 'Something wrong!',
    'displayErrorDetails' => false,
    'addContentLengthHeader' => false,
  ],
];
