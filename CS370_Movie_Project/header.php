<html>
<head>
    <title>Movie Database Portal</title>
    <link href="/css/bootstrap.css" rel="stylesheet" />
    <script src="/js/bootstrap.bundle.js"></script>
</head>
<body>

<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="index.php">Movie Home</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            href="#" role="button" aria-expanded="false">
            Data Import
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="movie_data_import.php">Import Movie Data</a>
            </li>
            <li>
                <a class="dropdown-item" href="critic_data_import.php">Import Critic Data</a>
            </li>
            <li>
                <a class="dropdown-item" href="actor_data_import.php">Import Actor Data</a>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
           href="#" role="button" aria-expanded="false">
            Reports
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="movie_data_report.php">Movie Data</a>
            </li>
            <li>
                <a class="dropdown-item" href="critic_data_report.php">Critic Data</a>
            </li>
            <li>
                <a class="dropdown-item" href="actor_data_report.php">Actor Data</a>
            </li>
        </ul>
    </li>
</ul>

<div class="container">