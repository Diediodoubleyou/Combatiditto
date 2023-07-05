<?php

class Conexion {
    // Propiedades de la conexión
    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "ditto";
    private $conn;

    // Establezco la conexión con la base de datos
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            // Si hay un error al conectar, muestro un mensaje de error y termino la ejecución
            die("Error de conexión: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // Cierro la conexión con la base de datos
    public function closeConnection() {
        $this->conn->close();
    }
}
