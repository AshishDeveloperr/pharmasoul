<?php 
    include '../../auth/session_config.php';
    
    include '../../config/db_connect.php'; 

    include '../alert/alert_success.php';
    include '../alert/alert_danger.php';

    $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_status` != 'trash'  ORDER BY `sno`");
    // total records
    $totalRecords = mysqli_num_rows($sql);

    $postLimit = 20;
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }
    $offset = ($page - 1) * $postLimit;

    //category
    $sql = "SELECT * FROM `categories`";
    $catData = mysqli_query($conn,$sql);


    // FOR ADDING POST IN TRASH
    if (isset($_REQUEST['trashPost'])) {
        $trashPost = $_REQUEST['trashPost'];
        $sql = "UPDATE `posts` SET `post_status` = 'trash' WHERE `post_id` = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }

        $stmt->bind_param('i', $trashPost);
        
        $check = $stmt->execute();
        if (!$check) {
            die("Error: " . $stmt->error);
        }
    
        if ($stmt->affected_rows > 0) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Post Added to Trash Successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Post Not Added to Trash.</div>', $errorAlert);
            echo $errorAlert;
        }
        
        $stmt->close();
    }

    // FOR DELETING POST FROM TRASH
    if (isset($_REQUEST['deletePost'])) {
        $deletePost = $_REQUEST['deletePost'];
        $sql = "DELETE FROM `posts` WHERE `post_id`= ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }

        $stmt->bind_param('i', $deletePost);

        $check = $stmt->execute();

        if (!$check) {
            die("Error: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Post Deleted Successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Post Not Deleted.</div>', $errorAlert);
            echo $errorAlert;
        }
        
        $stmt->close();
    }


    // FOR RESTORING POST FROM TRASH
    if (isset($_REQUEST['restorePost'])) {
        $restorePost = $_REQUEST['restorePost'];
        $sql = "UPDATE `posts` SET `post_status` = 'draft' WHERE `post_id` = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }
        
        $stmt->bind_param('i', $restorePost);
        $check = $stmt->execute();
        if (!$check) {
            die("Error: " . $stmt->error);
        }
        
        if ($stmt->affected_rows > 0) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Post Restored Successfully.</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error Restoring Post.</div>', $errorAlert);
            echo $errorAlert;
        }

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
<style>
.search_action {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.side_post_search:hover .search_action {
    visibility: visible;
    opacity: 1;
}

</style>
<body>
    <!-- HEADER STARTS-->
    <?php include '../component/header.php';?>
    <!-- HEADER ENDS -->
    <section>
        <div class="grid grid-cols-7">
            <aside class="col-span-1">
                <div class="h-screen fixed px-4 bg-slate-200 pt-16 w-[14%]">
                    <ul class="space-y-3 text-primary-gray list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <div class="categories_filter_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base font-normal py-1 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="posts.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="post_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="../category/categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="../category/category_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Media</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../media/media.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Media</a></li>
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
            <div class="col-span-6 h-screen mb-10 pt-5">
                <div class="py-12 px-6">
                    <div class="flex pb-3 items-center">
                        <h2 class="text-xl font-medium text-gray-900 pr-5">Posts</h2>
                        <a href="post_new.php">
                            <button class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">Add New Post</button>
                        </a>
                    </div>
                    <div class="py-3">
                        <div class="grid grid-cols-2">
                            <div class="col-span-1 flex items-center">
                                <ul class="flex space-x-3 text-base font-medium">
                                    <li>All <a href="posts.php" class="text-indigo-700 hover:underline">(<?php
                                        $sql = mysqli_query($conn, "SELECT COUNT(*) as allPost FROM `posts`");
                                        $row = mysqli_fetch_assoc($sql);
                                        $allPost = $row['allPost'];
                                        echo $allPost;?>)</a>
                                    </li>
                                    <li>Published <a href="?visibility=active" class="text-indigo-700 hover:underline">(<?php
                                        $sql = mysqli_query($conn, "SELECT COUNT(*) as publishedPost FROM `posts` WHERE `post_status` = 'active' AND `post_status` != 'trash'");
                                        $row = mysqli_fetch_assoc($sql);
                                        $publishedPost = $row['publishedPost'];
                                        echo $publishedPost;?>)</a>
                                    </li>
                                    <li>Trash <a href="?visibility=trash" class="text-red-600 hover:underline">(<?php
                                        $sql = mysqli_query($conn, "SELECT COUNT(*) as totalTrashPost FROM `posts` WHERE `post_status` = 'trash'");
                                        $row = mysqli_fetch_assoc($sql);
                                        $totalTrashPost = $row['totalTrashPost'];
                                        echo $totalTrashPost;?>)</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-span-1 relative">
                                <div class="search flex justify-end">
                                    <form class="flex relative">   
                                        <div class="relative w-72">
                                            <input type="search" id="search_input" oninput="load_data(this.value)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none block w-full  p-2.5" placeholder="Search Post..." required>
                                        </div>
                                        <button type="submit" class="inline-flex items-center py-2.5 px-3 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                            </svg>Search
                                        </button>
                                        <div id="search_preview" class="absolute z-[5] shadow-lg bg-gray-100 px-1 mt-12 rounded-md w-full max-h-[31.5rem] overflow-y-scroll">
                                            <!-- Search result will display here -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Drop Down Filters Starts -->
                    <div class="flex space-x-5">
                        <div class="bulk_edit_dropdown pb-4">
                            <div class="dropdown inline-block relative">
                                <button class="bg-gray-50 text-base text-gray-900 font-medium py-2 px-8 rounded inline-flex items-center border border-gray-300">
                                    <span class="mr-1">Bulk actions</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/> </svg>
                                </button>
                                <ul class="dropdown-menu absolute hidden text-gray-900 pt-1 z-30 border border-gray-300 rounded-md">
                                    <li class=""><a class="rounded-t bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">One</a></li>
                                    <li class=""><a class="bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">Two</a></li>
                                    <li class=""><a class="rounded-b bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">Three is the magic number</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="date_filter_dropdown pb-4">
                            <div class="dropdown inline-block relative">
                                <button class="bg-gray-50 text-base text-gray-900 font-medium py-2 px-5 rounded inline-flex items-center border border-gray-300">
                                    <span class="mr-1">All Dates</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/> </svg>
                                </button>
                                <ul class="dropdown-menu absolute hidden text-gray-900 pt-1 z-30 border border-gray-300 rounded-md">
                                    <li><a class="rounded-t bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">All Dates</a></li>
                                    <li><a class="bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">July 2023</a></li>
                                    <li><a class="rounded-b bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="#">September 2023</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="categories_filter_dropdown pb-4">
                            <div class="dropdown inline-block relative">
                                <button class="bg-gray-50 text-base text-gray-900 font-medium py-2 px-5 rounded inline-flex items-center border border-gray-300">
                                    <span class="mr-1">All Categories</span>
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/> </svg>
                                </button>
                                <ul class="dropdown-menu absolute hidden text-gray-900 pt-1 z-30 border border-gray-300 rounded-md w-full">
                                    <?php
                                        if(mysqli_num_rows($catData)>0){
                                            while($data = mysqli_fetch_assoc($catData)){
                                                ?>
                                                <li class="border-b"><a class="rounded-t bg-gray-50 hover:bg-gray-100 py-2 px-4 block whitespace-no-wrap" href="?category=<?php echo $data['category_name'];?>"><?php echo $data['category_name'];?></a></li>
                                                <?php
                                            }
                                        }else{
                                            echo $data = "No category";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center py-2.5 px-5 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>Filter
                            </button>
                        </div>
                    </div>
                    <!-- Drop Down Filters Ends -->
                    <!-- Main Posts Table Starts -->
                    <div class="posts_table">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Title
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Author
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Tags
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Featured Image
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($_REQUEST['category'])) {
                                            $postCategory = $_REQUEST['category'];
                                            $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_category` = '$postCategory' ORDER BY `sno` DESC LIMIT {$offset},{$postLimit}");  

                                        }elseif(isset($_REQUEST['visibility'])){
                                            $postVisibility = $_REQUEST['visibility'];
                                            $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_status` = '$postVisibility' ORDER BY `sno` DESC LIMIT {$offset},{$postLimit}");

                                        }else{
                                            $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_status` != 'trash' ORDER BY `sno` DESC LIMIT {$offset}, {$postLimit}");

                                        }
                                        
                                        while($row = mysqli_fetch_assoc($sql)){
                                    ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                           <h2>
                                                <?php echo ($row['post_status'] === 'draft') ? $row['post_title'].'<span class="pl-3 text-gray-500">--Draft</span>' : $row['post_title'];?>
                                            </h2>
                                           <div class="py-3 space-x-3">
                                                <a href="../../admin/post/edit.php?editId=<?php echo $row['post_id']?>" class="font-medium text-blue-600 hover:underline">Edit</a>
                                                <?php echo ($row['post_status'] != 'trash') ? '<a href="../../../'. $row['post_slug'] .'" class="font-medium text-teal-600 hover:underline">View</a>' : ''; ?>
                                                <?php echo ($row['post_status'] === 'trash') ? '<a href="?visibility=trash&restorePost=' . $row['post_id'] . '" class="font-medium text-indigo-600 hover:underline">Restore</a>' : ''; ?>
                                                <?php echo ($row['post_status'] === 'draft') ? '<a href="preview.php/' . $row['post_id'] . '" target="_blank" class="font-medium text-indigo-600 hover:underline">Preview</a>' : ''; ?>
                                                <a href="<?php echo ($row['post_status'] === 'trash') ? '?visibility=trash&deletePost' : '?trashPost';?>=<?php echo $row['post_id']?>" class="font-medium text-red-600 hover:underline"><?php echo ($row['post_status'] === 'trash') ? 'Delete' : 'Trash';?></a>
                                           </div>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?php echo $row['post_author']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['post_category']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['post_tag']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['post_date']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center w-32 h-20 rounded">
                                                <img src="<?php echo $row['post_featured_img']?>" alt="featured_img" class="bg-cover bg-center object-cover w-full h-full rounded">
                                            </div>
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
                    <!-- Pagination starts -->
                    <div class="flex justify-center pt-8 pb-5">
                        <?php 
                            $postLimit = 20;
                            $totalPage = ceil($totalRecords / $postLimit);
                            
                            echo '<ul class="inline-flex -space-x-px text-sm">';
                            if($page > 1){
                                echo '<li class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700"><a href="posts.php?page='.($page - 1).'">Prev</a><li>';
                            }
                            for($i = 1; $i <= $totalPage; $i++){
                                echo '<a href="posts.php?page='.$i.'"><li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">'.$i.'</li></a>';
                            }
                            if($totalPage > $page){
                                echo '<li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700"><a href="posts.php?page='.($page + 1).'">Next</a><li>';
                            }
                            echo '</ul>';
                        ?>
                    </div>
                    <!-- Pagination ends -->
                </div>
            </div>
        </div>
    </section>    
</body>
<script>
    // search post
    function load_data(search=''){
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "search/search_post.php?search="+search, true);
        xhr.onload = function(){
            document.getElementById('search_preview').innerHTML = xhr.responseText;
        }
        xhr.send();
    }
</script>

<script src="../alert/alert_dismiss.js"></script>

</html>