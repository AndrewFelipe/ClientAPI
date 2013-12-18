<?php 

class PagueVeloz_Boleto_Model_Dto_ComprarCreditosSMSDTO
{
	private $_creditos;
	private $_valor;

	public function setCreditos($creditos)
	{
		if (empty($creditos))
			throw new PagueVeloz_Boleto_Model_Exceptions_ArgumentNullException("O argumento \"creditos\" passado está NULL. \"$creditos\" é null.");
		
		$this->_creditos = $creditos;
	}

	public function setValor($valor)
	{
		if (empty($valor))
			throw new PagueVeloz_Boleto_Model_Exceptions_ArgumentNullException("O argumento \"valor\" passado está NULL. \"$valor\" é null.");
		
		$valor *=100;
		$this->_valor = $valor;
	}

	public function getCreditos()
	{
		return $this->_creditos;
	}

	public function getValor()
	{
		return $this->_valor;
	}


}