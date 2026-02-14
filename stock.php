<?php
require_once '../../includes/db.php';
include '../../includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $sku = $_POST['sku'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO products (name, sku, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $sku, $price, $stock);
    $stmt->execute();
}

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<div class="animate-fade">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="sap-section-title">Materials Management - Stock Overview</h1>
        <button class="sap-btn" onclick="document.getElementById('addModal').style.display='block'">
            <i class="fas fa-plus"></i> Create Material
        </button>
    </div>

    <div class="sap-table-card">
        <table>
            <thead>
                <tr>
                    <th>Material ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td>#MAT-
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['sku']; ?>
                        </td>
                        <td>$
                            <?php echo number_format($row['price'], 2); ?>
                        </td>
                        <td>
                            <?php echo $row['stock']; ?>
                        </td>
                        <td>
                            <?php if ($row['stock'] > 10): ?>
                                <span style="color: green; font-weight: 600;">In Stock</span>
                            <?php else: ?>
                                <span style="color: red; font-weight: 600;">Low Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($products->num_rows == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--sap-grey);">No materials found. Click
                            "Create Material" to add one.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Simple Modal for Adding Product -->
<div id="addModal"
    style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div
        style="background:white; margin: 10% auto; padding: 2rem; width: 400px; border-radius: 0.5rem; box-shadow: var(--tile-hover-shadow);">
        <h2 style="margin-bottom: 1.5rem;">Add New Material</h2>
        <form method="POST">
            <div class="sap-form-group">
                <label class="sap-label">Material Name</label>
                <input type="text" name="name" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">SKU</label>
                <input type="text" name="sku" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Price ($)</label>
                <input type="number" step="0.01" name="price" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Initial Stock</label>
                <input type="number" name="stock" class="sap-input" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="sap-btn" style="background:#ccc; color: black;"
                    onclick="document.getElementById('addModal').style.display='none'">Cancel</button>
                <button type="submit" name="add_product" class="sap-btn">Save Material</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>