<?php

class PagueVeloz_Boleto_Model_Exceptions_InvalidValueException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}