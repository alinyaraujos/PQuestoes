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

/*	public function add($request, $response, $args){
		
		$arquivo=$_POST["nomeArquivo"];
		$quantidade=$_POST["quantidade"];
		$resposta=null;

		for ($i=1; $i <=$quantidade ; $i++) {
			$resposta=$resposta . $i. '-' .$_POST["q".$i];
		}

		$gabarito = DB::dispense("gabarito");
		$gabarito->respostas = $resposta;
		$gabarito->id_arquivo = $arquivo;
		$id = DB::store($gabarito); 

		return $response->withRedirect("/questaoCadastrada");
	}*/

}