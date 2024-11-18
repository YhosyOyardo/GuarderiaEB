<?php 
require('../librerias/fpdf/fpdf.php');

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "guarderiaeb");

// Verificar si la conexión es exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Variables de búsqueda
$buscar_ci_nombre = isset($_POST['buscar_ci_nombre']) ? $_POST['buscar_ci_nombre'] : '';
$buscar_fecha = isset($_POST['buscar_fecha']) ? $_POST['buscar_fecha'] : '';

// Crear consulta SQL con parámetros
$sql_asistencia = "SELECT n.CI_N, n.Nombre_N, n.Apellido_PN, n.Apellido_MN, a.Fecha_Asist, a.Estado 
                   FROM asistencia a 
                   JOIN ninos n ON a.CI_N = n.CI_N 
                   WHERE (n.CI_N LIKE CONCAT('%', ?, '%') 
                          OR n.Nombre_N LIKE CONCAT('%', ?, '%') 
                          OR n.Apellido_PN LIKE CONCAT('%', ?, '%') 
                          OR n.Apellido_MN LIKE CONCAT('%', ?, '%'))";

if (!empty($buscar_fecha)) {
    $sql_asistencia .= " AND a.Fecha_Asist = ?";
}

// Preparar la declaración
$stmt = $conexion->prepare($sql_asistencia);

// Ligar parámetros y ejecutar la declaración
if (!empty($buscar_fecha)) {
    $stmt->bind_param('sssss', $buscar_ci_nombre, $buscar_ci_nombre, $buscar_ci_nombre, $buscar_ci_nombre, $buscar_fecha);
} else {
    $stmt->bind_param('ssss', $buscar_ci_nombre, $buscar_ci_nombre, $buscar_ci_nombre, $buscar_ci_nombre);
}

$stmt->execute();
$resultado_asistencia = $stmt->get_result();

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Título del PDF
$pdf->Cell(0, 10, 'Lista de Asistencia', 0, 1, 'C');

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 10, 'CI', 1);
$pdf->Cell(35, 10, 'Nombre', 1);
$pdf->Cell(35, 10, 'Apellido Paterno', 1);
$pdf->Cell(35, 10, 'Apellido Materno', 1);
$pdf->Cell(30, 10, 'Fecha Asist.', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Ln();

// Contenido de la tabla
$pdf->SetFont('Arial', '', 10);
if ($resultado_asistencia->num_rows > 0) {
    while ($asistencia = $resultado_asistencia->fetch_assoc()) {
        $pdf->Cell(25, 10, $asistencia['CI_N'], 1);
        $pdf->Cell(35, 10, $asistencia['Nombre_N'], 1);
        $pdf->Cell(35, 10, $asistencia['Apellido_PN'], 1);
        $pdf->Cell(35, 10, $asistencia['Apellido_MN'], 1);
        $pdf->Cell(30, 10, $asistencia['Fecha_Asist'], 1);
        $pdf->Cell(30, 10, $asistencia['Estado'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No hay registros de asistencia', 1, 1, 'C');
}

// Salida del PDF
$pdf->Output();
?>
