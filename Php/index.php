<?php

use \PagueVeloz\Api\Assinar;
use \PagueVeloz\Api\Saldo;
use \PagueVeloz\Api\EmitirBoleto;
use \PagueVeloz\Api\ContaBancaria;
use \PagueVeloz\Api\Transferencia;
use \PagueVeloz\Api\ConsultarBoleto;
use \PagueVeloz\Api\Boleto;
use \PagueVeloz\Api\ConsultarCliente;
use \PagueVeloz\Api\ComprarCreditosSMS;
use \PagueVeloz\Api\Saque;

use \PagueVeloz\Dto\AssinarDTO;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\ContaBancariaDTO;
use \PagueVeloz\Dto\EmitirBoletoDTO;
use \PagueVeloz\Dto\ConsultarBoletoDTO;
use \PagueVeloz\Dto\BoletoDTO;
use \PagueVeloz\Dto\ComprarCreditosSMSDTO;
use \PagueVeloz\Dto\SaqueDTO;
use \PagueVeloz\Dto\TransferenciaDTO;
use \PagueVeloz\Dto\ConsultarClienteDTO;

require 'SplClassLoader.php';

$path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR);
$loader = new SplClassLoader('PagueVeloz', $path);
$loader->register();

define('PAGUEVELOZ_URL', 'http://pagueveloz.homolog.bludata.net/api'); // Variavel global que deve ser criada para passagem da url do pagueveloz
// define('PAGUEVELOZ_URL', 'http://192.168.0.173:49484/api'); // Variavel global que deve ser criada para passagem da url do pagueveloz

// Assinar

/*
$assinar = new Assinar();
$dto = new AssinarDTO();
$dto->setNome('Teste BLUDATA');
$dto->setDocumento('1234523422');
$dto->setTipoPessoa('Juridica');
$dto->setEmail(new EmailDTO('testemudanca@bludatateste.com.br'));
$dto->setLoginUsuarioDefault(new EmailDTO('testemudanca@bludatateste.com.br'));

$resposta_final = $assinar->Post($dto);
*/

//Conta Bancaria - (Necessário o email e token do assinar)
/*
$conta = new ContaBancaria(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto   = new ContaBancariaDTO();


$dto->setBanco('001');
$dto->setAgencia('224');
$dto->setConta('338');
$dto->setDescricao('Conta teste API ');

$resposta_final = $conta->Post($dto);

*/
//Get - Listar todas as contas
// $resposta_final = $conta->Get();

// GetById - Lista a conta que for passada no Id
/*
$dto->setId(52);
$resposta_final = $conta->GetById($dto);
*/

//Put - Alterar a conta informada no Id 
/*
$dto->setId(52);
$dto->setBanco('001'); // Banco deve estar cadastrado no pague veloz
$dto->setAgencia('222');
$dto->setConta('333');
$dto->setDescricao('Conta teste API - Alterada');
$resposta_final = $conta->Put($dto);
*/

// Delete - Excluir conta informada no Id
/*
$dto->setId(20);
$resposta_final = $conta->Delete($dto);
*/

// Emitir Boleto - (Necessário o email e token do assinar) -- Maneira velha
/*
$boleto = new EmitirBoleto(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new EmitirBoletoDTO();

$dto->setVencimento('2013-09-30');
$dto->setValor(10); //Valor passado puro, sem separador de decimal (ex: 10 = 10,00)
$dto->setSeuNumero(123);
$dto->setSacado('Nome do Sacado');
$dto->setCpfCnpjSacado('00000000000');
$dto->setParcela(1);
$dto->setLinha1('Texto da linha 1 - Teste da API');
$dto->setLinha2('Texto da linha 2 - Teste da API');
$dto->setCpfCnpjCedente('00000000000');
$dto->setCedente('Nome do Cedente');
$resposta_final = $boleto->Post($dto);
*/
// Consultar Boletos -  Maneira velha 
/*
$consultaBoleto = new ConsultarBoleto(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new ConsultarBoletoDTO();

$dto->setData('2013-07-31');
$resposta_final = $consultaBoleto->Get($dto);

*/


// Emitir Boleto - (Necessário o email e token do assinar) -- V2 - Maneira nova
/*
$boleto = new Boleto(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new BoletoDTO();

$dto->setVencimento('2013-09-30');
$dto->setValor(10); 
$dto->setSeuNumero(123);
$dto->setSacado('Nome do Sacado');
$dto->setCpfCnpjSacado('00000000000');
$dto->setParcela(1);
$dto->setLinha1('Texto da linha 1 - Teste da API');
$dto->setLinha2('Texto da linha 2 - Teste da API');
$dto->setCpfCnpjCedente('00000000000');
$dto->setCedente('Nome do Cedente');
$resposta_final = $boleto->Post($dto);
*/
/*
$dto->setData('2013-07-31');
$resposta_final = $boleto->Get($dto);
*/

//Saque
/*
$Saque = new Saque(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new SaqueDTO();

$dto->setId(60);
$dto->setValor(2.00);
$resposta_final = $Saque->Post($dto);
*/
// $resposta_final = $Saque->Get();
/*
$dto->setId(26);
$resposta_final = $Saque->GetById($dto);
*/
/*
$dto->setId(26);
$resposta_final = $Saque->Delete($dto);
*/

// Saldo
/*
$Saldo = new Saldo(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$resposta_final = $Saldo->Get();
*/

//Transferencia
/*
$Transferencia = new Transferencia(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new TransferenciaDTO();

$dto->setClienteDestino(new EmailDTO('contabancaria@testeapibludata.com.br'));
$dto->setValor(1.08);
$dto->setDescricao("Teste API");

$resposta_final = $Transferencia->Post($dto);
*/

//Consultar Cliente
/*
$ConsultaCliente = new ConsultarCliente(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new ConsultarClienteDTO();

$dto->setTipo('ClienteJaCadastrado');
$dto->setFiltro('bluteste@bluteste.com.br');

$resposta_final = $ConsultaCliente->Get($dto);
*/

echo '<pre>';
print_r($resposta_final);
echo '</pre>';

//ComprarCredito
/*

$compraCredito = new ComprarCreditosSMS(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new ComprarCreditosSMSDTO();

$dto->setCreditos(10);
$dto->setValor(5);
$resultado_final = $compraCredito->Post($dto);

print_r($resultado_final);
*/