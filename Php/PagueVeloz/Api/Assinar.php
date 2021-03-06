<?php

namespace PagueVeloz\Api;

use \PagueVeloz\Common;
use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\AssinarDTO;

class Assinar extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';

	public function __construct(IHttpClient $machine = null)
	{
		parent::__construct('/v1/Assinar', $machine);
	}

	public function Post(AssinarDTO $dto)
	{
		$contexto = new HttpContext();
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