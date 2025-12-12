<?php
session_start();
require_once 'crud.php';

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']);

$menuItems = getAllMenuItems();

$editingItem = null;
if (isset($_GET['edit'])) {
    $editingItem = getMenuItem($_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeonBurger - Digital Menu Management</title>
    <link rel="stylesheet" href="style_01.css">
</head>
<body>
    <div class="header">
        <h1>NEONBURGER</h1>
        <p>DIGITAL MENU MANAGEMENT SYSTEM</p>
    </div>

    <?php if ($success_message): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <div class="add-btn-container">
        <button class="launch-btn" onclick="toggleForm()">+ LAUNCH NEW ITEM</button>
    </div>

    <div class="form-container" id="addForm" style="display: none;">
        <h2>ADD NEW ITEM</h2>
        <form method="POST" action="crud.php" id="menuForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="item_name">ITEM NAME *</label>
                <input type="text" name="item_name" id="item_name" required>
                <span class="error" id="error_name"></span>
            </div>

            <div class="form-group">
                <label for="description">DESCRIPTION</label>
                <textarea name="description" id="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="price">PRICE ($) *</label>
                <input type="text" name="price" id="price" placeholder="0.00" required>
                <span class="error" id="error_price"></span>
            </div>

            <div class="form-group">
                <label for="category">CATEGORY</label>
                <select name="category" id="category">
                    <option value="Burger">Burger</option>
                    <option value="Drink">Drink</option>
                    <option value="Side">Side</option>
                </select>
            </div>

            <div class="form-group">
                <label for="spicy_level">SPICY LEVEL (1-5)</label>
                <input type="number" name="spicy_level" id="spicy_level" min="1" max="5" value="1">
                <span class="error" id="error_spicy"></span>
            </div>

            <button type="submit" class="submit-btn">ADD TO MENU</button>
        </form>
    </div>

    <div class="menu-grid">
        <?php while ($item = $menuItems->fetch_assoc()): ?>
            <div class="menu-item">
                <?php if ($editingItem && $editingItem['id'] == $item['id']): ?>
                    <h3 class="item-name"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    
                    <form method="POST" action="crud.php" onsubmit="return validateEditForm()">
                        <input type="hidden" name="update_id" value="<?php echo $item['id']; ?>">
                        
                        <div class="form-group">
                            <label>DESCRIPTION</label>
                            <textarea name="description" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>

                        <!-- Price -->
                        <div class="form-group">
                            <label>PRICE ($) *</label>
                            <input type="text" name="price" value="<?php echo $item['price']; ?>" required>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn-save">💾 SAVE</button>
                            <a href="index.php" class="btn-cancel">✖ CANCEL</a>
                        </div>
                    </form>
                <?php else: ?>
                    
                    <div class="category-badge category-<?php echo strtolower($item['category']); ?>">
                        <?php echo strtoupper($item['category']); ?>
                    </div>

                    <h3 class="item-name">
                        <?php echo htmlspecialchars($item['item_name']); ?>
                        <?php 
                        if ($item['spicy_level'] >= 4): 
                        ?>
                            🔥
                        <?php endif; ?>
                    </h3>

                    <p class="item-description">
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>

                    <div class="item-details">
                        <div class="price-tag">
                            <span>$<?php echo number_format($item['price'], 2); ?></span>
                        </div>

                        <?php if ($item['category'] != 'Drink'): ?>
                            <div class="spicy-level">
                                SPICY: <?php echo $item['spicy_level']; ?>/5
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="action-buttons">
                        <a href="?edit=<?php echo $item['id']; ?>" class="btn-edit">✏ EDIT</a>
                        <a href="crud.php?delete=<?php echo $item['id']; ?>" 
                           class="btn-delete">
                            🗑 DELETE
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="footer">
        NEONBURGER © 2077 | CYBERPUNK MENU SYSTEM v1.0
    </div>

    <script src="script.js"></script>
</body>
</html>