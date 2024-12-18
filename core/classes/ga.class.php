<?php

class GA
	{
	private $ga;

	function __construct()
		{
		$this->ga = new PHPGangsta_GoogleAuthenticator();
		}

	function genSecret()
		{
		return $this->ga->createSecret();
		}

	function getQRLink($secret)
		{
		global $config;
		return $this->ga->getQRCodeGoogleUrl($config['2fa']['name'], $secret);
		}

	function verify($secret,$code)
		{
		return $this->ga->verifyCode($secret, $code, 2);
		}

	}