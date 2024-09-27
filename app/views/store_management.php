<?php
// Store Management Page for Admin
// Check if the user is admin
if (!isAdmin()) {
    header("Location: login.php");
    exit();
}

// Load the necessary models
require_once '../app/models/Store.php';
require_once '../app/models/User.php';

$storeModel = new Store();
$userModel = new User();

// Fetch all stores
$stores = $storeModel->getAllStores();

// Check for form submission to update store status or commission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['store_id'])) {
        $storeId = $_POST['store_id'];
        $status = $_POST['status'];
        $commission = $_POST['commission'];

        // Update store status and commission
        $storeModel->updateStoreStatus($storeId, $status, $commission);
        header("Location: store_management.php?success=1");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management</title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Store Management</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Store updated successfully!</div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Commission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores as $store): ?>
                    <tr>
                        <td><?= htmlspecialchars($store['name']); ?></td>
                        <td><?= htmlspecialchars($userModel->getUserById($store['owner_id'])['username']); ?></td>
                        <td>
                            <form action="store_management.php" method="post">
                                <input type="hidden" name="store_id" value="<?= $store['id']; ?>">
                                <select name="status">
                                    <option value="active" <?= $store['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?= $store['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                        </td>
                        <td>
                                <input type="number" name="commission" value="<?= htmlspecialchars($store['commission']); ?>" min="0" max="100"> %
                        </td>
                        <td>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
