<?php
require_once 'conexion.php';

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirigir a la página de inicio de sesión si el usuario no ha iniciado sesión
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del usuario actual
    $userID = $_SESSION['user_id'];

    // Verificar si se debe eliminar el usuario
    if (isset($_POST['delete'])) {
        $conexion = new Conexion();
        $conn = $conexion->connect();

        // Eliminar todos los datos del usuario
        $sqlDelete = "DELETE FROM usuario WHERE id_usuario = $userID";
        if ($conn->query($sqlDelete) === TRUE) {
            // Redirigir a la página de inicio después de la eliminación exitosa
            echo '<script>alert("Usuario eliminado"); window.location.href = "index.html";</script>';
            exit;
        } else {
            $mensaje = 'Error al eliminar el usuario: ' . $conn->error;
        }

        $conn->close();
    } else {
        // Obtener los nuevos valores del nombre de usuario, contraseña y "sobre mí"
        $newUsername = $_POST['username'];
        $newPassword = $_POST['password'];
        $newAboutMe = $_POST['sobre-mi'];
        $selectedPokemon1 = $_POST['pokemon-fav1'];
        $selectedPokemon2 = $_POST['pokemon-fav2'];
        $selectedPokemon3 = $_POST['pokemon-fav3'];
        $selectedPokemon4 = $_POST['pokemon-fav4'];

        $conexion = new Conexion();
        $conn = $conexion->connect();

        // Obtener la ruta de la imagen de perfil
        $profileImage = '';
        if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['profile-image']['tmp_name'];
            $fileName = $_FILES['profile-image']['name'];
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $profileImage = 'img/' . uniqid() . '.' . $extension;
            move_uploaded_file($tmpName, $profileImage);

            // Renombrar la imagen de perfil utilizando el nombre original
            $newFileName = 'img/' . $fileName;
            rename($profileImage, $newFileName);

            $profileImage = $newFileName;
        }

        // Obtener las rutas de las imágenes de los Pokémon seleccionados desde la tabla "pokemon"
        $sqlSelectPokemon1 = "SELECT img FROM pokemon WHERE nombre_pokemon = '$selectedPokemon1'";
        $resultPokemon1 = $conn->query($sqlSelectPokemon1);
        $rowPokemon1 = $resultPokemon1->fetch_assoc();

        $sqlSelectPokemon2 = "SELECT img FROM pokemon WHERE nombre_pokemon = '$selectedPokemon2'";
        $resultPokemon2 = $conn->query($sqlSelectPokemon2);
        $rowPokemon2 = $resultPokemon2->fetch_assoc();

        $sqlSelectPokemon3 = "SELECT img FROM pokemon WHERE nombre_pokemon = '$selectedPokemon3'";
        $resultPokemon3 = $conn->query($sqlSelectPokemon3);
        $rowPokemon3 = $resultPokemon3->fetch_assoc();

        $sqlSelectPokemon4 = "SELECT img FROM pokemon WHERE nombre_pokemon = '$selectedPokemon4'";
        $resultPokemon4 = $conn->query($sqlSelectPokemon4);
        $rowPokemon4 = $resultPokemon4->fetch_assoc();

        // Verificar si se encontraron los Pokémon seleccionados
        if ($rowPokemon1 !== null && $rowPokemon2 !== null && $rowPokemon3 !== null && $rowPokemon4 !== null) {
            // Aplicar addslashes para escapar las barras invertidas
            $selectedPokemonImage1 = addslashes($rowPokemon1['img']);
            $selectedPokemonImage2 = addslashes($rowPokemon2['img']);
            $selectedPokemonImage3 = addslashes($rowPokemon3['img']);
            $selectedPokemonImage4 = addslashes($rowPokemon4['img']);

            // Actualizar los campos correspondientes en la base de datos
            $sqlUpdate = "UPDATE usuario SET nombre = '$newUsername', contraseña = '$newPassword', sobre_mi = '$newAboutMe', pkmn_fav1 = '$selectedPokemonImage1', pkmn_fav2 = '$selectedPokemonImage2', pkmn_fav3 = '$selectedPokemonImage3', pkmn_fav4 = '$selectedPokemonImage4', img_perfil = '$profileImage' WHERE id_usuario = $userID";
            if ($conn->query($sqlUpdate) === TRUE) {
                // Redirigir a la página de perfil del usuario después de la actualización exitosa
                echo '<script>alert("Usuario actualizado"); window.location.href = "profile.php";</script>';
                exit;
            } else {
                $mensaje = 'Error al actualizar el perfil: ' . $conn->error;
            }
            
        } else {
            $mensaje = 'No se encontraron los Pokémon seleccionados.';
        }

        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Perfil de Usuario</title>
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
        <a href="profile.php" class="btn-blue"><button>VOLVER</button></a>
    </div>
</header>

<main>
<div class="container" style="display: grid; place-items: center;height: 130vh;">
    <h2>Editar Perfil</h2>
    <?php if (isset($mensaje)): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <div class="form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" style="height: 100vh";>
        <div class="input-group">
            <label for="profile-image">Imagen de Perfil:</label>
            <input type="file" id="profile-image" name="profile-image">
        </div>
        <div class="input-group">
            <label for="username">Nuevo Nombre de Usuario:</label>
            <input type="text" id="username" name="username">
        </div>
        <div class="input-group">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="input-group">
            <label for="sobre-mi">Sobre mí:</label>
            <textarea id="sobre-mi" name="sobre-mi"></textarea>
        </div>
        <div class="input-group">
            <label for="pokemon-fav1">Pokemon Favorito 1:</label>
            <select id="pokemon-fav1" name="pokemon-fav1">
                <?php
                $conexion = new Conexion();
                $conn = $conexion->connect();

                // Obtener los nombres de los Pokémon desde la tabla "pokemon"
                $sqlSelectPokemon = "SELECT nombre_pokemon FROM pokemon";
                $resultPokemon = $conn->query($sqlSelectPokemon);

                if ($resultPokemon->num_rows > 0) {
                    while ($rowPokemon = $resultPokemon->fetch_assoc()) {
                        $pokemonName = $rowPokemon['nombre_pokemon'];
                        echo "<option value='$pokemonName'>$pokemonName</option>";
                    }
                }

                $conexion->closeConnection();
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="pokemon-fav2">Pokemon Favorito 2:</label>
            <select id="pokemon-fav2" name="pokemon-fav2">
                <?php
                $conexion = new Conexion();
                $conn = $conexion->connect();

                // Obtener los nombres de los Pokémon desde la tabla "pokemon"
                $sqlSelectPokemon2 = "SELECT nombre_pokemon FROM pokemon";
                $resultPokemon2 = $conn->query($sqlSelectPokemon2);

                if ($resultPokemon2->num_rows > 0) {
                    while ($rowPokemon2 = $resultPokemon2->fetch_assoc()) {
                        $pokemonName2 = $rowPokemon2['nombre_pokemon'];
                        echo "<option value='$pokemonName2'>$pokemonName2</option>";
                    }
                }

                $conexion->closeConnection();
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="pokemon-fav3">Pokemon Favorito 3:</label>
            <select id="pokemon-fav3" name="pokemon-fav3">
                <?php
                $conexion = new Conexion();
                $conn = $conexion->connect();

                // Obtener los nombres de los Pokémon desde la tabla "pokemon"
                $sqlSelectPokemon3 = "SELECT nombre_pokemon FROM pokemon";
                $resultPokemon3 = $conn->query($sqlSelectPokemon3);

                if ($resultPokemon3->num_rows > 0) {
                    while ($rowPokemon3 = $resultPokemon3->fetch_assoc()) {
                        $pokemonName3 = $rowPokemon3['nombre_pokemon'];
                        echo "<option value='$pokemonName3'>$pokemonName3</option>";
                    }
                }

                $conexion->closeConnection();
                ?>
            </select>
        </div>
        <div class="input-group">
            <label for="pokemon-fav4">Pokemon Favorito 4:</label>
            <select id="pokemon-fav4" name="pokemon-fav4">
                <?php
                $conexion = new Conexion();
                $conn = $conexion->connect();

                // Obtener los nombres de los Pokémon desde la tabla "pokemon"
                $sqlSelectPokemon4 = "SELECT nombre_pokemon FROM pokemon";
                $resultPokemon4 = $conn->query($sqlSelectPokemon4);

                if ($resultPokemon4->num_rows > 0) {
                    while ($rowPokemon4 = $resultPokemon4->fetch_assoc()) {
                        $pokemonName4 = $rowPokemon4['nombre_pokemon'];
                        echo "<option value='$pokemonName4'>$pokemonName4</option>";
                    }
                }

                $conexion->closeConnection();
                ?>
            </select>
        </div><br><br>
        <button type="submit" style="width: 160px; margin-left: 20px; font-weight: 700; color: #fff; padding: 9px 25px; background-color: #59203B; border: 1px solid #C6B4D9; border-radius: 0.7rem; cursor: pointer; transition: all 0.3s ease 0s;">Guardar Cambios</button>
        <br><br>
        <button type="submit" name="delete" class="btn-red" style="width: 160px; margin-left: 20px; font-weight: 700; color: #000; padding: 9px 25px; background-color: #99E9F2; border: 1px solid #79B4D9; border-radius: 0.7rem; cursor: pointer; transition: all 0.3s ease 0s;">Eliminar Cuenta</button>


    </form>
    </div>
    </div>
</main>

<footer>
        ©️️️2023 Pokémon. ©️️️1995-2023 Nintendo/Creatures Inc./GAME FREAK inc. TM, ®️ y los nombres de los personajes son marcas de Nintendo
</footer>
</body>
</html>
