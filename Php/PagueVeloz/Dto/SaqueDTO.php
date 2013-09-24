<?php

namespace PagueVeloz\Dto;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Services\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Exceptions\ArgumentNullException;
use \PagueVeloz\Exceptions\InvalidValueException;

class SaqueDTO
{
	private $_id = '';
	private $_valor = '';

	public function setId($id)
	{
		if (empty($id))
			throw new ArgumentNullException("O argumento \"id\" é requirido. Não deve ser NULL ou vazio.");
			
		$this->_id = $id;
	}

	public function setValor($valor)
	{
		if (empty($valor))
			throw new ArgumentNullException("O argumento \"valor\" é requirido. Não deve ser NULL ou vazio.");

		if (!is_numeric($valor))
			throw new InvalidValueException("O argumento \"valor\" deve ser um valor numérico válido.");

		$this->_valor = $valor;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getValor()
	{
		return $this->_valor;
	}
}