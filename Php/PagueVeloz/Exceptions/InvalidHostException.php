<?php

namespace PagueVeloz\Exceptions;

class InvalidHostException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}