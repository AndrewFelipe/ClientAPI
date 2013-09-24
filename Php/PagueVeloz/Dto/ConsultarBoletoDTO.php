<?php 

namespace PagueVeloz\Dto;

use \PagueVeloz\Exceptions\ArgumentNullException;

class ConsultarBoletoDTO
{
	private $_data;

	public function setData($data)
	{
		if (empty($data))
			throw new ArgumentNullException("Argumento informado não deve ser NULL. \"$data\" é null.");

		$this->_data = $data;			
	}

	public function getData()
	{
		return $this->_data;
	}
}
