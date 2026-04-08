@extends('layouts.app')

@section('content')
<div class="page-dimension">
    <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    <h2 class="titre2">Dépôt d'un nouveau fichier (Profil : {{ $grade }})</h2>

    <div class="contenu-accueil">
        <div class="depot">
            <form action="{{ url('/repartition') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="drop-zone" onclick="document.getElementById('input-file').click()">
                    <ion-icon name="download-outline" style="font-size: 50px;"></ion-icon>
                    <p>Veuillez déposer un fichier .csv</p>
                    <input type="file" name="f_excel" id="input-file" accept=".csv" style="display:none">
                </div>
                <input type="date" name="date-sortie" required>
                <button type="submit" class="bouton-style-envoi">Envoyer</button>
            </form>
        </div>

        <div class="historique">
            <h2 class="titre2">Sorties précédentes</h2>
            <ul>
                @foreach($fichiers as $nom => $time)
                    <li class="tag-sortie">{{ pathinfo($nom, PATHINFO_FILENAME) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection