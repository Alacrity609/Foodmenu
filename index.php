<?php
// Start session at the beginning of the file
session_start();

// Include the database connection
include('conn/conn.php');

// Handle registration logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $role = htmlspecialchars($_POST['role']); // User or Admin

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='index.php';</script>";
        exit();
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "<script>alert('Username already exists!'); window.location.href='index.php';</script>";
        exit();
    }

    // Check if trying to create another admin account
    if ($role == 'admin') {
        $stmt = $conn->prepare("SELECT id FROM users WHERE role = 'admin'");
        $stmt->execute();
        if ($stmt->fetch()) {
            echo "<script>alert('There can only be one admin!'); window.location.href='index.php';</script>";
            exit();
        }
    }

    // Hash password and insert new user into the database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $role]);

    echo "<script>alert('Registration successful! You can now log in.'); window.location.href='index.php';</script>";
    exit();
}

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: admin.php");  // Admin dashboard
        } else {
            header("Location: menu.php");   // Regular user menu page
        }
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password!'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Menu App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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

        #navbar-header {
            position: fixed;
            top: 0;
            width: 100%;
        }

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .home-container {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 1100px;
            color: rgb(255, 255, 255);
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            padding: 40px;
            text-shadow: 2px 2px rgb(100, 100, 100);
            background-color: rgba(0, 0, 0, 0.4);
            margin-top: 50px;
        }

        .home-container > img {
            width: 350px;
        }

        .home-container > h1 {
            font-size: 90px;
            font-weight: bold;
        }

        .home-container > p {
            font-size: 25px;
        }

        .contact-us-container {
            width: 1100px;
            color: rgb(255, 255, 255);
            text-shadow: 2px 2px rgb(100, 100, 100);
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            margin-top: 64px;
        }

        .food-menu-container {
            display: flex;
            flex-direction: column;
            width: 1300px;
            height: 920px;
            margin-top: 100px;
            color: rgb(255, 255, 255);
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .menus {
            display: flex;
            flex-wrap: wrap;
            overflow: auto;
        }

        .card {
            margin: 19px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar-header">
    <a class="navbar-brand ml-3" href="#">BURGER HUB MENU</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#foodMenu">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contactUs">Contact Us</a>
            </li>
        </ul>
        <a class="nav-link ml-auto" href="#" data-toggle="modal" data-target="#loginModal" style="color: rgb(255, 255, 255);">Admin Login</a>
        <a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal" style="color: rgb(255, 255, 255);">Register</a>
    </div>
</nav>

<!-- Home Section -->
<section id="home">
    <div class="home-container row">
        <img src="https://static.wixstatic.com/media/9a1d3f_1d3fc69803b646bfb2d460a528cbb6c4~mv2.png/v1/crop/x_0,y_150,w_500,h_200/fill/w_500,h_200,al_c,q_85,enc_avif,quality_auto/Burger%20Hub%20Logo%20_%206231%20S%2027th%20St%20Greenfield%2C%20WI%2053221%20.png" alt="">
        <h1>WELCOME BURGER HUB MENU</h1>
        <p>Welcome to Burger Hub! We’re a burger restaurant in the heart of the community that serves up delicious, mouth-watering burgers. Our burgers are made with 100% HALAL ingredients, so you can rest assured that you’re getting the highest quality products.</p>
    </div>
</section>

<!-- Food Menu Section -->
<section id="foodMenu">
    <div class="food-menu-container">
        <h1 class="text-center">Our Food Menu</h1>
        <div class="menus">
            <!-- Add PHP Code here for fetching menu items -->
            <?php
            $stmt = $conn->prepare("SELECT * FROM `tbl_menu`");
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $image = $row['image'];
                $menuName = $row['menu_name'];
                $price = $row['price'];
                $description = $row['description'];
            ?>
                <div class="card" style="width: 15rem; background-color:rgba(0,0,0,0.5);">
                    <img src="images/<?= $image ?>" class="card-img-top" alt="..." style="height:150px;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $menuName ?></h5>
                        <p class="card-text">Description: <?= $description ?></p>
                    </div>
                    <div class="card-footer">
                        Price: <?= $price ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Contact Us Section -->
<section id="contactUs">
    <div class="contact-us-container row">
        <div class="col-6">
            <h1>Contact Us</h1>
            <p>Have questions, feedback, or just want to say hello? We'd love to hear from you!</p>
            <h6><span><i class="fa-solid fa-envelope"></i></span> Email: info@BurgerHub.com</h6>
            <h6><span><i class="fa-solid fa-phone"></i></span> Phone: +1 (555) 123-4567</h6>
            <h6><span><i class="fa-solid fa-location-dot"></i></span> Address: 123 Main Street, Cityville, State, 12345</h6>
        </div>
        <div class="col-6">
            <form action="./send-message.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea class="form-control" name="message" id="message" cols="30" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-dark form-control">Send Message -></button>
            </form>
        </div>
    </div>
</section>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Admin Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-dark form-control">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="registerUsername">Username:</label>
                        <input type="text" class="form-control" id="registerUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password:</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="register" class="btn btn-dark form-control">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
