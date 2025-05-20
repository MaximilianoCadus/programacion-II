<?php
	function sumar($a, $b){
		return $a + $b;
	}

	function restar($a, $b){
		return $a - $b;
	}

	function multiplicar($a, $b){
		return $a * $b;
	}

	function dividir($a, $b){
		return $a / $b;
	}

	echo sumar(2, 2);
	echo restar(2, 2);
	echo multiplicar(2, 2);
	echo dividir(2, 2);

	function comparar($a, $b){
		if ($a = $b) {
			return "Los números son iguales";
		}
		if ($a > $b) {
			return $a;
		} else {
			return $b;
		}
	}

	echo comparar(4, 5);

	function concatenar($a, $b){
		return $a . $b;
	}

	echo concatenar("primer cadena", "segunda cadena");
?>
