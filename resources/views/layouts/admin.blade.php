<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        body {
            background-color: #f5f6fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: #343a40;
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: #0d6efd;
        }

        .admin-content {
            padding: 30px;
        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- Sidebar -->

        <div class="col-md-2 px-0 sidebar">

            <div class="p-3 text-white">

                <h4>
                    <i class="fa-solid fa-store"></i>
                    Ecommerce
                </h4>

                <hr>

            </div>

            <ul class="nav flex-column">

                <li class="nav-item">

                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link">

                        <i class="fa-solid fa-gauge me-2"></i>

                        Dashboard

                    </a>

                </li>

                <li class="nav-item">

                    <a href="{{ route('admin.categories.index') }}"
                       class="nav-link">

                        <i class="fa-solid fa-list me-2"></i>

                        Categories

                    </a>

                </li>

                <li class="nav-item">

                    <a href="{{ route('admin.products.index') }}"
                       class="nav-link">

                        <i class="fa-solid fa-box me-2"></i>

                        Products

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#"
                       class="nav-link">

                        <i class="fa-solid fa-cart-shopping me-2"></i>

                        Orders

                    </a>

                </li>

                <li class="nav-item">

                    <a href="#"
                       class="nav-link">

                        <i class="fa-solid fa-users me-2"></i>

                        Users

                    </a>

                </li>

            </ul>

        </div>


        <!-- Main Content -->

        <div class="col-md-10">

            <nav class="navbar navbar-light bg-white shadow-sm">

                <div class="container-fluid">

                    <span class="navbar-brand">
                        Admin Panel
                    </span>

                    <div>

                        <span class="me-3">
                            {{ auth()->user()->name }}
                        </span>

                        <form action="{{ route('logout') }}"
                              method="POST"
                              class="d-inline">

                            @csrf

                            <button class="btn btn-danger btn-sm">

                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </nav>


            <main class="admin-content">

                @yield('content')

            </main>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>