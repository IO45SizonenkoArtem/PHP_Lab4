<?php
require_once __DIR__ . '/db.php';
/** @var PDO $pdo */

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header('Location: index.php');
exit;