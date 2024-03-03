<?php
include '../../auth/session_config.php';

include '../../config/db_connect.php'; 

include '../alert/alert_success.php';
include '../alert/alert_danger.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');


$post_id = '';
$sql = "SELECT * FROM `posts` ORDER BY sno DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result) {
    
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $post_id = $row['sno'];
    } else {
        echo "No rows found in the 'posts' table";
    }

    mysqli_free_result($result);
} else {
    echo "Error executing query: " . mysqli_error($conn);
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buttonType'])) {
    
    $buttonType = $_POST['buttonType'];  
    // Get input data
    $author =  $_SESSION['username']; 
    $category =  $_POST['category'];
    $tag =  $_POST['tag'];
    $content = $_POST['input'];
    $featured_img = $_POST['featured_img'];
    $featured_img_alt = $_POST['featured_img_alt'];
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $slugOld = '';
    $quickInfo1 = $_POST['quickInfo1'];
    $quickInfo2 = $_POST['quickInfo2'];
    $quickInfo3 = $_POST['quickInfo3'];
    $postMetaDesc = $_POST['post_meta_desc'];
    $postId = $row['post_id'];

    if ($buttonType == 'save') {
        $status = $_POST['status'];
    }elseif ($buttonType == 'save_draft') {
        $status = 'draft';
    }


    // Get the current Indian time
    $indianTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $currentIndianTime = $indianTime->format('Y-m-d H:i:s');

    $sql = "UPDATE `posts` SET `post_author`=?, `post_category`=?, `post_tag`=?, `post_content`=?, `post_featured_img`=?, `post_featured_img_alt`=?, `post_title`=?, `post_status`=?, `post_slug`=?, `post_slug_old`=?, `post_info1`=?, `post_info2`=?, `post_info3`=?, `post_meta_desc`=?, `post_modified`=? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sssssssssssssssi", $author, $category, $tag, $content, $featured_img, $featured_img_alt, $title, $status, $slug, $slugOld, $quickInfo1, $quickInfo2, $quickInfo3, $postMetaDesc, $currentIndianTime, $post_id);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Post added successfully'?>
        <script>
            alert("Post Added");
            window.location.href = 'edit.php?editId=<?php echo $postId; ?>';
        </script>
        <?php
    } else {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Post not added.</div>', $errorAlert);
        echo $errorAlert;
    }

    $stmt->close();

}
//category
$sql = "SELECT * FROM `categories`";
$catData = mysqli_query($conn,$sql);


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
<body class="bg-gray-100">
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
                <form method="POST" id="autosave-form">
                    <div class="grid grid-cols-6">
                        <div class="col-span-5">
                            <div class="py-10 px-6">
                                <div class="mb-4">
                                    <input name="title" class="w-full py-3 px-2 border border-gray-300 outline-none" value="Add Title Here..." required>
                                </div>
                                <div class="mb-4 flex items-center">
                                    <span class="text-sm font-medium text-gray-900 pr-1">Permalink: </span>
                                    <input name="slug" id="slug-input" class="text-sm w-full py-1 px-2 border border-gray-300 outline-none" placeholder="Add Permalink Here..." required>
                                </div>
                                <textarea id="editor" name="input">
                                    <figure class="table"><table><tbody><tr><td>Profession</td><td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae suscipit nostrum commodi explicabo non amet esse consequatur,&nbsp;</td></tr><tr><td colspan="2"><h2>Physical Stats &amp; More</h2></td></tr><tr><td>Height (approx.)</td><td>&nbsp;</td></tr><tr><td>Weight (approx.)</td><td>&nbsp;</td></tr><tr><td>Eye Colour</td><td>&nbsp;</td></tr><tr><td>Hair Colour</td><td>&nbsp;</td></tr><tr><td colspan="2"><h2>Career</h2></td></tr><tr><td>Awards &amp; Honours</td><td>&nbsp;</td></tr><tr><td colspan="2"><h2>Personal Life</h2></td></tr><tr><td>Date of Birth</td><td>&nbsp;</td></tr><tr><td>Age (as of 2023)</td><td>&nbsp;</td></tr><tr><td>Birthplace</td><td>&nbsp;</td></tr><tr><td>Zodiac sign</td><td>&nbsp;</td></tr><tr><td>Nationality</td><td>&nbsp;</td></tr><tr><td>Hometown</td><td>&nbsp;</td></tr><tr><td>School</td><td>&nbsp;</td></tr><tr><td>College/University</td><td>&nbsp;</td></tr><tr><td>Educational Qualification(s)</td><td>&nbsp;</td></tr><tr><td>Religion</td><td>&nbsp;</td></tr><tr><td>Food Habit</td><td>&nbsp;</td></tr><tr><td>Address</td><td>&nbsp;</td></tr><tr><td>Tattoo</td><td>&nbsp;</td></tr><tr><td>Controversies</td><td>&nbsp;</td></tr><tr><td colspan="2"><h2>Relationships &amp; More</h2></td></tr><tr><td>Marital Status</td><td>&nbsp;</td></tr><tr><td>Marriage Date</td><td>&nbsp;</td></tr><tr><td colspan="2"><h2>Family</h2></td></tr><tr><td>Wife/Spouse</td><td>&nbsp;</td></tr><tr><td>Children</td><td>&nbsp;</td></tr><tr><td>Parents</td><td>&nbsp;</td></tr><tr><td colspan="2"><h2>Style Quotient</h2></td></tr><tr><td>Car Collection</td><td>&nbsp;</td></tr></tbody></table></figure><p>&nbsp;</p><h2>Some Lesser Known Facts About Cyriac Abby Philips</h2><ul style="list-style-type:disc"><li>Lorem ipsum dolor sit amet consectetur adipisicing elit.&nbsp;</li><li>Lorem ipsum dolor sit amet consectetur adipisicing elit.&nbsp;</li></ul><p>&nbsp;</p>
                                </textarea>
                            </div>
                        </div>
                        <aside class="col-span-1 pl-2 pr-4 py-8">
                            <div class="px-4 py-4 border border-gray-300 rounded-md bg-white">
                                <div class="border-b border-gray-300 pb-2">
                                    <h2 class="text-base font-medium text-gray-950">Publish</h2>
                                    <button type="submit" name="buttonType" value="save" class="text-sm text-white bg-blue-700 hover:bg-blue-800 px-6 py-2 rounded-md mt-2 block">Publish</button>
                                    <button type="submit" name="buttonType" value="save_draft" class="text-sm text-white bg-indigo-700 hover:bg-indigo-800 px-3 py-2 rounded-md mb-4 mt-2 block">Save as draft</button>
                                    <div class="block mb-5">
                                        <a href="preview.php/<?php echo $post_id + 1?>" target="_blank" class="text-sm text-white bg-slate-800 hover:bg-slate-900 px-3 py-2 rounded-md my-4">Preview</a>
                                    </div>
                                    <div class="block mb-5">
                                        <label for="postID" class="block mb-1 text-sm font-medium text-gray-900">Post ID</label>
                                        <input type="text" name="post_id" class="mb-6 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="<?php echo $post_id + 1?>" readonly>
                                    </div>
                                </div>
                                <div class="flex space-x-3 items-center mt-4">
                                    <label for="status" class="block mb-1 text-sm font-medium text-gray-900">Status</label>
                                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:outline-none block px-3 py-1">
                                        <option selected>draft</option>
                                        <option value="active">active</option>
                                        <option value="review">review</option>
                                    </select>
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Category</h2>
                                </div>
                                <div class="mt-3">
                                    <select  name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:outline-none block px-3 py-1 w-full">
                                        <?php
                                            if(mysqli_num_rows($catData)>0){
                                                while($data = mysqli_fetch_assoc($catData)){
                                                    ?>
                                                    <option value="<?php echo $data['category_name'];?>"><?php echo $data['category_name'];?></option>
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
                                    <textarea type="text" name="tag" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Add Tag Here..."></textarea>
                                </div>
                                <div>
                                    <div class="pt-3">
                                        <h2 class="text-base font-medium text-gray-950">Quick Info</h2>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo1" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Quick info 1"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo2" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Quick info 2"></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <textarea type="text" name="quickInfo3" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Quick info 3"></textarea>
                                    </div>
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Featured Image</h2>
                                </div>
                                <div class="mt-3">
                                    <input type="text" name="featured_img" id="featuredImage" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Add Image Link Here...">
                                    <textarea type="text" name="featured_img_alt" class="mt-3 text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Featured img alt"></textarea>
                                </div>
                                <div class="py-3">
                                    <img id="featuredImagePreview" src="https://placehold.co/400" alt="Featured Image">
                                </div>
                                <div class="pt-3">
                                    <h2 class="text-base font-medium text-gray-950">Add Meta Description</h2>
                                </div>
                                <div class="mt-3">
                                    <textarea type="text" name="post_meta_desc" class="text-sm w-full py-2 px-2 border border-gray-300 outline-none" placeholder="Meta description"></textarea>
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

        // rewriting slug into lower case and adding '-'
        document.getElementById('slug-input').addEventListener('input', function () {
            const inputValue = this.value.trim().toLowerCase();
            const slugValue = inputValue.replace(/\s+/g, '-');
            this.value = slugValue;
        });

        // event listner for space 
        document.getElementById('slug-input').addEventListener('keyup', function (event) {
            if (event.key === ' ') {
                // Adding - sign
                const inputValue = this.value.trim().toLowerCase();
                if (!inputValue.endsWith(' ')) {
                    this.value = inputValue.replace(/\s+/g, '-') + ' ';
                }
            }
        });
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
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                toolbar: {
                    items: [  
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
                mediaEmbed: {
                    previewsInData: true,
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
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
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
            function dismissAlert(alertId) {
                const alertElement = document.getElementById(alertId);

                if (alertElement) {
                    alertElement.classList.add('dismiss-transition');
                    alertElement.style.opacity = 0;
                    setTimeout(() => {
                        alertElement.style.display = "none";
                    }, 300); 
                }
            }
            setTimeout(function () {
                const alertElement = document.getElementById("toast-alert");

                alertElement.classList.add('dismiss-transition');
                alertElement.style.opacity = 0;
                setTimeout(() => {
                    alertElement.style.display = "none";
                }, 300); 
            }, 5000);
        </script>

        <script>

        function autosave() {
            if (editor) {
                var content = editor.getData();
                var formData = $('#autosave-form').serialize() + '&input=' + encodeURIComponent(content);

                $.ajax({
                    type: 'POST',
                    url: 'save_scripts/save_new.php',
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

        // Initial call after 15 sec
        var intervalId = setInterval(function() {
            autosave();
            clearInterval(intervalId);
            
            setInterval(function() {
                autosave();
            }, 60000);
            
        }, 15000);

        </script>
</body>
</html>