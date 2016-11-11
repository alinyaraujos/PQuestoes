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
			
}	
