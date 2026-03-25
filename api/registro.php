<?php
/* api/registro.php – Endpoint de registro Café Forestal */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')    { echo json_encode(['success'=>false,'message'=>'Método no permitido']); exit; }

/* ─── Entrada ── */
$body = json_decode(file_get_contents('php://input'), true);
if (!$body) { echo json_encode(['success'=>false,'message'=>'Datos inválidos']); exit; }

$nombre  = trim($body['nombre']  ?? '');
$correo  = trim($body['correo']  ?? '');
$celular = trim($body['celular'] ?? '');
$ciudad  = trim($body['ciudad']  ?? '');
$barrio  = trim($body['barrio']  ?? '');
$lotteryId = 3;
$status = 1;
$message = 0;

/* ─── Validación ── */
$errors = [];
if (strlen($nombre)  < 3)                               $errors[] = 'nombre';
if (!filter_var($correo, FILTER_VALIDATE_EMAIL))         $errors[] = 'correo';
if (!preg_match('/^3\d{9}$/', $celular))                 $errors[] = 'celular';
if (strlen($ciudad)  < 2)                               $errors[] = 'ciudad';
if (strlen($barrio)  < 2)                               $errors[] = 'barrio';

if ($errors) {
    echo json_encode(['success'=>false,'message'=>'Campos inválidos: '.implode(', ',$errors)]);
    exit;
}

/* ─── Conexión ── */
// Pon aquí tu usuario y contraseña reales
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=forestal_registro;charset=utf8mb4',
        'cortesias',   // <- tu usuario
        'aZvcw0Cn47Y6U',    // <- tu contraseña
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']);
    exit;
}

try {
    $pdo2 = new PDO(
        'mysql:host=localhost;dbname=comercial;charset=utf8mb4',
        'cortesias',   // <- tu usuario
        'aZvcw0Cn47Y6U',    // <- tu contraseña
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Error de conexión a la base de datos']);
    exit;
}

/* ─── Verificar celular duplicado ── */
$stmtCheck = $pdo->prepare('SELECT id FROM registros WHERE celular = ? LIMIT 1');
$stmtCheck->execute([$celular]);
if ($stmtCheck->fetch()) {
    echo json_encode(['success'=>false,'error'=>'celular_duplicado','message'=>'Este número ya fue registrado.']);
    exit;
}

/* ─── Generar código único ── */
do {
    $codigo = 'CF-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 7));
    $chk = $pdo->prepare('SELECT id FROM registros WHERE codigo = ? LIMIT 1');
    $chk->execute([$codigo]);
} while ($chk->fetch());

/* ─── Insertar ── */
try {
    $stmt = $pdo->prepare('
        INSERT INTO registros (nombre, correo, celular, ciudad, barrio, codigo)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$nombre, $correo, $celular, $ciudad, $barrio, $codigo]);
    
    $stmt2 = $pdo2->prepare('
    INSERT INTO LotteryParticipations (date, code, createdAt, updatedAt, lotteryId, subscriberId, status, message)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt2->execute([date('Y-m-d H:i:s'), $codigo, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $lotteryId, $celular, $status, $message]);

    echo json_encode(['success'=>true,'codigo'=>$codigo]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo json_encode(['success'=>false,'error'=>'celular_duplicado','message'=>'Este número ya fue registrado.']);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Error al guardar el registro']);
    }
}