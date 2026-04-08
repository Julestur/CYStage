

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styleconnexion.css') }}">
    <link rel="icon" id="iconOnglet" href="{{ asset('Images/LogoCyNoir.png') }}" type="image/png">

    <title>Inscription</title>
</head>
<body>


<!-- Affichage -->


<div class="contenu-centre" style="margin-bottom:450px;">
    <h1>Inscription terminée !</h1>
    <p>Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.</p>
    <br><br>
    <a href="{{ route('login') }}" id="bouton-style-connexion">Se connecter</a>
</div>

<script src="{{ asset('js/confetti.js') }}"></script>








<!-- Footer  -->
<footer class="footer">
    <p>&copy; {{ date('Y') }}, CYStage</p>
    <p id="footerLogo"><img id="logo" src="{{ asset('Images/LogoCyBlanc.png') }}"></p>
</footer>

</body>


<!-- Gestion du logo CY dans l'onglet  -->
<script> 
window.laravelAssets = {lightIcon: "{{ asset('Images/LogoCyNoir.png') }}",darkIcon: "{{ asset('Images/LogoCyBlanc.png') }}"};
</script>
<script src="{{ asset('js/iconOnglet.js') }}"></script>



</body>