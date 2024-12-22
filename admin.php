<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu App</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background-image: url('https://img.freepik.com/premium-psd/burger-restaurant-plate-fast-food-doodle-background_23-2148421443.jpg?w=1060');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 93.6vh;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .manage-menu-container {
            display: flex;
            flex-direction: column;
            width: 1200px;
            height: 720px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.5);

        }

        .manage-menu-container > h2 {
            color: rgb(255, 255, 255);
            text-shadow: 2px 2px rgb(100, 100, 100);
        }

        table {
            color: rgb(255, 255, 255) !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar-header">
    <a class="navbar-brand ml-3" href="#">Food Menu</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./index.php">Home</a>
            </li>
        </ul>
        <a class="nav-link ml-auto" href="./admin.php" style="color: rgb(255, 255, 255);">Admin Area</a>
         </div>
</nav>
    
    
    <div class="main">

        <div class="manage-menu-container">
            <h2>Manage Menu Area</h2>

            <div class="menu-container">

                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addMenuModal">Add Menu</button>

                <!-- Add Menu Modal -->
                <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenu" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addMenu">Add Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="endpoint/add-menu.php" method="POST" class="add-form" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="image">Menu Image:</label>
                                        <input type="file" class="form-control-file" id="image" name="image" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="menuName">Menu Name:</label>
                                            <input type="text" class="form-control" id="menuName" name="menu_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price:</label>
                                        <input type="number" class="form-control" id="price" name="price"  required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="8"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-dark">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Menu Modal -->
                <div class="modal fade" id="updateMenuModal" tabindex="-1" aria-labelledby="updateMenu" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateMenu">Update Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="endpoint/update-menu.php" method="POST" class="add-form" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control-file" id="updateMenuID" name="tbl_menu_id" required>

                                    <div class="form-group">
                                        <label for="updateImage">Menu Image:</label>
                                        <input type="file" class="form-control-file" id="updateImage" name="image">
                                    </div>
                                    <div class="form-group">
                                        <label for="updateMenuName">Menu Name:</label>
                                            <input type="text" class="form-control" id="updateMenuName" name="menu_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="updatePrice">Price:</label>
                                        <input type="number" class="form-control" id="updatePrice" name="price"  required>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateDescription">Description:</label>
                                        <textarea class="form-control" name="description" id="updateDescription" cols="30" rows="8"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-dark">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <table class="table table mt-3">
                    <thead>
                        <tr>
                            <th>Menu ID</th>
                            <th>Image</th>
                            <th>Menu Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                    
            
                        <?php 
                        
                            include('conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM `tbl_menu`");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach($result as $row) {
                                $menuID = $row['tbl_menu_id'];
                                $image = $row['image'];
                                $menuName = $row['menu_name'];
                                $price = $row['price'];
                                $description = $row['description'];
                                ?>

                                <tr>
                                    <th id="menuID-<?= $menuID ?>"><?= $menuID ?></th>
                                    <td id="image-<?= $menuID ?>"><img src="images/<?php echo $image ?>" class="img-fluid" style="height: 80px; width:120px;" alt=""></td>
                                    <td id="menuName-<?= $menuID ?>"><?= $menuName ?></td>
                                    <td id="price-<?= $menuID ?>"><?= $price ?></td>
                                    <td id="description-<?= $menuID ?>"><?= $description ?></td>
                                    <td>
                                        <button type="submit" class="btn btn-secondary" onclick="updateMenu('<?= $menuID ?>')"><i class="fa-solid fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" onclick="deleteMenu('<?= $menuID ?>')"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>

                                <?php
                            }
                        ?>
                    
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <!-- Make sure to include the full version of jQuery -->
   <!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script>
    function updateMenu(id) {
        $("#updateMenuModal").modal("show");

        let updateMenuID = $("#menuID-" + id).text();
        let updateMenuName = $("#menuName-" + id).text();
        let updateImage = $("#image-" + id).find('img').attr('src'); 
        let updatePrice = $("#price-" + id).text();
        let updateDescription = $("#description-" + id).text();

        $("#updateMenuID").val(updateMenuID);
        $("#updateMenuName").val(updateMenuName);
        $("#updateImage").find('img').attr('src', updateImage); 
        $("#updatePrice").val(updatePrice);
        $("#updateDescription").val(updateDescription);
    }

    function deleteMenu(id){
        if(confirm("Do you confirm to delete this menu?")){
            window.location="endpoint/delete-menu.php?menu="+id;
        }
    }
</script>

</body>
</html>
