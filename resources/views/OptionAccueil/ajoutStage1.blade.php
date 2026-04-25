

@extends('layouts.app')

@section('content')

<div class="page-dimension">
    <div class="contenu-principal">
        <h2 class="titre1">Ajout d'un nouveau stage</h2>
        <hr id="redline-mdp">


        <form action="{{ route('ajoutStage.Etape1_GESTION') }}" method="POST" class="portail">
            @csrf <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="text" name="pseudo" placeholder="Identifiant" required>
            <input type="password" name="MDP" placeholder="Mot de passe" required>
            <input type="password" name="MDP_confirmation" placeholder="Confirmer mot de passe" required>

            <select name="grade" required>
                <option value="Etudiant">Étudiant</option>
                <option value="Professeur">Professeur</option>
                <option value="Entreprise">Entreprise</option>
                <option value="admin">Admin</option>
            </select>

            <div class="bouton">
                <input type="submit" value="Créer l'utilisateur" id="bouton-style-connexion">
            </div>
        </form>
                
    </div>
</div>
@endsection