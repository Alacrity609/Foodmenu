<?php
include('../conn/conn.php');

if (isset($_GET['menu'])) {
    $menuID = $_GET['menu'];

    try {
        // Use parameterized query to prevent SQL injection
        $query = "DELETE FROM `tbl_menu` WHERE tbl_menu_id = :menuID";
        $statement = $conn->prepare($query);
        $statement->bindParam(':menuID', $menuID, PDO::PARAM_INT);

        // Execute the query and check the result
        if ($statement->execute()) {
            // Redirect with a success message
            echo "
            <script>
                alert('Menu item deleted successfully!');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
        } else {
            // Redirect with a failure message
            echo "
            <script>
                alert('Failed to delete the menu item. Please try again.');
                window.location.href = 'http://localhost:8080/food-menu/admin.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Log the error and provide feedback
        error_log($e->getMessage(), 3, "../error_logs.txt"); // Logs error to a file
        echo "
        <script>
            alert('An error occurred: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = 'http://localhost:8080/food-menu/admin.php';
        </script>";
    }
} else {
    // Handle case where menu ID is not provided
    echo "
    <script>
        alert('Invalid request: Menu ID is missing.');
        window.location.href = 'http://localhost:8080/food-menu/admin.php';
    </script>";
}
?>
