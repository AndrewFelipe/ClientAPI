<?php

use \PagueVeloz\Api\Assinar;
use \PagueVeloz\Api\Saldo;
use \PagueVeloz\Api\EmitirBoleto;
use \PagueVeloz\Api\ContaBancaria;
use \PagueVeloz\Api\Transferencia;
use \PagueVeloz\Api\ConsultarBoleto;
use \PagueVeloz\Api\ConsultarCliente;
use \PagueVeloz\Api\ComprarCreditosSMS;
use \PagueVeloz\Api\Saque;

use \PagueVeloz\Dto\AssinarDTO;
use \PagueVeloz\Dto\EmailDTO;
use \PagueVeloz\Dto\ContaBancariaDTO;
use \PagueVeloz\Dto\EmitirBoletoDTO;
use \PagueVeloz\Dto\ConsultarBoletoDTO;
use \PagueVeloz\Dto\ComprarCreditosSMSDTO;
use \PagueVeloz\Dto\SaqueDTO;
use \PagueVeloz\Dto\TransferenciaDTO;
use \PagueVeloz\Dto\ConsultarClienteDTO;

require 'SplClassLoader.php';

$path = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR);
$loader = new SplClassLoader('PagueVeloz', $path);
$loader->register();

define('PAGUEVELOZ_URL', 'http://pagueveloz.homolog.bludata.net/api/v1');
// define('PAGUEVELOZ_URL', 'http://192.168.0.173/api/v1');

/* Assinar */
/*
$assinar = new Assinar();
$dto = new AssinarDTO();
$dto->setNome('Teste API - Bludata');
$dto->setDocumento('123452');
$dto->setTipoPessoa('Juridica');
$dto->setEmail(new EmailDTO('testeapi@testeapibludata.com.br'));
$dto->setLoginUsuarioDefault(new EmailDTO('testeapi@testeapibludata.com.br'));

$resposta_final = $assinar->Post($dto);

print_r($resposta_final);
*/

/*Conta Bancaria - (Necessário o email e token do assinar) */
/*
$conta = new ContaBancaria(new EmailDTO('testeapi@testeapibludata.com.br'),'12318121-f908-49e7-8e6b-c1ad1d9f1658');
$dto   = new ContaBancariaDTO();
*/

/* Post - Criar conta*/

/*
$dto->setBanco('413');
$dto->setAgencia('224');
$dto->setConta('335');
$dto->setDescricao('Conta teste API 2');

$resposta_final = $conta->Post($dto);
*/

/*Get - Listar todas as contas*/
// $resposta_final = $conta->Get();

// $dto->setId(42); 
/*GetById - Lista a conta que for passada no Id */
// $resposta_final = $conta->GetById($dto); 

/*Put - Alterar a conta informada no Id*/
/*
$dto->setBanco('1111');
$dto->setAgencia('222');
$dto->setConta('333');
$dto->setDescricao('Conta teste API - Alterada');
$resposta_final = $conta->Put($dto);

*/
 /*Delete - Excluir conta informada no Id*/
 
// $resposta_final = $conta->Delete($dto);

// print_r($resposta_final);

 /*Emitir Boleto - (Necessário o email e token do assinar) */

/*
$boleto = new EmitirBoleto(new EmailDTO('testeapi@testeapibludata.com.br'),'6e20c352-666f-495f-83cb-256299f55d36');
$dto = new EmitirBoletoDTO();
*/
/*
$dto->setVencimento('2013-07-31');
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

print_r($resposta_final);
*/

/*
$consultaBoleto = new ConsultarBoleto(new EmailDTO('testeapi@testeapibludata.com.br'),'6e20c352-666f-495f-83cb-256299f55d36');
$dto = new ConsultarBoletoDTO();

$dto->setData('2013-07-31');
$resultado_final = $consultaBoleto->Get($dto);

print_r($resultado_final);
*/

/*

$compraCredito = new ComprarCreditosSMS(new EmailDTO('testebug@testeapibludata.com.br'),'0fbf0200-f74a-4cef-90c0-1e2bc44eb250');
$dto = new ComprarCreditosSMSDTO();

$dto->setCreditos(10);
$dto->setValor(5);
$resultado_final = $compraCredito->Post($dto);

print_r($resultado_final);
*/

/*Saque*/
/*
$Saque = new Saque(new EmailDTO('contabancaria@testeapibludata.com.br'),'544b1836-279a-44eb-b947-fa6ba05a4318');
$dto = new SaqueDTO();

$dto->setId(60);
$dto->setValor(2.00);
$resultado_final = $Saque->Post($dto);
$dto->setId(26);
$resultado_final = $Saque->GetById($dto);
$resultado_final = $Saque->Delete($dto);

print_r($resultado_final);*/

/*Saldo*/

/*$Saldo = new Saldo(new EmailDTO('contabancaria@testeapibludata.com.br'),'544b1836-279a-44eb-b947-fa6ba05a4318');
$resultado_final = $Saldo->Get();

print_r($resultado_final);*/

/*Transferencia*/
/*
$Transferencia = new Transferencia(new EmailDTO('contabancaria@testeapibludata.com.br'),'544b1836-279a-44eb-b947-fa6ba05a4318');
$dto = new TransferenciaDTO();

$dto->setClienteDestino(new EmailDTO('contabancaria@testeapibludata.com.br'));
$dto->setValor(1.08);
$dto->setDescricao("Teste API");

$resultado_final = $Transferencia->Post($dto);

print_r($resultado_final);*/

/*Consultar Cliente*/
/*
$ConsultaCliente = new ConsultarCliente(new EmailDTO('oi@oi.com.br'),'1701bec6-ec6b-4aff-b706-961bda5e9fee');
$dto = new ConsultarClienteDTO();

$dto->setTipo('ClienteJaCadastrado');
$dto->setFiltro('bluteste@bluteste.com.br');

$resultado_final = $ConsultaCliente->Get($dto);

print_r($resultado_final);*/