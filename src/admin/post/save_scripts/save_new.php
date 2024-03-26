<?php
include '../../../auth/session_config.php';

include '../../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $author =  $_SESSION['username']; 
    $category =  $_POST['category'];
    $tag =  $_POST['tag'];
    $contentTab1 = $_POST['input1'];
    $contentTab2 = $_POST['input2'];
    $contentTab3 = $_POST['input3'];
    $featured_img = $_POST['featured_img'];
    $featured_img_alt = $_POST['featured_img_alt'];
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $slugOld = '';
    $status = $_POST['status'];
    $quickInfo1 = $_POST['quickInfo1'];
    $quickInfo2 = $_POST['quickInfo2'];
    $quickInfo3 = $_POST['quickInfo3'];
    $postMetaDesc = $_POST['post_meta_desc'];


    // Indian time
    $indianTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $currentIndianTime = $indianTime->format('Y-m-d H:i:s');


    // Check post_id
    if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
        $post_id = $_POST['post_id'];

        // Check post_id already exists
        $checkSql = "SELECT * FROM `posts` WHERE `post_id` = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);

        if ($checkStmt) {
            // Bind parameters
            mysqli_stmt_bind_param($checkStmt, "i", $post_id);

            // Execute statement
            mysqli_stmt_execute($checkStmt);

            // Store result for fetching
            mysqli_stmt_store_result($checkStmt);

            // Check number of rows returned
            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                // perform update
                $updateSql = "UPDATE posts SET `post_author`=?, `post_category`=?, `post_tag`=?, `post_content1`=?, `post_content2`=?, `post_content3`=?, `post_featured_img`=?, `post_featured_img_alt`=?, `post_title`=?, `post_status`=?, `post_slug`=?, `post_slug_old`=?, `post_info1`=?, `post_info2`=?, `post_info3`=?, `post_meta_desc`=?, `post_modified`=? WHERE post_id = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);

                if ($updateStmt) {
                    // Bind parameters to the statement
                    mysqli_stmt_bind_param($updateStmt, "sssssssssssssssssi", $author, $category, $tag, $contentTab1, $contentTab2, $contentTab3, $featured_img, $featured_img_alt, $title, $status, $slug, $slugOld, $quickInfo1, $quickInfo2, $quickInfo3, $postMetaDesc, $currentIndianTime, $post_id);

                    // Execute the statement
                    if (mysqli_stmt_execute($updateStmt)) {
                        echo 'Data updated successfully with post_id: ' . $post_id;
                    } else {
                        echo 'Error updating data: ' . mysqli_error($conn);
                    }

                    // Close the update statement
                    mysqli_stmt_close($updateStmt);
                } else {
                    echo 'Error preparing update statement: ' . mysqli_error($conn);
                }
            } else {
                // insert a new one
                $insertSql = "INSERT INTO posts (`post_id`, `post_author`, `post_category`, `post_tag`, `post_content1`, `post_content2`, `post_content3`, `post_featured_img`, `post_featured_img_alt`, `post_title`, `post_status`, `post_slug`, `post_slug_old`, `post_info1`, `post_info2`, `post_info3`, `post_meta_desc`, `post_date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $insertStmt = mysqli_prepare($conn, $insertSql);

                if ($insertStmt) {
                    // Bind parameters to the statement
                    mysqli_stmt_bind_param($insertStmt, "isssssssssssssssss", $post_id, $author, $category, $tag, $contentTab1, $contentTab2, $contentTab3, $featured_img, $featured_img_alt, $title, $status, $slug, $slugOld, $quickInfo1, $quickInfo2, $quickInfo3, $postMetaDesc, $currentIndianTime);

                    // Execute the statement
                    if (mysqli_stmt_execute($insertStmt)) {
                        echo 'Data inserted successfully with post_id: ' . $post_id;
                    } else {
                        echo 'Error inserting data: ' . mysqli_error($conn);
                    }

                    // Close statement
                    mysqli_stmt_close($insertStmt);
                } else {
                    echo 'Error preparing insert statement: ' . mysqli_error($conn);
                }
            }

            // Close statement
            mysqli_stmt_close($checkStmt);
        } else {
            echo 'Error preparing check statement: ' . mysqli_error($conn);
        }
    } else {
        echo 'Error: post_id not provided';
    }

    // Close connection
    mysqli_close($conn);
    exit(); 
}



?>