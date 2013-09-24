<?php

namespace PagueVeloz\Dto;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Services\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Exceptions\ArgumentNullException;
use \PagueVeloz\Exceptions\InvalidValueException;

class TransferenciaDTO
{
	private $_clienteDestino = '';
	private $_valor = '';
	private $_descricao = '';

	public function setClienteDestino(EmailDTO $clienteDestino)
	{
		if (empty($clienteDestino))
			throw new ArgumentNullException("O argumento \"clienteDestino\" é requirido. Não deve ser NULL ou vazio.");
			
		$this->_clienteDestino = $clienteDestino;
	}

	public function setValor($valor)
	{
		if (empty($valor))
			throw new ArgumentNullException("O argumento \"valor\" é requirido. Não deve ser NULL ou vazio.");

		if (!is_numeric($valor))
			throw new InvalidValueException("O argumento \"valor\" deve ser um valor numérico válido.");

		$this->_valor = $valor;
	}

	public function setDescricao ($descricao)
	{
		if (empty($descricao))
			throw new ArgumentNullException("O argumento \"descricao\" é requerido. Não deve ser NULL ou vazio.");

		$this->_descricao = $descricao;
	}

	public function getClienteDestino()
	{
		return $this->_clienteDestino;
	}

	public function getValor()
	{
		return $this->_valor;
	}

	public function getDescricao()
	{
		return $this->_descricao;
	}
}