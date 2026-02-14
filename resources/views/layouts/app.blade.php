<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Koleksi Buku</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    /* RESET */
    body{
        background:#f1f5f9;
        overflow-x:hidden;
    }

    .sidebar{
        position:fixed;
        top:0;
        left:0;
        width:260px;
        height:100vh;
        background:linear-gradient(180deg,#020617,#0f172a);
        padding-top:20px;
        z-index:1000;
    }

    .main-wrapper{
        margin-left:260px;
        min-height:100vh;
        display:flex;
        flex-direction:column;
    }

    .topbar{
        height:70px;
        background:#020617;
        display:flex;
        align-items:center;
        padding:0 25px;
    }

    .content{
        flex:1;
        padding:30px;
    }

    .footer{
        background:#020617;
        color:#94a3b8;
        padding:15px;
        text-align:center;
    }

    .sidebar .nav-link{
        color:#cbd5e1;
        margin:6px 12px;
        border-radius:10px;
        padding:12px 16px;
    }

    .sidebar .nav-link:hover{
        background:#1e293b;
        color:white;
    }

    .sidebar .active{
        background:#facc15;
        color:black !important;
        font-weight:600;
    }
    </style>
</head>

<body>

@auth
    @include('layouts.partials.sidebar')
@endauth

<div class="main-wrapper">

    @auth
        @include('layouts.partials.header')
    @endauth

    <div class="content">
        @yield('content')
    </div>

    @auth
        @include('layouts.partials.footer')
    @endauth

</div>

</body>


</html>
