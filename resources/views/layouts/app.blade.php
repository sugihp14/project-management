<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Laravel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


</head>

<body>
    @if (!request()->is('login') && !request()->is('register') )

    <nav class="navbar navbar-expand-lg" style=" background-color: #6f42c1;">
        <div class="container-fluid px-3">
            <a class="navbar-brand text-white" href="#">Project Management</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto" id="nav-links">

                    <li class="nav-item">
                        <button id="logoutBtn" class="btn btn-danger">Logout</button>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    @endif

    <div class="container-fluid px-3 mt-4">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            let token = localStorage.getItem("token");
            let navLinks = $("#nav-links");

         

            $("#logoutBtn").click(function () {
                $.ajax({
                    url: "/api/logout",
                    type: "POST",
                    headers: { "Authorization": "Bearer " + token },
                    success: function () {
                        localStorage.removeItem("token");
                        window.location.href = "/login";
                    },
                    error: function () {
                        alert("Logout gagal!");
                    }
                });
            });
        });
    </script>
</body>


</html>