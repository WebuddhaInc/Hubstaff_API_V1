<?php namespace WebuddhaInc\Hubstaff_API_V1;

class Curl {

  public $endpoint;
  public $request;
  public $response;

  public function __construct( $endpoint ){
    $this->endpoint = $endpoint;
  }

  public function call( $method, $route, $headers = array(), $params = array() ){

    // Store Request Details
    $url = $this->endpoint . $route;
    $this->request = (object)array(
      'url'      => $url,
      'method'   => $method,
      'route'    => $route,
      'headers'  => $headers,
      'params'   => $params
      );

    // Build Request
    $ch = curl_init();
    if ($headers) {
      $http_headers = array();
      foreach ($headers AS $key => $val) {
        $http_headers[] = $key . ': '. $val;
      }
      curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
    }
    switch ($method) {
      case 'POST':
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        break;
      default:
        curl_setopt($ch, CURLOPT_URL, $url . ($params ? '?' . http_build_query($params) : ''));
        break;
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    // curl_setopt($ch, CURLOPT_INTERFACE, "0.0.0.0");

    // Collect Response
    $raw         = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header      = explode("\n", preg_replace('/[\n\r]+$/','',substr($raw, 0, $header_size)));
    $content     = json_decode( substr($raw, $header_size) );

    // Parse Header Block
    $header_data = array();
    foreach ($header AS $line) {
      if (strpos($line, ':') !== false) {
        $set = explode(':', $line);
        $header_data[ reset($set) ] = trim(end($set));
      }
    }

    // Identify Response Code
    $code = @$header_data['Status'] ? preg_replace('/^(\d+).*$/','$1',$header_data['Status']) : 520;
    if ($content && $content->error) {
      $content->code = $code;
    }

    // Idenfity Response Error
    $message = (@$content && @$content->error ? $content->error : (@$header_data['Status'] ? preg_replace('/^\d+\s(.*)$/','$1',$header_data['Status']) : 'Unknown Response'));

    // Package Response
    $this->response = new CurlResponse(array(
      'raw'     => $raw,
      'header'  => $header_data,
      'content' => $content,
      'code'    => $code,
      'message' => $message
      ));
    curl_close($ch);

    // Return
    return $this->response->content;
  }

}