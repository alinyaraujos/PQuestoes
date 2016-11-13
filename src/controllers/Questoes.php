<?php
namespace controllers;
use configs\DB as DB;

require __DIR__ .'/../classes/Filetotext.php';

class Questoes
{
	protected $app;
	
	function __construct($app)
	{
		$this->app=$app;
	}

	public function add($request, $response, $args){

			$uploadfile = 'pdf/' . $_FILES['arquivo']['name'];
			$inforNome = pathinfo($_FILES['arquivo']['name']);

			if(($inforNome['extension']=='pdf') || ($inforNome['extension']=='docx') || ($inforNome['extension']=='doc') ){
				
				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)){
					$docObj = new Filetotext($_FILES['arquivo']['name']);  
					$resultado = $docObj->convertToText();
					

					if($inforNome['extension']=='pdf'){

						$arquivo = DB::dispense("arquivos");
						$arquivo->nome = $_FILES['arquivo']['name'];
						$arquivo->nome_assunto = $_POST['assunto'];
						$id = DB::store($arquivo);
						$perguntas=utf8_encode($resultado);
						if(isset($_POST['assunto'])){
							$result = preg_split('/([0-9]{1,}\s)-|[0-9]{1,}[\.]/',$perguntas); 
						}

						foreach ($result as $valor) {
							$questao = DB::dispense("cadastro");
							$questao->pergunta = $valor;
							$questao->id_arquivos = $arquivo->id;
							$id = DB::store($questao);
						}
					}


					else{

						$arquivo = DB::dispense("arquivos");
						$arquivo->nome = $_FILES['arquivo']['name'];
						$arquivo->nome_assunto = $_POST['assunto'];
						$id = DB::store($arquivo);

						if(isset($_POST['assunto'])){
							$result = preg_split('/[0-9]{1,}-|[0-9]{1,}[\.]/', $resultado);
						}
						foreach ($result as $valor) {
							$questao = DB::dispense("cadastro");
							$questao->pergunta = $valor;
							$questao->id_arquivos = $arquivo->id;
							$id = DB::store($questao);
						}			
					}
				}
				
				else {
					echo "Arquivo não enviado";
				}
				
				return $response->withRedirect("/questaoCadastrada");
			}	
				return $response->withRedirect("/questaoCadastrada");	
	}

	public function exibirFormulario($request, $response, $args){
		$busca= DB::findAll("cadastro","id_arquivos = ?", 
			array(
				$_POST["idNome"]
			)
		);

		$buscaNome= DB::findOne("arquivos","id= ?", 
			array(
				$_POST["idNome"]
			)
		);

		$info=explode('.',$buscaNome->nome);

		$i=0;
		$soma=0;
		$arrayQuestoes=array();
		$letra= array('a','b','c','d','e');

	
	foreach ($busca as $valor) {
	
		if(preg_match('/[a-e]\)/', $valor->pergunta)){
			
			if($info[1]=='pdf'){
				$result = preg_split('/[a-e]\)|[a-e]\-|[a-e]\s\-|[a-e]\s\)/', $valor->pergunta);
			}else{
				$result = preg_split('/[a-e]\)|[a-e]\-/', $valor->pergunta);
			}
			$quantidade=count($result); 
			$j=0;
			foreach ($result as $value) {
				if($j==0){
					array_push($arrayQuestoes,
						array(
						$value	
						)

					);
				}else{
					$arrayQuestoes[$i-1][1][$j]= [$letra[$j-1], $value];
				}

				$j++;
			}
		}
		$i++;
	}

	return $this->app->view->render($response, 
			'formulario.twig', array(
			'questoes'=>$arrayQuestoes,
			'idArquivo'=> $_POST["idNome"]
		));
			
	}
			
}	
