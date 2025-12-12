<?php
require_once 'config.php';

$conn = getDBConnection();

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM menu_items WHERE id = $id";
    $conn->query($delete_sql);
    header("Location: index.php");
    exit();
}

// Handle CREATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update_id'])) {
    // Get and sanitize inputs
    $item_name = $conn->real_escape_string(trim($_POST['item_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $spicy_level = $category == 'Drink' ? 0 : intval($_POST['spicy_level']);

    // Server-side validation
    $errors = [];
    
    if (empty($item_name)) {
        $errors[] = "Item name is required";
    }
    
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    if ($category != 'Drink' && ($spicy_level < 1 || $spicy_level > 5)) {
        $errors[] = "Spicy level must be between 1 and 5";
    }

    if (empty($errors)) {
        $insert_sql = "INSERT INTO menu_items (item_name, description, price, category, spicy_level) 
                      VALUES ('$item_name', '$description', $price, '$category', $spicy_level)";
        
        if ($conn->query($insert_sql)) {
            $_SESSION['success_message'] = "Item added successfully!";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors;
    }
}

// Handle UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = floatval($_POST['price']);

    // Server-side validation for update
    if ($price > 0) {
        $update_sql = "UPDATE menu_items SET description = '$description', price = $price WHERE id = $id";
        $conn->query($update_sql);
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['errors'] = ["Price must be greater than 0"];
        header("Location: index.php?edit=$id");
        exit();
    }
}

// READ - Fetch all items
function getAllMenuItems() {
    $conn = getDBConnection();
    $result = $conn->query("SELECT * FROM menu_items ORDER BY id DESC");
    return $result;
}

// Get single item for editing
function getMenuItem($id) {
    $conn = getDBConnection();
    $id = intval($id);
    $result = $conn->query("SELECT * FROM menu_items WHERE id = $id");
    return $result->fetch_assoc();
}
?>