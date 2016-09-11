<?php

spl_autoload_register(function($class) {
  $classStr = (string)$class;
  $classMap = array(
    );
  if (isset($classMap[$classStr])) {
    $path = __DIR__ . $classMap[$classStr];
  }
  else {
    $path = __DIR__ . '/src/' . str_replace('\\','/',str_replace('WebuddhaInc\\Hubstaff_API_V1\\','',$classStr)) . '.php';
  }
  if (is_readable($path)) {
    require_once $path;
  }
});
