<?php

namespace PagueVeloz\Api;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\SaqueDTO;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\AuthenticationDTO;

class Saque extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(new AuthenticationDTO($email->getEmail(), $token));
		
		parent::__construct('/Saque', $this->getAuthDto(), $machine);
	}

	public function Post(SaqueDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('post');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost());

		$json = '{
					"ContaBancaria": 
					{
				    	"Id": %u
				  	},
				    "Valor": %s
				 }';
		$json = sprintf($json,
						$dto->getId(),
						$dto->getValor()
						);
		
		$contexto->setBody($json);

		return $this->getMachine()->Send($contexto);
	}

	public function Get()
	{
		$contexto = new HttpContext();
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost());

		return $this->getMachine()->Send($contexto);
	}

	public function GetById(SaqueDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

		return $this->getMachine()->Send($contexto);
	}

	public function Delete(SaqueDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('delete');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

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