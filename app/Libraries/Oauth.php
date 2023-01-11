<?php namespace App\Libraries;

//use \OAuth2\Storage\Pdo;
use \App\Libraries\CustomOauthStorage;


class Oauth{
  var $server;

  function __construct(){
    $this->init();
  }

  public function init(){
    $dsn = getenv('database.default.DSN');
	$username = getenv('database.default.username');
    $password = getenv('database.default.password');

    $storage = new CustomOauthStorage(['dsn' => $dsn, 'username' => $username, 'password' => $password]);
	// $config = array(
		// 'access_lifetime' => 86400,
		// 'always_issue_new_refresh_token' => true
	// );
	// $this->server = new \OAuth2\Server($storage, $config);
	
    $this->server = new \OAuth2\Server($storage);	
    $this->server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));

	$config = array(
		'always_issue_new_refresh_token' => true
	);
	$this->server->addGrantType(new \OAuth2\GrantType\RefreshToken($storage, $config));
	
  }
}
