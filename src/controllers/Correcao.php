<?php
namespace controllers;
use configs\DB as DB;

class Correcao
{
	protected $app;

	function __construct($app)
	{
		$this->app=$app;
	}

	public function calcularAcertos($request, $response, $args){
		$acertou=0;
		$arrayErrada=array();
		$arrayRespostas=array();

		$busca= DB::findOne("gabarito","id_arquivo = ?", 
				array(
					$_POST["nomeArquivo"]
					)
				);

		
		if( $busca == NULL ){
			
			$qnt=0;
			
			$quantidadePerguntas = DB::findAll("cadastro","id_arquivos = ?", 
				array(
					$_POST["nomeArquivo"]
					)
			);
			$letra= array('a','b','c','d','e');
			
			foreach ($quantidadePerguntas as $valor) {
				if(preg_match('/[a-e]\)/', $valor->pergunta)){
				$qnt++;
				}
			}


			for ($i=1; $i <count($quantidadePerguntas) ; $i++) { 
			
				if(isset($_POST["q".$i])){
					array_push($arrayRespostas, 
					array('respostas' => $_POST["q".$i]
					)
				);		
				}
			
			}

			$_SESSION["respostas"]=$arrayRespostas;
			return $this->app->view->render($response,'addGabarito.twig', array(
				'quantidade'=>$qnt, 
				'letra'=>$letra, 
				'idArquivo'=> $_POST["nomeArquivo"],
				)
			);

		}else{

		$gabarito=preg_split('/[0-9]{1,}-/', $busca->respostas);


		for ($i=1; $i <count($gabarito) ; $i++) { 
			
			if(strcasecmp($_POST["q".$i],$gabarito[$i])==0){
				$acertou++;			
			}else{
				array_push($arrayErrada, 
					array('erradas' => $i
					)
				);
			}
			
		}
		

		$erro=count($gabarito)-($acertou+1);
		$porcentagem=$acertou*100/(count($gabarito)-1);
		
		return $this->app->view->render($response, 'corrigido.twig', array(
				'acertou'=>$acertou,
				'erro'=>$erro,
				'porcentagem'=>number_format($porcentagem,2),
				'erradas'=>$arrayErrada
			));
		}
	}
		
}