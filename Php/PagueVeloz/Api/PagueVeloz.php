<?php
/**
 * PagueVelozAPI
 * 
 * @copyright - 2013 (c) Bludata Software
 * @version   - 1.0.51r
 * 
 */
namespace PagueVeloz\Api;

use \PagueVeloz\Services\Curl;
use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Exceptions\ArgumentNullException;
use \PagueVeloz\Exceptions\InvalidHostException;
use \PagueVeloz\Dto\AuthenticationDTO;

abstract class PagueVeloz
{
	private $_machine;
	private $_host;
	private $_authentication = null;

	public function __construct($host, AuthenticationDTO $auth = null, IHttpClient $machine = null)
	{
		if (!defined('PAGUEVELOZ_URL'))
			trigger_error("Você deve ter definido uma constante global PAGUEVELOZ_URL", E_WARNING);

		if (empty($machine))
			$this->_machine = new Curl();
		else
			$this->_machine = $machine;
	
		$this->setHost($host);

		if ($auth)
			$this->setAuthentication($auth);
	}

	public function setHost($host)
	{
		$host = PAGUEVELOZ_URL . $host;

		if (!self::isValidHost($host))
			throw new InvalidHostException("$host é inválido.");

		$this->_host = $host;			
	}

	public function setAuthentication(AuthenticationDTO $auth)
	{
		$this->_authentication = $auth;
	}

	public function getAuthentication()
	{
		return $this->_authentication;
	}

	public function getMachine()
	{
		return $this->_machine;
	}	

	public function getHost()
	{
		return $this->_host;
	}

	private static function isValidHost($host)
	{
		return 1 === preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $host);
	}
}