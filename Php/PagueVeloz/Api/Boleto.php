<?php 

namespace PagueVeloz\Api;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Common\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\BoletoDTO;
use \PagueVeloz\Dto\AuthenticationDTO;

class Boleto extends PagueVeloz
{
	private $_default_header = 'Content-Type: application/json';
	private $_authDto;

	public function __construct(EmailDTO $email, $token)
	{
		$machine = null;
		$this->setAuthDto(new AuthenticationDTO($email->getEmail(), $token));

		parent::__construct('/v2/Boleto',$this->getAuthDto(),$machine);
	}

	public function Get(BoletoDTO $dto)
	{
		$contexto = new HttpContext();
		$contexto->setMethod('get');
		$contexto->addHeader($this->_default_header);
		$contexto->setAuthorization($this->getAuthDto()->getEmail(), $this->getAuthDto()->getToken());
		$contexto->setHost($this->getHost().'?data='.$dto->getData());

		return $this->getMachine()->Send($contexto);
	}

	public function Post(BoletoDTO $dto)
	{
		$contexto = new HttpContext();
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

	public function setAuthDto(AuthenticationDTO $authDto)
	{
		$this->_authDto = $authDto;
	}

	public function getAuthDto()
	{
		return $this->_authDto;
	}
}