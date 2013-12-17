<?php 

class Daluz_PagueVeloz_Model_Api_EmitirBoleto extends Daluz_PagueVeloz_Model_Api_PagueVeloz
{
	private $_default_header = 'content-type: application/json';
	private $_authDto;

	public function __construct(Daluz_PagueVeloz_Model_Dto_EmailDTO $email, $token, Daluz_PagueVeloz_Model_Interfaces_IHttpClient $machine = null)
	{
		$this->setAuthDto(new Daluz_PagueVeloz_Model_Dto_AuthenticationDTO($email->getEmail(), $token));

		parent::__construct('/v1/EmitirBoleto', $this->getAuthDto(), $machine);		
	}

	public function Post(Daluz_PagueVeloz_Model_Dto_EmitirBoletoDTO $dto)
	{
		$contexto = Mage::getModel('pagueveloz/common_httpContext');
		$contexto->setMethod('post');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost());

		$json = '{
					"Vencimento": "%s"
					, "Valor"          : %u
					, "SeuNumero"      : "%s"
					, "Sacado"         : "%s"
					, "CPFCNPJSacado"  : "%s"
					, "Parcela"        : "%s"
					, "Linha1"         : "%s"
					, "Linha2"         : "%s"
					, "CPFCNPJCedente" : "%s"
					, "Cedente"        : "%s"
				}';

		$json = sprintf($json,
						$dto->getVencimento(),
						$dto->getValor(),
						$dto->getSeuNumero(),
						$dto->getSacado(),
						$dto->getCpfCnpjSacado(),
						$dto->getParcela(),
						$dto->getLinha1(),
						$dto->getLinha2(),
						$dto->getCpfCnpjCedente(),
						$dto->getCedente()
						);

		$contexto->setBody($json);

		return $this->getMachine()->Send($contexto);
	}

	private function setAuthDto(Daluz_PagueVeloz_Model_Dto_AuthenticationDTO $authDto)
	{
		$this->_authDto = $authDto;
	}

	private function getAuthDto()
	{
		return $this->_authDto;
	}
}