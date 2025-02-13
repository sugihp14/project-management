@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8">
        <div class="card shadow-lg rounded-4">
            <div class="card-header text-center  text-white" style="background-color: #6f42c1;">
                <h3>Login</h3>
            </div>
            <div class="card-body p-4">
                <div id="alert" class="alert d-none"></div>
                <form id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn text-white w-100"
                        style=" background-color: #6f42c1;">Login</button>
                </form>
                <p class="text-center mt-3">
                    Don't have an account? <a href="{{ route('register') }}">Sign up here </a>
                </p>
            </div>
        </div>
    </div>
</div>



<script src=" https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        let token = localStorage.getItem("token");
        
        if (token) {
            window.location.href = "/dashboard"; 
        }
        $("#loginForm").submit(function(e) {
            e.preventDefault(); 
            
            let username = $("#username").val();
            let password = $("#password").val();
            
            $.ajax({
                url: "/api/login",
                type: "POST",
                data: {
                    username: username,
                    password: password
                },
                success: function(response) {
                    if (response.success) {
                        localStorage.setItem("token", response.data.token); 
                        $("#alert").removeClass("d-none alert-danger").addClass("alert-success").text("Success");
                        setTimeout(function() {
                            window.location.href = "/dashboard"; 
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || "Login gagal!";
                    $("#alert").removeClass("d-none alert-success").addClass("alert-danger").text(errorMessage);
                }
            });
        });
    });
</script>
@endsection