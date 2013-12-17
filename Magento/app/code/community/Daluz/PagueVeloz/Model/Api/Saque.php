<?php

class Daluz_PagueVeloz_Model_Api_Saque extends Daluz_PagueVeloz_Model_Api_PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(Daluz_PagueVeloz_Model_Dto_EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(Mage::getModel('pagueveloz/dto_authenticationDTO', $email->getEmail(), $token));
		
		parent::__construct('/v1/Saque', $this->getAuthDto(), $machine);
	}

	public function Post(Daluz_PagueVeloz_Model_Dto_SaqueDTO $dto)
	{
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
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
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost());

		return $this->getMachine()->Send($contexto);
	}

	public function GetById(Daluz_PagueVeloz_Model_Dto_SaqueDTO $dto)
	{
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

		return $this->getMachine()->Send($contexto);
	}

	public function Delete(Daluz_PagueVeloz_Model_Dto_SaqueDTO $dto)
	{
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
		$contexto->setMethod('delete');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?id='.$dto->getId());

		return $this->getMachine()->Send($contexto);
	}
	
	public function setAuthDto(Daluz_PagueVeloz_Model_Dto_AuthenticationDTO $authDto)
	{
		$this->_authDto = $authDto;
	}

	public function getAuthDto()
	{
		return $this->_authDto;
	}
}