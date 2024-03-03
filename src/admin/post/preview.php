<?php
include '../../config/db_connect.php'; 

$slug = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

if ($slug == '' || $slug == 'home') {
    echo "post not found";
} else {
    $stmt = $conn->prepare("SELECT * FROM `posts` WHERE `post_id` = ? AND post_status != 'trash'");

    $stmt->bind_param("i", $slug);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $postTitle = $row['post_title'];
        $quickInfo1 = $row['post_info1'];
        $quickInfo2 = $row['post_info2'];
        $quickInfo3 = $row['post_info3'];
        $postContent = $row['post_content'];
        $postImg = $row['post_featured_img'];
        $postCategory = $row['post_category'];

    } else {
        echo "post not found";
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="http://localhost/blog/">

    <link rel="stylesheet" type="text/css" href="src/css/output.css">
    <meta name="robots" content="noindex" />
    <!-- <link rel="stylesheet" type="text/css" href="src/css/blog/blog.min.css"> -->
    <!-- Font -->
    <script>
        setTimeout(function() {
            var fontlink = document.createElement('link');
            fontlink.rel = 'stylesheet';
            fontlink.type = 'text/css';
            fontlink.href = 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap';
            document.head.appendChild(fontlink);
        }, 1500); 
    </script>
    
    <title><?php echo $postTitle;?></title>
    <style>
        *{
          font-family: 'Poppins', sans-serif;
          margin: 0;
          padding: 0;
        }
        body{
            background: #f1eee5;
        }

        /* table style */        
        tr{
            background: white;
        }

        td ul{
            padding-left:30px;
        }

        td{
            padding-left:10px;
            padding-right:10px
        }

        td{
            padding-top:5px;
            padding-bottom:8px
        }

        td img{
            max-width:75% !important;
        }

        td p{ 
            padding-bottom: 10px;
        }
        td:first-child:not([colspan="2"]) {
            display: flex;
        }
        tr:nth-child(even) { 
            background-color: #f9fafb; 
        }
        tr:hover{
            background-color: #f5f6f7; 
        }
        /* Heading in table */
        td h2{
            font-size: 20px;
            color: #ff7a00;
            font-weight: 600;
        }
        /* image after table */
        figure.image{
            width:80%;
            padding: 8px;
            border: 0.6px solid #bdbbbb69;
            border-radius: 3px;
        }
        .image img{
            max-width: 100%;
        } 
        figcaption{
            display: inline;
            padding-top:3px;
        }
        /* image inside table  */
        .table .image{
            margin: 15px 0px;
        }
        .table figure.image{
            width: 100% !important;
            padding: 0px;
            border: none;
            border-radius: 0px;
        }
        ul{
            padding-left: 25px;
        }
        @media screen and (min-width: 768px) {
            td img{
                max-width:35% !important;
            }
            .table .image{
                margin: 5px 0px;
            }
        }
        
        td:first-child:not([colspan="2"]) {
            display: flex;
        }

        td[colspan="2"] h1,
        td[colspan="2"] h2,
        td[colspan="2"] h3,
        td[colspan="2"] h4,
        td[colspan="2"] h5,
        td[colspan="2"] h6,
        h1,h2,h3,h4,h5,h6{
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            color:#155e75;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
        td ul{
            list-style-type: disc;
        }

        /* footnotes */
        .footnote_text {
            display: none;
            position: absolute;
            font-size: 13px;
            background-color: #ecfeff;
            color: #155e75;
            padding: 7px 13px 7px 13px;
            border: 1px solid #155e75;
            border-radius:3px;
            z-index: 1;
            top: -28px;
            left: 23px;
            width:max-content;
            box-shadow: 2px 2px 11px #666666;
            -webkit-box-shadow: 2px 2px 11px #666666;
            -moz-box-shadow: 2px 2px 11px #666666;
        }

        .footnote_sup:hover ~ .footnote_text {
            display: block;
        }
        .footnote_parent:hover ~ .footnote_text {
            display: block;
        }
        .footnote_text:hover{
            display: block;
        }
        .footnote_sup{
            color:#155e75;
            font-weight:700;
            font-size:14px;
        }
        .footnote_parent{
            position: relative;
        }
        /* Media lightbox */
        .dismiss-transition {
            transition: opacity 0.3s ease-out; 
        }

        /* Translate style */
        .goog-te-combo{
            border: 1px solid #d6dadf;
            padding: 15px 18px;
            font-size: 18px;
            width: 100%;
            border-radius: 8px;
            color: #b6b7ba;
            font-weight: 500;
        }
        a.VIpgJd-ZVi9od-l4eHX-hSRGPd{
            display: flex;
            align-items: center;
        }
        a.VIpgJd-ZVi9od-l4eHX-hSRGPd img{
            height: 20px;
            width: 45px;
            padding-top: 2px;
        }
      </style>
</head>
<body class="text-gray-800">
    <!-- Header starts -->
    <header class="bg-cyan-900">
        <div class="container mx-auto px-1 md:px-52">
            <div class="grid grid-cols-4">
                <div class="col-span-1">
                    <a class="w-44" href="/blog">
                        <img src="https://medbiography.com/src/uploads/medbiography.webp" alt="MedBiography" class="w-36">
                    </a>
                </div>
                <div class="col-span-3">
                    
                </div>
            </div>
        </div>
    </header>
    
    <!-- Header ends -->
    <div class="container mx-auto md:px-5 lg:px-24 xl:px-24 2xl:px-52">
        <div class="grid grid-cols-3 bg-white">
            <article class="col-span-3 lg:col-span-2 px-4 pt-5 pb-10">
                <div class="title pb-2 h-28 md:h-20">
                    <h1 class="text-xl font-medium text-gray-800 pb-3"><?php echo (isset($postTitle) ? $postTitle : ''); ?></h1>
                </div>
                <!---------------- Quick info block ------------------->
                <div class="quickInfo bg-cyan-900 p-2 h-32 md:h-24">
                    <div class="grid grid-cols-4">
                        <div class="col-span-4 md:col-span-1 md:border-r-2 px-3 pb-1">
                            <h1 class="text-lg md:text-base font-medium text-white">Quick Info <span class="text-xl">&#8594;</span></h1>
                        </div>
                        <div class="col-span-4 md:col-span-1 md:border-r-2 px-3">
                            <h1 class="text-base font-semibold text-white"><?php echo (isset($quickInfo1) ? $quickInfo1 : ''); ?></h1>
                        </div>
                        <div class="col-span-4 md:col-span-1 md:border-r-2 px-3">
                            <h1 class="text-base font-semibold text-white"><?php echo (isset($quickInfo2) ? $quickInfo2 : ''); ?></h1>
                        </div>
                        <div class="col-span-4 md:col-span-1 px-3">
                            <h1 class="text-base font-semibold text-white"><?php echo (isset($quickInfo3) ? $quickInfo3 : ''); ?></h1>
                        </div>
                    </div>
                </div>
                <div class="post">
                    <!-------------- Featured image block ------------->
                    <div class="featured-img relative">
                        <div role="status" class="flex justify-center items-center pt-8 pb-5">
                            <div class="flex items-center justify-center w-full h-80 bg-gray-300 rounded sm:w-96">
                                <svg class="w-40 h-40 absolute z-10 text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                </svg>
                                <img src="<?php echo $postImg;?>" alt="" class="h-full w-full object-cover z-20" loading="lazy">
                            </div>
                        </div>
                    </div>
                    <!-------------- Ads block -------------->
                    <div class="adsblock py-4">
                        <span class="text-xs text-gray-500">Advertisement</span>
                        <div role="status" class="animate-pulse flex justify-center items-center">
                            <div class="flex items-center justify-center w-full h-60  bg-gray-300 rounded sm:w-96 md:w-full">
                                <svg class="w-36 h-36 text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!----------- Main article block ----------->
                    <div class="main_article">
                        <div class="relative">
                            <h2 class="text-xl font-semibold px-2 text-cyan-900 py-3 bg-[#d9edf7] mb-3">Bio/Wiki</h2>
                            <!-- table starts here -->
                            <?php echo (isset($postContent) ? $postContent : ''); ?>
                            <!-- table ends here -->
                        </div>
                    </div>
                </div>
            </article>
            <aside class="col-span-3 lg:col-span-1 bg-[#f1eee5]">
                <div class="px-4 pb-10 shadow-md shadow-gray-300 bg-white">
                    <div class="adsblock py-4">
                        <span class="text-xs text-gray-500">Advertisementyyy</span>
                        <div role="status" class="animate-pulse flex justify-center items-center">
                            <div class="flex items-center justify-center w-full h-52 bg-gray-300 rounded">
                                <svg class="w-28 h-28 text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                    <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="search py-2">
                        <div class="relative">   
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" id="search_input" oninput="load_data(this.value)" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none" placeholder="Search something..." required>
                            </div>
                            <div id="search_preview" class="absolute z-50 w-full shadow-lg bg-gray-100 px-1 mt-1 rounded-md">
                                <!-- Search result will display here -->
                            </div>
                        </div>
                    </div>
                    <div class="google_translate py-6 h-40">
                        <div id="google_translate_element"></div>
                    </div>
                    <div id="related_post">
                        <h2 class="text-xl text-gray-800 font-medium py-2">Related Post</h2>
                            <?php
                                if (isset($postCategory)) {
                                    $sql = "SELECT * FROM posts WHERE post_category = '$postCategory' AND post_status = 'active' ORDER BY `sno` DESC";
                                }else{
                                    $sql = "SELECT * FROM posts WHERE post_status = 'active' AND post_status = 'active' ORDER BY `sno` DESC";
                                }
                                $result = $conn->query($sql);         
                                $counter = 0;           
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                            ?>                            
                        <div class="side_post py-4 border-b-2 border-dashed">
                            <div role="status" class="space-y-1 animate-pulse md:space-y-0 space-x-3 rtl:space-x-reverse flex ">
                                <a href="https://medbiography.com/<?php echo $row['post_slug'];?>" class="flex items-center justify-center w-2/3 md:w-1/2 h-24 md:h-28 lg:h-20 bg-gray-300 rounded">
                                    <img src="<?php echo $row['post_featured_img']?>" alt="" class="h-24 md:h-28 lg:h-20 w-full rounded object-cover">
                                </a>
                                <div class="w-full h-full hover:text-orange-500 transition-all duration-300 ease-out">
                                    <a href="https://medbiography.com/<?php echo $row['post_slug'];?>">
                                        <h1 class="text-base font-medium">
                                            <?php
                                                $max_length = 60;
                                                $post_title = $row['post_title'];
                                                if (strlen($post_title) > $max_length) {
                                                    $post_title = substr($post_title, 0, $max_length) . '...';
                                                } 
                                                echo $post_title;
                                            ?>
                                        </h1>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $counter++;

                            if ($counter == 4) {
                                break;
                            }
                        }}
                        $conn->close();?>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <footer class="bg-[#222222] mt-10">
        <div class="container mx-auto px-6 md:px-52 py-10">
            <div class="grid grid-cols-3">
                <div class="col-span-3 md:col-span-1">
                    <a href="https://medbiography.com/">
                        <img src="https://medbiography.com/src/uploads/medbiography.webp" alt="MedBiography" class="w-36">
                    </a>
                    <p class="text-sm text-gray-300 py-5">Follow us on our social media channels to stay connected. Report a problem? Email us at <a href="mailto:admin@medbiography.com">admin@medbiography.com</a></p>
                </div>
                <div class="col-span-3 md:col-span-1">
                    <div class="flex justify-start md:justify-center">
                        <img src="https://images.dmca.com/Badges/_dmca_premi_badge_2.png?ID=74b15546-bd32-47b1-903d-24c7a39e80c4" alt="MedBiography" class="w-20">
                    </div>
                    <p class="text-center py-4 px-2 text-sm text-gray-300">MedBiography Copyright &copy; 2023</p>
                </div>
                <div class="col-span-3 md:col-span-1">
                    <div class="px-4">
                        <ol class="list-disc text-sm text-gray-300">
                            <li class="pb-1">Privacy Policy</li>
                            <a href="contact.php">
                                <li class="pb-1">Contact</li>
                            </a>
                            <a href="about.php">
                                <li class="pb-1">About</li>
                            </a>
                            <li class="pb-1">Make Your Profile | PR | Advertising</li>
                        </ol>
                    </div>
                    <div class="py-2">
                        <h3 class="text-base font-medium text-gray-200">Socialize with MedBiography</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#111111]">
            <div class="container mx-auto px-6 md:px-52 py-4">
                <h2 class="text-base text-white font-medium">MedBiography <span class="text-gray-400">Copyright &copy; 2023.</span></h2>
            </div>
        </div>
    </footer>
     <!-- media lighthouse starts -->
     <div class="show overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex hidden">
        <div class="overlay w-full h-full fixed top-0 left-0 bg-black opacity-80"></div>
        <div class="img-show w-full md:w-1/3 h-3/4 absolute top-1/2 left-1/2 overflow-hidden transform -translate-x-1/2 -translate-y-1/4 md:-translate-y-1/2">
            <span class="p-1 rounded absolute top-3 right-5 md:right-2 lg:right-4 z-50 cursor-pointer">
                <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </span>
            <div class="w-full flex justify-center">
                <img src="" class="h-4/5 w-4/5 object-cover relative pt-3">
            </div>
            <div class="py-4 text-center">
                <h2 class="text-white popup_alt">Alternative Text</h2>
            </div>
        </div>
    </div>
    <!-- media lighthouse ends -->
</body>
<script>


// for footnotes starts
var elementsWithFootnote = document.querySelectorAll('a');
elementsWithFootnote.forEach(function(element, index) {
    var originalContent = element.innerHTML;

    var match = /\[footnote=(.*?)\]/.exec(originalContent);

    if (match) {
        element.classList.add("footnote_parent");
    }
});

var elementsWithFootnote = document.querySelectorAll('.footnote_parent');
var footnoteIndex = 0;
elementsWithFootnote.forEach(function(element, index) {
    var originalContent = element.innerHTML;

    var match = /\[footnote=(.*?)\]/.exec(originalContent);

    if (match) {
     
        var footnoteText = match[1];

        element.innerHTML = originalContent.replace(
            /\[footnote=(.*?)\]/,
            '<sup class="footnote_sup"><a id="#footnote' + (footnoteIndex + 1) + '" class="footnote_link">[' + (footnoteIndex + 1) + ']</a></sup><a class="footnote_text" href="#">' + footnoteText + '</a>'
        );
        footnoteIndex++;
    }
});


// Function to modify href attributes
function modifyHref() {
        var footnoteParents = document.querySelectorAll('.footnote_parent');

        footnoteParents.forEach(function(footnoteParent) {
            var originalHref = footnoteParent.getAttribute('href');

            footnoteParent.setAttribute('href', '#');

            var footnoteText = footnoteParent.querySelector('.footnote_text');

            footnoteText.setAttribute('href', originalHref);
        });
    }
    setTimeout(modifyHref, 4000);

// for footnotes ends


  


// search
function load_data(search=''){
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "src/search/search.php?search="+search, true);
    xhr.onload = function(){
        document.getElementById('search_preview').innerHTML = xhr.responseText;
    }
    xhr.send();
}

// skeleton effect
const allSkeleton = document.querySelectorAll('.animate-pulse');

window.addEventListener('load', function () {
    allSkeleton.forEach(item => {
        item.classList.remove('animate-pulse');
        console.log("removed");
    });
});




// Remove the "display" style property
var tdElement = document.querySelector('td[colspan="2"]');
tdElement.style.display = '';



// lighthouse
document.addEventListener('DOMContentLoaded', function() {
    "use strict";
    
    var popupImages = document.querySelectorAll('.image img');
    var show = document.querySelector('.show');
    var imgShow = document.querySelector('.img-show');
    var overlay = document.querySelector('.overlay');
    var imgShowImage = document.querySelector('.img-show img');
    var closeBtn = document.querySelector('.img-show span');
    var imgAlt = document.querySelector('.popup_alt');
    
    popupImages.forEach(function(img) {
        img.addEventListener('click', function() {
            var src = this.src;
            var altText = this.alt;
            imgShowImage.src = src;
            imgAlt.innerHTML = altText;
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


// google translate
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'},  'google_translate_element');
}
</script>
<script type="text/javascript">
    window.addEventListener('load', function() {
        setTimeout(function() {
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                document.head.appendChild(script);
            }, 5000);
        });
</script>
</html>