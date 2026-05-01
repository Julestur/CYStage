@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">

<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    </div>

    <h2 class="titre2">Tableau de bord</h2>

    <div class=" tab_bord_etudiant">
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Étudiants suivis</h3>
                <p class="valeur">{{ $stats['nbEtudiants'] }}</p>
            </div>
            <ion-icon name="school-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Stages en cours</h3>
                <p class="valeur">{{ $stats['nbStages'] }}</p>
            </div>
            <ion-icon name="briefcase-outline" class="style_icone"></ion-icon>
        </div>
    </div>

    {{-- Barre de choix pour le prof --}}
    <div class="barre_choix">
         <button type="button" onclick="chargerContenu('candidatures')" class="bouton_choix {{ $choix == 'candidatures' ? 'actif' : '' }}">
            <ion-icon name="paper-plane-outline"></ion-icon> Candidatures
        </button>
        <button type="button" onclick="chargerContenu('etudiant')" class="bouton_choix {{ $choix == 'etudiant' ? 'actif' : '' }}">
            <ion-icon name="school-outline"></ion-icon> Étudiants
        </button>
        <button type="button" onclick="chargerContenu('stage')" class="bouton_choix {{ $choix == 'stage' ? 'actif' : '' }}">
            <ion-icon name="briefcase-outline"></ion-icon> Stages
        </button>
       
    </div>

    {{-- Barre latérale --}}
    <div id="barreLatérale">
        <div id="contenuBarreLat">
            <span onclick="fermerBarreLat()">&times;</span>
            <h2 id="titre"></h2><br><br>

            <div id="infosDynamiques"></div>


            <div id="zoneStatut" style="display:none; margin-top: 20px; font-size: 1rem;">
                <p><strong>Statut :</strong> <span  id="statut" style="font-size: 1rem;"></span></p>
            </div>

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
    const classe      = bouton.getAttribute('info_classe');
    const description = bouton.getAttribute('info_description');
    const debut       = bouton.getAttribute('info_debut');
    const fin         = bouton.getAttribute('info_fin');
    const statut      = bouton.getAttribute('info_statut');
    const entreprise  = bouton.getAttribute('info_entreprise');
    const intitule    = bouton.getAttribute('info_intitule');
    const statutEntreprise = bouton.getAttribute('info_statut_entreprise');
    const statutProf       = bouton.getAttribute('info_statut_prof');
    const remarqueProf = bouton.getAttribute('info_remarque_prof');

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

    if (type === 'etudiant') {
        zoneTitre.innerText = `${prenom} ${nom}`;
        contenu = `<br><hr><br>
            <p><strong>Identité :</strong> ${prenom} ${nom}</p>
            <p><strong>Rôle :</strong> Étudiant</p>
            <p><strong>Email :</strong> ${email}</p>
            <p><strong>Classe :</strong> ${classe}</p>`;
        if (zoneStatut) zoneStatut.style.display = 'none';

    } else if (type === 'stage') {
        zoneTitre.innerText = intitule;
        contenu = `<br><hr><br>
            <p><strong>Entreprise :</strong> ${entreprise}</p>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>`;
        if (zoneStatut) zoneStatut.style.display = 'none';

    }  else if (type === 'candidatures') {
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

        contenu = `<br><hr>
            <p><strong>Candidat :</strong> ${prenom} ${nom}</p>
            <p><strong>Email :</strong> ${email}</p>
            <p><strong>Période :</strong> du ${debut} au ${fin}</p>
            <p><strong>Missions :</strong><br>${description}</p>
            <p style="font-size: 1rem; margin-top: 10px;">
                <strong>Statut :</strong> 
                <span style="color: ${statutCouleur};  float : none; font-size : 1rem; font-weight: bold;">${statutTexte}</span>
            </p>`;

        if(zoneStatut) zoneStatut.style.display = 'none';


        contenu2 = `
            <hr>
                <h3>Commentaire du professeur :</h3>
                ${remarqueProf ? `<p style="color: grey; font-style: italic;">${remarqueProf}</p>` : '<p>Aucun commentaire pour l\'instant.</p>'}
                
                <form action="/candidature/commenter/${idCandidature}" method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <textarea name="remarque" rows="4" placeholder="Écrire un commentaire..." 
                        style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; resize: vertical;"
                    >${remarqueProf ?? ''}</textarea>
                    <button type="submit" 
                        style="margin-top: 10px; background-color: #17a2b8; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%;">
                        <ion-icon name="save-outline"></ion-icon> Enregistrer le commentaire
                    </button>
                </form>
            <br><hr>
            <h3>Documents du candidat :</h3>
            <div style="margin-top: 10px;">
                ${cheminCV ? `<a href="${storageUrl}${cheminCV}" target="_blank" class="bouton_telecharger" id="downloadCV">
                    <ion-icon name="document-text-outline"></ion-icon> Télécharger le CV
                </a>` : '<p>Aucun CV joint</p>'}
                
                ${cheminLM ? `<a href="${storageUrl}${cheminLM}" target="_blank" class="bouton_telecharger" id="downloadLM">
                    <ion-icon name="mail-outline"></ion-icon> Lettre de Motivation
                </a>` : '<p>Aucune lettre jointe</p>'}
            </div>


            ${convention ? `
                <br><hr>
                <h3>Convention de stage :</h3>
                <a href="${storageUrl}${convention}" target="_blank" class="bouton_telecharger">
                    <ion-icon name="document-text-outline"></ion-icon> Télécharger la convention
                </a>
                ${verifConvProf== 1 ? '<p style="color:green; margin-top:10px;">✅ Vous avez déjà signé</p>' : `
                <form action="/candidature/convention/signer/prof/${idCandidature}" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
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



            <br><hr>
            <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                <a href="/candidature/accepter/prof/${idCandidature}" 
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
    if (zoneInfos2) zoneInfos2.innerHTML = contenu2; 
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