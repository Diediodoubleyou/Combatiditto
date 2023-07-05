<?php
require_once 'conexion.php';

// Verifico si se ha enviado el formulario de registro
if (isset($_POST['register'])) {
    // Obtengo los valores enviados del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Creo una instancia de la clase Conexion
    $conexion = new Conexion();

    // Establezco la conexión
    $conn = $conexion->connect();

    // Verifico si el nombre de usuario ya está registrado
    $query = "SELECT * FROM usuario WHERE nombre = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // El nombre de usuario ya está registrado
        echo "<script>alert('Usuario ya registrado con ese nombre');</script>";
    } else {
        // Inserto el usuario en la base de datos
        $sql = "INSERT INTO usuario (nombre, contraseña) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Muestro un mensaje de registro exitoso en JavaScript
            echo "<script>alert('Registro exitoso');</script>";
            // Redirijo al usuario al archivo index.html después de 3 segundos
            header("refresh:3; url=index.html");
            exit;
        } else {
            echo "Error en el registro: " . $conn->error;
        }
    }

    // Cierro la conexión
    $conexion->closeConnection();
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-left">
            <a href="index.html">
                <img src="img/317061259_1775438706175062_3158229757504465086_n.jfif" alt="Logo">
            </a>
            <h2>CombatiDitto</h2>
        </div>
    </header>

    <div class="login-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3>Crear Cuenta</h3><br><br>
            <div class="input-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div><br>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" name="register">Crear Cuenta</button>
            </div><br>
            <a href="index.html">volver</a>
        </form>
    </div>

    <footer>
        ©️️️2023 Pokémon. ©️️️1995-2023 Nintendo/Creatures Inc./GAME FREAK inc. TM, ®️ y los nombres de los personajes son marcas de Nintendo
    </footer>

</body>
</html>
