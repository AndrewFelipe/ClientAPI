* procurar no google por markdown syntax

Manual do PagueVeloz
--------------------

Exemplo
-------

	// $machine = new MinhaMaquina();
	$a = new Assinar();
	$dto = new AssinarDTO();
	$dto->setNome('xxxyyy');
	$dto->setDocumento('5432');
	$dto->setTipoPessoa('Juridica');
	$dto->setEmail(new EmailDTO('111xxx@ooo.com'));
	$dto->setLoginUsuarioDefault(new EmailDTO('111xxx@ooo.com'));

	$resposta_final = $a->Post($dto);

	print_r($resposta_final);


TODO
----

* Criar exceptions específicas
* Criar métodos no lugar das propriedades públicas	
* colocar underline como prefixo no nome dos atributos privados
* colocar link do host num config pra poder trocar de produção/homolação/localhost facilmente