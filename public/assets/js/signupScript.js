$(document).ready(function() {
    // ---------------------------------------- CRUD functions ---------------------------------------

    function fetchData() {
        $.ajax({
            type: "GET",
            url: "/fetch",
            dataType: "json",
            success: function(response) {
                console.log("fetch executed successfully.")
                if ($.fn.DataTable.isDataTable('#myTable')) {
                    $("#myTable").DataTable().destroy();
                }
                dataTable = $("#myTable").DataTable({
                    dom: 'lBfrtip',
                    layout: {
                        topStart: {
                            'buttons': ['copy', 'csv', 'excel', 'pdf', 'print']
                        }
                    },
                    responsive : true,
                    scrollY: 300,
                    scrollCollapse: true,
                    paging: true,
                    data: response,
                    columns : [
                        {data : 'u_id', visible : false},
                        {data : 'username'},
                        {data : 'dob', visible : false},
                        {data : 'phone_no', visible : false},
                        {data : 'email'},
                        {data : 'u_address', visible : false},
                        {data : 'gender', visible : false},
                        {data : 'state_id', visible : false},
                        {data : 'country_id', visible : false},
                        {data : 'hobbies', render: function (data) {
                            if(data!==null) {
                                let hobbyList = data.split(",");
                                hobbyList = hobbyList.map(hobby => hobby.charAt(0).toUpperCase() + hobby.slice(1));
                                return hobbyList.join(", ");
                            }
                        }},
                        {data : 'img_url', render: function(data, type, row) {
                            if (data === null) {
                                return "<p>No image in database.</p>"
                            } else {
                                return "<img src = '" + data + "' alt='uploaded image' class='table-img'/>"
                            }
                        }},
                        {data: 'state_name'},
                        {data : 'country_name'},
                        {data : null, render: function(data, type, row) {
                            return `<button type="button" class="btn btn-success btn-sm cert-btn text-white"><img src="${window.location.origin}/assets/images/downloadBtn.png"></button> <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-action="edit" data-bs-target="#inputFormModal">Edit</button> <button type="button" class="btn btn-danger btn-sm del-btn" data-action="del" onclick="">Delete</button>`
                        }}
                    ],
                });
                return dataTable;
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        }); 
    }

    console.log("fetching datatable data");
    var dataTable = fetchData();

    function sendData(data, u_id=null) {
        let action = u_id === null ? "insert" : `update/${u_id}`;
        $.ajax({
            type: "POST",
            url: `/${action}`,
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response.message);
                $("#inputFormModal").modal('hide');
                dataTable = fetchData();

                if (u_id === null){
                    $("#passwordModal").modal('show');
                }
            }, error: function(xhr, status, error) {
                console.log("ERROR = " + error);
                console.log(xhr.responseText);
            }
        });
        
        
    }

    function deleteData(u_id) {
        $.ajax({
            type : "DELETE",
            url : "/delete/" + u_id,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            encode: true,
            success: function(result) {
                console.log(result.message);
                dataTable = fetchData();
            },
            error: function(xhr, status, error) {           
                console.log(status);
                console.log(xhr.responseText);
                console.log(error);
            }
        })
    }

    function downloadCert(u_id) {
        $.ajax({
            type: "GET",
            url: "/download/" + u_id,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                let blob = new Blob([response], { type: 'application/pdf' });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'DegreeCert' + u_id + '.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.log(status);
                console.log(xhr.responseText);
                console.log(error);
            }
        });
    }

    // ----------------------------------- COUNTRY/STATE SELECTIZE ----------------------------------------

    var countrySelectize = $('#country').selectize({
        valueField: "country_id",
        labelField: "country_name",
        searchField: ["country_name"],
        placeholder: "Select country",
        create: false,
        sortField: 'country_name',
    });

    var stateSelectize = $('#state').selectize({
        valueField: "state_id",
        labelField: "state_name",
        searchField: ["value"],
        placeholder: "Select state",
        create: false,
        sortField: 'state_name'
    });

    // If no country is selected, clear the state dropdown
    stateSelectize[0].selectize.clearOptions();
    stateSelectize[0].selectize.setValue('');

    // setting country options when modal is on screen
    $('#inputFormModal').on('shown.bs.modal', function () {
        
        console.log('Modal is shown');

        $.ajax({
            type: "GET",
            url: "/getCountries",
            dataType: "json",
            success: function(response) {
                if(response.status === 'error') {
                    console.log(response.message);
                } else {
                    console.log("countries fetched successfully.");

                    countrySelectize[0].selectize.clearOptions();
                    $.each(response, function (index, country) {
                        countrySelectize[0].selectize.addOption({ country_id: country.country_id, country_name: country.country_name });
                    });

                    if($('#hiddenCountry').val() === null || $('#hiddenCountry').val() === '') {

                        console.log('executes if hiddenCountry does not have any value.')
                        countrySelectize[0].selectize.setValue('');
                        
                    } else {
                        countrySelectize[0].selectize.setValue($('#hiddenCountry').val());
                        console.log("setting row's country value = " + countrySelectize[0].selectize.getValue());
                        $('#hiddenCountry').val('');
                    }
                    countrySelectize[0].selectize.refreshOptions(false);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                console.log(xhr.responseText);
            }
        });
        console.log('modal on open functionality has finished execution.');
    });

    // setting the states in the selectize dropdown
    $('#country').on('change', function() {
        
        let country_id = $(this).find("option").val();
        console.log("onchange country exec, country_id = " + country_id)

        if(country_id !== '' || country_id !== null) {
            console.log("country inside = " + country_id);

            $.ajax({
                type: "GET",
                url: "/getStates/" + country_id,
                dataType: "json",
                success: function(response) {
                    if(response.status === 'error') {
                        console.log(response.message);
                        console.log(response.error);
                    } else {
                        console.log("states fetched successfully.");

                        stateSelectize[0].selectize.setValue('');
                        stateSelectize[0].selectize.clearOptions();
                        $.each(response, function (index, state) {
                            stateSelectize[0].selectize.addOption({ state_id: state.state_id,  state_name: state.state_name });
                        });
                        if($('#hiddenState').val() === null| $('#hiddenState').val() === '') {

                            console.log('executes if hiddenState does not have any value.')
                            stateSelectize[0].selectize.setValue('');

                        } else {
                            stateSelectize[0].selectize.setValue($('#hiddenState').val());
                            console.log("setting row's state value = " + stateSelectize[0].selectize.getValue());
                            $('#hiddenState').val('');
                        }
                        stateSelectize[0].selectize.refreshOptions(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log(xhr.responseText);
                }
            });    
        }
    });

    // ------------------------------------ BUTTON CLICK HANDLERS --------------------------------------

    $('#excel').on('change', function() {
        // if there's a file selected
        if ($(this).val()) {
            $('#uploadBtn').removeClass('btn-success').addClass('btn-dark').text('Preview');
            $('#invalidMessages').empty();
            $('#invalidMessages').addClass("hide-field");
        }
    });

    $('.open-uploadmodal-btn').click(function() {
        $('#uploadBtn').removeClass('btn-success');
        $("#uploadForm")[0].reset();
        $('#uploadBtn').addClass('btn-dark').text('Preview');
        $("#excel-container").addClass("hide-field");
        $('#invalidMessages').empty();
        $('#invalidMessages').addClass("hide-field");
        $('#uploadDataModal').modal('show');
    });

    $(document).on('click', '#downloadFormatBtn', function() {
        window.location.href='../scripts/excelFormatHandler.php';
    });

    $('.add-btn').click(function() {

        $('#inputForm').attr('data-action', 'add');
        
        stateSelectize[0].selectize.clearOptions();
        stateSelectize[0].selectize.setValue('');
        // countrySelectize[0].selectize.clearOptions();
        countrySelectize[0].selectize.setValue('');

        $('#inputForm')[0].reset();
        $("#inputForm").removeClass("was-validated");
        if ($('.gender-feedback').hasClass('d-block')){
            $('.gender-feedback').addClass('d-none').removeClass('d-block');
        }
        if ($('.hobbies-feedback').hasClass('d-block')){
            $('.hobbies-feedback').addClass('d-none').removeClass('d-block');
        }
        $('#imageFilename').hide();
        $('#img-preview').hide();
        $('#inputFormModal').modal('show');
    });

    $(document).on('click', '.cert-btn', function() {
        var u_id = dataTable.row($(this).parents('tr')).data().u_id;
        downloadCert(u_id);
    });

    $(document).on('click', '.del-btn', function() {
        var u_id = dataTable.row($(this).parents('tr')).data().u_id;
        console.log("Waiting for confirmation to delete user " + u_id);        
        if (confirm("Are you sure you want to delete this row?")) {
            deleteData(u_id);
        } else {
            console.log("Delete operation cancelled by user.")
        }
    });

    $(document).on('click', '.edit-btn', function() {
        
        $('#inputForm').attr('data-action', 'edit');

        let rowData = dataTable.row($(this).parents('tr')).data();

        $('#name').val(rowData.username);
        $('#dob').val(rowData.dob);
        
        $('#phone').val(rowData.phone_no.slice(-10));
        $('#countryCode').val(rowData.phone_no.slice(0, -10));

        $('#email').val(rowData.email);
        $('#email').addClass("email-input-bg");

        $('#address').val(rowData.u_address);
        
        $('input[name="gender"][value="' + rowData.gender.toLowerCase() + '"]').prop('checked', true);

        // Uncheck all checkboxes first
        $('input[type="checkbox"]').prop('checked', false); 
        if (rowData.hobbies) {
            var hobbyList = rowData.hobbies.split(",");
            hobbyList.forEach(function(hobby) {
                $('#hobby-' + hobby.trim()).prop('checked', true);
            });
        }

        let filename = rowData.img_url.split('/').pop();

        $('#imageFilename').text(filename);
        // $('#image').val(rowData.img_url);
        $('#img-preview').attr('src', rowData.img_url);
        $('#img-preview').show();
        
        // console.log(rowData);

        let u_id = rowData.u_id;
        $('#inputForm').attr('data-u-id', u_id);

        $('#hiddenCountry').val(rowData.country_id);
        $('#hiddenState').val(rowData.state_id);

        $('#inputFormModal').modal('show');

        // -------------------------- setting country and state in EDIT FORM ------------------------------
        
        // countrySelectize[0].selectize.setValue(rowData.country);
        // stateSelectize[0].selectize.setValue(rowData.state);
    });
    
    // $('.login-btn').click(function() {
    //     let url = "{{ route('login') }}";
    //     window.location.href = url;
    // });
    $(document).on('click', '#passwordModalBtn', function() {
        window.location.href = '../dashboard/';
    });

    // -------------- code to preview image -----------------
    $(document).on("change", "#image", function () {
        if(this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // console.log(e.target.result);
                $('#img-preview').attr('src', e.target.result);
                $('#img-preview').show();
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // --------------------------------- SIGNUP FORM VALIDATION + FURTHER OPERATIONS ------------------------------

    $('#inputForm').on('submit', function(event) {

        // prevent form from submitting normally
        event.preventDefault();

        var form = $(this);

        if($('input[name="gender"]:checked').length > 0) {
            console.log("Gender selected.");
            form.find('.gender-feedback').addClass('d-none').removeClass('d-block');
        } else {
            $('.gender-feedback').addClass('d-block').removeClass('d-none');
            console.log("no gender selected.");
        }

        if (!$('#country').val()) {
            $('#country').addClass('is-invalid');
        } else if($('#country').val() && !$('#state').val()){
            $('#country').removeClass('is-invalid');
            $('#state').addClass('is-invalid');
        } else {
            $('#state').removeClass('is-invalid');
            // $('#country').removeClass('is-invalid');
        }

        // validating hobbies input
        let hobbiesChecked = form.find('input[type="checkbox"]:checked').length;

        if(hobbiesChecked === 0){
            form.find('.hobbies-feedback').addClass('d-block').removeClass('d-none');
            // event.preventDefault();
            event.stopPropagation();
            console.log("no hobbies selected.");

        } else {
            form.find('.hobbies-feedback').addClass('d-none').removeClass('d-block');
            console.log("at least one hobby selected.");
        }
        
        // validating image file upload size
        var imageFile = form.find('#image')[0].files[0];
        if (imageFile && imageFile.size < 120 * 1024) {
            form.find('.file-size-feedback').addClass('d-block').removeClass('d-none');
            form.find('.file-size-feedback').text('File size must be less than 120KB.');
            event.stopPropagation();
            console.log("file size exceeds 120KB.");
            return;
        } else {
            // form.find('.file-size-feedback').addClass('d-none').removeClass('d-block');
            // console.log("file size is within limit.");
        }

        if(!form[0].checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            console.log("form fields are invalid.");
            // form.find('.invalid-feedback').addClass('d-block').removeClass('d-none');
            form.addClass('was-validated');
            return;
        } else {
            form.find('.invalid-feedback').addClass('d-none').removeClass('d-block');
        }

        console.log("form validated. proceeding to send data.");

        let formData = new FormData(form[0]);

        // handling multiple hobbies selected
        var hobbyList = [];
        for (let [key, value] of formData) {
            if (key === "hobby") {
                hobbyList.push(value);
            }
        }

        formData.delete("hobby");
        formData.append("hobbies", hobbyList);

        // handling countrycode and phone number append
        let phone_no = formData.get("countryCode") + formData.get("phone");
        formData.delete("countryCode");
        formData.delete("phone");
        formData.append("phone_no", phone_no);

        console.log("printing formdata:");
        for (let [key, value] of formData) {
            console.log(key + ": " + value);
        }

        var action = $(this).attr('data-action');
        if (action === 'add') {
            sendData(formData);
        } else if (action === 'edit') {

            var u_id = $(this).attr('data-u-id');
            formData.append('u_id', u_id);

            // console.log("passing u_id " + u_id + " to be updated.");
            
            sendData(formData, u_id);
        }

    });

    // ------------------------------------- DATA UPLOAD FORM FUNCTIONALITY -----------------------------------
    $('#uploadForm').on('submit', function(event) {
        if($('#uploadBtn').text() === 'Upload') {
            console.log('upload can be done now');
            let excelData = $('body').data('exceldata');
            keys = Object.values(excelData[0]);

            let formattedData = {};

            for(let i = 1; i < excelData.length; ++i) {
                let values = Object.values(excelData[i]);
                let row = {};
                for(let j = 0; j < 9; ++j) {
                    row[keys[j]] = values[j];
                }
                formattedData[i-1] = row;
            }

            formattedData['length'] = excelData.length - 1;

            // can send this data
            console.log("sending this data to insert to db:");
            console.log(formattedData);
            $.ajax({
                type: "POST",
                url: "../scripts/excelUploadHandler.php",
                data: JSON.stringify(formattedData),
                dataType: "json",
                contentType: "application/json",
                processData: false,
                success: function (response) {
                    if(response.status === 'success') {
                        response.message.forEach(message => {
                            console.log(message + "\n");
                        });
                        $('#uploadDataModal').modal('hide');
                        dataTable = fetchData();
                    } else {
                        console.log(response.status);
                        console.log(response.message);
                    }
                    
                }, error: function(xhr, status, error) {
                    console.log("ERROR = " + error);
                    console.log(xhr.responseText);
                }
            });

        } else {
            event.preventDefault();
            event.stopPropagation();
            let formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: "../scripts/excelValidator.php",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log("status: " + response.status + "!");
                    console.log("message: " + response.message);
                    let x = response.columns ? console.log("columns: " + response.columns) : null;
                    
                    if(response.status === 'valid') {
                        $('#uploadBtn').removeClass('btn-dark').addClass('btn-success').text('Upload');
                        console.log(response.data);
                        $('body').data("exceldata", response.data);
                        displayExcelData(response.data);
                    } else if(response.status === 'invalid') {
                        $('#invalidMessages').removeClass('hide-field');
                        console.log(response.errors);
                        response.errors.forEach(error => {
                            $('#invalidMessages').append(`<p class='my-1'>${error.message}</p>`);
                        });
                    } else{
                        console.log("status: " + response.status);
                        console.log("message: " + response.message);
                    }
                    
                }, error: function(xhr, status, error) {
                    console.log("ERROR = " + error);
                    console.log(xhr.responseText);
                }
            });
        }

        event.preventDefault();
        event.stopPropagation();
    });

    function displayExcelData(data) {
        $('#excelDataTable thead').empty();
        $('#excelDataTable tbody').empty();

        // adding table headers
        let excelHeaders = Object.keys(data[0]);
        let dataHeaders = Object.values(data[0]);
        let headerRow = $('<tr></tr>');
        dataHeaders.forEach(header => {
            headerRow.append(`<th class='bg-dark'>${header}</th>`);
        });
        $('#excelDataTable thead').append(headerRow);

        data.forEach((row, index) => {
            if (index > 0) { // Skip the first row (index 0)
                let tableRow = $('<tr></tr>');
                excelHeaders.forEach(header => {
                    tableRow.append(`<td>${row[header]}</td>`);
                });
                $('#excelDataTable tbody').append(tableRow);
            }
        });
        $("#excel-container").removeClass("hide-field");
    }

    
});