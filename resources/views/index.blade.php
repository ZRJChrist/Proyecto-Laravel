<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700;800&display=swap');
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>@yield('title')- Bunglebuild </title>
</head>

<body>
    <div id="main">
        <div id="nav_content">
            <nav>
                @include('template.navbar')
            </nav>
            <section id="content">
                @yield('content')
            </section>
        </div>
        <footer>
            <!-- Sección de contacto -->
            <section id="contacto" class="py-5">
                <div class="container">
                    <h2 class="text-center mb-4">Contáctanos</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Para más información, contáctanos:</p>
                            <ul>
                                <li>Dirección: [Dirección de tu empresa]</li>
                                <li>Teléfono: [Número de teléfono]</li>
                                <li>Email: [Correo electrónico]</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </footer>
    </div>
</body>

</html>
