<?php namespace WebuddhaInc\Hubstaff_API_V1;

class Exception extends \Exception {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}
