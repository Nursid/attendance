<?php
/**
 * Database Optimization Script
 * Run this script once to create indexes for better performance
 */

// Include CodeIgniter bootstrap
require_once('index.php');

// Get CI instance
$CI =& get_instance();
$CI->load->model('Web_Model', 'web');

echo "Starting database optimization...\n";

try {
    $result = $CI->web->createOptimizationIndexes();
    if ($result) {
        echo "Database indexes created successfully!\n";
        echo "Your monthly report queries should now be much faster.\n";
    } else {
        echo "Failed to create some indexes.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Optimization complete.\n";
?> 