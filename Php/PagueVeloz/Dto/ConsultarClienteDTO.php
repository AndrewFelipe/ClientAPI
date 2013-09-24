<?php 

namespace PagueVeloz\Dto;

use \PagueVeloz\Exceptions\ArgumentNullException;

class ConsultarClienteDTO
{
	private $_tipo;
	private $_filtro;

	public function setTipo($tipo)
	{
		if (empty($tipo))
			throw new ArgumentNullException("Argumento informado não deve ser NULL ou vazio. O tipo informado é null.");

		$this->_tipo = $tipo;			
	}

	public function setFiltro($filtro)
	{
		$this->_filtro = $filtro;			
	}

	public function getTipo()
	{
		return $this->_tipo;
	}

	public function getFiltro()
	{
		return $this->_filtro;
	}
}
