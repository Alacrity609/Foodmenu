<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menuID = $_POST['tbl_menu_id'];
    $updateMenuName = $_POST['menu_name'];
    $updatePrice = $_POST['price'];
    $updateDescription = $_POST['description'];
    $uploadOk = 1;

    // Check if a new image file is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $targetDir = "../images/";
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate the image file
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
            echo "
            <script>
                alert('The file is not a valid image.');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
            exit();
        }

        // Restrict file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            $uploadOk = 0;
            echo "
            <script>
                alert('Only JPG, JPEG, PNG, and GIF formats are allowed.');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
            exit();
        }

        // Attempt to upload the file
        if ($uploadOk == 1 && move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Update menu with the new image
            $stmt = $conn->prepare("UPDATE tbl_menu SET menu_name = ?, price = ?, description = ?, image = ? WHERE tbl_menu_id = ?");
            $stmt->execute([$updateMenuName, $updatePrice, $updateDescription, $imageName, $menuID]);
        } else {
            echo "
            <script>
                alert('Error uploading the image.');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
            exit();
        }
    } else {
        // Update menu without changing the image
        $stmt = $conn->prepare("UPDATE tbl_menu SET menu_name = ?, price = ?, description = ? WHERE tbl_menu_id = ?");
        $stmt->execute([$updateMenuName, $updatePrice, $updateDescription, $menuID]);
    }

    // Redirect back to the admin page with a success message
    echo "
    <script>
        alert('Menu updated successfully!');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
    exit();
}
?>
