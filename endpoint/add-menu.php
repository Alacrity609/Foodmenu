<?php
include('../conn/conn.php');

$menuName = $_POST['menu_name'];
$price = $_POST['price'];
$description = $_POST['description'];
$menuImageName = $_FILES['image']['name'];
$menuImageTmpName = $_FILES['image']['tmp_name'];

$target_dir = "../images/";
$target_file = $target_dir . basename($menuImageName);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a valid image
$check = getimagesize($menuImageTmpName);
if ($check === false) {
    $uploadOk = 0;
    echo "
    <script>
        alert('File is not a valid image.');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
    exit();
}

// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    echo "
    <script>
        alert('File already exists.');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
    exit();
}

// Check file size
if ($_FILES["image"]["size"] > 500000) {
    $uploadOk = 0;
    echo "
    <script>
        alert('File size exceeds the 500KB limit.');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
    exit();
}

// Allow only certain image formats
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

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "
    <script>
        alert('Sorry, your file was not uploaded.');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
    exit();
} else {
    if (move_uploaded_file($menuImageTmpName, $target_file)) {
        try {
            $menuImage = $menuImageName;

            $stmt = $conn->prepare("INSERT INTO `tbl_menu` (`tbl_menu_id`, `image`, `menu_name`, `price`, `description`) 
                                    VALUES (NULL, :menuImage, :menuName, :price, :description)");
            $stmt->bindParam(':menuImage', $menuImage);
            $stmt->bindParam(':menuName', $menuName);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            echo "
            <script>
                alert('Menu added successfully!');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
            exit();
        } catch (Exception $e) {
            echo "
            <script>
                alert('Error adding menu: " . $e->getMessage() . "');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
            exit();
        }
    } else {
        echo "
        <script>
            alert('Sorry, there was an error uploading your file.');
            window.location.href = 'http://localhost:8080/food-menu/admin.php';
        </script>";
        exit();
        }
}
?>
