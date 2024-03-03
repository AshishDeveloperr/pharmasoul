<?php 
    include '../../auth/session_config.php';
    
    include '../../config/db_connect.php'; 

    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $cat_name = $_GET['cat_name'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category_name = $_POST['category_name'];
        $author_name = $_SESSION['username']; 
        
        // Use prepared statement to update the record
        $sql = "UPDATE `categories` SET `category_name`= ?, `category_author`= ? WHERE `cat_id`= ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
    
        // Bind the parameters
        $stmt->bind_param('ssi', $category_name, $author_name, $id);
    
        // Execute the statement
        if ($stmt->execute()) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Category updated successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error category not updated.</div>', $errorAlert);
            echo $errorAlert;
        }
    
        // Close the statement
        $stmt->close();
    }
    
    // Close the connection
    $conn->close();
    
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
                    <!-- Main Category Starts -->
                    <div class="grid grid-cols-3">
                        <div class="col-span-1">
                            <form method="POST">
                                <div class="col-span-6">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 py-2">Category Name</label>
                                    <input type="text" name="category_name" value="<?php echo $cat_name;?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <button name="updatecat" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 my-3">Update Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Main Category Ends -->
                </div>
            </div>
        </div>
    </section>    
</body>
<script src="../alert/alert_dismiss.js"></script>
</html>