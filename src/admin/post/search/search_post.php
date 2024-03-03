<?php
include '../../../config/db_connect.php';


$search = $_GET['search'];

if ($search == '') {
    // echo "no record found";
} else {
    $query = "SELECT * FROM `posts` WHERE `post_title` LIKE '%$search%'";
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
                        <a href="https://medbiography.com/' . $data['post_slug'] . '" class="flex items-center justify-center w-2/3 md:w-1/2 h-24 md:h-28 lg:h-20 bg-gray-300 rounded">
                            <img src="' . $data['post_featured_img'] . '" alt="" class="h-24 md:h-28 lg:h-20 w-full rounded object-cover">
                        </a>
                        <div class="w-full h-full hover:text-cyan-800 transition-all duration-300 ease-out">
                            <a href="https://medbiography.com/' . $data['post_slug'] . '">
                                <h1 class="text-sm font-medium">';
                                    $max_length = 60;
                                    $post_title = $data['post_title'];
                                    if (strlen($post_title) > $max_length) {
                                        $post_title = substr($post_title, 0, $max_length) . '...';
                                    }
                                    echo $post_title;
                                echo '</h1>
                            </a>
                        </div>
                    </div>
                    <div class="text-sm font-light py-1 space-x-3 search_action">
                        <a href="../../admin/post/edit.php?editId=' . $data['post_id'] . '" class="font-medium text-blue-600 hover:underline">Edit</a>';
            echo ($data['post_status'] != 'trash') ? '<a href="../../../' . $data['post_slug'] . '" class="font-medium text-teal-600 hover:underline">View</a>' : '';
            echo ($data['post_status'] === 'trash') ? '<a href="?visibility=trash&restorePost=' . $data['post_id'] . '" class="font-medium text-indigo-600 hover:underline">Restore</a>' : '';
            echo ($data['post_status'] === 'draft') ? '<a href="preview.php/' . $data['post_id'] . '" target="_blank" class="font-medium text-indigo-600 hover:underline">Preview</a>' : '';
            echo '<a href="' . (($data['post_status'] === 'trash') ? '?visibility=trash&deletePost' : '?trashPost') . '=' . $data['post_id'] . '" class="font-medium text-red-600 hover:underline">' . (($data['post_status'] === 'trash') ? 'Delete' : 'Trash') . '</a>
                    </div>
                </div>
            ';
        }
    }
}

?>

