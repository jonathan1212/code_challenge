<?php

return [
  'api_url' => env('RANDOM_USER_API_URL', 'https://randomuser.me/api/'),
  'default_count' => env('IMPORT_DEFAULT_COUNT', 100),
  'default_nationality' => env('IMPORT_DEFAULT_NATIONALITY', 'AU'),
  'batch_size' => env('BATCH_SIZE', 20),
];
