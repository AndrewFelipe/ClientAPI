<?php

namespace PagueVeloz\Exceptions;

class InvalidJSONException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}