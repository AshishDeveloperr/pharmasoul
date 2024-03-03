<?php
    include '../../auth/session_config.php';

    include '../../config/db_connect.php'; 
    if ($_SESSION['userrole'] !== 'administrator') {
        header("location: users.php");
        exit(); 
    }
    
    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $display_name = $_POST["display_name"];
        $password = md5($_POST["password"]); // Use md5 for password hashing
        $role = $_POST["role"];

        // Get the current Indian time
        $indianTime = new DateTime(null, new DateTimeZone('Asia/Kolkata'));
        $currentIndianTime = $indianTime->format('Y-m-d H:i:s');

        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO users (user_name, user_pass, user_email, display_name, user_registered, user_role) VALUES (?, ?, ?, ?, ?, ?)";

        // Create a prepared statement
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $email, $display_name, $currentIndianTime, $role);

        // Execute the prepared statement
        $result = mysqli_stmt_execute($stmt);

        // Check if the query was successful
        if ($result) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">User added successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error user not added.</div>', $errorAlert);
            echo $errorAlert;
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

        // Close the database connection if necessary
        mysqli_close($conn);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/output.css">

    <link rel="icon" href="../../img/ultramr.webp" type="image/x-icon">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>PharmaSoul</title>
</head>
<body>
    <!-- HEADER STARTS-->
    <?php include '../component/header.php';?>
    <!-- HEADER ENDS -->
    <section>
        <div class="grid grid-cols-7">
        <aside class="col-span-1 bg-slate-200 shadow-md">
                <div class="h-screen fixed px-4 bg-slate-200 pt-14">
                    <ul class="space-y-3 text-primary-gray list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <!-- <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base font-normal py-2 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../post/posts.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="../post/post_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="../category/categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="../category/categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
                            </ul>
                        </div>
                    </div> -->
                    <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Media</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../media/media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Media</a></li>
                                <li><a href="../media/media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Upload Media</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu_dropdown pb-2">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Users</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../user/users.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Users</a></li>
                                <?php 
                                echo ($_SESSION['userrole'] === 'administrator')
                                ? '<li><a href="../user/add_user.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New User</a></li>'
                                : '';
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="col-span-6 h-screen mb-10 pt-3">
                <div class="py-12 px-6">
                    <div class="flex pb-3 items-center">
                        <h2 class="text-xl font-medium text-gray-900 pr-5">Users</h2>
                        <a href="users.php">
                            <button class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">View All Users</button>
                        </a>
                    </div>
                    <!-- Main Posts Table Starts -->
                    <div class="grid grid-cols-3">
                        <div class="col-span-1 pt-10">
                            <form class="max-w-sm" method="POST">
                                <div class="mb-5">
                                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                    <input type="text" id="username" name="username" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-5">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input type="email" id="email" name="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-5">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                    <input type="text" id="name" name="display_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-5">
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Your password</label>
                                    <input type="password" id="password" name="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-5">
                                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Select user role</label>
                                    <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <option value="author">Author</option>
                                        <option value="administrator">Administrator</option>
                                    </select>
                                </div>
                                <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Register new account</button>
                            </form>
                        </div>
                    </div>
                    <!-- Main Posts Table Ends -->
                    
                </div>
            </div>
        </div>
    </section>    
</body>
<script src="../alert/alert_dismiss.js"></script>
</html>