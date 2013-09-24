<?php

namespace PagueVeloz\Exceptions;

class InvalidMethodException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}