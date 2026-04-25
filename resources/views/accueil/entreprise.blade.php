@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">

<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    </div>

    <h2 class="titre2">Tableau de bord</h2>

    {{-- Tableau de bord entreprise : 3 cartes centrées --}}
    <div class="tab_bord_entreprise">
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Mes offres publiées</h3>
                <p class="valeur">{{ $stats['nbOffres'] }}</p>
            </div>
            <ion-icon name="briefcase-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Candidatures reçues</h3>
                <p class="valeur">{{ $stats['nbCandidatures'] }}</p>
            </div>
            <ion-icon name="paper-plane-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>En attente</h3>
                <p class="valeur">{{ $stats['nbEnAttente'] }}</p>
            </div>
            <ion-icon name="time-outline" class="style_icone"></ion-icon>
        </div>
    </div>

    {{-- Barre de choix entreprise --}}
    <div class="barre_choix">
        <button type="button" onclick="chargerContenu('mes_offres')" class="bouton_choix {{ $choix == 'mes_offres' ? 'actif' : '' }}">
            <ion-icon name="briefcase-outline"></ion-icon> Mes offres
        </button>
        <button type="button" onclick="chargerContenu('candidatures')" class="bouton_choix {{ $choix == 'candidatures' ? 'actif' : '' }}">
            <ion-icon name="paper-plane-outline"></ion-icon> Candidatures reçues
        </button>
    </div>

    {{-- Barre latérale --}}
    <div id="barreLatérale">
        <div id="contenuBarreLat">
            <span onclick="fermerBarreLat()" style="cursor:pointer; float:right; font-size:2rem;">&times;</span>
            <h2 id="titre"></h2><br><br>
            <div id="infosDynamiques"></div>
            <div id="zoneStatut" style="display:none; margin-top: 20px;">
                <p><strong>Statut :</strong> <span id="statut"></span></p>
            </div>
        </div>
    </div>

    {{-- Zone du tableau rechargée en AJAX --}}
    <div class="tab_info" id="zoneAffTab">
        @include('accueil.tableauAff.tableau')
    </div>
</div>

<script>
// ── Barre latérale ───────────────────────────────────────────────────────────
function ouvrirBarreLat(bouton) {
    const type        = bouton.getAttribute('info_type');
    const nom         = bouton.getAttribute('info_nom');
    const prenom      = bouton.getAttribute('info_prenom');
    const email       = bouton.getAttribute('info_email');
    const description = bouton.getAttribute('info_description');
    const debut       = bouton.getAttribute('info_debut');
    const fin         = bouton.getAttribute('info_fin');
    const statut      = bouton.getAttribute('info_statut');
    const intitule    = bouton.getAttribute('info_intitule');

    const zoneTitre  = document.querySelector('#titre');
    const zoneInfos  = document.querySelector('#infosDynamiques');
    const zoneStatut = document.getElementById('zoneStatut');
    const statutElt  = document.getElementById('statut');

    let contenu = '';

    if (type === 'mes_offres') {
        // Détails d'une offre publiée par l'entreprise
        zoneTitre.innerText = intitule;
        contenu = `<br><hr><br>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>`;
        if (zoneStatut) zoneStatut.style.display = 'none';

    } else if (type === 'candidatures') {
        // Détails d'une candidature reçue
        zoneTitre.innerText = intitule;
        contenu = `<br><hr><br>
            <p><strong>Candidat :</strong> ${prenom} ${nom}</p>
            <p><strong>Email :</strong> ${email}</p>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>`;

        if (statutElt) {
            statutElt.innerText        = (statut == 1) ? 'Validée' : (statut == 3 ? 'Refusée' : 'En cours');
            statutElt.style.color      = (statut == 1) ? 'green'   : (statut == 3 ? 'red'     : 'orange');
            statutElt.style.fontWeight = 'bold';
        }
        if (zoneStatut) zoneStatut.style.display = 'block';
    }

    if (zoneInfos) zoneInfos.innerHTML = contenu;
    document.getElementById('barreLatérale').style.width = '500px';
}

function fermerBarreLat() {
    document.getElementById('barreLatérale').style.width = '0';
}

window.addEventListener('click', function (event) {
    const barre = document.getElementById('barreLatérale');
    if (!barre || barre.style.width !== '500px') return;
    const estUnBoutonDetails = event.target.closest('.boutonDetails');
    if (!barre.contains(event.target) && !estUnBoutonDetails) {
        fermerBarreLat();
    }
});

// ── Chargement AJAX du tableau ───────────────────────────────────────────────
function chargerContenu(choix) {
    const zone = document.getElementById('zoneAffTab');
    zone.style.opacity = '0.5';

    fetch('?choix=' + choix, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        zone.innerHTML     = html;
        zone.style.opacity = '1';

        document.querySelectorAll('.bouton_choix').forEach(btn => btn.classList.remove('actif'));
        const boutonCible = document.querySelector(`button[onclick*="${choix}"]`);
        if (boutonCible) boutonCible.classList.add('actif');

        window.history.pushState({}, '', '?choix=' + choix);
    })
    .catch(error => {
        console.error('Erreur:', error);
        zone.style.opacity = '1';
    });
}
</script>

<br><br><br><br>
@endsection