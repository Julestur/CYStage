





@extends('layouts.app')

@section('content')



<div class="page-dimension">
        <div class="contenu-principal"> 
            
            
            <div class="accueil">  
            <h2 class="titre1">Changement de mot de passe</h2>
            </div>

            <hr id="redline-mdp">
            <form action="{{ route('changementMDP_GESTION') }}" method="POST" class="portail">
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
                                    
                    <div style="color: #fa021b; background-color: #f8d7da; padding: 10px; margin-bottom: 20px; border-radius: 5px; font-size: 14px;">
                        <ul style="margin: 0;">
                            <li>{{ session('erreur') }}</li>
                        </ul>
                    </div>
                @endif
                @if (session('confirmation'))
                    <div style="color: #21a13f; background-color: #d4edda; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; border: 1px solid #c3e6cb;">
                    {{ session('confirmation') }}
                    </div>
                @endif

            

                <input type="password" placeholder="Ancien Mot De Passe" class="contenu-portail" name="ancien_MDP" required>
                <hr>
                <input type="password" placeholder="Nouveau Mot De Passe" class="contenu-portail" name="MDP" required>
                <hr>
                <input type="password" placeholder="Confirmation" class="contenu-portail" name="MDP_confirmation" required>
                <hr>

                <div class="bouton">
                    <input type="submit" value="Enregistrer" id="bouton-style-connexion">
                </div>
            </form>
        </div>
    </div>
















<br><br><br><br><br><br><br>

@endsection