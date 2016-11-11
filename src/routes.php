<?php  
use configs\DB as DB;
use controllers\Questoes as Questoes;
use controllers\Gabarito as Gabarito;
//instancia o banco de dados RedBean
new DB();

//Manipulacação das questões 
$app->group('/questoes', function() use($app){
	$app->post('/add', '\controllers\Questoes:add');

});

//Pagina inicial do site
$app->get('/', function ($request, $response, $args) {
	
	return $this->view->render($response, 'home.twig');
});

//Mostrar os arquivos das questoes Cadastradas.
$app->get('/questaoCadastrada', function ($request, $response, $args) {

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
		

	
	return $this->view->render($response, 'questaoCadastrada.twig', array(
			'arquivo'=>$arrayNomesArquivos
			));
});

//Dividir questoes das alternativas.
$app->post('/questao', function ($request, $response, $args) {

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

	return $this->view->render($response, 
			'formulario.twig', array(
			'questoes'=>$arrayQuestoes,
			'idArquivo'=> $_POST["idNome"]
		));
			
});	

//Correção do formulários respondidos. 
$app->post('/corrigir', function ($request, $response, $args) {
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
			return $this->view->render($response, 'addGabarito.twig', array(
			'quantidade'=>$qnt, 
			'letra'=>$letra, 
			'idArquivo'=> $_POST["nomeArquivo"],

		));

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
	
	return $this->view->render($response, 'corrigido.twig', array(
			'acertou'=>$acertou,
			'erro'=>$erro,
			'porcentagem'=>number_format($porcentagem,2),
			'erradas'=>$arrayErrada
		));
	}
});


$app->post('/addGabarito', function ($request, $response, $args) {

		
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
	
	return $this->view->render($response, 'corrigido.twig', array(
			'acertou'=>$acertou,
			'erro'=>$erro,
			'porcentagem'=>number_format($porcentagem,2),
			'erradas'=>$arrayErrada
		
		));
	
});

$app->post('/Gabarito', function($request, $response, $args) {
	
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
	return $this->view->render($response, 'gabarito.twig', array(
			'gabarito'=>$arrayGabarito

		));

});