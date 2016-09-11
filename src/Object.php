<?php namespace WebuddhaInc\Hubstaff_API_V1;

class Object {

  public function __construct( $data = null ){
    if ($data) {
      if (is_object($data)) {
        $data = (array)$data;
      }
      foreach (array_keys(get_object_vars($this)) AS $key) {
        if (isset($data[ $key ])) {
          $this->{ $key } = $data[ $key ];
        }
      }
    }
  }

}
