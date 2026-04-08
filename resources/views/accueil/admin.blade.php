@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleDropZone.css') }}">
<link rel="stylesheet" href="{{ asset('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css') }}">








<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    </div>

    <h2 class="titre2">Tableau de bord</h2>
    <div class="tab_bord">
        <div class="carre_style">
            <div class="contenu_carte"><h3>Étudiants</h3><p class="valeur">{{ $stats['etudiants'] }}</p></div>
            <ion-icon name="school-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte"><h3>Entreprises</h3><p class="valeur">{{ $stats['entreprises'] }}</p></div>
            <ion-icon name="business-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte"><h3>Stages</h3><p class="valeur">{{ $stats['stages'] }}</p></div>
            <ion-icon name="briefcase-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte"><h3>Professeurs</h3><p class="valeur">{{ $stats['profs'] }}</p></div>
            <ion-icon name="people-outline" class="style_icone"></ion-icon>
        </div>
    </div>

    <div class="barre_choix">
        <button type="button" onclick="chargerContenu('candidatures')" class="bouton_choix {{ $choix == 'candidatures' ? 'actif' : '' }}">
        <ion-icon name="paper-plane-outline"></ion-icon> Candidatures
    </button>
    <button type="button" onclick="chargerContenu('stage')" class="bouton_choix {{ $choix == 'stage' ? 'actif' : '' }}">
        <ion-icon name="briefcase-outline"></ion-icon> Stages
    </button>
    <button type="button" onclick="chargerContenu('etudiant')" class="bouton_choix {{ $choix == 'etudiant' ? 'actif' : '' }}">
        <ion-icon name="school-outline"></ion-icon> Étudiants
    </button>
    <button type="button" onclick="chargerContenu('prof')" class="bouton_choix {{ $choix == 'prof' ? 'actif' : '' }}">
        <ion-icon name="people-outline"></ion-icon> Professeurs
    </button>
    <button type="button" onclick="chargerContenu('entreprise')" class="bouton_choix {{ $choix == 'entreprise' ? 'actif' : '' }}">
        <ion-icon name="business-outline"></ion-icon> Entreprises
    </button>
    
    </div>

        <div id="barreLatérale">
        <div id="contenuBarreLat">

                <span onclick = "fermerBarreLat()"> &times; </span>
                <h2 id="titre"></h2> <br><br><br><br>
                <p id="date"></p><br><br>
                <p id="description"></p><br><br>
                <p id="statut"></p>




        </div>
    </div>

    <div class="tab_info" id="zoneAffTab">
        @include('accueil.tableauAff.tableau')
    </div>
</div>



    <script> 
    // JS pour gérer l'apparition de la barre latérale 

        function ouvrirBarreLat(bouton){
            
                const titre = bouton.getAttribute('info_titre');
                const description = bouton.getAttribute('info_description');
                const debut = bouton.getAttribute('info_debut');
                const fin = bouton.getAttribute('info_fin');
                const statut = bouton.getAttribute('info_statut');



                document.querySelector("#titre").innerText = titre;
                document.querySelector("#date").innerText = "Date : du "+debut+" au "+fin;
                document.querySelector("#description").innerText = "Description du poste : "+description;
                
                if (statut == 1){

                    document.querySelector("#statut").innerText = "Statut : Candidature validée";

                }
                else if (statut == 2){

                    document.querySelector("#statut").innerText = "Statut : En cours de validation";

                }
                else if (statut == 3){

                    document.querySelector("#statut").innerText = "Statut : Refusée";

                }
                document.getElementById("barreLatérale").style.width = "500px";
        }


        function fermerBarreLat(){
            document.getElementById("barreLatérale").style.width = "0";
        }

    </script>




    <script>
    function chargerContenu(choix) {
        const zone = document.getElementById('zoneAffTab');
        
        // On grise un peu pour montrer qu'on charge
        zone.style.opacity = '0.5';

        // On appelle l'URL actuelle avec le paramètre choix
        fetch('?choix=' + choix, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Force Laravel à voir que c'est de l'AJAX
            }
        })
        .then(response => response.text())
        .then(html => {
            zone.innerHTML = html; // On remplace le tableau
            zone.style.opacity = '1';

            // Mise à jour visuelle des boutons (classe actif)
            document.querySelectorAll('.bouton_choix').forEach(btn => btn.classList.remove('actif'));
const boutonCible = document.querySelector(`button[onclick*="${choix}"]`);
    if (boutonCible) {
        boutonCible.classList.add('actif');
    }
            // On change l'URL dans la barre d'adresse sans recharger
            window.history.pushState({}, '', '?choix=' + choix);
        })
        .catch(error => {
            console.error('Erreur:', error);
            zone.style.opacity = '1';
        });
    }
    </script>




<br><br><br><br><br><br>

@endsection