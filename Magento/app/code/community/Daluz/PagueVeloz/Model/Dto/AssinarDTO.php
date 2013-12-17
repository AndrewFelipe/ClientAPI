<?php

class Daluz_PagueVeloz_Model_Dto_AssinarDTO
{
	private $_nome       = '';
	private $_documento  = '';
	private $_tipoPessoa = '';
	private $_email      = '';
	private $_loginUsuarioDefault = '';

	public function setNome($nome)
	{
		if (empty($nome))
		{
			throw new Exception("$nome vazio");			
		}

		$this->_nome = $nome;
	}

	public function setDocumento($documento)
	{
		$this->_documento = $documento;
	}

	public function setTipoPessoa($tipoPessoa)
	{
		$this->_tipoPessoa = $tipoPessoa;
	}

	public function setEmail(Daluz_PagueVeloz_Model_Dto_EmailDTO $email)
	{
		$this->_email = $email;
	}

	public function setLoginUsuarioDefault($loginUsuarioDefault)
	{		
		$this->_loginUsuarioDefault = $loginUsuarioDefault;
	}

	public function getNome()
	{
		return $this->_nome;
	}

	public function getDocumento()
	{
		return $this->_documento;
	}

	public function getTipoPessoa()
	{
		return $this->_tipoPessoa;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function getLoginUsuarioDefault()
	{
		return $this->_loginUsuarioDefault;
	}

}