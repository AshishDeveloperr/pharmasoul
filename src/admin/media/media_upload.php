<?php
include '../../auth/session_config.php';

include '../alert/alert_success.php';
include '../alert/alert_danger.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES['myfile'])) {
        $filename = $_FILES['myfile']['name'];
        $tempname = $_FILES['myfile']['tmp_name'];
        $destination = '../../uploads/' . $filename;
        $mediaPath = '/src/uploads/' . $filename;

        if (empty($filename)) {
            echo "No file selected.";
        } else {
            // Database Connection
            include '../../config/db_connect.php';

            // Check if the filename already exists in the database
            $checkQuery = "SELECT COUNT(*) as count FROM uploaded_files WHERE filename = '$filename'";
            $checkResult = $conn->query($checkQuery);
            $row = $checkResult->fetch_assoc();

            if ($row['count'] > 0) {
                $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error: File with the same name already exists. Please Rename it!</div>', $errorAlert);
                echo $errorAlert;
            } else {
                // Move uploaded file to destination
                if (move_uploaded_file($tempname, $destination)) {
                    // Get file size and its format
                    $filesize = filesize($destination);
                    $formattedSize = formatFileSize($filesize);

                    // Insert into Database
                    $sql = "INSERT INTO uploaded_files (filename, filepath, filesize) VALUES ('$filename', '$mediaPath', '$formattedSize')";

                    if ($conn->query($sql) === TRUE) {
                        $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">File uploaded successfully.</div>', $successAlert);
                        echo $successAlert;
                    } else {
                        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error file not uploaded.</div>', $errorAlert);
                        echo $errorAlert;
                    }
                } else {
                    $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error uploading file. Check the server logs for more information.</div>', $errorAlert);
                    echo $errorAlert;
                }
            }

            $conn->close();
        }
    } else {
        echo "No files selected.";
    }
}

function formatFileSize($size) {
    if ($size < 1024) {
        return $size . ' B';
    } elseif ($size < 1024 * 1024) {
        return round($size / 1024, 2) . ' KB';
    } else {
        return round($size / (1024 * 1024), 2) . ' MB';
    }
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
    <style>
        /* for img upload */
        .hasImage:hover section {
            background-color: rgba(5, 5, 5, 0.4);
        }
        .hasImage:hover button:hover {
            background: rgba(5, 5, 5, 0.45);
        }

        #overlay p,
        i {
            opacity: 0;
        }

        #overlay.draggedover {
            background-color: rgba(255, 255, 255, 0.7);
        }
        #overlay.draggedover p,
        #overlay.draggedover i {
            opacity: 1;
        }

        .group:hover .group-hover\:text-blue-800 {
            color: #2b6cb0;
        }
      </style>
</head>
<body>
    <!-- HEADER STARTS-->
    <?php include '../component/header.php';?>
    <!-- HEADER ENDS -->
    <section>
        <div class="grid grid-cols-7">
            <aside class="col-span-1 bg-slate-200">
                <div class="h-screen fixed px-4 bg-slate-200 pt-14">
                    <ul class="space-y-3 text-primary-gray list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <!-- <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-primary-gray text-base font-normal py-1 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-primary-gray pt-0.5 z-30 w-full text-base font-normal">
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
                                <li><a href="media.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Media</a></li>
                                <li><a href="media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Upload Media</a></li>
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
                <div class="pt-16 pb-4 px-8 flex justify-center">
                    <!-- component starts -->
                    <form action="#" method="POST" enctype="multipart/form-data" id="uploadForm" class="w-full">
                        <div>
                            <label
                                class="flex justify-center w-full h-60 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none"
                                ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">
                                <span class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="font-medium text-gray-600 file-label">
                                        Drop files to Attach, or
                                        <span class="text-blue-600 underline">browse</span>
                                    </span>
                                </span>
                                <input type="file" name="myfile" class="hidden" id="fileInput" required>
                            </label>
                        </div>
                        <button type="submit" class="mt-4 inline-flex items-center py-2.5 px-5 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                            Upload Files
                        </button>
                    </form>
                </div>
                <!-- Add a container to display file previews -->
                <div class="py-2 px-8">
                    <h2 class="text-base text-gray-700 font-semibold py-4">To Upload</h2>
                    <div id="filePreviewContainer"></div>
                </div>
                <!-- component ends -->
            </div>
        </div>
    </section>    
</body>
<script>
    const dropHandler = (event) => {
        event.preventDefault();

        const fileInput = document.getElementById('fileInput');
        const files = event.dataTransfer.files;
        // console.log(fileInput);

        handleFiles(files);

        // Manually trigger the change event for the file input
        fileInput.files = files;
        const changeEvent = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(changeEvent);
        };

        const dragOverHandler = (event) => {
            event.preventDefault();

            const label = document.querySelector('.file-label');
            label.textContent = 'Release to Upload';

        // Add visual feedback for drag over if needed
    };

    const dragLeaveHandler = (event) => {
        // Remove visual feedback for drag leave if needed
        const label = document.querySelector('.file-label');
        label.textContent = 'Drop files to Attach, or browse';
    };

    const handleFiles = (files) => {
        // Handle the dropped files here
        console.log(files);

        const filePreviewContainer = document.getElementById('filePreviewContainer');

        // Clear existing previews
        filePreviewContainer.innerHTML = '';

        // Iterate over each file and create a preview
        Array.from(files).forEach(file => {
            const preview = createFilePreview(file);
            filePreviewContainer.className = 'bg-gray-100 max-w-sm p-4 rounded-md shadow-sm';
            filePreviewContainer.appendChild(preview);
        });
    };


    const createFilePreview = (file) => {
    const preview = document.createElement('div');
    preview.className = 'file-preview';

    // Check if image
    if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = file.name;
        img.className = 'h-40 object-contain rounded-md my-2 text-gray-800 previewImgSrc';

        // Append image preview
        preview.appendChild(img);
    }

    // Create elements for file details
    const fileName = document.createElement('p');
    fileName.className = 'text-xs font-medium my-1 text-gray-800';
    fileName.textContent = "Name: " + file.name;
    
    const fileSize = document.createElement('p');
    fileSize.className = 'text-xs font-medium my-1 text-gray-800';
    fileSize.textContent = "Size: " + formatFileSize(file.size);

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'py-1 px-4 bg-red-600 hover:bg-red-700 text-white text-sm rounded-md my-1';
    deleteButton.addEventListener('click', () => {
        const fileInput = document.getElementById('fileInput');
        fileInput.value = '';

        preview.remove();
    });

    // Append elements to the preview
    preview.appendChild(fileName);
    preview.appendChild(fileSize);
    preview.appendChild(deleteButton);

    // Show the preview on hover
    preview.addEventListener('mouseenter', () => {
        preview.style.opacity = '1';
    });

    // Hide the preview when not hovering
    preview.addEventListener('mouseleave', () => {
        preview.style.opacity = '1';
    });

    return preview;
    };

    const formatFileSize = (size) => {
        if (size < 1024) {
            return size + ' B';
        } else if (size < 1024 * 1024) {
            return (size / 1024).toFixed(2) + ' KB';
        } else {
            return (size / (1024 * 1024)).toFixed(2) + ' MB';
        }
    };

    // Add event listener for file input change
    const fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        handleFiles(files);
    });

</script>
<script src="../alert/alert_dismiss.js"></script>
</html>