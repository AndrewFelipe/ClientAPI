<?php 

namespace PagueVeloz\Dto;

use \PagueVeloz\Interfaces\IHttpClient;
use \PagueVeloz\Services\HttpContext;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Exceptions\ArgumentNullException;

class ContaBancariaDTO
{
	private $_id        = '';
	private $_banco     = '';
	private $_agencia   = '';
	private $_conta     = '';
	private $_descricao = '';

	public function setId($id)
	{
		if (empty($id))
			throw new ArgumentNullException("Argumento \"id\" não deve ser NULL");
		$this->_id = $id;			
	}

	public function setBanco($banco)
	{
		if (empty($banco))
			throw new ArgumentNullException("O argumento \"Banco\" está NULL ou vazio.");
			
		$this->_banco = $banco;
	} 

	public function setAgencia($agencia)
	{
		if (empty($agencia))
			throw new ArgumentNullException("O argumento \"Agencia\" está NULL ou vazio.");
			
		$this->_agencia = $agencia;
	}   

	public function setConta($conta)
	{
		if (empty($conta))
			throw new ArgumentNullException("O argumento \"Conta\" está NULL ou vazio.");
			
		$this->_conta = $conta;
	}   

	public function setDescricao($descricao)
	{
		if (empty($descricao))
			throw new ArgumentNullException("Argumento \"Descrição\" não pode ser NULL ou vazio.");
			
		$this->_descricao = $descricao;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getBanco()
	{
		return $this->_banco;
	}

	public function getAgencia()
	{
		return $this->_agencia;
	}

	public function getConta()
	{
		return $this->_conta;
	}

	public function getDescricao()
	{
		return $this->_descricao;
	}

}