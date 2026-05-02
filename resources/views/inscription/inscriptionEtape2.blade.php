
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

<div id="logoCY"><img src="../Images/logo2.png" alt="logo" style="display: block; margin: 20px auto;"></div>

<div class="boite_verif_code">
    <h3 class="titreVerif">Vérification par E-mail</h3>
        <p class="txtVerif">
            Un code de validation à 6 chiffres a été envoyé à l'adresse :<br>
            <strong>{{ session('form_inscription')['email'] }}</strong>
        </p>

    @if(session('erreur'))
        <p style="color: red;">{{ session('erreur') }}</p>
    @endif

    <form action="{{ route('inscription.Etape2_GESTION') }}" method="POST" class="portail">
        @csrf
            <input type="text" name="code_saisi" maxlength="6" placeholder="000000" class="zone_saisie saisie_code" required autofocus>
        <div class="bouton2">
                <input type="submit" value="Valider l'inscription" class="bouton_style_verif">
            </div>
    </form>
    
    <p class="txtVerif" style="margin-top:30px;">
            <a href="{{ route('inscription.Etape1_VU') }}" style="color: #699ff5;">Retour à l'inscription</a>
    </p>
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


</body>