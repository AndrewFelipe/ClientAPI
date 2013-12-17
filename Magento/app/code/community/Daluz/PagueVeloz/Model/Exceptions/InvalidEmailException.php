<?php

class Daluz_PagueVeloz_Model_Exceptions_InvalidEmailException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}