<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../../auth/session_config.php';

include '../../config/db_connect.php';

// Initialize $postContent
$postId = '';
$postContent = '';
$postTitle = '';
$postSlug = '';
$postCategory = '';
$postTag = '';
$postFeaturedImg = '';
$postStatus = '';
$postInfo1 = '';
$postInfo2 = '';
$postInfo3 = '';
$old_slug = '';

// Extract the slug from the URL
$editId = isset($_GET['editId']) ? $_GET['editId'] : '';

// Query the database to get the post content based on the slug
$sql = "SELECT * FROM posts WHERE post_id = '$editId'";
$result = $conn->query($sql);
// echo "I run again";

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $postId = $row['post_id'];
        $postCategory = $row['post_category'];
        $postTag = $row['post_tag'];
        $postContent = $row['post_content'];
        $postFeaturedImg = $row['post_featured_img'];
        $postFeaturedImgAlt = $row['post_featured_img_alt'];
        $postMetaDesc = $row['post_meta_desc'];
        $postTitle = $row['post_title'];
        $postStatus = $row['post_status'];
        $postSlug = $row['post_slug'];
        $postInfo1 = $row['post_info1'];
        $postInfo2 = $row['post_info2'];
        $postInfo3 = $row['post_info3'];
        $old_slug = $row['post_slug_old'];

        // Display post content 
        echo "";
    }
} else {
    echo "Post not found";
}

//category
$sql = "SELECT * FROM `categories`";
$catData = mysqli_query($conn,$sql);
// Close the database connection
$conn->close();
?>

<?php 
include '../../config/db_connect.php';
$id = $postId;
$oldSlug = $old_slug;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buttonType'])) {
    $buttonType = $_POST['buttonType']; 
    // Get input data
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

    // post_status
    if ($buttonType == 'save') {
        $post_status = $_POST['status'];
    }elseif ($buttonType == 'save_draft') {
        $post_status = 'draft';
    }

    // post slug
    if ($post_slug !== $postSlug) {
        $currentSlug = $postSlug;
    } elseif ($oldSlug == '') {
        $currentSlug = '';
    } else {
        $currentSlug = $oldSlug;
    }
    
    // Indian time
    $indianTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $currentIndianTime = $indianTime->format('Y-m-d H:i:s');
    
    // statement to update the record
    $sql = "UPDATE `posts` SET `post_author`=?, `post_category`=?, `post_tag`=?, `post_content`=?, `post_featured_img`=?, `post_featured_img_alt`=?, `post_title`=?, `post_status`=?, `post_slug`=?, `post_slug_old`=?, `post_info1`=?, `post_info2`=?, `post_info3`=?, `post_meta_desc`=?, `post_modified`=? WHERE `post_id`=?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param('sssssssssssssssi', $post_author, $post_category, $post_tag, $post_content, $post_featured_img, $post_featured_img_alt, $post_title, $post_status, $post_slug, $currentSlug , $post_quickInfo1, $post_quickInfo2, $post_quickInfo3, $post_meta_desc, $currentIndianTime, $postId);

    // Execute statement
    if ($stmt->execute()) {
        echo "Record updated successfully";?>
        <script>
            alert("Post Updated");
            window.location.href = 'edit.php?editId=<?php echo $editId; ?>';
        </script>
        <?php
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
    
// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/output.css">

    <link rel="icon" href="https://medbiography.com/src/uploads/favicon.webp" type="image/x-icon">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>MedBiography</title>
    <style>
        #container {
            width: 1000px;
            margin: 20px auto;
        }
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
        .ck-content .image {
            /* block images */
            max-width: 80%;
            margin: 20px auto;
        }
        .ck-restricted-editing_mode_standard.ck-content.ck-editor__editable.ck-rounded-corners{
            padding:5px 20px;
        }
        </style>
</head>
<body>
    <!-- HEADER STARTS-->
    <?php include '../component/header.php';?>
    <!-- HEADER ENDS -->
    <section>
        <div class="grid grid-cols-7">
            <aside class="col-span-1">
                <div class="h-screen fixed px-4 bg-[#1d2327] pt-10 w-[14%]">
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
                                <li><a href="posts.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="post_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="../category/categories.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="../category/category_new.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
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
            <div class="col-span-6">
                <form method="POST" id="autosave-editform">
                    <div class="grid grid-cols-6">
                        <div class="col-span-5">
                            <div class="py-10 px-6">
                                <div class="mb-4">
                                    <input name="title" class="w-full py-3 px-2 border border-gray-300 outline-none" value="<?php echo  $postTitle ; ?>" placeholder="Update Title Here...">
                                </div>
                                <div class="mb-4 flex items-center">
                                    <span class="text-sm font-medium text-gray-900 pr-1">Permalink: </span>
                                    <input name="slug" id="slug-input" class="text-sm w-full py-1 px-2 border border-gray-300 outline-none" value="<?php echo  $postSlug ; ?>" placeholder="Add Permalink Here...">
                                </div>
                                <textarea id="editor" name="input">
                                    <?php echo  $postContent ; ?>
                                </textarea>
                            </div>
                        </div>
                        <aside class="col-span-1 pl-2 pr-4 py-8">
                            <div class="px-4 pb-4 pt-2 border border-gray-300 rounded-md bg-white">
                                <div class="border-b border-gray-300 pb-2">
                                    <h2 class="text-base font-medium text-gray-950">Publish</h2>
                                    <button type="submit" name="buttonType" value="save" class="text-sm text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-md mt-2 block">Update</button>
                                    <button type="submit" name="buttonType" value="save_draft" class="text-sm text-white bg-indigo-700 hover:bg-indigo-800 px-3 py-2 rounded-md mb-4 mt-2 block">Save as draft</button>
                                    <div class="pb-4">
                                        <a href="preview.php/<?php echo $postId?>" target="_blank" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-md text-sm px-5 py-2.5 me-2 my-4">Preview</a>
                                    </div>
                                    <div class="block mb-5">
                                        <label for="postID" class="block mb-1 text-sm font-medium text-gray-900">Post ID</label>
                                        <input type="text" name="post_id" id="post_id" class="mb-6 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="<?php echo $postId?>" readonly>
                                    </div>
                                </div>
                                <div class="flex space-x-3 items-center mt-4">
                                    <label for="status" class="block mb-1 text-sm font-medium text-gray-900">Status</label>
                                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:outline-none block px-3 py-1">
                                        <?php
                                            $statusOptions = ["draft", "review", "active"];
                                            foreach ($statusOptions as $option) {
                                                echo "<option value=\"$option\"" . ($option == $postStatus ? ' selected' : '') . ">$option</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <h2 class="text-base font-medium text-gray-950 pt-2">Category</h2>
                                </div>
                                <div class="mt-3">
                                    <select  name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:outline-none block px-3 py-1 w-full">
                                        <?php
                                            if(mysqli_num_rows($catData)>0){
                                                while($data = mysqli_fetch_assoc($catData)){
                                                    ?>
                                                    <option value="<?php echo $data['category_name'];?>" <?php echo ($data['category_name'] == $postCategory) ? 'selected' : ''; ?>><?php echo $data['category_name'];?></option>
                                                    <?php
                                                }
                                            }else{
                                                echo $data = "No category";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Tag</h2>
                                </div>
                                <div class="mt-3">
                                    <textarea type="text" name="tag" onkeyup="toLower(event)" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Add Tag Here..."><?php echo  $postTag ; ?></textarea>
                                </div>
                                <div>
                                    <div class="pt-3">
                                        <h2 class="text-base font-medium text-gray-950">Quick Info</h2>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo1" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none"><?php echo $postInfo1?></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo2" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none"><?php echo $postInfo2?></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo3" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none"><?php echo $postInfo3?></textarea>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Featured Image</h2>
                                </div>
                                <div class="mt-3">
                                    <input type="text" name="featured_img" id="featuredImage" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" value="<?php echo  $postFeaturedImg ; ?>" placeholder="Add Image Link Here...">
                                    <textarea type="text" name="featured_img_alt" class="mt-3 text-sm w-full py-2 px-2 border border-gray-300 outline-none"><?php echo $postFeaturedImgAlt?></textarea>
                                    <div class="py-3">
                                        <img id="featuredImagePreview" src="<?php echo $postFeaturedImg; ?>" alt="Featured Image">
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Add Meta Description</h2>
                                </div>
                                <div class="mt-3">
                                    <textarea type="text" name="meta_desc" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none"><?php echo $postMetaDesc?></textarea>
                                </div>
                            </div>
                        </aside>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    
    window.addEventListener("beforeunload", function(event) {
        return "Did you save your stuff?"
    });
    </script>

    <script>
    // Get references to the input and img elements
    var featuredImageInput = document.getElementById('featuredImage');
    var featuredImagePreview = document.getElementById('featuredImagePreview');

    featuredImageInput.addEventListener('input', function () {
        featuredImagePreview.src = featuredImageInput.value;
    });

    // For rewriting slug into lower case and adding '-'
    document.getElementById('slug-input').addEventListener('input', function () {
        const inputValue = this.value.trim().toLowerCase();
        const slugValue = inputValue.replace(/\s+/g, '-');
        this.value = slugValue;
    });

    // Add a separate event listener for the space key
    document.getElementById('slug-input').addEventListener('keyup', function (event) {
        if (event.key === ' ') {
            // Replace space with hyphen
            const inputValue = this.value.trim().toLowerCase();
            if (!inputValue.endsWith(' ')) {
                this.value = inputValue.replace(/\s+/g, '-') + ' ';
            }
        }
    });

    //convert to lower case
    function toLower(e){
        setTimeout(function(){
            let v = e.target.value.toLowerCase();
        e.target.value = v;
        },100);
    }

    // Prevent form for resubmission on page reload
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    
    </script>

     <!--
            The "super-build" of CKEditor&nbsp;5 served via CDN contains a large set of plugins and multiple editor types.
            See https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/quick-start.html#running-a-full-featured-editor-from-cdn
        -->
        <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/super-build/ckeditor.js"></script>
        <!--
            Uncomment to load the Spanish translation
            <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/super-build/translations/es.js"></script>
        -->
        <script>
            // This sample still does not showcase all CKEditor&nbsp;5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                placeholder: 'Welcome to CKEditor&nbsp;5!',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22, 24, 26, 28, 30 ],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        },
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Open in a new tab',
                            defaultValue: true,	
                            attributes: {
                                target: '_blank',
                                rel: 'noopener'
                            }
                        }
                    }
                },
                mediaEmbed: {
                    previewsInData: true,
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    'ExportPdf',
                    'ExportWord',
                    'AIAssistant',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                    'MathType',
                    // The following features are part of the Productivity Pack and require additional license.
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced'
                ]
            })
            .then(instance => {
                editor = instance;
            })
            .catch(error => {
                console.error(error);
            });
        </script>
        <script>
        // for auto save
        function autosave() {
                if (editor) {
                    var content = editor.getData();
                    
                    var formData = $('#autosave-editform').serialize() + '&input=' + encodeURIComponent(content);

                    $.ajax({
                        type: 'POST',
                        url: 'save_scripts/update.php',
                        data: formData,
                        success: function (response) {
                            console.log('Autosave successful:', response);
                        },
                        error: function (error) {
                            console.error('Error during autosave:', error);
                        }
                    });
                } else {
                    console.error('CKEditor instance not found.');
                }
            }

        // Timer
        setInterval(function() {
            autosave();
        }, 60000);
        </script>

</body>
</html>