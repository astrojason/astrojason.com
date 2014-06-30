<?php
use Assetic\FilterManager;
use Assetic\AssetManager;

return array(
  'defaults' =>  array(
      'allow_credentials' => false,
      'allow_origin'=> array(),
      'allow_headers'=> array(),
      'allow_methods'=> array(),
      'expose_headers'=> array(),
      'max_age' => 0
  ),

  'paths' => array(
      'api/link/add' => array(
          'allow_origin'=> array('*'),
          'allow_headers'=> array('*'),
          'allow_methods'=> array('PUT'),
          'max_age' => 3600
      )
  ),

);
