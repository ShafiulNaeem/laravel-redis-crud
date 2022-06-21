<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Todo List</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/fontawesome.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/sweetalert2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/daterangepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/css/magnific-popup.css')}}" />
    <link
        href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"
        rel="stylesheet"
    />
    <link rel="stylesheet" href="{{asset('assets/sass/style.css')}}" />

</head>
<body>

<section class="login_form_wrapper sign_up_form_wrapper">
    <!-- Add todo section start -->
    <div class="add_food_button_area">
        <button type="button" data-bs-toggle="modal" data-bs-target="#addFoodModal">
            <img src="{{asset('assets/images/food/add_food_icon.svg')}}" alt="add icon" />
            <span>Add Todo</span>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal_popup_area">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4>Add Todo</h4>
                        <form class="form_area">
                            <div class="input_row">
                                <label for="">Title</label>
                                <input required id="title" type="text" placeholder="Title"/>
                                <div id="error_title"></div>
                            </div>
                            <div class="input_row" style="padding-top: 10px; padding-bottom: 5px;">
                                <label for="">Description</label>
                                <textarea id="info" required style="padding-left: 30px; height: 100px; width: 100%;-webkit-filter: drop-shadow(0px 2px 4px rgba(23, 23, 23, 0.18));filter: drop-shadow(0px 2px 4px rgba(23, 23, 23, 0.18));background: #ffffff;opacity: 0.8;border: none;" type="text" rows="3" placeholder="Description"></textarea>
                                <div id="error_info"></div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" onclick="addTodo()" class="save_button">Save</button>
                        <button type="button" class="cancle_button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add todo section end -->
    </div>

    <!--  todo list section start -->
    <div class="food_list_area">
        <div style="padding-top: 10px; display: none; font-size: 16px;" class="text-center" id="message"></div>
        <div class="food_list_header d-flex align-items-center justify-content-between flex-wrap">
            <h4>Todo List</h4>
        </div>

        <div class="food_list_table table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <td>
                       SI
                        <img
                            src="{{asset('assets/images/food/up_down_arrow.svg')}}"
                            alt="arrow"
                        />
                    </td>
                    <td>
                       Title
                        <img
                            src="{{asset('assets/images/food/up_down_arrow.svg')}}"
                            alt="arrow"
                        />
                    </td>

                    <td>
                        Description
                        <img
                           src="{{asset('assets/images/food/up_down_arrow.svg')}}"
                            alt="arrow"
                        />
                    </td>
                    <td>
                        Date
                        <img
                            src="{{asset('assets/images/food/up_down_arrow.svg')}}"
                            alt="arrow"
                        />
                    </td>

                    <td>
                        ACTION
                        <img
                            src="{{asset('assets/images/food/up_down_arrow.svg')}}"
                            alt="arrow"
                        />
                    </td>
                </tr>
                </thead>
                <tbody id="foodListTable">

                </tbody>
            </table>


        </div>
    </div>
    <!--  todo list section end -->
</section>

<script src="{{asset('assets/plugins/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/plugins/js/bootstrap.bundle.min.js')}}"></script>
<script
    src="https://kit.fontawesome.com/46f35fbc02.js"
    crossorigin="anonymous"
></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="{{asset('assets/plugins/js/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/js/chart.min.js')}}"></script>
<script src="{{asset('assets/plugins/js/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/js/moment.min.js')}}"></script>
<!-- <script src="{{'assets/plugins/js/moment.min.js'}}"></script> -->
<script src="{{asset('assets/plugins/js/daterangepicker.js')}}"></script>
<script src="{{asset('assets/plugins/js/jquery.magnific-popup.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="{{asset('assets/js/main.js')}}"></script>

<script>
    // todo list data show
    $.ajax({
        url:"/api/todo-list",
        type: "GET",
        async: false,
        data: {},
        success: function (response) {
            if(response.response.status == "success"){
                showData(response.response.response_data,response.response.total);
            }else {
                //show message
                messageShow(response.response.message,"red");
            }
        },
        error: function(error) {
            console.log(error);
        }
    });

    // add todo
    function addTodo(){
        //get data from input field
        var titleData = $('#title').val();
        var infoData = $('#info').val();

        // validation message id
        var errorTitle = $('#error_title');
        var errorInfo = $('#error_info');

        $.ajax({
            url:"/api/todo-list/create",
            type: "post",
            async: false,
            data: {
                title:titleData,
                info:infoData
            },
            success: function (response) {
                // update data show in list
                if(response.response.status == "success"){
                    // hide modal
                    $('#addFoodModal').modal('hide');

                    // Load updated Todo list Data
                    loadUpdatedTodoListData();

                    // show message
                    messageShow(response.response.message,"#28a745");

                    // reset input field
                    $('#title').val('');
                    $('#info').val('');
                }
                // validation message show
                else {
                    var message = response.response.message;
                    validation(message,errorTitle,errorInfo);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    }

    // update todo list
    function updateToDo(id){
        //get data from input field
        var titleData = $('#title-'+id).val();
        var infoData = $('#info-'+id).val();

        // validation message id
        var errorTitle = $('#errorTitle-'+id);
        var errorInfo = $('#errorInfo-'+id);

        $.ajax({
            url:"/api/todo-list/update/"+id,
            type: "post",
            async: false,
            data: {
                id:id,
                title:titleData,
                info:infoData
            },
            success: function (response) {
                // update data show in list
                if(response.response.status == "success"){
                    // hide modal
                    $('#editFoodModal-'+id).modal('hide');
                    // Load updated Todo list Data
                    loadUpdatedTodoListData();

                    // show message
                    messageShow(response.response.message,"#28a745");
                }
                // validation message show
                else {
                    var message = response.response.message;
                    validation(message,errorTitle,errorInfo);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    }

    // delete todo
    function deleteTodo(id){
        // swal alert
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) { // swal confirmation

                // call delete api
                $.ajax({
                    url:"/api/todo-list/delete/"+id,
                    type: "delete",
                    async: false,
                    data: {},
                    success: function (response) {
                        if(response.response.status == "success"){
                            // Load updated Todo list Data
                            loadUpdatedTodoListData();
                            // show message
                            messageShow(response.response.message,"#28a745");
                        }else {
                            //show message
                            messageShow(response.response.message,"red");
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    }

    // Load updated Todo list Data
    function loadUpdatedTodoListData(){
        $.ajax({
            url:"/api/todo-list",
            type: "GET",
            async: false,
            data: {},
            success: function (response) {
                if(response.response.status == "success"){
                    showData(response.response.response_data,response.response.total);
                }else {
                    //show message
                    messageShow(response.response.message,"red");
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // table body data show
    function showData(data,total){
        //get table body id
        var tableBody = $("#foodListTable");
        tableBody.empty();

        for (var i = 0; i < total; i++){
            var si = i +1;
            // date formation
            var date = moment(data[i]["created_at"]).format('MMMM Do YYYY, h:mm:ss a');
            if( data.hasOwnProperty(i) ){
                tableBody.append('<tr>' +
                    '<td>' + si +'</td>' +
                    '<td>' + data[i]["title"] +'</td>' +
                    '<td>' + data[i]["info"] +'</td>' +
                    '<td>' + date +'</td>' +
                    '<td class="action_button" id="action_button">' +

                    //update modal
                    '<div class="modal fade" id="editFoodModal-'+  data[i]["id"] +'" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">' +
                    '<div class="modal-dialog modal-dialog-centered modal_popup_area">' +
                    '<div class="modal-content">' +
                    '<form class="modal-body">' +
                    '<h4>Update Todo</h4> ' +
                    '<form class="form_area" action="main.blade.php" method="post">' +
                        '<div class="input_row">' +
                            '<label for="">Title</label>'+
                            '<input id="title-'+ data[i]['id'] +'" name="title-'+ data[i]['id'] +'" style="height: 50px; width: 100%;" required type="text" ' +
                            'placeholder="Title" value="'+ data[i]['title'] +'"/>'+
                            '<input hidden id="id-'+ data[i]['id'] +'" name="id-'+ data[i]['id'] +'" type="text" placeholder="Title" value="'+ data[i]['id'] +'"/>'+
                            '<div id="errorTitle-'+ data[i]['id'] +'"></div>'+
                        '</div'+

                        '<div class="input_row">' +
                            '<label style="padding-top: 15px;" for="">Description</label>'+
                            '<textarea id="info-'+ data[i]['id'] +'" name="info-'+ data[i]['id'] +'" required style="padding-left: 30px; height: 100px; width: 100%;' +
                            '-webkit-filter: drop-shadow(0px 2px 4px rgba(23, 23, 23, 0.18));' +
                            'filter: drop-shadow(0px 2px 4px rgba(23, 23, 23, 0.18));background: #ffffff;opacity: 0.8;border: none;" ' +
                            'type="text" rows="3" placeholder="Description">'+ data[i]['info'] +'</textarea>'+
                            '<div id="errorInfo-'+ data[i]['id'] +'"></div>'+
                        '</div>'+
                    '</form>'+
                    '<div class="modal-footer justify-content-center">' +
                    '<button type="button" onclick="updateToDo('+ data[i]['id'] +')" class="save_button">Update</button>'+
                    '<button type="button" class="cancle_button" data-bs-dismiss="modal">Cancel</button>'+
                    '</div>'+
                    '</div>'+

                    '</div>'+
                    '</div>'+
                    '</div>'+

                    // action Dropdown button
                    '<div class="dropdown" id="dropdown_action_btn">' +
                        '<button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">' +
                        '<img src="http://127.0.0.1:8000/assets/images/food/action_dot_button.svg" alt=""/></button>'+
                        ' <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">' +
                            '<li><button type="button" data-bs-toggle="modal" data-bs-target="#editFoodModal-'+  data[i]["id"] +'"><i class="fa-solid fa-pen-to-square"></i>Edit</button></li>'+
                            '<li><button onclick="deleteTodo('+ data[i]['id'] +')" type="button" id="deleteFoodList"><i class="fa-solid fa-trash-can"></i>Delete</button></li>'+
                        '</ul>'+

                    '</div>'+

                    '</td>' +

                    '</tr>');
            }
        }
    }

    // show message function
    function messageShow(message,status){
        $("#message").css("color", status).text(message).show("slow").delay(5000).hide("slow");
    }

    // validation message show
    function validation(message,errorTitle,errorInfo){

        if( message.hasOwnProperty('title') == true ){
            errorTitle.empty();
            errorTitle.append('<span style="color: red;">'+ message.title[0] +'</span>');
        }
        if( message.hasOwnProperty('title') == false ){
            errorTitle.empty();
        }
        if( message.hasOwnProperty('info') == true ){
            errorInfo.empty();
            errorInfo.append('<span style="color: red;">'+ message.info[0] +'</span>');
        }
        if( message.hasOwnProperty('info') == false ){
            errorInfo.empty();
        }
    }


</script>

</body>

</html>
