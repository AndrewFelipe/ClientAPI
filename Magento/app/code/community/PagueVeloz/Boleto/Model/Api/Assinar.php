<?php

class PagueVeloz_Boleto_Model_Api_Assinar extends PagueVeloz_Boleto_Model_Api_PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';

	public function __construct(PagueVeloz_Boleto_Model_Interfaces_IHttpClient $machine = null)
	{
		parent::__construct('/v1/Assinar', $machine);
	}

	public function Post(PagueVeloz_Boleto_Model_Dto_AssinarDTO $dto)
	{
		$contexto = new PagueVeloz_Boleto_Model_Common_HttpContext();
		$contexto->setMethod('post');
		$contexto->addHeader($this->_default_header);
		$contexto->setHost($this->getHost());
		
		$json = '{ 
			        "Nome": "%s",
					"Documento": "%s",
					"TipoPessoa": "%s",
					"Email": "%s",
					"LoginUsuarioDefault": "%s" 
				 }';
		$json = sprintf($json,
						$dto->getNome(),
						$dto->getDocumento(),
						$dto->getTipoPessoa(),
						$dto->getEmail()->getEmail(),
						$dto->getLoginUsuarioDefault()->getEmail());

		$contexto->setBody($json);

		return $this->getMachine()->Send($contexto);
	}
}