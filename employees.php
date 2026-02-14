<?php
require_once '../../includes/db.php';
include '../../includes/header.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $dept = $_POST['department'];
    $salary = $_POST['salary'];
    $joining = $_POST['joining_date'];

    $stmt = $conn->prepare("INSERT INTO employees (full_name, email, department, salary, joining_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $email, $dept, $salary, $joining);
    $stmt->execute();
}

// Fetch employees
$employees = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>

<div class="animate-fade">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="sap-section-title">Human Capital Management - Employee Central</h1>
        <button class="sap-btn" onclick="document.getElementById('empModal').style.display='block'">
            <i class="fas fa-user-plus"></i> Hire Employee
        </button>
    </div>

    <div class="sap-table-card">
        <table>
            <thead>
                <tr>
                    <th>Emp ID</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Salary</th>
                    <th>Joining Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $employees->fetch_assoc()): ?>
                    <tr>
                        <td>#EMP-
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $row['full_name']; ?>
                        </td>
                        <td>
                            <?php echo $row['department']; ?>
                        </td>
                        <td>$
                            <?php echo number_format($row['salary'], 2); ?>
                        </td>
                        <td>
                            <?php echo $row['joining_date']; ?>
                        </td>
                        <td>
                            <button style="border:none; background:none; color: var(--sap-blue); cursor:pointer;"><i
                                    class="fas fa-edit"></i></button>
                            <button
                                style="border:none; background:none; color: #d93025; cursor:pointer; margin-left: 10px;"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($employees->num_rows == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--sap-grey);">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="empModal"
    style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div
        style="background:white; margin: 10% auto; padding: 2rem; width: 450px; border-radius: 0.5rem; box-shadow: var(--tile-hover-shadow);">
        <h2 style="margin-bottom: 1.5rem;">Hire New Employee</h2>
        <form method="POST">
            <div class="sap-form-group">
                <label class="sap-label">Full Name</label>
                <input type="text" name="full_name" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Email Address</label>
                <input type="email" name="email" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Department</label>
                <select name="department" class="sap-input" style="height: auto;">
                    <option value="Finance">Finance</option>
                    <option value="Sales">Sales</option>
                    <option value="IT">IT</option>
                    <option value="Operations">Operations</option>
                    <option value="Human Resources">Human Resources</option>
                </select>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Annual Salary ($)</label>
                <input type="number" step="0.01" name="salary" class="sap-input" required>
            </div>
            <div class="sap-form-group">
                <label class="sap-label">Joining Date</label>
                <input type="date" name="joining_date" class="sap-input" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="sap-btn" style="background:#ccc; color: black;"
                    onclick="document.getElementById('empModal').style.display='none'">Cancel</button>
                <button type="submit" name="add_employee" class="sap-btn">Save Employee</button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>