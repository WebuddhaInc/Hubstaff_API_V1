<?php namespace WebuddhaInc\Hubstaff_API_V1;

class AuthUser {

  public $id;
  public $name;
  public $last_activity;
  public $auth_token;

  public function __construct( $data = array() ){
    if ($data) {
      foreach (array_keys(get_object_vars($this)) AS $key) {
        if (isset($data[ $key ])) {
          $this->{ $key } = $data[ $key ];
        }
      }
    }
  }

}
