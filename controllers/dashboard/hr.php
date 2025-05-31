<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login controller if not logged in
    header('Location: ../auth/login.php');
    exit();
}

// Include any necessary models or database connections
require_once('../../includes/db_wrapper.php');
require_once('../../includes/permissions.php');

// Get user role information
$role_id = $_SESSION['role_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;
$username = $_SESSION['username'] ?? 'User';

// Define navigation items
$navItems = [
    'dashboard.php' => ['Dashboard', 'fa-solid fa-gauge-high'],
    'setup.php' => ['Setup', 'fa-solid fa-cog'],
    'entry.php' => ['Entry', 'fa-solid fa-edit'],
    'hr.php' => ['HR', 'fa-solid fa-users-gear'],
    'reports.php' => ['Reports', 'fa-solid fa-file-lines']
];

// Set current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Define HR items with updated icon classes for Font Awesome 6
$hr_items = [
    'employee_entry' => [
        'name' => 'Employee Management',
        'description' => 'Add, edit and manage employee information',
        'icon' => 'fa-solid fa-user-tie',
        'color' => '#14b8a6', // Teal
        'link' => '../../views/entry/employees/manage_employees.php',
        'highlight' => true
    ],
    'payroll' => [
        'name' => 'Payroll Processing',
        'description' => 'Process employee salaries and payments',
        'icon' => 'fa-solid fa-money-check-dollar',
        'color' => '#4f46e5', // Indigo
        'link' => '../../views/hr/payroll/index.php'
    ],
    'attendance' => [
        'name' => 'Attendance Tracking',
        'description' => 'Monitor employee attendance and time records',
        'icon' => 'fa-solid fa-clipboard-user',
        'color' => '#0369a1', // Blue
        'link' => '../../views/hr/attendance/index.php'
    ],
    'leave_management' => [
        'name' => 'Leave Management',
        'description' => 'Track and approve employee leave requests',
        'icon' => 'fa-solid fa-calendar-day',
        'color' => '#ca8a04', // Yellow
        'link' => '../../views/hr/leave/index.php'
    ],
    'performance' => [
        'name' => 'Performance Evaluation',
        'description' => 'Conduct and track employee performance reviews',
        'icon' => 'fa-solid fa-chart-line',
        'color' => '#16a34a', // Green
        'link' => '../../views/hr/performance/index.php'
    ]
];

// Include the HR view
include('../../views/dashboard/hr.php');
?>