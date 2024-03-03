<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include '../../../auth/session_config.php';

include '../../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $post_author = $_SESSION['username']; 
    $post_category = $_POST['category'];
    $post_tag = $_POST['tag'];
    $post_content = $_POST['input'];
    $post_featured_img = $_POST['featured_img'];
    $post_featured_img_alt = $_POST['featured_img_alt'];
    $post_meta_desc = $_POST['meta_desc'];
    $post_title = $_POST['title'];
    $post_quickInfo1 = $_POST['quickInfo1'];
    $post_quickInfo2 = $_POST['quickInfo2'];
    $post_quickInfo3 = $_POST['quickInfo3'];
    $post_slug = $_POST['slug'];
    $post_status = $_POST['status'];
    
    //indian time
    $indianTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $currentIndianTime = $indianTime->format('Y-m-d H:i:s');
    
    // update
    $sql = "UPDATE `posts` SET `post_author`=?, `post_category`=?, `post_tag`=?, `post_content`=?, `post_featured_img`=?, `post_featured_img_alt`=?, `post_title`=?, `post_status`=?, `post_slug`=?, `post_info1`=?, `post_info2`=?, `post_info3`=?, `post_meta_desc`=?, `post_modified`=? WHERE `post_id`=?";
    
    // Prepare
    $stmt = $conn->prepare($sql);

    // Bind
    $stmt->bind_param('ssssssssssssssi', $post_author, $post_category, $post_tag, $post_content, $post_featured_img, $post_featured_img_alt, $post_title, $post_status, $post_slug , $post_quickInfo1, $post_quickInfo2, $post_quickInfo3, $post_meta_desc, $currentIndianTime, $postId);

    // Execute
    if ($stmt->execute()) {
        echo "Record updated successfully:" . $postId;
       
    } else {
        echo "Error updating record: " . $stmt->error;
    }

}
    
?>