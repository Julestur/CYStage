@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">

<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    </div>

    <h2 class="titre2" style="text-align: center;">Tableau de bord</h2>

    <div class="tab_bord_centre">
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
    {{-- Barre latérale --}}
    <div id="barreLatérale">
        <div id="contenuBarreLat">
            <span onclick="fermerBarreLat()" style="cursor:pointer; float:right; font-size:2rem;">&times;</span>
            <h2 id="titre"></h2><br><br>
            
            <div id="infosDynamiques"></div>
            
            <div id="zoneStatut" style="display:none; margin-top: 20px;">
                <p><strong>Statut :</strong> <span id="statut"></span></p>
            </div>
            
            {{-- ✅ Ajout obligatoire pour ne pas faire planter le JS --}}
            <div id="infosDynamiques2"></div>
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
    const statutEntreprise = bouton.getAttribute('info_statut_entreprise');
    const statutProf       = bouton.getAttribute('info_statut_prof');
    const remarqueProf = bouton.getAttribute('info_remarque_prof');
    
    // Nouveaux attributs
    const idStage     = bouton.getAttribute('info_idstage');
    const cheminCV    = bouton.getAttribute('info_cv'); 
    const cheminLM    = bouton.getAttribute('info_lm');

    const zoneTitre  = document.querySelector('#titre');
    const zoneInfos  = document.querySelector('#infosDynamiques');
    const zoneInfos2 = document.querySelector('#infosDynamiques2'); 
    const zoneStatut = document.getElementById('zoneStatut');
    const statutElt  = document.getElementById('statut');
    const convention          = bouton.getAttribute('info_convention');
    const verifConvEntreprise = bouton.getAttribute('info_verif_convention_entreprise');
    const verifConvProf       = bouton.getAttribute('info_verif_convention_prof');

    const storageUrl = "{{ asset('storage/') }}/";
    let contenu = '';
    let contenu2 = '';

    if (type === 'mes_offres') {
        // Détails d'une offre publiée par l'entreprise + Bouton de suppression
        zoneTitre.innerText = intitule;
        contenu = `<br><hr><br>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>
            <br><hr><br>
            <div style="display: flex; justify-content: center; margin-top: 20px;">
                <a href="/stage/supprimer/${idStage}" 
                onclick="return confirm('⚠️ Attention : Cela supprimera définitivement cette offre ET toutes les candidatures qui y sont liées. Voulez-vous continuer ?')"
                style="background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                    <ion-icon name="trash-outline"></ion-icon> Supprimer cette offre
                </a>
            </div>
            `;

            
        if (zoneStatut) zoneStatut.style.display = 'none';

    } else if (type === 'candidatures') {
        const idCandidature = bouton.getAttribute('info_id_candidature');
        zoneTitre.innerText = intitule;
        
         let statutTexte, statutCouleur;
        if (statutEntreprise == 1 && statutProf == 1) {
            statutTexte = "Acceptée"; statutCouleur = "green";
        } else if (statutEntreprise == 1) {
            statutTexte = "En attente prof"; statutCouleur = "orange";
        } else if (statutProf == 1) {
            statutTexte = "En attente entreprise"; statutCouleur = "orange";
        } else {
            statutTexte = "En attente"; statutCouleur = "grey";
        }

        contenu = `<br><hr><br>
            <p><strong>Candidat :</strong> ${prenom} ${nom}</p>
            <p><strong>Email :</strong> ${email}</p>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>
            <p style="font-size: 1rem; margin-top: 10px;">
                <strong>Statut :</strong> 
                <span style="color: ${statutCouleur};  float : none; font-size : 1rem; font-weight: bold;">${statutTexte}</span>
            </p>
            ${remarqueProf ? `<br><hr><br>
                <h3>Commentaire du professeur :</h3>
                <p style="color: grey; font-style: italic;">${remarqueProf}</p>
            ` : ''}`;

        if(zoneStatut) zoneStatut.style.display = 'none';


        
        // Zone des documents (déjà existante dans votre code)
        contenu2 = `
            <br><hr><br>
            <h3>Documents du candidat :</h3>
            <div style="margin-top: 10px;">
                ${cheminCV ? `<a href="${storageUrl}${cheminCV}" target="_blank" class="bouton_telecharger" id="downloadCV">
                    <ion-icon name="document-text-outline"></ion-icon> Télécharger le CV
                </a>` : '<p>Aucun CV joint</p>'}
                
                ${cheminLM ? `<a href="${storageUrl}${cheminLM}" target="_blank" class="bouton_telecharger" id="downloadLM">
                    <ion-icon name="mail-outline"></ion-icon> Lettre de Motivation
                </a>` : '<p>Aucune lettre jointe</p>'}
            </div><br>

            ${convention ? `
                <br><hr><br>
                <h3>Convention de stage :</h3>
                <a href="${storageUrl}${convention}" target="_blank" class="bouton_telecharger">
                    <ion-icon name="document-text-outline"></ion-icon> Télécharger la convention
                </a>
                ${verifConvEntreprise == 1 ? '<p style="color:green; margin-top:10px;">✅ Vous avez déjà signé</p>' : `
                <form action="/candidature/convention/signer/entreprise/${idCandidature}" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <p>Déposez votre version signée :</p>
                    <input type="file" name="convention_signee" accept=".pdf"
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <button type="submit"
                        style="margin-top: 10px; background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%;">
                        <ion-icon name="pencil-outline"></ion-icon> Signer la convention
                    </button>
                </form>`}
            ` : '<p>Aucune convention déposée par l\'étudiant.</p>'}



            <br><hr><br>
            <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                <a href="/candidature/accepter/entreprise/${idCandidature}" 
                   onclick="return confirm('Voulez-vous vraiment accepter cette candidature ?')"
                   style="background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon> Accepter
                </a>

                <a href="/candidature/refuser/${idCandidature}" 
                   onclick="return confirm('Voulez-vous vraiment refuser (et supprimer) cette candidature ?')"
                   style="background-color: #dc3545; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                    <ion-icon name="close-circle-outline"></ion-icon> Refuser
                </a>
            </div>`;
        

    }
    if (zoneInfos) zoneInfos.innerHTML = contenu;
    if (zoneInfos2) zoneInfos2.innerHTML = contenu2; //  Injection sécurisée

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
