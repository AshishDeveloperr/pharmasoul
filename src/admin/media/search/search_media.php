<?php
include '../../../config/db_connect.php';


$search = $_GET['search'];

if ($search == '') {
    // echo "no record found";
} else {
    $query = "SELECT * FROM `uploaded_files` WHERE `filename` LIKE '%$search%'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="relative rounded-md my-2">
                    <h2 class="py-1 px-3 rounded-md">No record found</h2>
              </div>';
    } else {
        while ($data = mysqli_fetch_assoc($result)) {

            echo '
            
                <div class="side_post_search py-2 border-b-2 border-dashed">
                    <div role="status" class="space-y-1 md:space-y-0 space-x-3 rtl:space-x-reverse flex">
                        <a href="../../..' . $data['filepath'] . '" class="flex items-center justify-center w-2/3 md:w-1/2 h-24 md:h-28 lg:h-20 bg-gray-300 rounded" target="_blank">
                            <img src="../../..' . $data['filepath'] . '" alt="" class="h-24 md:h-28 lg:h-20 w-full rounded object-cover">
                        </a>
                        <div class="w-full h-full hover:text-cyan-800 transition-all duration-300 ease-out">
                            <a href="../../..' . $data['filepath'] . '" target="_blank">
                                <h1 class="text-sm font-medium">';
                                    $max_length = 60;
                                    $post_title = $data['filename'];
                                    if (strlen($post_title) > $max_length) {
                                        $post_title = substr($post_title, 0, $max_length) . '...';
                                    }
                                    echo $post_title;
                                echo '</h1>
                            </a>
                        </div>
                    </div>
                    <div class="text-sm font-light py-1 space-x-3 search_action">
                        <a href="#" class="font-medium text-blue-600 hover:underline">View</a>
                        <a href="http://localhost/pharmasoul/' . $data['filepath'] . '" download="" class="font-medium text-fuchsia-800 hover:underline" id="media_url">Download</a>
                        <button class="font-medium text-yellow-600 hover:underline" data-url="http://localhost/pharmasoul/' . $data['filepath'] . '" onclick="copyMediaURL(this)" id="mediaURL">Copy URL</button>
                        <a href="?delete=' . $data['id'] . '" class="font-medium text-red-600 hover:underline">Delete</a>
                    </div>
                   
                </div>
            ';
        }
    }
}

?>

