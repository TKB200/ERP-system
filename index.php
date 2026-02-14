<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="animate-fade">
    <h1 class="sap-section-title">Home</h1>

    <div class="sap-grid">
        <!-- Finance Tile -->
        <a href="/SAP/modules/finance/dashboard.php" class="sap-tile">
            <div>
                <div class="sap-tile-header">Finance</div>
                <div class="sap-tile-subheader">General Ledger & Accounting</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-chart-line"></i></div>
            <div class="sap-tile-footer">12 New Entries</div>
        </a>

        <!-- Sales Tile -->
        <a href="/SAP/modules/sales/orders.php" class="sap-tile">
            <div>
                <div class="sap-tile-header">Sales & Distribution</div>
                <div class="sap-tile-subheader">Order Fulfilment</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="sap-tile-footer">8 Pending Orders</div>
        </a>

        <!-- Material Management Tile -->
        <a href="/SAP/modules/inventory/stock.php" class="sap-tile">
            <div>
                <div class="sap-tile-header">Materials Management</div>
                <div class="sap-tile-subheader">Inventory & Procurement</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-boxes-stacked"></i></div>
            <div class="sap-tile-footer">2 Low Stock Alerts</div>
        </a>

        <!-- Human Capital Management Tile -->
        <a href="/SAP/modules/hr/employees.php" class="sap-tile">
            <div>
                <div class="sap-tile-header">Human Capital Management</div>
                <div class="sap-tile-subheader">Employee Central</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-users"></i></div>
            <div class="sap-tile-footer">Admin Panel</div>
        </a>

        <!-- Analytics Tile -->
        <a href="#" class="sap-tile">
            <div>
                <div class="sap-tile-header">SAP Analytics Cloud</div>
                <div class="sap-tile-subheader">Predictive Insights</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-pie-chart"></i></div>
            <div class="sap-tile-footer">Live Data</div>
        </a>

        <!-- Settings Tile -->
        <a href="#" class="sap-tile">
            <div>
                <div class="sap-tile-header">System Configuration</div>
                <div class="sap-tile-subheader">Maintain System Settings</div>
            </div>
            <div class="sap-tile-icon"><i class="fas fa-cogs"></i></div>
            <div class="sap-tile-footer">Up to date</div>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>