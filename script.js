function toggleForm() {
    const form = document.getElementById('addForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        // Scroll to form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        form.style.display = 'none';
    }
}

function toggleSpicyLevel() {
    const category = document.getElementById('category').value;
    const spicyInput = document.getElementById('spicy_level');
    const spicyLabel = spicyInput.previousElementSibling;
    
    if (category === 'Drink') {
        spicyInput.disabled = true;
        spicyInput.value = 0;
        spicyLabel.style.color = '#666';
        spicyLabel.textContent = 'SPICY LEVEL (1-5) - Disabled for Drinks';
    } else {
        spicyInput.disabled = false;
        if (spicyInput.value == 0) {
            spicyInput.value = 1;
        }
        spicyLabel.style.color = '#00ffff';
        spicyLabel.textContent = 'SPICY LEVEL (1-5)';
    }
}

function validateForm() {
    let isValid = true;
    
    document.getElementById('error_name').textContent = '';
    document.getElementById('error_price').textContent = '';
    document.getElementById('error_spicy').textContent = '';

    const itemName = document.getElementById('item_name').value.trim();
    if (itemName === '') {
        document.getElementById('error_name').textContent = 'Item name is required';
        isValid = false;
    }

    const priceValue = document.getElementById('price').value;
    const price = parseFloat(priceValue);
    
    if (priceValue === '' || isNaN(price) || price <= 0) {
        document.getElementById('error_price').textContent = 'Price must be a number greater than 0';
        isValid = false;
    }

    const category = document.getElementById('category').value;
    const spicyLevel = parseInt(document.getElementById('spicy_level').value);
    
    if (category !== 'Drink') {
        if (isNaN(spicyLevel) || spicyLevel < 1 || spicyLevel > 5) {
            document.getElementById('error_spicy').textContent = 'Spicy level must be between 1 and 5';
            isValid = false;
        }
    }

    return isValid;
}

function validateEditForm() {
    const priceInputs = document.querySelectorAll('input[name="price"]');
    
    for (let priceInput of priceInputs) {
        const price = parseFloat(priceInput.value);
        
        if (isNaN(price) || price <= 0) {
            alert(' Price must be a number greater than 0');
            return false;
        }
    }
    
    return true;
}

function confirmDelete() {
    return confirm(' Are you sure you want to delete this item from the menu?');
}

function setupRealTimeValidation() {
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            const price = parseFloat(this.value);
            const errorSpan = document.getElementById('error_price');
            
            if (this.value && (isNaN(price) || price <= 0)) {
                errorSpan.textContent = 'Price must be a number greater than 0';
                this.style.boxShadow = '0 0 10px #ff0000';
            } else {
                errorSpan.textContent = '';
                this.style.boxShadow = '0 0 10px #00ffff';
            }
        });
    }
    
    const spicyInput = document.getElementById('spicy_level');
    if (spicyInput) {
        spicyInput.addEventListener('input', function() {
            const category = document.getElementById('category').value;
            const spicyLevel = parseInt(this.value);
            const errorSpan = document.getElementById('error_spicy');
            
            if (category !== 'Drink' && this.value) {
                if (isNaN(spicyLevel) || spicyLevel < 1 || spicyLevel > 5) {
                    errorSpan.textContent = 'Spicy level must be between 1 and 5';
                    this.style.boxShadow = '0 0 10px #ff0000';
                } else {
                    errorSpan.textContent = '';
                    this.style.boxShadow = '0 0 10px #00ffff';
                }
            }
        });
    }
    
    const itemNameInput = document.getElementById('item_name');
    if (itemNameInput) {
        itemNameInput.addEventListener('input', function() {
            const errorSpan = document.getElementById('error_name');
            if (this.value.trim() === '') {
                errorSpan.textContent = 'Item name is required';
                this.style.boxShadow = '0 0 10px #ff0000';
            } else {
                errorSpan.textContent = '';
                this.style.boxShadow = '0 0 10px #00ffff';
            }
        });
    }
}

function hideSuccessMessage() {
    const successMsg = document.getElementById('successMessage');
    if (successMsg && successMsg.style.display !== 'none') {
        setTimeout(() => {
            successMsg.style.opacity = '0';
            setTimeout(() => {
                successMsg.style.display = 'none';
                successMsg.style.opacity = '1';
            }, 500);
        }, 3000);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    console.log(' NeonBurger Menu System Loaded');
    
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        categorySelect.addEventListener('change', toggleSpicyLevel);
        toggleSpicyLevel();
    }
    
    setupRealTimeValidation();
    
    const menuForm = document.getElementById('menuForm');
    if (menuForm) {
        menuForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    hideSuccessMessage();
    
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirmDelete()) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    console.log('All systems operational!');
});