<?php

namespace PagueVeloz\Exceptions;

class ArgumentNullException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}