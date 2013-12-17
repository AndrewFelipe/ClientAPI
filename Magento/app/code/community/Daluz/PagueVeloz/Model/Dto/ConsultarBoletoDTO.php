<?php 

class Daluz_PagueVeloz_Model_Dto_ConsultarBoletoDTO
{
	private $_data;

	public function setData($data)
	{
		if (empty($data))
			throw new Daluz_PagueVeloz_Model_Exceptions_ArgumentNullException("Argumento informado não deve ser NULL. \"$data\" é null.");

		$this->_data = $data;			
	}

	public function getData()
	{
		return $this->_data;
	}
}
