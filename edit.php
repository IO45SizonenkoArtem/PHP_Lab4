<?php
require_once __DIR__ . '/db.php';
/** @var PDO $pdo */

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = :id");
$stmt->execute([':id' => $id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $priority = $_POST['priority'] ?? 'normal';
    $status = $_POST['status'] ?? 'open';

    $updateStmt = $pdo->prepare("UPDATE tickets SET subject = :subject, priority = :priority, status = :status WHERE id = :id");
    $updateStmt->execute([
        ':subject'  => $subject,
        ':priority' => $priority,
        ':status'   => $status,
        ':id'       => $id,
    ]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагування тікета #<?= htmlspecialchars($ticket['id']) ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f9; }
        .form-container { background: white; padding: 20px; border-radius: 8px; border: 1px solid #ccc; max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        a.cancel { display: inline-block; margin-left: 10px; color: #777; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Редагування тікета #<?= htmlspecialchars($ticket['id']) ?></h2>
    <form method="post" action="edit.php?id=<?= $ticket['id'] ?>">
        <div class="form-group">
            <label for="subject">Тема тікета:</label>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>" required>
        </div>
        <div class="form-group">
            <label for="priority">Пріоритет:</label>
            <select id="priority" name="priority" required>
                <option value="low" <?= $ticket['priority'] === 'low' ? 'selected' : '' ?>>Низький (low)</option>
                <option value="normal" <?= $ticket['priority'] === 'normal' ? 'selected' : '' ?>>Звичайний (normal)</option>
                <option value="urgent" <?= $ticket['priority'] === 'urgent' ? 'selected' : '' ?>>Терміновий (urgent)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Статус:</label>
            <select id="status" name="status" required>
                <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Відкритий (open)</option>
                <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Закритий (closed)</option>
            </select>
        </div>
        <button type="submit">Зберегти зміни</button>
        <a href="index.php" class="cancel">Скасувати</a>
    </form>
</div>

</body>
</html>