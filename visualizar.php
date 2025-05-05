<?php
// Conexión a la base de datos (MySQLi orientado a objetos)
$servername = "localhost";
$username = "root";
$password = "123456";            // Si tu usuario root tiene contraseña, escríbela aquí
$dbname = "sensores";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener temperaturas y fechas
$consulta = "SELECT temperatura, fecha FROM datos ORDER BY fecha ASC";
$resultado = $conexion->query($consulta);

$temperaturas = [];
$fechas = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $temperaturas[] = $fila['temperatura'];
        $fechas[] = $fila['fecha'];
    }
} else {
    echo "No se encontraron registros.";
}

$conexion->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Visualización de Temperatura</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h2>Temperatura registrada</h2>
  <canvas id="grafico" width="600" height="400"></canvas>

  <script>
    // Datos obtenidos desde PHP
    const fechas = <?php echo json_encode($fechas); ?>;
    const temperaturas = <?php echo json_encode($temperaturas); ?>;

    // Configuración del gráfico
    const ctx = document.getElementById('grafico').getContext('2d');
    const grafico = new Chart(ctx, {
      type: 'line',
      data: {
        labels: fechas,
        datasets: [{
          label: 'Temperatura (°C)',
          data: temperaturas,
          backgroundColor: 'rgba(100, 150, 255, 0.2)',
          borderColor: 'rgba(0, 100, 255, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Fecha y hora'
            }
          },
          y: {
            beginAtZero: false,
            title: {
              display: true,
              text: 'Temperatura (°C)'
            }
          }
        }
      }
    });
  </script>
</body>
</html>
