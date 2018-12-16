<?php

$vendorDir = dirname(__DIR__);

return array (
  'abryrath/syncdb' => 
  array (
    'class' => 'abryrath\\syncdb\\Syncdb',
    'basePath' => $vendorDir . '/abryrath/syncdb/src',
    'handle' => 'syncdb',
    'aliases' => 
    array (
      '@abryrath/syncdb' => $vendorDir . '/abryrath/syncdb/src',
    ),
    'name' => 'syncdb',
    'version' => '1.0.0',
    'description' => 'Plugin to sync database between different environments',
    'developer' => 'Abry Rath <abryrath@gmail.com>',
    'developerUrl' => 'github.com/abryrath',
    'documentationUrl' => '???',
    'changelogUrl' => '???',
    'hasCpSettings' => false,
    'hasCpSection' => false,
  ),
);
