<?php

class PagueVeloz_Boleto_Model_Exceptions_InvalidHostException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}