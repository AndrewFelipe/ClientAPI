<?php 

namespace PagueVeloz\Dto;

use \PagueVeloz\Exceptions\ArgumentNullException;

class ComprarCreditosSMSDTO
{
	private $_creditos;
	private $_valor;

	public function setCreditos($creditos)
	{
		if (empty($creditos))
			throw new ArgumentNullException("O argumento \"creditos\" passado está NULL. \"$creditos\" é null.");
		
		$this->_creditos = $creditos;
	}

	public function setValor($valor)
	{
		if (empty($valor))
			throw new ArgumentNullException("O argumento \"valor\" passado está NULL. \"$valor\" é null.");
		
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