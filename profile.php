<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
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
        <a href="seleccionarpokmn.php" class="btn-blue"><button>JUGAR</button></a>
        <a href="edit-profile.php" class="btn-blue"><button>EDITAR PERFIL</button></a>
        <a href="index.html" class="btn-purple"><button>CERRAR SESIÓN</button></a>   
    </div>
</header>

<div class="container" style="display: grid; place-items: center; text-align: center">
    <aside>
        <?php
        // Incluyo el archivo de la clase Conexion
        include 'Conexion.php';

        // Creo una instancia de la clase Conexion
        $conexion = new Conexion();

        // Conecto a la base de datos
        $conn = $conexion->connect();

        // Verifico si el usuario ha iniciado sesión
        session_start();
        if (isset($_SESSION['user_id'])) {
            // Obtengo el ID del usuario de la variable de sesión
            $userID = $_SESSION['user_id'];

            // Consulta para obtener los datos del usuario según su ID
            $query = "SELECT sobre_mi, img_perfil, pkmn_fav1, pkmn_fav2, pkmn_fav3, pkmn_fav4 FROM usuario WHERE id_usuario = '$userID'";
            $resultado = $conn->query($query);

            if ($resultado->num_rows > 0) {
                // Obtengo el resultado de la consulta
                $fila = $resultado->fetch_assoc();

                // Obtengo la ruta de la imagen de perfil
                $imgPerfil = $fila['img_perfil'];

                // Imprimo la imagen de perfil con la ruta obtenida
                echo '<img src="' . $imgPerfil . '" alt="Profile Image">';
                // Imprimo el contenido del campo "sobre_mi"
                echo '<h2>Sobre mí</h2>';
                echo '<p>' . $fila['sobre_mi'] . '</p>';

                // Obtengo las rutas de las imágenes de los Pokémon favoritos
                $pkmnFav1 = $fila['pkmn_fav1'];
                $pkmnFav2 = $fila['pkmn_fav2'];
                $pkmnFav3 = $fila['pkmn_fav3'];
                $pkmnFav4 = $fila['pkmn_fav4'];

                // Imprimo las imágenes de los Pokémon favoritos con las rutas obtenidas
                echo '
                <section>
                    <h2>Mis Pokémon favoritos</h2><br><br>
                    <div class="container">
                        <div class="square-row">
                            <div class="square">
                                <img class="bpkmn" src="' . $pkmnFav1 . '" alt="Photo 1">
                            </div>
                            <div class="square">
                                <img class="bpkmn" src="' . $pkmnFav2 . '" alt="Photo 2">
                            </div>
                        </div>
                        <div class="square-row">
                            <div class="square">
                                <img class="bpkmn" src="' . $pkmnFav3 . '" alt="Photo 3">
                            </div>
                            <div class="square">
                                <img class="bpkmn" src="' . $pkmnFav4 . '" alt="Photo 4">
                            </div>
                        </div>
                    </div>
                </section>';
            }
        } else {
            // Si el usuario no ha iniciado sesión, redirijo al formulario de inicio de sesión
            echo "<script>window.location.href = 'login.html';</script>";
            exit;
        }

        // Cierro la conexión a la base de datos
        $conexion->closeConnection();
        ?>
    </aside>
</div>

    <footer>
        ©️️️2023 Pokémon. ©️️️1995-2023 Nintendo/Creatures Inc./GAME FREAK inc. TM, ®️ y los nombres de los personajes son marcas de Nintendo
    </footer>
</body>
</html>
