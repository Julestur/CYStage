<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Stages - CY Tech')</title>

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">

    <link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleDropZone.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">


    <link rel="icon" id="iconOnglet" href="{{ asset('Images/LogoCyNoir.png') }}" type="image/png">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    
    @include('partials.footer')


    </body>
    
    
    <script src="{{ asset('js/app.js') }}"></script>



    <!-- Gestion du logo CY dans l'onglet  -->
    <script> 
    window.laravelAssets = {lightIcon: "{{ asset('Images/LogoCyNoir.png') }}",darkIcon: "{{ asset('Images/LogoCyBlanc.png') }}"};
    </script>
    <script src="{{ asset('js/iconOnglet.js') }}"></script>
</html>