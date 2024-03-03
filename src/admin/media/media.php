
<?php 
include '../../auth/session_config.php';

include '../../config/db_connect.php'; 

include '../alert/alert_success.php';
include '../alert/alert_danger.php';

// DELETE MEDIA
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['delete'])) {
    $mediaIdToDelete = $_GET['delete'];

    // Get filepath from the database using the media id
    $sql = "SELECT filepath FROM uploaded_files WHERE id = '$mediaIdToDelete'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filepathToDelete = '../../..' . $row['filepath'];

        // Delete the file from the folder
        unlink($filepathToDelete);

        // Delete the record from the database
        $sqlDelete = "DELETE FROM uploaded_files WHERE id = '$mediaIdToDelete'";
        if ($conn->query($sqlDelete) === TRUE) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Media deleted successfully!</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error deleting media.</div>', $errorAlert);
            echo $errorAlert;
        }
    } else {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error: Media id not found in the database.</div>', $errorAlert);
        echo $errorAlert;
    }

}
// pagination for media
$sql = mysqli_query($conn, "SELECT COUNT(*) as totalRecords FROM `uploaded_files`");
$row = mysqli_fetch_assoc($sql);
$totalRecords = $row['totalRecords'];
// echo $totalRecords;

$postLimit = 20;
if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}
$offset = ($page - 1) * $postLimit;


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
            <aside class="col-span-1">
                <div class="h-screen fixed px-4 bg-slate-200 pt-14 w-[14%]">
                    <ul class="space-y-3 text-primary-gray list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <!-- <div class="categories_filter_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base font-normal py-1 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="../post/posts.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="../post/post_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="../category/categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="../category/category_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
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
            <div class="col-span-6 h-screen mb-10">
            <div class="py-12 px-6">
                    <div class="flex pb-3 items-center">
                        <h2 class="text-xl font-medium text-gray-900 pr-5">Media</h2>
                        <a href="media_upload.php">
                            <button class="text-base font-medium text-sky-700 px-5 py-2 border border-sky-700 hover:bg-gray-100 rounded-md">Add New Media</button>
                        </a>
                    </div>
                    <div class="py-3">
                        <div class="grid grid-cols-2">
                            <div class="col-span-1 flex items-center">
                                <ul class="flex space-x-3 text-base font-medium">
                                    <li>All <a href="#" class="text-indigo-700 hover:underline">(<?php
                                        $sql = mysqli_query($conn, "SELECT COUNT(*) as allPost FROM `uploaded_files`");
                                        $row = mysqli_fetch_assoc($sql);
                                        $allPost = $row['allPost'];
                                        echo $allPost;?>)</a>
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
                                            Size
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Featured Image
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM `uploaded_files` ORDER BY `id` DESC LIMIT {$offset},{$postLimit}");  
                                        $sno = ($page - 1) * $postLimit + 1;      
                                        while($row = mysqli_fetch_assoc($sql)){
                                    ?>
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <h2>
                                                <?php echo $sno++?>
                                            </h2>
                                           <div class="py-3 space-x-2">
                                                <a href="#" class="font-medium text-blue-600 hover:underline">View</a>
                                                <a href="<?php echo "https://domain.com" . $row['filepath']; ?>" download class="font-medium text-fuchsia-800 hover:underline" id="media_url">Download</a>
                                                <button class="font-medium text-yellow-600 hover:underline" data-url="<?php echo "https://domain.com" . $row['filepath']; ?>" onclick="copyMediaURL(this)" id="mediaURL">Copy URL</button>
                                                <a href="?delete=<?php echo $row['id']; ?>" class="font-medium text-red-600 hover:underline">Delete</a>
                                           </div>
                                        </th>
                                        <td class="px-6 py-4">
                                            <?php echo $row['filename']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo $row['filesize']?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center w-32 h-20 rounded popup overflow-hidden">
                                                <img src="<?php echo  "../../.." . $row['filepath']; ?>" alt="<?php echo $row['filename']?>" class="bg-cover bg-center object-cover w-44 max-h-28 rounded" loading="lazy">
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
                    <!-- Pagination starts here -->
                    <div class="flex justify-center pt-8 pb-5">
                        <?php 
                            $postLimit = 20;
                            $totalPage = ceil($totalRecords / $postLimit);
                            
                            echo '<ul class="inline-flex -space-x-px text-sm">';
                            if($page > 1){
                                echo '<li class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700"><a href="media.php?page='.($page - 1).'">Prev</a><li>';
                            }
                            for($i = 1; $i <= $totalPage; $i++){
                                echo '<a href="media.php?page='.$i.'"><li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">'.$i.'</li></a>';
                            }
                            if($totalPage > $page){
                                echo '<li class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700"><a href="media.php?page='.($page + 1).'">Next</a><li>';
                            }
                            echo '</ul>';
                        ?>
                    </div>
                    <!-- Pagination ends here-->
                </div>
            </div>
        </div>
    </section>    
    <!-- media lighthouse starts -->
    
    <div class="show overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex hidden">
        <div class="overlay w-full h-full fixed top-0 left-0 bg-black opacity-80"></div>
        <span class="p-1 rounded absolute top-6 md:top-14 right-4 md:right-2 lg:right-7 z-50 cursor-pointer close-btn">
            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </span>
        <div class="img-show w-full md:w-1/3 h-3/4 absolute top-1/2 left-1/2 overflow-hidden transform -translate-x-1/2 -translate-y-1/2 md:-translate-y-1/2 flex flex-col items-center">
            <div class="w-full flex justify-center items-center h-full">
                <img src="" class="max-h-full w-4/5 object-cover relative pt-3">
            </div>
        </div>
    </div>

    <!-- media lighthouse ends -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            var popupImages = document.querySelectorAll('.popup img');
            var show = document.querySelector('.show');
            var imgShow = document.querySelector('.img-show');
            var overlay = document.querySelector('.overlay');
            var imgShowImage = document.querySelector('.img-show img');
            var closeBtn = document.querySelector('.show .close-btn');

            popupImages.forEach(function(img) {
                img.addEventListener('click', function() {
                    var src = this.src;
                    imgShowImage.src = src;
                    show.classList.add('block');
                    show.classList.remove('hidden');
                    setTimeout(function() {
                        show.style.opacity = 1;
                        show.classList.add('dismiss-transition');
                    }, 10);
                });
            });

            function closePopup() {
                show.classList.add('dismiss-transition');
                show.style.opacity = 0;
                setTimeout(function() {
                    show.classList.add('hidden');
                }, 300); 
            }

            closeBtn.addEventListener('click', closePopup);
            overlay.addEventListener('click', closePopup);
        });
 
    // Copy Media URL
    function copyMediaURL(button) {
        var dataUrl = button.getAttribute("data-url");

        var textarea = document.createElement("textarea");
        textarea.value = dataUrl;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);

        textarea.select();
        textarea.setSelectionRange(0, 99999);
        document.execCommand('copy');

        document.body.removeChild(textarea);

        button.textContent = "Copied URL";

        setTimeout(function() {
            button.textContent = "Copy URL";
        }, 3000);
    }


      // search post
      function load_data(search=''){
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "search/search_media.php?search="+search, true);
        xhr.onload = function(){
            document.getElementById('search_preview').innerHTML = xhr.responseText;
        }
        xhr.send();
    }
    </script>
</body>
<script src="../alert/alert_dismiss.js"></script>
</html>