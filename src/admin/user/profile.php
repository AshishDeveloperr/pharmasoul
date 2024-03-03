<?php
    include '../../auth/session_config.php';

    include '../../config/db_connect.php'; 

    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    // Initialize $postContent
    $userId = '';
    $username = '';
    $email = '';
    $name = '';
    
    // FETCHING USER INFO
    $slug = isset($_GET['name']) ? $_GET['name'] : '';
    // Query the database to get the post content based on the slug
    $sql = "SELECT * FROM `users` WHERE `user_name` = '$slug'";
    $result = $conn->query($sql);
    // echo "I run again";
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $userId = $row['sno'];
            $username = $row['user_name'];
            $email = $row['user_email'];
            $name = $row['display_name'];
        }
    } else {
        header("location: users.php");
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $display_name = $_POST["display_name"];
        $indianTime = new DateTime(null, new DateTimeZone('Asia/Kolkata'));
        $currentIndianTime = $indianTime->format('Y-m-d H:i:s');
    
        if (empty($_POST["password"])) {
            $sql = "UPDATE `users` SET `user_name`=?, `user_email`=?, `display_name`=?, `user_modified`=? WHERE `sno`=?";
            $stmt = mysqli_prepare($conn, $sql);
        
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $display_name, $currentIndianTime, $userId);
        } else {
            $password = md5($_POST["password"]);
            $sql = "UPDATE `users` SET `user_name`=?, `user_pass`=?, `user_email`=?, `display_name`=?, `user_modified`=? WHERE `sno`=?";
            $stmt = mysqli_prepare($conn, $sql);
        
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $email, $display_name, $currentIndianTime, $userId);
        }
    
        // Execute the prepared statement
        $result = mysqli_stmt_execute($stmt);
    
        // Check if the query was successful
        if ($result) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Profile updated successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error while updating.</div>', $errorAlert);
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
                    <div class="flex pb-3 items-center justify-center">
                        <div class="bg-gray-200 rounded-full">
                            <img src="user_img.webp" alt="user_img">
                        </div>
                    </div>
                    <div class="w-full">
                        <h2 class="text-center"><?php echo ucwords($name);?></h2>
                    </div>
                    <div class="flex justify-center">
                        <button type="button" class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br font-medium rounded-3xl text-xs px-5 py-2 text-center me-2 mb-2">@<?php echo $username;?></button>
                    </div>
                    <!-- Main Posts Table Starts -->
                    <div class="grid grid-cols-3 pt-5">
                        <div class="col-span-3 flex justify-center">
                            <form class="w-[30rem] max-w-lg flex justify-center flex-col" method="POST">
                                <div class="mb-5">
                                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                        </svg>
                                        </span>
                                        <input type="text" id="username" name="username" class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5" value="<?php echo $username;?>" required>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input type="email" id="email" name="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $email;?>" required>
                                </div>
                                <div class="mb-5">
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                    <input type="text" id="name" name="display_name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $name;?>" required>
                                </div>
                                <div class="mb-5">
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Enter new password</label>
                                    <input type="password" id="password" name="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Update</button>
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