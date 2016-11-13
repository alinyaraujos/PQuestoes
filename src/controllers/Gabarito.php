<?php
namespace controllers; 
use configs\DB as DB;

class Gabarito
{
	protected $app;

	function __construct($app)
	{
		$this->app=$app;
	}

	public function add($request, $response, $args){
		
		$arquivo=$_POST["nomeArquivo"];
		$quantidade=$_POST["quantidade"];
		$resposta=null;
		$acertou=0;
		$arrayErrada=array();
		$arrayRespo=" ";
		$arrayR=array();

		foreach ($_SESSION["respostas"] as $key) {
			$arrayRespo=$arrayRespo. "," . $key["respostas"];
		}
		unset($_SESSION["respostas"]);


		for ($i=1; $i <=$quantidade ; $i++) {
			$resposta=$resposta . $i. '-' .$_POST["q".$i];
		}

		$gabarito = DB::dispense("gabarito");
		$gabarito->respostas = $resposta;
		$gabarito->id_arquivo = $arquivo;
		$id = DB::store($gabarito); 

		$resultado=preg_split('/[0-9]{1,}-/', $resposta);
		$arrayR=explode(',', $arrayRespo);

		for ($i=1; $i <count($resultado) ; $i++) { 
			
			if(strcasecmp($_POST["q".$i],$arrayR[$i])==0){
				echo $arrayRespo[$i];
				$acertou++;		

			}else{
				array_push($arrayErrada, 
					array('erradas' => $i
					)
				);
			}
		}

		$erro=count($resultado)-($acertou+1);
		$porcentagem=$acertou*100/(count($resultado)-1);
	
		return $this->app->view->render($response, 'corrigido.twig', array(
				'acertou'=>$acertou,
				'erro'=>$erro,
				'porcentagem'=>number_format($porcentagem,2),
				'erradas'=>$arrayErrada
			
			));
	}

	public function mostrarGabarito($request, $response, $args){

		$arrayGabarito=array();

		$busca= DB::findOne("gabarito","id_arquivo = ?", 
				array(
					$_POST["nomeArquivo"]
					)
				);
		if($busca!=null){
		$gabarito=preg_split('/[0-9]{1,}-/', $busca->respostas);

		for ($i=1; $i <count($gabarito) ; $i++) { 
			
				array_push($arrayGabarito, 
					array('gabarito' => $gabarito[$i]
					)
				);
		}
		}
		return $this->app->view->render($response, 'gabarito.twig', array(
				'gabarito'=>$arrayGabarito

			));
	}

}