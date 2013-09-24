<?php 

namespace PagueVeloz\Api;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\ConsultarBoletoDTO;
use \PagueVeloz\Dto\AuthenticationDTO;

class ConsultarBoleto extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(new AuthenticationDTO($email->getEmail(), $token));

		parent::__construct('/ConsultarBoleto',$this->getAuthDto(),$machine);
	}

	public function Get(ConsultarBoletoDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?data='.$dto->getData());

		return $this->getMachine()->Send($contexto);
	}

	public function setAuthDto(AuthenticationDTO $authDto)
	{
		$this->_authDto = $authDto;
	}

	public function getAuthDto()
	{
		return $this->_authDto;
	}
}