# Hubstaff PHP Client Library

A lightweight PHP library for the Hubstaff API v1.

## Usage

The Hubstaff API requires that you authenticate only once, then use the authentication token provided to make repeat requests.  The authentication token does not expire.  Your project will need to have a mechanism for storing the authentication token.

````
require 'autoload.php';

use \WebuddhaInc\Hubstaff_API_V1\Hubstaff;
use \WebuddhaInc\Hubstaff_API_V1\Config;
use \WebuddhaInc\Hubstaff_API_V1\AuthUser;

$config = new Config(array(
  'app_token' => '<application-token>',
  'app_user' => '<user-email>',
  'app_password' => '<user-password>',
  ));
$hubstaff = new HubStaff( $config );
$authUser = $hubstaff->auth();
$users = $hubstaff->call('/v1/users', array('offset' => 1));
````

Once authorization is obtained you will include those details to future class instances.

````
require 'autoload.php';

use \WebuddhaInc\Hubstaff_API_V1\Hubstaff;
use \WebuddhaInc\Hubstaff_API_V1\Config;
use \WebuddhaInc\Hubstaff_API_V1\AuthUser;

$config = new Config(array(
  'app_token' => '<application-token>',
  'app_user' => '<user-email>',
  'app_password' => '<user-password>',
  ));
$authUser = new AuthUser(array(
  'id' => '<user-id>',
  'name' => '<user-name>',
  'last_activity' => '<activity-date>',
  'auth_token' => '<authentication-token>'
  ));
$hubstaff = new HubStaff( $config, $authUser );
$users = $hubstaff->call('/v1/users', array('offset' => 1));
````

## API Reference
This library does not package specific requests, rather provides the transport for calling Hubstaff routes directly.  A list of all Hubstaff API methods can be found @ https://developer.hubstaff.com/docs/api.
