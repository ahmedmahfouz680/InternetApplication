<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'simple_crud';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

$id = 0;
$name = '';
$email = '';
$isEditing = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($name) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $deleteId = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$editId]);
    $user = $stmt->fetch();

    if ($user) {
        $id = $user['id'];
        $name = $user['name'];
        $email = $user['email'];
        $isEditing = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $updateId = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($updateId > 0 && !empty($name) && !empty($email)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $updateId]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternetApplication</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 40px 16px;
            line-height: 1.5;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        h2 {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #0f172a;
        }

        .field {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #2563eb;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        button, a.btn-link {
            cursor: pointer;
            border: none;
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        .btn-edit:hover {
            background: #dbeafe;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .btn-delete:hover {
            background: #fee2e2;
        }

        .table-wrap {
            padding: 0;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        td {
            color: #334155;
        }

        .col-id {
            width: 48px;
            color: #94a3b8;
        }

        .col-actions {
            width: 140px;
            text-align: right;
        }

        .empty-state {
            text-align: center;
            color: #94a3b8;
            padding: 32px 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2><?= $isEditing ? 'Edit User' : 'Add User'; ?></h2>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="action" value="<?= $isEditing ? 'update' : 'save'; ?>">
            <?php if ($isEditing): ?>
                <input type="hidden" name="id" value="<?= (int)$id; ?>">
            <?php endif; ?>

            <div class="field">
                <label for="userName">Name</label>
                <input type="text" id="userName" name="name" required placeholder="e.g. John Doe" value="<?= htmlspecialchars($name); ?>">
            </div>

            <div class="field">
                <label for="userEmail">Email</label>
                <input type="email" id="userEmail" name="email" required placeholder="john@example.com" value="<?= htmlspecialchars($email); ?>">
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary"><?= $isEditing ? 'Update' : 'Save'; ?></button>
                <?php if ($isEditing): ?>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="btn-link btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="col-id">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="empty-state">No users registered yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $index => $row): ?>
                        <tr>
                            <td class="col-id"><?= $index + 1; ?></td>
                            <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td class="col-actions">
                                <a href="?action=edit&id=<?= (int)$row['id']; ?>" class="btn-sm btn-edit">Edit</a>
                                <a href="?action=delete&id=<?= (int)$row['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>