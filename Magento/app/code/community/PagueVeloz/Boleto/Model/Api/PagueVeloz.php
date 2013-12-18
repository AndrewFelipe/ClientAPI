<?php
/**
 * 
 * @copyright - 2013 (c) Bludata Software
 * @version   - 1.0.51r
 * 
 */

abstract class PagueVeloz_Boleto_Model_Api_PagueVeloz extends Mage_Core_Model_Abstract
{
	private $_machine;
	private $_host;
	private $_authentication = null;

	public function __construct($host, PagueVeloz_Boleto_Model_Dto_AuthenticationDTO $auth = null, PagueVeloz_Boleto_Model_Interfaces_IHttpClient $machine = null)
	{
		if (empty($machine))
			$this->_machine = new PagueVeloz_Boleto_Model_Services_Curl();
		else
			$this->_machine = $machine;
	
		$this->setHost($host);

		if ($auth)
			$this->setAuthentication($auth);
	}

	public function setHost($host)
	{
		$host = Mage::getModel('pagueveloz/boletoMethod')->getPaguevelozUrl() . $host;

		if (!self::isValidHost($host))
			throw new PagueVeloz_Boleto_Model_Exceptions_InvalidHostException("$host é inválido.");

		$this->_host = $host;			
	}

	public function setAuthentication(PagueVeloz_Boleto_Model_Dto_AuthenticationDTO $auth)
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