<?php
require_once 'conexion.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Creo una instancia de la clase Conexion
    $conexion = new Conexion();
    $conn = $conexion->connect();

    // Preparo la consulta para seleccionar al usuario
    $query = "SELECT * FROM usuario WHERE nombre = '$username' AND contraseña = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Inicio de sesión exitoso
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id_usuario']; // Almaceno el ID del usuario en una variable de sesión
        echo "<script>alert('Inicio de sesión exitoso');</script>";
        echo "<script>window.location.href = 'profile.php';</script>";
        exit;
    }
    else {
        // Nombre de usuario incorrecto o no registrado
        echo "<script>alert('Nombre de usuario incorrecto o no registrado');</script>";
    }

    // Cierro la conexión con la base de datos
    $conexion->closeConnection();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            <h3>Iniciar sesión</h3><br><br>
            <div class="input-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div><br>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div><br><br>
            <div class="input-group">
                <button type="submit" name="submit">Iniciar sesión</button>
            </div>
            <a href="register.php">¿No tienes una cuenta?</a><br>
            <a href="index.html">Volver</a>
        </form>
    </div>

    <footer>
        ©️️️2023 Pokémon. ©️️️1995-2023 Nintendo/Creatures Inc./GAME FREAK inc. TM, ®️ y los nombres de los personajes son marcas de Nintendo
    </footer>

</body>
</html>
