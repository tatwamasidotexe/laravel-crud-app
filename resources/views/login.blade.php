<?php
    // session_start();

    // if(isset($_SESSION['u_id'])) {
    //     header("Location: ../dashboard/");
    //     exit;
    // }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Login</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/tabicon.png') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        
        <!-- FONT LINKS -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        
        <!-- BOOTSTRAP CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body style="background-image: url({{asset('assets/images/bg-trippy.jpg')}})">
        <div class="container-fluid mx-0 py-5">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <h1 class="fw-bolder text-light text-center textShadow"></h1>
                    <div class="card mx-auto mt-5 bg-transparent" style="max-width: 500px;">
                        <div class="card-header bg-light">
                            <h5 class="card-title m-1 FW-BOLD text-dark">USER LOGIN</h5>
                        </div>

                        <div class="card-body bg-transparent">
                            <form id="loginForm" action="" method="POST" class="text-light needs-validation" novalidate >
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                                    <div class="invalid-feedback">Please enter email address.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" id="loginPassword" required>
                                    <div class="invalid-feedback">Please enter password.</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                            <p class="mt-3 text-light" style="text-shadow: 3px 3px 7px rgba(0, 0, 0, 0.21);">New user? <a href="../signup/" class="signup-link text-light">Sign up here.</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jquery"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        <script type="text/javascript" src="{{ asset('assets/js/loginScript.js') }}"></script>

    </body>
</html>