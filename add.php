<?php
require_once __DIR__ . '/db.php';
/** @var PDO $pdo */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $priority = $_POST['priority'] ?? 'normal';
    $status = $_POST['status'] ?? 'open';

    if (!empty($subject)) {
        $stmt = $pdo->prepare("INSERT INTO tickets (subject, priority, status) VALUES (:subject, :priority, :status)");
        $stmt->execute([
            ':subject'  => $subject,
            ':priority' => $priority,
            ':status'   => $status,
        ]);
    }
}

header('Location: index.php');
exit;