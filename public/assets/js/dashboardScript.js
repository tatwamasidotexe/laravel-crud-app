$(document).ready(function () {
    
    $('#logOutBtn').click(function() {
        logOut();
    });

    $('#exploreButton').click(function() {
        // Scroll to the section with smooth animation
        $('html, body').animate({
            scrollTop: $('#scrollToSection').offset().top
        }, 'slow');
    });

    $('#viewMoreButton').click(function() {

        $.ajax({
            type: "GET",
            url: "/dashboard/fetch",
            dataType: "json",
            success: function(response) {
                if(response.status !== 'error') {
                    if($(".modalUserDetails").attr('data-status') === 'displayed') {
                        return;
                    } else {
                        let userDetails = response;
                        console.log(userDetails);
                        
                        let hobbiesArray = userDetails.hobbies.split(',');
                        let hobbies = hobbiesArray.length > 1 ? hobbiesArray.slice(0, -1).join(', ') + ' and ' + hobbiesArray.slice(-1) : hobbiesArray[0];
                        var modalContent = `
                            <div class="row w-100 px-3 modalUserDetails" data-status='displayed'>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <img height='150px' width='150px' src="${userDetails.img_url}" alt="User Image" class="rounded img-fluid">
                                </div>
                                <div class="col-md-8 border-start border-white ">
                                    <ul class="list-group list-group-flush">
                                        <li id="modalUsername" class="list-group-item bg-transparent text-white"><h3>${userDetails.username}</h3></li>
                                        <li class="list-group-item bg-transparent text-white text-capitalize"><img src="${window.location.origin}/assets/images/dashboardIcons/country2-24x24.png">&ensp;${userDetails.gender}, ${userDetails.country_name}</li>
                                        <li class="list-group-item bg-transparent text-white"><img src="${window.location.origin}/assets/images/dashboardIcons/phone20x20.png">&ensp;${userDetails.phone_no}</li>
                                        <li class="list-group-item bg-transparent text-white"><img src="${window.location.origin}/assets/images/dashboardIcons/email20x20.png">&ensp;${userDetails.email}</li>
                                        <li class="list-group-item bg-transparent text-white"><img src="${window.location.origin}/assets/images/dashboardIcons/heart20x20.png">&ensp;Likes ${hobbies}</li>
                                    </ul>
                                </div>
                            </div>
                        `;

                        $(".modal-body .container-fluid").append(modalContent);
                    }

                } else {
                    console.log(response.status);
                    console.log(response.message);
                }                
            },
            error: function(xhr, status, error) {
                console.log(status);
                console.log("Error: " + error);
                console.log(xhr.responseText);
            }
        });
        $('#viewMoreModal').modal('show');
    });
    
    console.log(displayCards());
    // $('.card').on('click', function() {
    //     $(this).find('#userID').text;
    //     window.location('./userdetails.php?u_id=');
    // });
    
});

function displayCards() {
    $.ajax({
        type: "GET",
        url: "/fetch",
        dataType: "json",
        success: function(response) {
                
            let userDetails = response;

            userDetails.forEach(function(user, index) {
                let hobbiesArray = user.hobbies.split(',');
                let hobbies = hobbiesArray.length > 1 ? hobbiesArray.slice(0, -1).join(', ') + ' and ' + hobbiesArray.slice(-1) : hobbiesArray[0];
                var card = `
                    <div class="col-sm-4 mb-4">
                                <div class="card bg-transparent glass-card">
                                    <div id="userID" style='display: none;'>${user.u_id}</div>
                                    <img src="${user.img_url}" alt="User Photo" class="card-img-top rounded-top rounded-bottom" style="object-fit:cover; border-radius: 16px;">
                                    <div class="card-body text-dark">
                                        <h5 class="card-title text-light">${user.username}</h5>
                                            <ul class="card-text text-light card-detail-list">
                                                <li class="text-capitalize">${user.gender}, ${user.country_name}</li>
                                                <li>${user.email} | ${user.phone_no}</li>
                                                <li>&hearts; Likes <span class="text-lowercase">${hobbies}</span></li>
                                            </ul>
                                        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                    </div>
                                </div>
                            </div>
                `;

                // Append card to carousel item
                let slideIndex = Math.floor(index / 3) + 1;

                $(".carousel-inner .carousel-item.slide"+ slideIndex + " .container .row").append(card);
            });
            
        },
        error: function(xhr, status, error) {
            console.log(status);
            console.log("Error: " + error);
            console.log(xhr.responseText);
        }
    });
    return "function executed."
}

function logOut() {
    $.ajax({
        url: '/dashboard/logout',
        type: 'GET',
        success: function(response) {
            console.log("User logged out.");
            window.location.href = response.redirectURL;
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}