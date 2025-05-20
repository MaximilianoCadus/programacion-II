<?php
	$edad = 18;
	if ($edad >= 18) {
		echo "Mayor de edad" . "\n";
	} else {
		echo "Menor de edad" . "\n";
	}

	for ($i = 0; $i  <= 20; $i ++){
		echo $i . "\n";
	}

	$i = 1;
	$acumulador = 0;
	while ($i <= 50) {
		$acumulador = $acumulador + $i;
		echo $acumulador . "\n";
		$i++;
	}

	$nombres = array("Maxi", "Fede", "Bruno", "Valen");
	echo "<ul>";
	foreach ($nombres as $nombre) {
		echo "<li>$nombre</li>";
	}
	echo "</ul>";

	$dia = 3;

	switch ($dia) {
		case 1:
			echo "Lunes";
			break;
		case 2:
			echo "Martes";
			break;
		case 3:
			echo "Miércoles";
			break;
		case 4:
			echo "Jueves";
			break;
		case 5:
			echo "Viernes";
			break;
		case 6:
			echo "Sábado";
			break;
		case 7:
			echo "Domingo";
			break;
		default:
			echo "Día no válido";
			break;
	}

?>