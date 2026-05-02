

@extends('layouts.app')

@section('content')

<div class="page-dimension">
    <div class="contenu-principal">
        <h2 class="titre1">Inscription d'un nouvel utilisateur</h2>
        <hr id="redline-mdp">

                

        <form action="{{ route('inscriptionAdmin.Etape1_GESTION') }}" method="POST" class="portail">
            
            <!-- AFFICHAGE DES ERREURS -->
            @if ($errors->any())
                    <div style="color: #fa021b; background-color: #f8d7da; padding: 10px; margin-bottom: 20px; border-radius: 5px; font-size: 14px;">
                        <ul style="margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @csrf

            <input type="text" name="nom" style="font-size:20px;" placeholder="Nom" class="contenu-portail" value="{{ old('nom') }}" required>
            <hr>
            <input type="text" name="prenom" style="font-size:20px;" placeholder="Prénom" class="contenu-portail" value="{{ old('prenom') }}" required>
            <hr>
            <input type="email" name="mail" style="font-size:20px;" placeholder="Email" class="contenu-portail" value="{{ old('mail') }}" required>
            <hr>
            <input type="text" name="pseudo" style="font-size:20px;" placeholder="Identifiant" class="contenu-portail" value="{{ old('pseudo') }}" required>
            <hr>
            <input type="password" name="MDP" style="font-size:20px;" placeholder="Mot de passe" class="contenu-portail" required>
            <hr>
            <input type="password" name="MDP_confirmation" style="font-size:20px;" placeholder="Confirmation" class="contenu-portail" required>
            <hr>

            <br><br>

            <label for="grade">Statut de l'utilisateur :</label>
            <select name="grade" id="grade-select" class="contenu-portail" onchange="toggleFields()">
                
                <option value="" disabled {{ old('grade') === null ? 'selected' : '' }}> Choisissez le statut </option>

    
                <option value="Etudiant" {{ old('grade') == 'Etudiant' ? 'selected' : '' }}>Étudiant</option>
                <option value="Professeur" {{ old('grade') == 'Professeur' ? 'selected' : '' }}>Professeur</option>
                <option value="Entreprise" {{ old('grade') == 'Entreprise' ? 'selected' : '' }}>Entreprise</option>
                <option value="admin" {{ old('grade') == 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>

            <div id="field-entreprise" style="display: none;">
                <br><br>
                <input type="text" name="nom_entreprise" style="font-size:20px;" placeholder="Nom de l'entreprise" class="contenu-portail" value="{{ old('nom_entreprise') }}">
                <hr>
            </div>

            <br><br>

            <div id="field-classe" style="display: none;">
                <label for="classe">Classe :</label>
                <select name="classe" id ="grade-select" class="contenu-portail" style="font-size:17px;">
                    <option value="" disabled {{ old('classe') === null ? 'selected' : '' }}> Choisissez la classe </option>
                    
                    <option value="Pré ING1" {{ old('classe') == "Pré ING1" ? 'selected' : '' }}>Pré ING1</option>
                    <option value="Pré ING2" {{ old('classe') == "Pré ING2" ? 'selected' : '' }}>Pré ING2</option>
                    
                    <option value="ING1 GM" {{ old('classe') == "ING1 GM" ? 'selected' : '' }}>ING1 GM</option> 
                    <option value="ING1 GI" {{ old('classe') == "ING1 GI" ? 'selected' : '' }}>ING1 GI</option>
                    
                    <option value="ING2 GM" {{ old('classe') == "ING2 GM" ? 'selected' : '' }}>ING2 GM</option>
                    <option value="ING2 GI" {{ old('classe') == "ING2 GI" ? 'selected' : '' }}>ING2 GI</option>

                    <option value="ING3 GM" {{ old('classe') == "ING3 GM" ? 'selected' : '' }}>ING3 GM</option>
                    <option value="ING3 GI" {{ old('classe') == "ING3 GI" ? 'selected' : '' }}>ING3 GI</option>
                </select>            
            </div>

            <div class="bouton">
                <input type="submit" value="Créer l'utilisateur" id="bouton-style-connexion">
            </div>
        </form>
        <br><br>
        <br><br>
        <br><br>

    </div>
</div>

<script>
    // Fonction pour afficher/cacher les champs selon le grade sélectionné
    function toggleFields() {
        const grade = document.getElementById('grade-select').value;
        const divEntreprise = document.getElementById('field-entreprise');
        const divClasse = document.getElementById('field-classe');

        divEntreprise.style.display = (grade === 'Entreprise') ? 'block' : 'none';
        divClasse.style.display = (grade === 'Etudiant') ? 'block' : 'none';
    }

    // Exécuter au chargement pour garder les champs si erreur de formulaire
    window.onload = toggleFields;
</script>
@endsection