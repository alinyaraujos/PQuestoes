<?php

use configs\DB as DB;
use controllers\Questoes as Questoes;
use controllers\Gabarito as Gabarito;
//instancia o banco de dados RedBean
new DB();

$app->get('/', function ($request, $response, $args) {
	
	return $this->view->render($response, 'home.twig');
});

//Manipulacação das questões 
$app->group('', function() use($app){
	//Cadastrar as questoes. 
	$app->post('/questoes', '\controllers\Questoes:add');
	//Dividir questoes das alternativas.
	$this->post("/questao","controllers\Questoes:exibirFormulario")->setName("exibirFormulario");

});


//Manipulacação dos Arquivos 
$app->group('', function () use ($app) {
	//Mostrar os arquivos das questoes Cadastradas.
	$this->get("/questaoCadastrada","controllers\Arquivo:mostrar")->setName("mostrar");

});


//Manipulacação dos Gabarito
$app->group('', function () use ($app) {
	//Adicionar gabarito
	$this->post("/addGabarito","controllers\Gabarito:add")->setName("add");
	//Mostrar Gabarito 
	$this->post("/Gabarito","controllers\Gabarito:mostrarGabarito")->setName("mostrarGabarito");

});


//Manipulacação das Correções
$app->group('', function () use ($app) {
	//Correção do formulários respondidos, calculo de quantos estao certas , erradas e qual a porcentagem de acertos. 
	$this->post("/corrigir","controllers\Correcao:calcularAcertos")->setName("calcularAcertos");
});