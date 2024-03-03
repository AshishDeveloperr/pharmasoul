
<aside class="col-span-1 bg-[#1d2327]">
                <div class="h-screen fixed px-4 bg-[#1d2327] pt-10">
                    <ul class="space-y-3 text-gray-200 list-inside text-base font-normal">
                        <li>
                            Dashboard
                        </li>
                    </ul>
                    <div class="menu_dropdown">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base font-normal py-2 rounded inline-flex items-center">
                                <span class="mr-1">Posts</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['media.php', 'media_upload.php']) ? '../post/posts.php' : (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post_new.php', 'edit.php']) ? 'posts.php' : '')); ?>" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Posts</a></li>
                                <li><a href="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['media.php', 'media_upload.php']) ? '../post/post_new.php' : (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post_new.php', 'edit.php']) ? 'post_new.php.php' : ''); ?>" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Add New Post</a></li>
                                <li><a href="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['media.php', 'media_upload.php']) ? '../category/categories.php' : (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post_new.php', 'edit.php']) ? '../category/categories.php' : ''); ?>" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Categories</a></li>
                                <li><a href="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['media.php', 'media_upload.php']) ? '../category/categories.php' :  (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post_new.php', 'edit.php']) ? '../category/categories.php' : ''); ?>" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Tags</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu_dropdown pb-2">
                        <div class="dropdown inline-block relative">
                            <button class="text-gray-200 text-base pb-1 font-normal  rounded inline-flex items-center">
                                <span class="mr-1">Media</span>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path> 
                                </svg>
                            </button>
                            <ul class="dropdown-menu relative hidden text-gray-200 pt-0.5 z-30 w-full text-base font-normal">
                                <li><a href="media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">All Media</a></li>
                                <li><a href="media_upload.php" class="py-0.5 px-2 block whitespace-no-wrap hover:text-blue-600">Upload Media</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>