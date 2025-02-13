@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8">
        <div class="card shadow-lg rounded-4" style="min-height: 500px;">
            <div class="card-header text-center text-white" style="background-color: #6f42c1;">
                <h3>Register</h3>
            </div>
            <div class="card-body p-5">
                <div id="alert" class="alert d-none"></div>
                <form id="registerForm">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                            required>
                    </div>
                    <button type="submit" class="btn text-white w-100 py-2"
                        style="background-color: #6f42c1;">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#registerForm").submit(function(e) {
            e.preventDefault();

            let username = $("#username").val();
            let password = $("#password").val();
            let confirmPassword = $("#confirm_password").val();

            if (password !== confirmPassword) {
                $("#alert").removeClass("d-none alert-success").addClass("alert-danger").text("Passwords do not match.");
                return;
            }

            $.ajax({
                url: "/api/register",
                type: "POST",
                data: {
                    username: username,
                    password: password
                },
                success: function(response) {
                    if (response.success) {
                        $("#alert").removeClass("d-none alert-danger").addClass("alert-success").text("Registration successful! Redirecting...");
                        setTimeout(function() {
                            window.location.href = "/login";
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || "Registration failed!";
                    $("#alert").removeClass("d-none alert-success").addClass("alert-danger").text(errorMessage);
                }
            });
        });
    });
</script>
@endsection