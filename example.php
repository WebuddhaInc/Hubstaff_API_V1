<?php

// https://developer.hubstaff.com/docs/api

if( !function_exists('inspect') ){
  function inspect(){
    echo '<pre>' . print_r(func_get_args(), true) . '</pre>';
  }
}

require 'autoload.php';

use \WebuddhaInc\Hubstaff_API_V1\Hubstaff;
use \WebuddhaInc\Hubstaff_API_V1\Config;
use \WebuddhaInc\Hubstaff_API_V1\AuthUser;

$hubstaff = new Hubstaff(
  new Config(array(
    'app_token'    => 'ATnEQKwB00e9EGLddx_4lc8a6RvZZONI7CLfHckVTDc',
    'app_user'     => 'holodyn@gmail.comx',
    'app_password' => 'HubPass28!'
    )),
  new AuthUser(array(
    'id'            => '67033',
    'name'          => 'David',
    'last_activity' => '2016-07-08T18:06:53Z',
    'auth_token'    => 'nNQVI3a3_6zkrDdWLErrXWIcFNtO2AZFd71c5SoKw3Q'
    ))
  );

inspect( $hubstaff->call( '/v1/users' ) );
inspect( $hubstaff->call( '/v1/users/66389' ) );
inspect( $hubstaff->call( '/v1/users/66389/organizations' ) );

echo '<pre>';
system('tail error_log');