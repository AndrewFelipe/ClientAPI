<?php

namespace PagueVeloz\Api;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\ContaBancariaDTO;
use \PagueVeloz\Dto\AuthenticationDTO;

class ContaBancaria extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(new AuthenticationDTO($email->getEmail(), $token));
	
		parent::__construct('/v2/ContaBancaria', $this->getAuthDto(), $machine);
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

	public function GetById(ContaBancariaDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

		return $this->getMachine()->Send($contexto);
	}

	public function Post(ContaBancariaDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod ('post');
		$contexto->addHeader ($this->_default_header);
		$contexto->setAuthorization ($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost ($this->getHost());

		$json = '{ 
				   "CodigoBanco": %u,
		 	       "CodigoAgencia": %u,
		 	       "NumeroConta": %u,
		 	       "Descricao": "%s" 
		 	     }';
		$json = sprintf($json, 
						$dto->getBanco(), 
						$dto->getAgencia(), 
						$dto->getConta(),
						$dto->getDescricao());

		$contexto->setBody($json);

		return $this->getMachine()->Send($contexto);
	}

	public function Put(ContaBancariaDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('put');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

		$json = '{ 
			       "CodigoBanco"   : %u,
		 	       "CodigoAgencia" : %u,
		 	       "NumeroConta"   : %u,
		 	       "Descricao"     : "%s",
		 	   	   "Id"            : %u 
		 	   	 }';
		$json = sprintf($json, 
						$dto->getBanco(), 
						$dto->getAgencia(), 
						$dto->getConta(),
						$dto->getDescricao(),
						$dto->getId());

		$contexto->setBody($json);

		return $this->getMachine()->Send($contexto);
	}

	public function Delete(ContaBancariaDTO $dto)
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