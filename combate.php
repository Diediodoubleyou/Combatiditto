<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Combate</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="profile.php">
                <img src="img/317061259_1775438706175062_3158229757504465086_n.jfif" alt="Logo">
            </a>
            <h2>CombatiDitto</h2>
        </div>
        <div class="header-right">
            <a href="seleccionarpokmn.php" class="btn-blue"><button>VOLVER</button></a>
        </div>
    </header>
      
    <div class="container">
        <div class="div-left">
            <?php
            // Obtener el índice del Pokémon seleccionado
            if (isset($_POST['pokemonIndex'])) {
                $pokemonIndex = $_POST['pokemonIndex'];

                // Conectarse a la base de datos
                require_once('conexion.php'); // Incluir el archivo de la clase Conexion

                $conexion = new Conexion(); // Crear una instancia de la clase Conexion
                $conn = $conexion->connect(); // Establecer la conexión

                // Verificar la conexión
                if ($conn->connect_error) {
                    die("Error de conexión a la base de datos: " . $conn->connect_error);
                }

                // Consultar la información del Pokémon seleccionado
                $pokemonQuery = "SELECT * FROM pokemon WHERE id_pokemon = $pokemonIndex";
                $pokemonResult = $conn->query($pokemonQuery);

                if ($pokemonResult->num_rows > 0) {
                    // Mostrar los datos del Pokémon
                    while ($pokemonRow = $pokemonResult->fetch_assoc()) {
                        $nombrePokemon = $pokemonRow["nombre_pokemon"];
                        $vidaPokemon = $pokemonRow["vida"];

                        echo "<h3>" . $nombrePokemon . "</h3>";
                        echo "Vida: <span id=\"vida-" . $pokemonIndex . "\">" . $vidaPokemon . "</span><br>";

                        // Mostrar la imagen del Pokémon
                        echo '<img src="' . $pokemonRow["img"] . '" alt="Pokémon" style="width: 200px; height: 200px;">';

                        // Consultar los ataques del Pokémon
                        $ataquesQuery = "SELECT * FROM ataques WHERE id_pokemon = $pokemonIndex";
                        $ataquesResult = $conn->query($ataquesQuery);

                        if ($ataquesResult->num_rows > 0) {
                            // Mostrar los ataques del Pokémon
                            while ($ataquesRow = $ataquesResult->fetch_assoc()) {
                                echo '<br>Ataque 1: <button onclick="performAttack(\'' . $ataquesRow["ataque1_nombre"] . '\', ' . $ataquesRow["ataque1_poder"] . ', \'vida-rival\', \'vida-' . $pokemonIndex . '\')">' . $ataquesRow["ataque1_nombre"] . '</button> (Poder: ' . $ataquesRow["ataque1_poder"] . ')<br>';
                                echo 'Ataque 2: <button onclick="performAttack(\'' . $ataquesRow["ataque2_nombre"] . '\', ' . $ataquesRow["ataque2_poder"] . ', \'vida-rival\', \'vida-' . $pokemonIndex . '\')">' . $ataquesRow["ataque2_nombre"] . '</button> (Poder: ' . $ataquesRow["ataque2_poder"] . ')<br>';
                                echo 'Ataque 3: <button onclick="performAttack(\'' . $ataquesRow["ataque3_nombre"] . '\', ' . $ataquesRow["ataque3_poder"] . ', \'vida-rival\', \'vida-' . $pokemonIndex . '\')">' . $ataquesRow["ataque3_nombre"] . '</button> (Poder: ' . $ataquesRow["ataque3_poder"] . ')<br>';
                                echo 'Ataque 4: <button onclick="performAttack(\'' . $ataquesRow["ataque4_nombre"] . '\', ' . $ataquesRow["ataque4_poder"] . ', \'vida-rival\', \'vida-' . $pokemonIndex . '\')">' . $ataquesRow["ataque4_nombre"] . '</button> (Poder: ' . $ataquesRow["ataque4_poder"] . ')<br>';
                            }
                        } else {
                            echo "No se encontraron ataques para este Pokémon.";
                        }
                    }
                } else {
                    echo "No se encontró ningún Pokémon con ese índice.";
                }

                // Cerrar la conexión a la base de datos
                $conexion->closeConnection();
            }
            ?>
        </div>

        <div class="div-center">
            <div id="registro-ataques">
                <!-- Registro de ataques y cambios en la barra de vida -->
            </div>
        </div>

        <div class="div-right">
            <?php
            // Generar un índice aleatorio entre 1 y 28
            $pokemonIndex = rand(1, 28);

            // Conectarse a la base de datos
            require_once('conexion.php'); // Incluir el archivo de la clase Conexion

            $conexion = new Conexion(); // Crear una instancia de la clase Conexion
            $conn = $conexion->connect(); // Establecer la conexión

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Error de conexión a la base de datos: " . $conn->connect_error);
            }

            // Consultar la información del Pokémon seleccionado
            $pokemonQuery = "SELECT * FROM pokemon WHERE id_pokemon = $pokemonIndex";
            $pokemonResult = $conn->query($pokemonQuery);

            if ($pokemonResult->num_rows > 0) {
                // Mostrar los datos del Pokémon
                while ($pokemonRow = $pokemonResult->fetch_assoc()) {
                    echo "<h3>" . $pokemonRow["nombre_pokemon"] . "</h3>";
                    echo "Vida: <span id=\"vida-rival\">100</span><br>";

                    // Mostrar la imagen del Pokémon
                    echo '<img src="' . $pokemonRow["img"] . '" alt="Pokémon" style="width: 200px; height: 200px;">';

                    // Consultar los ataques del Pokémon
                    $ataquesQuery = "SELECT * FROM ataques WHERE id_pokemon = $pokemonIndex";
                    $ataquesResult = $conn->query($ataquesQuery);

                    if ($ataquesResult->num_rows > 0) {
                        // Mostrar los ataques del Pokémon
                        while ($ataquesRow = $ataquesResult->fetch_assoc()) {
                            echo "<br>Ataque 1: " . $ataquesRow["ataque1_nombre"] . " (Poder: " . $ataquesRow["ataque1_poder"] . ")<br>";
                            echo "<span id=\"ataque-rival-1-poder\" style=\"display: none;\">" . $ataquesRow["ataque1_poder"] . "</span>";
                            echo "Ataque 2: " . $ataquesRow["ataque2_nombre"] . " (Poder: " . $ataquesRow["ataque2_poder"] . ")<br>";
                            echo "<span id=\"ataque-rival-2-poder\" style=\"display: none;\">" . $ataquesRow["ataque2_poder"] . "</span>";
                            echo "Ataque 3: " . $ataquesRow["ataque3_nombre"] . " (Poder: " . $ataquesRow["ataque3_poder"] . ")<br>";
                            echo "<span id=\"ataque-rival-3-poder\" style=\"display: none;\">" . $ataquesRow["ataque3_poder"] . "</span>";
                            echo "Ataque 4: " . $ataquesRow["ataque4_nombre"] . " (Poder: " . $ataquesRow["ataque4_poder"] . ")<br>";
                            echo "<span id=\"ataque-rival-4-poder\" style=\"display: none;\">" . $ataquesRow["ataque4_poder"] . "</span>";
                        }
                    } else {
                        echo "No se encontraron ataques para este Pokémon.";
                    }
                }
            } else {
                echo "No se encontró ningún Pokémon con ese índice.";
            }

            // Cerrar la conexión a la base de datos
            $conexion->closeConnection();
            ?>
        </div>
    </div>

    <footer>
        ©️️️2023 Pokémon. ©️️️1995-2023 Nintendo/Creatures Inc./GAME FREAK inc. TM, ®️ y los nombres de los personajes son marcas de Nintendo
    </footer>

    <script>
        function performAttack(attackName, attackPower, targetHPElementId, ownHPElementId) {
            // Lógica para realizar el ataque
            var rivalHPElement = document.getElementById(targetHPElementId);
            var rivalHP = parseInt(rivalHPElement.innerHTML);
            rivalHP -= attackPower;
            rivalHPElement.innerHTML = rivalHP;
            alert("Has realizado el ataque " + attackName + " con poder " + attackPower + ".\nLa vida del rival ahora es " + rivalHP);
            
            // Registro del ataque realizado
            var registroAtaquesElement = document.getElementById("registro-ataques");
            var registroAtaque = "Has realizado el ataque " + attackName + " con poder " + attackPower + ". La vida del rival ahora es " + rivalHP + "<br>";
            registroAtaquesElement.innerHTML += registroAtaque;

            if (rivalHP <= 0) {
                alert("¡Has ganado el combate!");
                window.location.href = "seleccionarpokmn.php";
                return;
            }

            // Lógica para que el rival realice un ataque
            var ownHPElement = document.getElementById(ownHPElementId);
            var ownHP = parseInt(ownHPElement.innerHTML);
            var rivalAttackPowerElement = document.getElementById("ataque-rival-" + Math.ceil(Math.random() * 4) + "-poder");
            var rivalAttackPower = parseInt(rivalAttackPowerElement.innerHTML);
            ownHP -= rivalAttackPower;
            ownHPElement.innerHTML = ownHP;
            alert("El rival ha realizado un ataque con poder " + rivalAttackPower + ".\nTu vida ahora es " + ownHP);
            
            // Registro del ataque del rival
            registroAtaque = "El rival ha realizado un ataque con poder " + rivalAttackPower + ". Tu vida ahora es " + ownHP + "<br>";
            registroAtaquesElement.innerHTML += registroAtaque;

            if (ownHP <= 0) {
                alert("¡Has perdido el combate!");
                window.location.href = "seleccionarpokmn.php";
            }
        }
    </script>
</body>
</html>
