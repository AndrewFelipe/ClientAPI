<?php 

namespace PagueVeloz\Api;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\ComprarCreditosSMSDTO;
use \PagueVeloz\Dto\AuthenticationDTO;

class ComprarCreditosSMS extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(EmailDTO $email, $token, $compraCredito = 1)
	{
		$machine = null;
		$this->setAuthDto(new AuthenticationDTO($email->getEmail(), $token));

		$host = null;

		switch ($compraCredito) {
			case 1:
				$host = '/v1/ComprarCreditoSMSPorDeposito';
				break;
				
			case 2:
				$host = '/v1/ComprarCreditoSMSPorBoleto';
				break;
		}	
		
		parent::__construct($host, $this->getAuthDto(), $machine);
	}

	public function Post(ComprarCreditosSMSDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('post');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost());

		$json = '{
				   "Creditos": %u,
				   "Valor": %u
				 }';
		$json = sprintf($json,
						$dto->getCreditos(),
						$dto->getValor());

		$contexto->setBody($json);
		return $this->getMachine()->Send($contexto);
	}

	private function setAuthDto	(AuthenticationDTO $authDto)
	{
		$this->_authDto = $authDto;
	}

	private function getAuthDto()
	{
		return $this->_authDto;
	}
}