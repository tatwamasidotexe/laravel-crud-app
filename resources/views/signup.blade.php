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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Users: Sign up</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/tabicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <!-- FONT LINKS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FOMANTIC UI CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.semanticui.css">

    <!-- DATATABLE CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- selectize -->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
    integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    />

</head>
<body style="background-image: url({{asset('assets/images/bg-trippy.jpg')}}); background-size: cover; background-position: center;">
    <div class="container-fluid mx-0 pt-0" style="">
        <div class="row justify-content-center mt-5 px-2">
            <div class="col-md-11">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h1 class="fw-bolder text-light textShadow">USERS</h1>
                    </div>
                    <!-- buttons -->
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-block btn-round glass-btn col-2 open-uploadmodal-btn text-white px-2" style="">Upload</button>
                        <a type="button" class="btn btn-block btn-round glass-btn col-2 login-btn text-white" href="{{ route('login') }}">Log in</a>
                        <button type="button" class="btn btn-block btn-round glass-btn col-2 add-btn text-white" data-bs-toggle="modal" data-action="add" data-bs-target="#inputFormModal">Sign Up</button>
                    </div>
                </div>
                <div class="table-responsive glass-transparent datatable-card text-light">
                    <table id="myTable" class="ui celled table table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="hide-field"></th>
                                <th>Name</th>
                                <th class="hide-field"></th>
                                <th class="hide-field"></th>
                                <th>Email</th>
                                <th class="hide-field"></th>
                                <th class="hide-field"></th>
                                <th class="hide-field">State_id</th>
                                <th class="hide-field">Country_id</th>
                                <th>Hobbies</th>
                                <th>Image</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- add rows dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- INPUT FORM MODAL -->
                <div class="modal fade" id="inputFormModal" tabindex="-1" aria-labelledby="inputFormModalLabel" aria-hidden="true">
                    <div class="modal-dialog bg-transparent glass-blue fw-bold">
                        <div class="modal-content bg-transparent">
                            <div class="modal-header">
                                <h5 class="modal-title text-light" id="inputFormModalLabel">Enter student details</h5>
                                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="inputForm" action="" method="POST" class="needs-validation text-light" novalidate>
                                    @csrf
                                    <!-- NAME -->
                                    <div class="form-group my-2">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" name="username" id="name" placeholder="Enter name" required>
                                        <div class="invalid-feedback">Please fill out your name.</div>
                                    </div>
                
                                    <!-- DOB -->
                                    <div class="form-group my-2">
                                        <label for="dob">Date of Birth:</label>
                                        <input type="date" name="dob" class="form-control" id="dob" required>
                                        <div class="invalid-feedback">Please enter DOB</div>
                                    </div>
                
                                    <!-- PHONE NUMBER -->
                                    <div class="form-group my-2">
                                        <label for="phone">Phone Number:</label>
                                        <div class="input-group">
                                            <select id="countryCode" name="countryCode">
                                                <option value="+91">+91 (INDIA)</option>
                                                <option value="+1">+1 (US)</option>
                                                <!-- <option value="+44">+44 (UK)</option> -->
                                                <option value="+1">+1 (CANADA)</option>
                                                <option value="+977">+977 (NEPAL)</option>
                                                <option value="+33">+33 (FRANCE)</option>
                                            </select>
                                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter phone number" required pattern="\d{10}">
                                            <div class="invalid-feedback">Please enter a 10-digit number.</div>
                                        </div>
                                    </div>
                
                                    <!-- EMAIL -->
                                    <div class="form-group my-2">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                    
                                    <!-- ADDRESS -->
                                    <div class="form-group my-2">
                                        <label for="address">Address:</label>
                                        <textarea name="u_address" class="form-control" id="address" placeholder="Enter address" required></textarea>
                                        <div class="invalid-feedback">Please fill out this field.</div>
                                    </div>
                                    
                                    <!-- GENDER -->
                                    <div class="form-group my-2">
                                        <label>Gender:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                            <label class="form-check-label" for="female">Female</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                            <label class="form-check-label" for="other">Other</label>
                                        </div>
                                        <div class="invalid-feedback gender-feedback">Please select a gender.</div>
                                    </div>
                                    
                                    <!-- COUNTRY -->
                                    <div class="form-group my-2">
                                        <label for="country">Country:</label>
                                        <select name="country_id" class="form-control selectized" id="country" required>
                                        </select>
                                        <div class="invalid-feedback">Please select your country.</div>
                                        <input type="hidden" id='hiddenCountry'>
                                    </div>

                                    <!-- STATE -->
                                    <div class="form-group stateInput my-2">
                                        <label for="state">State:</label>
                                        <select name="state_id" class="form-control selectized" id="state" required></select>
                                        <div class="invalid-feedback">Please select your state/province.</div>
                                        <input type="hidden" id='hiddenState'>
                                    </div>
                                    
                                    <!-- HOBBIES -->
                                    <div class="form-group my-2">
                                        <label>Hobbies:</label><br>
                                        <div class="form-check">
                                            <input class="form-check-input" name="hobby" type="checkbox" value="reading" id="hobby-reading">
                                            <label class="form-check-label" for="hobby1">Reading</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="hobby" type="checkbox" value="gaming" id="hobby-gaming">
                                            <label class="form-check-label" for="hobby2">Gaming</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="hobby" type="checkbox" value="cooking" id="hobby-cooking">
                                            <label class="form-check-label" for="hobby3">Cooking</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="hobby" type="checkbox" value="traveling" id="hobby-traveling">
                                            <label class="form-check-label" for="hobby4">Traveling</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="hobby" type="checkbox" value="sports" id="hobby-sports">
                                            <label class="form-check-label" for="hobby5">Sports</label>
                                        </div>
                                        <div class="invalid-feedback hobbies-feedback" style="display: none;">Please select at least one hobby.</div>
                                    </div>    
                                    
                                    <!-- IMAGE UPLOAD -->
                                    <div class="form-group my-2">
                                        <label for="image">Upload image (.jpg, .jpeg, .png):</label>
                                        <p class="mb-0 text-light" id="imageFilename"></p>
                                        <input type="file" accept=".jpg, .jpeg, .png" class="form-control" name="image" id="image" required> 
                                        <div class="invalid-feedback file-size-feedback">Please upload an image.</div>
                                        <p class="mb-0 pb-0 text-light preview-text">Preview:</p>
                                        <img src="" alt="Preview Uploaded Image" id="img-preview" class="mt-0 pt-0"/>
                                    </div>

                                    <button type="submit" class="btn btn-primary bg-gradient btn-block btn-round mt-3 valid-data col-12">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Modal -->
                <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- <h5 class="modal-title" id="passwordModalLabel">Your Password</h5> -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Sign up successful! Please note that your password is set to the first three characters of your username, in lowercase, 
                                    followed by your dob in yyyymmdd format. No spaces.</br>For example, if your name is Smruti and dob is April 30, 2002, then your password
                                    is set to smr20010102.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success passwordModalBtn" id="passwordModalBtn" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPLOAD DATA MODAL -->
                <div class="modal fade" id="uploadDataModal" tabindex="-1" aria-labelledby="uploadDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog mx-auto" id="uploadModalDialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadDataModalLabel">Upload spreadsheet data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row px-2">
                                    <button type="button" class="btn btn-primary col-4" style="" id="downloadFormatBtn">Download Format</button>
                                    <!-- <button type="button" class="btn btn-secondary col-4" id="validateBtn">Validate</button> -->
                                    <form id="uploadForm" class="mt-3 col-12" enctype="multipart/form-data">
                                        @csrf
                                        <label for="excel" class="mt-3">Upload data sheet (.xlsx, .xls):</label>
                                        <input type="file" accept=".xlsx, .xls" class="form-control" name="excel" id="excel" required>
                                        <div class="my-2" id="invalidMessages"></div>
                                        <button type="submit" class="btn btn-success col-4 mt-3" id="uploadBtn">Validate</button>
                                    </form>

                                    

                                    <div class="table-responsive" id="excel-container">
                                        <table class="col-12 mt-3 table" id="excelDataTable">
                                            <thead class="thead-dark text-light">
                                                    
                                            </thead>
                                            <tbody>
                                                        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>

    <script src="{{ asset('assets/js/signupScript.js') }}"></script>

</body>
</html>