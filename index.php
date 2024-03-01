<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Iniciar sesión
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];

    // Consulta para verificar las credenciales del usuario
    $consulta = "SELECT id, email FROM usuarios WHERE email = ? AND contrasena = ?";
    $declaracion = $conexion->prepare($consulta);
    $declaracion->bind_param("ss", $email, $contrasena);
    $declaracion->execute();
    $resultado = $declaracion->get_result();

    if ($resultado->num_rows == 1) {
        // Iniciar sesión y redirigir al usuario a la página principal
        $_SESSION["email"] = $email;
        header("location: pagina_principal.php");
    } else {
        // Mostrar mensaje de error si las credenciales son incorrectas
        $mensaje_error = "Correo electrónico o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>

    <?php if(isset($mensaje_error)) { ?>
        <p><?php echo $mensaje_error; ?></p>
    <?php } ?>
</body>
</html>
