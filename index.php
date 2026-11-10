<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
/** @var PDO $pdo */

$filterPriority = $_GET['priority'] ?? '';
$filterStatus = $_GET['status'] ?? '';

if (!empty($filterPriority)) {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE priority = :priority ORDER BY id DESC");
    $stmt->execute([':priority' => $filterPriority]);
} elseif ($filterStatus === 'open') {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE status = 'open' ORDER BY id DESC");
    $stmt->execute();
} else {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY id DESC");
}

$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Практична 4 — Варіант 19 (HelpDesk PDO)</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f9; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.85em; color: white; }
        .badge-urgent { background-color: #e74c3c; }
        .badge-normal { background-color: #7f8c8d; }
        .badge-low { background-color: #95a5a6; }
        .badge-open { background-color: #2ecc71; }
        .badge-closed { background-color: #e67e22; }
        .filters { margin-bottom: 20px; background: #eef2f7; padding: 10px 15px; border-radius: 6px; }
        .filters a { margin-right: 15px; color: #2980b9; text-decoration: none; font-weight: bold; }
        .form-box { background: #fafafa; padding: 15px; border: 1px solid #eee; border-radius: 6px; margin-top: 25px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 3px; }
        .form-group input, .form-group select { width: 100%; padding: 7px; box-sizing: border-box; }
        button { padding: 8px 15px; background-color: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-edit { color: #2980b9; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #c0392b; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>Система тікетів (PDO / MySQL)</h1>

    <div class="filters">
        <strong>Фільтрація:</strong>
        <a href="index.php">Усі тікети</a>
        <a href="index.php?status=open">Тільки відкриті (openTickets)</a>
        <a href="index.php?priority=urgent">Пріоритет: Urgent</a>
        <a href="index.php?priority=normal">Пріоритет: Normal</a>
    </div>

    <!-- Таблиця виводу (SELECT) -->
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Пріоритет</th>
            <th>Статус</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($tickets)): ?>
            <tr><td colspan="5" style="text-align:center;">Тікетів не знайдено.</td></tr>
        <?php else: ?>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['id']) ?></td>
                    <td><?= htmlspecialchars($t['subject']) ?></td>
                    <td>
                            <span class="badge badge-<?= htmlspecialchars($t['priority']) ?>">
                                <?= htmlspecialchars($t['priority']) ?>
                            </span>
                    </td>
                    <td>
                            <span class="badge badge-<?= htmlspecialchars($t['status']) ?>">
                                <?= htmlspecialchars($t['status']) ?>
                            </span>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $t['id'] ?>" class="btn-edit">Редагувати</a>
                        <a href="delete.php?id=<?= $t['id'] ?>" class="btn-delete" onclick="return confirm('Ви дійсно бажаєте видалити цей тікет?');">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Форма додавання (INSERT) -->
    <div class="form-box">
        <h3>Додати новий тікет</h3>
        <form method="post" action="add.php">
            <div class="form-group">
                <label for="subject">Тема тікета:</label>
                <input type="text" id="subject" name="subject" required placeholder="Опишіть коротко проблему">
            </div>
            <div class="form-group">
                <label for="priority">Пріоритет:</label>
                <select id="priority" name="priority">
                    <option value="low">Низький (low)</option>
                    <option value="normal" selected>Звичайний (normal)</option>
                    <option value="urgent">Терміновий (urgent)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Статус:</label>
                <select id="status" name="status">
                    <option value="open" selected>Відкритий (open)</option>
                    <option value="closed">Закритий (closed)</option>
                </select>
            </div>
            <button type="submit">Додати тікет</button>
        </form>
    </div>
</div>

</body>
</html>