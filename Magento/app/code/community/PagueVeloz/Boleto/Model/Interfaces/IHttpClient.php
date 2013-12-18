<?php

interface PagueVeloz_Boleto_Model_Interfaces_IHttpClient
{
	function Send(PagueVeloz_Boleto_Model_Common_HttpContext $context);
}