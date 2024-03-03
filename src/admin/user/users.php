
<?php
include '../../auth/session_config.php';

include '../../config/db_connect.php'; 

include '../alert/alert_success.php';
include '../alert/alert_danger.php';

// FOR DELETING USER

if (isset($_REQUEST['deleteUser']) && $_SESSION['userrole'] === 'administrator')  {
    $deleteUser = $_REQUEST['deleteUser'];
    $sql = "DELETE FROM `users` WHERE `user_name`= ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param('s', $deleteUser);

    // Execute the statement
    $check = $stmt->execute();

    if (!$check) {
        // Output the error message
        die("Error: " . $stmt->error);
    }

    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">User deleted successfully.</div>', $successAlert);
        echo $successAlert;
    } else {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error user not deleted.</div>', $errorAlert);
        echo $errorAlert;
    }
    // Close the statement
    $stmt->close();
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
                        <?php 
                            echo ($_SESSION['userrole'] === 'administrator')
                            ? '<a href="add_user.php" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Add New User</a>'
                            : '';
                        ?>
                    </div>
                    <div class="py-3">
                        <div class="grid grid-cols-2">
                            <div class="col-span-1 flex items-center">
                                <ul class="flex space-x-3 text-base font-medium">
                                    <li>All <a href="#" class="text-indigo-700 hover:underline">(<?php
                                        if($_SESSION['userrole'] === 'administrator'){
                                            $sql = mysqli_query($conn, "SELECT COUNT(*) as allUser FROM `users`");
                                        }else{
                                            $sql = mysqli_query($conn, "SELECT COUNT(*) as allUser FROM `users` WHERE `user_role`= '{$_SESSION['userrole']}'");
                                        }
                                        $row = mysqli_fetch_assoc($sql);
                                        $allUser = $row['allUser'];
                                        echo $allUser;?>)</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-span-1 relative">
                                <div class="search flex justify-end">
                                    <form class="flex relative">   
                                        <div class="relative w-52">
                                            <input type="search" id="search_input" oninput="load_data(this.value)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full  p-2.5" placeholder="Search Post..." required>
                                        </div>
                                        <button type="submit" class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                            </svg>Search
                                        </button>
                                        <div id="search_preview" class="absolute z-50 shadow-lg bg-gray-100 px-1 mt-12 rounded-md">
                                            <!-- Search result will display here -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Posts Table Starts -->
                    <div class="posts_table">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Sno
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Role
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($_SESSION['userrole'] === 'administrator'){
                                            $sql = mysqli_query($conn, "SELECT * FROM `users` ORDER BY `sno` DESC");  
                                        }else{
                                            $sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `user_role`= '{$_SESSION['userrole']}' ORDER BY `sno` DESC");
                                        }
                                        $sno = 1;                                      
                                        while($row = mysqli_fetch_assoc($sql)){
                                    ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                           <h2>
                                           <img src="user_img.webp" alt="" class="w-8 bg-gray-300 rounded-full">
                                            </h2>
                                           <div class="py-3 space-x-2">
                                                <a href="profile.php?name=<?php echo $row['user_name']?>" class="font-medium text-blue-600 hover:underline">View</a>
                                                <?php 
                                                    echo ($_SESSION['userrole'] === 'administrator')
                                                    ? '<a href="?deleteUser='. $row['user_name'].'" class="font-medium text-red-600 hover:underline">Delete</a>' : '';
                                                ?>
                                           </div>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?php echo $row['display_name']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['user_email']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['user_role']?>
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