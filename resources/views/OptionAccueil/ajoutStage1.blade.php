

@extends('layouts.app')

@section('content')

<div class="page-dimension">
    <div class="contenu-principal">
        <h2 class="titre1">Ajout d'un nouveau stage</h2>
        <hr id="redline-mdp">


        <form action="{{ route('ajoutStage.Etape1_GESTION') }}" method="POST" class="portail">
            

            <input type="text" name="intitule" style="font-size:20px;" placeholder="Intitule" class="contenu-portail" value="{{ old('nom') }}" required>
            <hr>


            <label>Date de début :</label>
            <input type="date" name="dateDebut" style="font-size:20px;" class="contenu-portail" value="{{ old('dateDebut') }}" required>
            <hr>
            
            <label>Date de fin :</label>
            <input type="date" name="dateFin" style="font-size:20px;" class="contenu-portail" value="{{ old('dateFin') }}" required>
            <hr>

            {{-- Grande zone de texte pour la Description --}}
            <textarea name="description" 
                    style="backgroud-color:grey;font-size:20px; width: 100%; min-height: 150px; padding: 10px;" 
                    placeholder="Description détaillée du stage..." 
                    class="contenu-portail" 
                    required>{{ old('description') }}</textarea>
            <hr>

            @if(session('idStatut') == 4) 
        
                {{-- Cas du Professionnel --}}
                <p style="font-size:20px;">Entreprise : <strong>VOTRE ENTREPRISE</strong></p>
                <input type="hidden" name="idEntreprise" value="{{ session('idEntreprise') }}">
            @else
                {{-- Cas de l'Admin ou autre --}}
                <select name="idEntreprise" style="font-size:20px; width: 100%;" class="contenu-portail" required>
                    <option value="">-- Choisir une entreprise --</option>
                    @foreach($entreprises as $e)
                        <option value="{{ $e->idEntreprise }}">{{ $e->nom }}</option>
                    @endforeach
                </select>
            @endif


            <div class="bouton">
                <input type="submit" value="Créer l'utilisateur" id="bouton-style-connexion">
            </div>

            <br><br>
            <br><br>

        </form>
                
    </div>
</div>
@endsection