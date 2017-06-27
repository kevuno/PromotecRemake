<?php
	require_once("Login/Login.php");

	function testCreateArrayCombinations(){
		$prefijos = Array("promotec" => "pt", "tarifario" => "tar","taf" => "taf");
		$campos = Array("sess_nombre" => "nom","sess_tipo" => "tipo","sess_lugar" => "lugar");
		$data = Array("nom" => "Kevin","tipo" => "grande","lugar" => "casa");
		$result = Login::createArrayCombinations($prefijos,$campos,$data);
		$result_expected = ["ptsess_nombre" => "Kevin","ptsess_tipo" => "grande","ptsess_lugar" => "casa","tarsess_nombre" => "Kevin","tarsess_tipo" => "grande","tarsess_lugar" => "casa","tafsess_nombre" => "Kevin","tafsess_tipo" => "grande","tafsess_lugar" => "casa"];
		var_dump(array_diff_key($result, $result_expected));
		var_dump(array_diff($result, $result_expected));
	}

	testCreateArrayCombinations();
?>