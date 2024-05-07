$(document).ready(function() {
    
    $('#loginForm').trigger('reset');

    function sendData(data, event) {
        $.ajax({
            type: "POST",
            url: "/login",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status === "error") {
                    console.log("Status returned error.");

                    if(response.type === "email" || response.type === "password") {
                        $('#loginForm')[0].reset();
                        alert(response.message);
                    }
                } else {
                    console.log("User logged in, redirecting.");
                    window.location.href = response.redirectURL;
                }
                
            }, error: function(xhr, error) {
                console.log("Server error = " + error);
                console.log(xhr.responseText);
            }
        });
    }

    $('#loginForm').on('submit', function(event) {

        // prevent form from submitting normally
        event.preventDefault();
        event.stopImmediatePropagation();

        let form = $(this);

        if(!form[0].checkValidity()) {
            event.preventDefault();
            event.stopImmediatePropagation();
            form.addClass('was-validated');
            return;
        }

        console.log("form validated. proceeding to send data.");

        var formData = new FormData(form[0]);
        // for (let [key, value] of formData) {
        //     console.log(key + ": " + value);
        // }

        sendData(formData, event);

        return false;

    });
});