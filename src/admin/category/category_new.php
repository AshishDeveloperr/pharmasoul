<?php
    include '../../auth/session_config.php';

    include '../../config/db_connect.php'; 

    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    // ADDING CATEGORY
    if(isset($_POST['category'])){
        $catname = $_POST['category'];
        $author_name = $_SESSION['username']; 

        // Get the current Indian time
        $indianTime = new DateTime(null, new DateTimeZone('Asia/Kolkata'));
        $currentIndianTime = $indianTime->format('Y-m-d H:i:s');
    
        // Check if the category already exists
        $checkQuery = "SELECT * FROM categories WHERE category_name = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param('s', $catname);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        $stmtCheck->close();
    
        if ($result->num_rows > 0) {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Category already exists.</div>', $errorAlert);
            echo $errorAlert;
        } else {
            // Use prepared statement to insert the category
            $insertQuery = "INSERT INTO `categories` (category_name, category_author, category_date) VALUES (?,?,?)";
            $stmtInsert = $conn->prepare($insertQuery);
            $stmtInsert->bind_param('sss', $catname, $author_name, $currentIndianTime);
    
            $check = $stmtInsert->execute();
            $stmtInsert->close();
    
            if($check){
                $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Category added Successfully.</div>', $successAlert);
                echo $successAlert;
            } else {
                $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Category not added.</div>', $errorAlert);
                echo $errorAlert;
            }
        }
    }
    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/output.css">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>MedBiography</title>
</head>
<body>
    <!-- HEADER STARTS-->
    <?php include '../component/header.php';?>
    <!-- HEADER ENDS -->
    <section>
        <div class="grid grid-cols-7">
            <aside class="col-span-1 bg-[#1d2327]">
                <div class="h-screen fixed px-4 bg-[#1d2327] pt-10">
                    <ul class="space-y-3 text-gray-200 list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <div class="categories_filter_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base font-normal py-2 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../post/posts.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="../post/post_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="category_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Media</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../media/media.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Media</a></li>
                                <li><a href="../media/media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Upload Media</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu_dropdown pb-2">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Users</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
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
            <div class="col-span-6 h-screen mb-96">
                <div class="py-12 px-6">
                    <div class="flex pb-3 items-center">
                        <h2 class="text-xl font-medium text-gray-900 pr-5">Categories</h2>
                        <a href="categories.php">
                            <button class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">View Categories</button>
                        </a>
                    </div>
                    <!-- Main Posts Table Starts -->
                    <div class="grid grid-cols-3">
                        <div class="col-span-1">
                            <form method="POST">
                                <div class="col-span-6">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 py-2">Category Name</label>
                                    <input type="text" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <button name="addcat" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 my-3">Add Category</button>
                                </div>
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