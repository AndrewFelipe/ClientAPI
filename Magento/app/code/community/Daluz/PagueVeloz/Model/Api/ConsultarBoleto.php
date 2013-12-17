<?php 

class Daluz_PagueVeloz_Model_Api_ConsultarBoleto extends Daluz_PagueVeloz_Model_Api_PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(Daluz_PagueVeloz_Model_Dto_EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(Mage::getModel('pagueveloz/dto_authenticationDTO', $email->getEmail(), $token));

		parent::__construct('/v1/ConsultarBoleto',$this->getAuthDto(),$machine);
	}

	public function Get(Daluz_PagueVeloz_Model_Dto_ConsultarBoletoDTO $dto)
	{
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?data='.$dto->getData());

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