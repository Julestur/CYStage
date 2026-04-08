
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

<div id="logoCY">
    <img id="logoCY" src="{{ asset('Images/logo2.png') }}" alt="logo de cytech">
</div>
<h3 id="titre">PORTAIL GESTION DES STAGES</h3>




<form action="{{ route('inscription.Etape1_GESTION') }}" method="POST" class="portail">


    @if(session('erreur_mail'))
        <p style="color: red;">{{ session('erreur_mail') }}</p>
    @endif
    @if(session('erreur_mdp'))
        <p style="color: orange;">{{ session('erreur_mdp') }}</p>
    @endif

    <input type="text" name="nom" style="font-size:20px;" placeholder="Nom" class="contenu-portail" value="{{ old('nom') }}" required>
    <hr>
    <input type="text" name="prenom" style="font-size:20px;" placeholder="Prénom" class="contenu-portail" value="{{ old('prenom') }}" required>
    <hr>
    <input type="email" name="email" style="font-size:20px;" placeholder="Adresse e-mail" class="contenu-portail" value="{{ old('email') }}" required>
    <hr>
    <input type="text" name="pseudo" style="font-size:20px;" placeholder="Identifiant" class="contenu-portail" value="{{ old('pseudo') }}" required>
    <hr>
    <input type="password" name="mot-de-passe" style="font-size:20px;" placeholder="Mot de passe" class="contenu-portail" required>
    <hr>
    <input type="password" name="confirmation-mot-de-passe" style="font-size:20px;" placeholder="Confirmation" class="contenu-portail" required>
    <hr>
    <select name="grade" id="type_grade" style="font-size:20px;" class="selection-administration">
        <option value="" disabled selected>-- Statut --</option>
        <option value="Etudiant">Etudiant</option>
        <option value="Professeur">Professeur</option>
        <option value="Entreprise">Entreprise</option>
    </select>
    <br>
    <br>

    <div id="bloc_entreprise" style="font-size:20px;display:none;" >
        <input type="text" id="entreprise" name="entreprise" placeholder="Nom de l'entreprise" class="contenu-portail">
        <hr>
    </div>
    
     <div id="bloc_classe" style="display: none;" >

        <select name="classe" id ="classe" class="selection-administration" style="font-size:20px;">
            <option value="" disabled selected>-- Choisissez la classe --</option>
            <option value="Pré ING1">Pré ING1</option>
            <option value="Pré ING2">Pré ING2</option>                  
            <option value="ING1 GM">ING1 GM</option>
            <option value="ING1 GI">ING1 GI</option>
            <option value="ING2 GM">ING2 GM</option>
            <option value="ING2 GI">ING2 GI</option>
            <option value="ING3 GM">ING3 GM</option>
            <option value="ING3 GI">ING3 GI</option>
        </select>
    </div>


    <div class="bouton">
        <input type="submit" value="Inscription" id="bouton-style-connexion">
    </div>
</form>

<br><br>


<p class="info" style="margin-top:30px;margin-bottom:100px;">
    <a href="{{ route('login') }}" style="color: white;">Retour</a>
</p>



<!-- Footer  -->
<footer class="footer">
    <p>&copy; {{ date('Y') }}, CYStage</p>
    <p id="footerLogo"><img id="logo" src="{{ asset('Images/LogoCyBlanc.png') }}"></p>
</footer>

</body>





















<!-- Gestion de l'affichage des objets dans le formulaire  -->
    
    <script>

    // On récupère les éléments
    const grade = document.getElementById('type_grade');
    
    const bloc_Entreprise = document.getElementById('bloc_entreprise');
    const nom_Entreprise = document.getElementById('entreprise');
    const bloc_Classe = document.getElementById('bloc_classe');
    const Classe = document.getElementById('classe');

    // Fonction pour gérer l'affichage
    grade.addEventListener('change', function() {
        if (this.value === 'Entreprise') {
            bloc_Entreprise.style.display = 'block';
            nom_Entreprise.required = true; // On le rend obligatoire seulement si affiché
        } 
        else {
            bloc_Entreprise.style.display = 'none';
            nom_Entreprise.required = false; // On retire l'obligation
            nom_Entreprise.value = ''; // On vide le champ
        }

        if (this.value === 'Etudiant') {
            bloc_Classe.style.display = 'block';
            Classe.required = true; // On le rend obligatoire seulement si affiché
        }
        else {
            bloc_Classe.style.display = 'none';
            Classe.required = false; // On retire l'obligation
            Classe.value = ''; // On vide le champ
        }
    });


    </script>



<!-- Gestion du logo CY dans l'onglet  -->
<script> 
window.laravelAssets = {lightIcon: "{{ asset('Images/LogoCyNoir.png') }}",darkIcon: "{{ asset('Images/LogoCyBlanc.png') }}"};
</script>
<script src="{{ asset('js/iconOnglet.js') }}"></script>



</body>
