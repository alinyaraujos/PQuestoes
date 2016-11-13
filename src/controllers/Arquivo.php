<?php
namespace controllers;
use configs\DB as DB;

class Arquivo
{
	protected $app;
	
	function __construct($app)
	{
		$this->app=$app;
	}


	public function mostrar($request, $response, $args){
		$arquivo= DB::findAll("arquivos");
		$arrayNomesArquivos=array();

		foreach ($arquivo as $valor) {
					array_push($arrayNomesArquivos,
						array(
							"nome"=>$valor->nome_assunto,
							"id"=>$valor->id
							)
						);
		}
		return $this->app->view->render($response, 'questaoCadastrada.twig',
			array(
				'arquivo'=>$arrayNomesArquivos
				
			)
		);
	}

}