<?php
include '../config/db_connect.php';

$search = $_GET['search'];

if($search==''){
    // echo "no record found";
}else{
    $query = "SELECT * FROM `posts` WHERE `post_title` LIKE '%$search%' AND post_status = 'active'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result)==0){
        echo '<div class="relative rounded-md my-2">
                    <h2 class="py-1 px-3 rounded-md">No record found</h2>
              </div>';
    }else{
        while($data = mysqli_fetch_assoc($result)){
            
            echo '
                
                <div class="side_post py-4 border-b-2 border-dashed">
                            <div role="status" class="space-y-1 md:space-y-0 space-x-3 rtl:space-x-reverse flex">
                                <a href="https://medbiography.com/' . $data['post_slug'] . '" class="flex items-center justify-center w-2/3 md:w-1/2 h-24 md:h-28 lg:h-20 bg-gray-300 rounded">
                                    <!-- <svg class="w-10 h-10 absolute text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                    </svg> -->
                                    <img src="' . $data['post_featured_img'] . '" alt="" class="h-24 md:h-28 lg:h-20 w-full rounded object-cover">
                                </a>
                                <div class="w-full h-full hover:text-orange-500 transition-all duration-300 ease-out">
                                    <a href="https://medbiography.com/' . $data['post_slug'] . '">
                                        <h1 class="text-base font-medium">';
                                            $max_length = 60;
                                            $post_title = $data['post_title'];
                                            if (strlen($post_title) > $max_length) {
                                                $post_title = substr($post_title, 0, $max_length) . '...';
                                            } 
                                            echo $post_title;
                                        echo'</h1>
                                    </a>
                                </div>
                            </div>
                        </div>
                
                ';
        
        }
    }

}


?>