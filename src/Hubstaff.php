<?php namespace WebuddhaInc\Hubstaff_API_V1;

class Hubstaff {

  /**
   * Configuration
   * @var Config object
   */
  public $_config;

  /**
   * Authenticated User
   * @var AuthUser object
   */
  public $_authUser;

  /**
   * Class Constructor
   * @param Config        $config   Configuration
   * @param AuthUser|null $authUser Authenticated User
   */
  public function __construct( Config $config, AuthUser $authUser = null ){
    if ($config) {
      $this->setConfig( $config );
    }
    if ($authUser) {
      $this->setAuthUser( $authUser );
    }
  }

  /**
   * Apply Configuration
   * @param Config $config Config object
   */
  public function setConfig( Config $config ){
    $this->_config = $config;
  }

  /**
   * Return Current Configuration
   * @return Config object
   */
  public function getConfig(){
    return $this->_config;
  }

  /**
   * Apply Authenticated User
   * @param AuthUser $authUser AuthUser object
   */
  public function setAuthUser( AuthUser $authUser ){
    $this->_authUser = $authUser;
  }

  /**
   * Return Authenticated User
   * @return AuthUser object
   */
  public function getAuthUser(){
    return $this->_authUser;
  }

  /**
   * Retrieve Authenticated User
   * @return boolean Status
   */
  public function auth(){

    if (!$this->getAuthUser()) {
      $curl = new Curl( $this->getConfig()->app_endpoint );
      $res = $curl->call(
        'POST',
        '/v1/auth',
        array(
          'App-Token' => $this->getConfig()->app_token
        ),
        array(
          'email'    => $this->getConfig()->app_user,
          'password' => $this->getConfig()->app_password
        ));
      if (!$res) {
        throw new Exception( $curl->response->code.': '.$curl->response->message );
      }
      else if ($res->error) {
        throw new Exception($res->error);
      }
      else if ($res->user) {
        $this->setAuthUser( new AuthUser( (array)$res->user ) );
      }
      else {
        throw new Exception('Invalid Response');
      }
    }

    return $this->getAuthUser();

  }

  /**
   * Call Endpoint & Return Result Object
   * @param  string $route  Request route
   * @param  array  $params Request parameters
   * @param  string $method GET, POST
   * @return object         Response object
   */
  public function call( $route, $params = null, $method = null ) {
    if (!$this->getAuthUser()) {
      throw new Exception('Not Authenticated');
    }
    else {
      $curl = new Curl( $this->getConfig()->app_endpoint );
      $content = $curl->call(
        $method,
        $route,
        array(
          'App-Token'  => $this->getConfig()->app_token,
          'Auth-Token' => $this->getAuthUser()->auth_token
        ),
        $params
        );
      return new Response(array(
        'code'    => $curl->response->code,
        'message' => $curl->response->message,
        'content' => $content
        ));
    }

  }

}