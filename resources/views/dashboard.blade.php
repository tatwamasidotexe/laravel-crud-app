<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $userDetails->img_url }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <!-- FONT LINKS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/dashboardStyles.css') }}">

</head>
<body style="background-image: url({{asset('assets/images/bg-trippy-dark.jpg')}})">
    <div class="container-fluid d-flex align-items-center justify-content-center">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top glass-nav py-0 py-1">
            <div class="container align-items-center px-4" style="min-width: 100vw !important;">
                <a class="navbar-brand" href="#">
                    <img src="{{ $userDetails->img_url }}" alt="User Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"><span><?php echo " ". explode(" ", $userDetails->username)[0]?>'s Dashboard</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <button id="logOutBtn" class="nav-link textShadow-dark btn-block rounded-pill px-3 py-1 fw-bold bg-transparent border-0 text-light" type="button">
                                <img width="18" height="18" src="{{ asset('assets/images/dashboardIcons/exiticon.png') }}" alt="exit"/> Log out
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="section row landing-section justify-content-center align-items-center">
            <div class="col-md-11 justify-content-center py-auto textShadow-dark text-center">
                <img height="100px" width="100px" src="{{ $userDetails->img_url }}" alt="User Photo" class="my-3 rounded-circle" style="object-fit: cover;">
                <h1 class="fw-bolder text-light text-center" style="text-wrap: nowrap;">Hi, <span id="usernameHeader" style="text-wrap: nowrap;">{{ $userDetails->username }}</span>!</h1>
                <div class="row justify-content-center">
                    <button id="viewMoreButton" class="col-sm-5 mx-1 btn btn block rounded-pill glass-btn text-light fw-bold py-2 px-3 mt-2">View more</button>
                    <button id="exploreButton" class="col-sm-5 mx-1 btn btn block rounded-pill glass-btn text-light fw-bold py-2 px-3 mt-2">Explore</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content glass-modal text-white" style="background-image: url({{asset('assets/images/bg-trippy-dark.jpg')}})">
                    <!-- <div class="modal-header">
                        <h5 class="modal-title" id="viewMoreModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> -->
                    <div class="modal-body px-0">
                        <div class="container-fluid bg-transparent px-0 py-0">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn block rounded-pill glass-btn text-light fw-bold px-3" data-bs-dismiss="modal">Close</button>
                        <!-- You can add more buttons here if needed -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section row card-section py-5 text-light justify-content-center align-items-center text-center" id="scrollToSection">
            <h1 class="text-center fw-bold textShadow-dark mb-2 mt-4">TOP USERS</h1>
            <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel">
                <!-- <div class="carousel-indicators">
                    <button type="button" data-bs-target="#cardCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#cardCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div> -->
                <div class="carousel-inner">
                    <div class="carousel-item active slide1">
                        <div class="container">
                            <div class="row px-xs-3 px-md-5">
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slide2">
                        <div class="container">
                            <div class="row px-xs-3 px-md-5">
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slide3">
                        <div class="container">
                            <div class="row px-xs-3 px-md-5">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script type="text/javascript" src="{{ asset('assets/js/dashboardScript.js') }}"></script>

</body>
</html>