<?php

namespace PagueVeloz\Interfaces;

use \PagueVeloz\Common\HttpContext;

interface IHttpClient
{
	function Send(HttpContext $context);
}