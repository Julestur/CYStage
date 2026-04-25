
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styleconnexion.css') }}">
    <link rel="icon" id="iconOnglet" href="{{ asset('Images/LogoCyNoir.png') }}" type="image/png">

    <title>Réinitialisation du mot de passe</title>
</head>
<body>



<!-- Affichage -->

<div id="logoCY">
    <img id="logoCY" src="{{ asset('Images/logo2.png') }}" alt="logo de cytech">
</div>
<h3 id="titre">Réinitialisation du mot de passe</h3>





<div class="page-dimension">
        <div class="contenu-principal">    
            



            <form action="{{ route('reinitMDP_GESTION') }}" method="POST" class="portail">
                @csrf


                <!-- Affichage des erreurs au dessus de du remplissage des champs -->
                @if ($errors->any())
                    <div style="color: #fa021b; background-color: #f8d7da; padding: 10px; margin-bottom: 20px; border-radius: 5px; font-size: 14px;">
                        <ul style="margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('erreur'))
                    <p style="color: red; text-align: center; font-weight: bold;">{{ session('erreur') }}</p>
                @endif

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <input type="password" placeholder="Nouveau Mot De Passe" class="contenu-portail" name="password" required>
                <hr>
                <input type="password" placeholder="Confirmation" class="contenu-portail" name="password_confirmation" required>
                <hr>

                <div class="bouton">
                    <input type="submit" value="Enregistrer" id="bouton-style-connexion">
                </div>
            </form>
        </div>
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
</body>
</html>