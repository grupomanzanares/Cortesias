<?php
/* api/registro.php – Endpoint de registro Café Forestal */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')    { echo json_encode(['success'=>false,'message'=>'Método no permitido']); exit; }

/* ─── Configuración BD ─────────────────────────────────────────────────── */
define('DB_HOST', 'localhost');
define('DB_NAME', 'forestal_registro');   // Cambia según tu BD
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHAR', 'utf8mb4');

/* ─── Entrada ──────────────────────────────────────────────────────────── */
$raw   = file_get_contents('php://input');
$body  = json_decode($raw, true);

if (!$body) { echo json_encode(['success'=>false,'message'=>'Datos inválidos']); exit; }

$nombre  = trim($body['nombre']  ?? '');
$correo  = trim($body['correo']  ?? '');
$celular = trim($body['celular'] ?? '');
$ciudad  = trim($body['ciudad']  ?? '');
$barrio  = trim($body['barrio']  ?? '');

/* ─── Validación básica ────────────────────────────────────────────────── */
$errors = [];
if (strlen($nombre)  < 3)                                $errors[] = 'nombre';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL))          $errors[] = 'correo';
if (!preg_match('/^3\d{9}$/', $celular))                  $errors[] = 'celular';
if (strlen($ciudad)  < 2)                                $errors[] = 'ciudad';
if (strlen($barrio)  < 2)                                $errors[] = 'barrio';

if ($errors) {
    echo json_encode(['success'=>false,'message'=>'Campos inválidos: '.implode(', ',$errors)]);
    exit;
}

/* ─── Conexión BD ──────────────────────────────────────────────────────── */
try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR,
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']);
    exit;
}

/* ─── Crear tabla si no existe ─────────────────────────────────────────── */
$pdo->exec("
    CREATE TABLE IF NOT EXISTS registros (
        id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre    VARCHAR(150)  NOT NULL,
        correo    VARCHAR(150)  NOT NULL,
        celular   VARCHAR(15)   NOT NULL UNIQUE,
        ciudad    VARCHAR(100)  NOT NULL,
        barrio    VARCHAR(100)  NOT NULL,
        codigo    VARCHAR(20)   NOT NULL,
        creado_en DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

/* ─── Verificar celular duplicado ──────────────────────────────────────── */
$stmtCheck = $pdo->prepare('SELECT id FROM registros WHERE celular = ? LIMIT 1');
$stmtCheck->execute([$celular]);

if ($stmtCheck->fetch()) {
    echo json_encode(['success'=>false,'error'=>'celular_duplicado','message'=>'Este número ya fue registrado.']);
    exit;
}

/* ─── Generar código único ─────────────────────────────────────────────── */
function generarCodigo(PDO $pdo): string {
    do {
        $codigo = 'CF-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 7));
        $s = $pdo->prepare('SELECT id FROM registros WHERE codigo = ? LIMIT 1');
        $s->execute([$codigo]);
    } while ($s->fetch());
    return $codigo;
}

$codigo = generarCodigo($pdo);

/* ─── Insertar registro ────────────────────────────────────────────────── */
try {
    $stmt = $pdo->prepare('
        INSERT INTO registros (nombre, correo, celular, ciudad, barrio, codigo)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$nombre, $correo, $celular, $ciudad, $barrio, $codigo]);
} catch (PDOException $e) {
    // Doble control: posible race condition en UNIQUE
    if ($e->getCode() === '23000') {
        echo json_encode(['success'=>false,'error'=>'celular_duplicado','message'=>'Este número ya fue registrado.']);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Error al guardar el registro']);
    }
    exit;
}

echo json_encode(['success'=>true,'codigo'=>$codigo]);