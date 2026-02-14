<?php
require_once '../../includes/db.php';
include '../../includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $product_id = $_POST['product_id'];
    $qty = $_POST['quantity'];

    // Fetch product price
    $res = $conn->query("SELECT price, stock FROM products WHERE id = $product_id");
    if ($res && $res->num_rows > 0) {
        $prod = $res->fetch_assoc();
        $total = $prod['price'] * $qty;

        if ($prod['stock'] >= $qty) {
            // Start Transaction
            $conn->begin_transaction();
            try {
                // Record Sale
                $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, total_price) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $product_id, $qty, $total);
                $stmt->execute();

                // Update Stock
                $conn->query("UPDATE products SET stock = stock - $qty WHERE id = $product_id");

                // Record Finance Log (Income)
                $desc = "Sales Order for Product #$product_id";
                $conn->query("INSERT INTO finance_logs (description, amount, type) VALUES ('$desc', $total, 'INCOME')");

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
            }
        }
    }
}

// Fetch products for dropdown
$products_list = $conn->query("SELECT id, name, price, stock FROM products");

// Fetch sales
$sales = $conn->query("SELECT s.*, p.name as product_name FROM sales s JOIN products p ON s.product_id = p.id ORDER BY s.id DESC");
?>

<div class="animate-fade">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="sap-section-title">Sales & Distribution - Order Processing</h1>
        <button class="sap-btn" onclick="document.getElementById('saleModal').style.display='block'">
            <i class="fas fa-cart-plus"></i> Create Sales Order
        </button>
    </div>

    <div class="sap-table-card">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Revenue</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sales->fetch_assoc()): ?>
                    <tr>
                        <td>#SO-
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['product_name']; ?>
                        </td>
                        <td>
                            <?php echo $row['quantity']; ?>
                        </td>
                        <td style="font-weight: 600; color: #2e7d32;">$
                            <?php echo number_format($row['total_price'], 2); ?>
                        </td>
                        <td>
                            <?php echo $row['sale_date']; ?>
                        </td>
                        <td><span style="color: #008cff; font-weight: 600;">Completed</span></td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($sales->num_rows == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--sap-grey);">No sales orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Sale Modal -->
<div id="saleModal"
    style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div
        style="background:white; margin: 10% auto; padding: 2rem; width: 400px; border-radius: 0.5rem; box-shadow: var(--tile-hover-shadow);">
        <h2 style="margin-bottom: 1.5rem;">Create Sales Order</h2>
        <form method="POST">
            <div class="sap-form-group">
                <label class="sap-label">Select Product (Material)</label>
                <select name="product_id" class="sap-input" style="height: auto;">
                    <?php while ($p = $products_list->fetch_assoc()): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo $p['name']; ?> ($
                            <?php echo $p['price']; ?>) - Stock:
                            <?php echo $p['stock']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Order Quantity</label>
                <input type="number" name="quantity" class="sap-input" value="1" min="1" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="sap-btn" style="background:#ccc; color: black;"
                    onclick="document.getElementById('saleModal').style.display='none'">Cancel</button>
                <button type="submit" name="place_order" class="sap-btn">Submit Order</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>