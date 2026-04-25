<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styleconnexion.css') }}">
    <link rel="icon" id="iconOnglet" href="{{ asset('Images/LogoCyNoir.png') }}" type="image/png">

    <title>Connexion</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>



<!-- Affichage -->

<div id="logoCY">
    <img id="logoCY" src="{{ asset('Images/logo2.png') }}" alt="logo de cytech">
</div>
<h3 id="titre">PORTAIL GESTION DES STAGES</h3>

<form action="{{ url('/login-process') }}" method="POST" class="portail">
    
    <input type="text" name="pseudo" placeholder="Identifiant ou adresse e-mail" class="contenu-portail" required>
    <hr>
    <input type="password" name="mot-de-passe" placeholder="Mot de passe" class="contenu-portail" required>
    <hr>

    @if(isset($session_expiree) && $session_expiree)
    <p id="erreur" style="background-color: #e67e22;">
        <ion-icon name="time-outline"></ion-icon> 
        Votre session a expiré, veuillez vous reconnecter.
        <ion-icon name="time-outline"></ion-icon>
    </p>

    @elseif($errors->has('login_error'))
        <p id="erreur">
            <ion-icon name="close-outline"></ion-icon> 
            Identifiant ou mot de passe incorrect 
            <ion-icon name="close-outline"></ion-icon>
        </p>

    @elseif($errors->has('message') && str_contains($errors->first(), 'expired'))
        <p id="erreur" style="background-color: #e67e22;">
            <ion-icon name="time-outline"></ion-icon> 
            Session expirée, merci de réessayer.
            <ion-icon name="time-outline"></ion-icon>
        </p>
    @endif

    <div class="bouton">
        <input type="submit" value="Connexion" id="bouton-style-connexion">
    </div> 
</form>

<div class="info">
    <a href="{{ url('/mot-de-passe-oublie') }}">Mot de passe oublié ?</a>
</div>
<div class="info">
    <a href="{{ url('/inscription') }}"> Pas encore de compte ?</a>
</div>



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



</html>