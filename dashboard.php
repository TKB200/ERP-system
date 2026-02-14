<?php
require_once '../../includes/db.php';
include '../../includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_finance'])) {
    $desc = $_POST['description'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO finance_logs (description, amount, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $desc, $amount, $type);
    $stmt->execute();
}

// Fetch stats
$income = $conn->query("SELECT SUM(amount) as total FROM finance_logs WHERE type='INCOME'")->fetch_assoc()['total'] ?? 0;
$expense = $conn->query("SELECT SUM(amount) as total FROM finance_logs WHERE type='EXPENSE'")->fetch_assoc()['total'] ?? 0;
$balance = $income - $expense;

// Fetch logs
$logs = $conn->query("SELECT * FROM finance_logs ORDER BY id DESC LIMIT 10");
?>

<div class="animate-fade">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="sap-section-title">Finance - General Ledger Dashboard</h1>
        <button class="sap-btn" onclick="document.getElementById('finModal').style.display='block'">
            <i class="fas fa-file-invoice-dollar"></i> New Posting
        </button>
    </div>

    <!-- KPI Row -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div
            style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: var(--tile-shadow); border-left: 5px solid #28a745;">
            <div style="font-size: 0.8rem; color: var(--sap-grey); font-weight: 600;">TOTAL INCOME</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #28a745;">$
                <?php echo number_format($income, 2); ?>
            </div>
        </div>
        <div
            style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: var(--tile-shadow); border-left: 5px solid #dc3545;">
            <div style="font-size: 0.8rem; color: var(--sap-grey); font-weight: 600;">TOTAL EXPENSE</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #dc3545;">$
                <?php echo number_format($expense, 2); ?>
            </div>
        </div>
        <div
            style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: var(--tile-shadow); border-left: 5px solid var(--sap-blue);">
            <div style="font-size: 0.8rem; color: var(--sap-grey); font-weight: 600;">NET BALANCE</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--sap-blue);">$
                <?php echo number_format($balance, 2); ?>
            </div>
        </div>
    </div>

    <div class="sap-table-card">
        <h3 style="margin-bottom:1rem; font-size: 1rem;">Recent Financial Postings</h3>
        <table>
            <thead>
                <tr>
                    <th>Doc ID</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $logs->fetch_assoc()): ?>
                    <tr>
                        <td>#DOC-
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['description']; ?>
                        </td>
                        <td>
                            <span
                                style="padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; background: <?php echo $row['type'] == 'INCOME' ? '#e8f5e9' : '#ffebee'; ?>; color: <?php echo $row['type'] == 'INCOME' ? '#2e7d32' : '#c62828'; ?>;">
                                <?php echo $row['type']; ?>
                            </span>
                        </td>
                        <td
                            style="font-weight: 600; color: <?php echo $row['type'] == 'INCOME' ? '#2e7d32' : '#c62828'; ?>;">
                            <?php echo $row['type'] == 'INCOME' ? '+' : '-'; ?> $
                            <?php echo number_format($row['amount'], 2); ?>
                        </td>
                        <td>
                            <?php echo $row['log_date']; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($logs->num_rows == 0): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--sap-grey);">No financial records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Posting Modal -->
<div id="finModal"
    style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div
        style="background:white; margin: 10% auto; padding: 2rem; width: 400px; border-radius: 0.5rem; box-shadow: var(--tile-hover-shadow);">
        <h2 style="margin-bottom: 1.5rem;">New Financial Posting</h2>
        <form method="POST">
            <div class="sap-form-group">
                <label class="sap-label">Description</label>
                <input type="text" name="description" class="sap-input" placeholder="e.g. Q3 Server Maintenance"
                    required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Amount ($)</label>
                <input type="number" step="0.01" name="amount" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Transaction Type</label>
                <select name="type" class="sap-input" style="height: auto;">
                    <option value="INCOME">Income / Revenue</option>
                    <option value="EXPENSE">Expense / Cost</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="sap-btn" style="background:#ccc; color: black;"
                    onclick="document.getElementById('finModal').style.display='none'">Cancel</button>
                <button type="submit" name="add_finance" class="sap-btn">Post Transaction</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>