<?php
    include '../../auth/session_config.php';

    include '../../config/db_connect.php'; 

    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    // FOR DELETING CATEGORY
    if (isset($_REQUEST['delid'])) {
        $delid = $_REQUEST['delid'];
        $sql = "DELETE FROM `categories` WHERE `cat_id`= ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }

        // Bind the parameter
        $stmt->bind_param('i', $delid);

        // Execute the statement
        $check = $stmt->execute();

        if (!$check) {
            // Output the error message
            die("Error: " . $stmt->error);
        }

        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Category deleted Successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Category not deleted.</div>', $errorAlert);
            echo $errorAlert;
        }

        // Close the statement
        $stmt->close();
    }

    
    //category
    $sql = "SELECT * FROM `categories`";
    $catData = mysqli_query($conn,$sql);
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
    <style>
        
      </style>
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
                        <a href="category_new.php">
                            <button class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Add New Category</button>
                        </a>
                    </div>
                    <!-- Main Posts Table Starts -->
                    <div class="py-3 text-lg text-slate-900 font-normal">
                        <h2>All categories</h2>
                    </div>
                    <div class="posts_table">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            #
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Category Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Author
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM `categories` ORDER BY `cat_id` DESC");
                                        $sno = 1;
                                        while($row = mysqli_fetch_assoc($sql)){
                                    ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                           <h2>
                                                <?php echo $sno++?>
                                            </h2>
                                           <div class="py-3 space-x-3">
                                                <a href="category_edit.php?id=<?php echo $row['cat_id']?>&cat_name=<?php echo $row['category_name']?>" class="font-medium text-blue-600 hover:underline">Edit</a>
                                                <a href="../../../category/<?php echo $row['category_name']?>" class="font-medium text-teal-600 hover:underline">View</a>
                                                <a href="?delid=<?php echo $row['cat_id']?>&delcat_name=<?php echo $row['category_name']?>" class="font-medium text-red-600 hover:underline">Delete permanently</a>
                                           </div>
                                        </th>
                                        <td class="px-6 py-4 font-medium">
                                            <?php echo $row['category_name']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['category_author']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['category_date']?>
                                        </td>
                                    </tr>
                                    <?php } 
                                        $conn->close();
                                    ?>
                                </tbody>
                            </table>
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