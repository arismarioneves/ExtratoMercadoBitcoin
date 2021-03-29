<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<!-- 
		▓▓▓▓Dev by Mari05liM▓▓▓▓
		Mari05liM
		mariodev@outlook.com.br
		 -->
		<meta charset="UTF-8">
		<title>Extrato - MercadoBTC</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/journal/bootstrap.min.css">
	</head>
	<?php
		date_default_timezone_set('America/Sao_Paulo');
		include "mbtc.php";
		
		$apiId = null;
		$apiKey = null;
		$anoExtrato = null;
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(isset($_POST['id'])){$apiId=$_POST['id'];}
			if(isset($_POST['key'])){$apiKey=$_POST['key'];}
			
			if(isset($_POST['ano'])){$anoExtrato=$_POST['ano'];}
		}
	?>
	<body>
		<header class="header">
			<nav class="navbar bg-list-primary">
				<div class="container">
					<a class="navbar-brand" href="">
						Extrato Criptos
					</a>
				</div>
			</nav>
		</header>
		<div id="app" class="container">
			<div class="text-center">
				<h1>Extrato - MercadoBTC</h1>
				<h6>Extrato de Criptomoedas e Tokens do MercadoBitcoin para Imposto de Renda<h6>
			</div>
			<div class="row">
				<div class="mb-3">Chave de API de Negociações</div>
				<div class="form-group">	
					<form class="form-inline" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
						<div class="form-group mx-sm-3 mb-2">
							<label class="sr-only">Identificador</label>
							<input type="id" name="id" class="form-control" placeholder="Identificador">
						</div>
						<div class="form-group mb-2">
							<label class="sr-only">Segredo</label>
							<input type="key" name="key" class="form-control" placeholder="Segredo">
						</div>
						<div class="custom-control custom-radio mx-sm-3 mb-2">
							<input type="radio" id="customRadio1" name="ano" value="2020" class="custom-control-input">
							<label class="custom-control-label" for="customRadio1" <?php echo ($anoExtrato == "2020") ? "checked" : null; ?>>2020</label>
						</div>
						<div class="custom-control custom-radio mb-2">
							<input type="radio" id="customRadio2" name="ano" value="2021" class="custom-control-input">
							<label class="custom-control-label" for="customRadio2" <?php echo ($anoExtrato == "2021") ? "checked" : null; ?>>2021</label>
						</div>
						<div class="custom-control custom-radio mx-sm-3 mb-2">
							<input type="radio" id="customRadio3" name="ano" value="COMPLETO" class="custom-control-input">
							<label class="custom-control-label" for="customRadio3" <?php echo ($anoExtrato == "COMPLETO") ? "checked" : null; ?>>COMPLETO</label>
						</div>
						<button type="submit" class="btn btn-primary mb-2">Gerar Extrato</button>
					</form>
					<div id="help" class="form-text">Use uma Chave de <b>Apenas Leitura</b></div>
				</div>
		<?php
			if (empty($apiId)) {
				echo "Informe um Identificador para gerar o Extrato! Acesse&nbsp;<a href=\"https://www.mercadobitcoin.com.br/trade-api/configuracoes/ target=\"_blank\" \">
					https://www.mercadobitcoin.com.br/trade-api/configuracoes/</a>&nbsp;e obtenha a&nbsp;<b>Chaves de API de Negociações</b>";
			} else {
				
				$mbtc = new MercadoBitcoin($apiId, $apiKey);
				
				if ($anoExtrato == "2020") {
					$from_timestamp = "1577847600"; //01/01/2020 00:00:00
					$to_timestamp = "1609469999"; //12/07/2022 23:59:59
				} else if ($anoExtrato == "2021"){
					$from_timestamp = "1609470000"; //01/01/2021 00:00:00
					$to_timestamp = "1641005999"; //31/12/2021 23:59:59
				} else {
					$from_timestamp = null;
					$to_timestamp = null;
				}
				
				// Criptomoedas
				$btc = $mbtc->listOrders("BRLBTC", "true", $from_timestamp, $to_timestamp);
				$bch = $mbtc->listOrders("BRLBCH", "true", $from_timestamp, $to_timestamp);
				$eth = $mbtc->listOrders("BRLETH", "true", $from_timestamp, $to_timestamp);
				$ltc = $mbtc->listOrders("BRLLTC", "true", $from_timestamp, $to_timestamp);
				$paxg = $mbtc->listOrders("BRLPAXG", "true", $from_timestamp, $to_timestamp);
				$usdc = $mbtc->listOrders("BRLUSDC", "true", $from_timestamp, $to_timestamp);
				$xrp = $mbtc->listOrders("BRLXRP", "true", $from_timestamp, $to_timestamp);
				
				// Tokens
				$link = $mbtc->listOrders("BRLLINK", "true", $from_timestamp, $to_timestamp);
				$chz = $mbtc->listOrders("BRLCHZ", "true", $from_timestamp, $to_timestamp);
				$mco2 = $mbtc->listOrders("BRLMCO2", "true", $from_timestamp, $to_timestamp);
				$wbx = $mbtc->listOrders("BRLWBX", "true", $from_timestamp, $to_timestamp);
				
				//$JSON = json_encode($xrp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
				//echo($JSON);
				
				$objectMap = array_merge(
					(array) $btc->response_data->orders,
					(array) $bch->response_data->orders,
					(array) $eth->response_data->orders,
					(array) $ltc->response_data->orders,
					(array) $paxg->response_data->orders,
					(array) $usdc->response_data->orders,
					(array) $xrp->response_data->orders,
					
					(array) $link->response_data->orders,
					(array) $chz->response_data->orders,
					(array) $mco2->response_data->orders,
					(array) $wbx ->response_data->orders);
				?>
				
			<?PHP $date = new DateTime();
				  $soma = null;?>
			
			<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Moeda</th>
					<th scope="col">Ordem</th>
					<th scope="col">Quantidade</th>
					<th scope="col">Preço Moeda (R$)</th>
					<th scope="col">Valor Ordem (R$)</th>
					<th scope="col">Data</th>
				</tr>
			</thead>
			
			<?PHP foreach($objectMap as $key => $cripto): ?>
				<tr>
					<td><?PHP echo str_replace("BRL", "", $cripto->coin_pair); ?></td>
					<?PHP echo $cripto->order_type == 1 ? "<td style=\"color:#008000;\">COMPRA" : "<td style=\"color:#CC0000;\">VENDA"; ?></td>
					<td><?PHP echo number_format($cripto->executed_quantity,8,",","."); ?></td>
					<td><?PHP echo number_format($cripto->executed_price_avg,3,",","."); ?></td>
					<td><?PHP echo number_format(($cripto->executed_quantity * $cripto->executed_price_avg),2,",","."); ?></td>
					<td><?PHP $date->setTimestamp($cripto->updated_timestamp); echo $date->format('H:i d/m/Y'); ?></td>
				</tr>
			<?PHP
				$soma += ($cripto->executed_quantity * $cripto->executed_price_avg);
				endforeach; ?>
					<thead>
						<tr>
							<th scope="col"></th>
							<th scope="col"></th>
							<th scope="col"></th>
							<th scope="col">Movimentação Total</th>
							<th scope="col"><?PHP echo number_format($soma,2,",",".") ?></th>
							<th scope="col"></th>
						</tr>
					</thead>
			</div>
		 <?php 
			 }?>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	</body>
</html>
