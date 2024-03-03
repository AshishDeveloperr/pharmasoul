<?php
include '../config/db_connect.php'; 

include '../admin/alert/alert_danger.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
  
    $sql = "SELECT * FROM users WHERE user_name='$username'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
  
    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['user_pass'] === md5($password)) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userrole'] = $row['user_role'];

                // Check if "Remember Me" is checked
                if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (10*60*60);
                }

                header("location: ../admin/user/users.php");
            } else {
                $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Incorrect username or password.</div>', $errorAlert);
                echo $errorAlert;
            }
        }
    } else {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Multiple Username.</div>', $errorAlert);
        echo $errorAlert;
    }
}

  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/output.css">

    <link rel="icon" href="../../img/ultramr.webp" type="image/x-icon">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <title>PharmaSoul</title>
    <style>
        *{
          font-family: 'Poppins', sans-serif;
          margin: 0;
          padding: 0;
        }

        .dismiss-transition {
        transition: opacity 0.3s ease-out;
    }
        </style>
</head>
<body>
    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                <img class="w-32" src="../img/ultramr.webp" alt="Pharmasoul">
            </a>
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="" method="POST">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Your username</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Username" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" id="password" placeholder="********" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" name="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300" required="">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500">Remember me</label>
                                </div>
                            </div>
                            <a href="#" class="text-sm font-medium text-amber-500 hover:text-amber-600 hover:underline">Forgot password?</a>
                        </div>
                        <button type="submit" class="w-full text-white bg-primary-orange hover:bg-primary-gray focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>
<!-- Alert script -->
<script src="../admin/alert/alert_dismiss.js"></script>
</html>