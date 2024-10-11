<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "computer_lab_inventory";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  echo "Connected successfully";
}

// Reset auto increment
$conn->query("ALTER TABLE inventory AUTO_INCREMENT = 1");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO inventory (item_name, category, quantity) VALUES ('$item_name', '$category', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New item added successfully!');</script>";
    } else {
        
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_item'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE inventory SET item_name='$item_name', category='$category', quantity='$quantity' WHERE id=$item_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item updated successfully!'); window.location.href = '';</script>";
    } else {
        
    }
}

if (isset($_GET['remove_item'])) {
    $item_id = $_GET['remove_item'];
    $sql = "DELETE FROM inventory WHERE id=$item_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item removed successfully!'); window.location.href = 'inventory.php';</script>";
    } else {
        
    }
}

$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>

</head>
<body>

<h2>Inventory Management</h2>
<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            text-align: center;
            color: #0a4275;
            margin-bottom: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #dddddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
            color: #333;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Button styles */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
            margin: 0 10px;
        }

        .btn:hover {
            background: linear-gradient(135deg, #0056b3, #007bff);
            transform: translateY(-3px);
        }

        .edit-btn {
            background: #119F15;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
            margin: 0 10px;
        }

        .remove-btn {
            background: #D80B1E;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
            margin: 0 10px;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            margin: auto;
            position: relative;
        }

        .close {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #333;
            font-size: 20px;
            cursor: pointer;
        }

        /* Form input styles */
        input[type="text"], input[type="number"], select, textarea {
            width: 95%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 96%;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
<table>
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Date Added</th>
        <th>Actions</th>
    </tr>

    <?php
   
    $sql = "SELECT * FROM inventory";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["item_name"] . "</td>
                    <td>" . $row["category"] . "</td>
                    <td>" . $row["quantity"] . "</td>
                    <td>" . $row["date_added"] . "</td>
                    <td class='action-btns'>
                        <button class='edit-btn' onclick='openEditModal(" . $row["id"] . ", \"" . $row["item_name"] . "\", \"" . $row["category"] . "\", " . $row["quantity"] . ")'>Edit</button>
                        <button class='remove-btn' onclick='confirmRemove(" . $row["id"] . ")'>Remove</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No items found</td></tr>";
    }
    ?>
</table>


<div class="btn-container">
    <button class="btn" onclick="openModal('addModal')">Add Item</button>
</div>
<div id ="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addModal')">&times;</span>
        <h3>Add New Item</h3>
        <form method="post" action="">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <button type="submit" name="add_item" class="btn">Add Item</button>
        </form>
    </div>
</div>
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h3>Edit Item</h3>
        <form id="editForm" method="post" action="">
            <input type="hidden" name="item_id" id="edit_item_id">
            <input type="text" name="item_name" id="edit_item_name" placeholder="Item Name" required>
            <input type="text" name="category" id="edit_item_category" placeholder="Category" required>
            <input type="number" name="quantity" id="edit_item_quantity" placeholder="Quantity" required>
            <button type="submit" name="edit_item" class="btn">Save Changes</button>
        </form>
    </div>
</div>
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    function openEditModal(id, name, category, quantity) {
        openModal('editModal');
        document.getElementById('edit_item_id').value = id;
        document.getElementById('edit_item_name').value = name;
        document.getElementById('edit_item_category').value = category;
        document.getElementById('edit_item_quantity').value = quantity;
    }

    function confirmRemove(id) {
        if (confirm("Are you sure you want to remove this item?")) {
            window.location.href = "?remove_item=" + id;
        }
    }
</script>

</body>
</html>
<?php
$conn->close();
?>