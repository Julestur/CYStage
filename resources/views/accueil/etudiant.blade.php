@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">

<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">{{ $salutation }}, {{ $prenom }} !</h2>
    </div>

    <h2 class="titre2">Tableau de bord</h2>

    {{-- Tableau de bord étudiant : 2 cartes centrées --}}
    <div class="tab_bord_etudiant">
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Mes candidatures</h3>
                <p class="valeur">{{ $stats['nbCandidatures'] }}</p>
            </div>
            <ion-icon name="paper-plane-outline" class="style_icone"></ion-icon>
        </div>
        <div class="carre_style">
            <div class="contenu_carte">
                <h3>Offres disponibles</h3>
                <p class="valeur">{{ $stats['nbStages'] }}</p>
            </div>
            <ion-icon name="briefcase-outline" class="style_icone"></ion-icon>
        </div>
    </div>

    {{-- Barre de choix réduite pour l'étudiant --}}
    <div class="barre_choix">
        <button type="button" onclick="chargerContenu('candidatures')" class="bouton_choix {{ $choix == 'candidatures' ? 'actif' : '' }}">
            <ion-icon name="paper-plane-outline"></ion-icon> Candidatures
        </button>
        <button type="button" onclick="chargerContenu('stage')" class="bouton_choix {{ $choix == 'stage' ? 'actif' : '' }}">
            <ion-icon name="briefcase-outline"></ion-icon> Stages
        </button>
    </div>

    {{-- Barre latérale --}}
    <div id="barreLatérale">
        <div id="contenuBarreLat">
            
            <span onclick="fermerBarreLat()" style="cursor:pointer; float:right; font-size:2rem;"> &times; </span>
        
            <h2 id="titre"></h2> <br><br>

            
            <div id="infosDynamiques"></div>
            <br>

            <div id="zoneStatut" style="display:none; margin-top: 20px;">
                <p class="statut"><strong>Statut :</strong> <span class="statut" id="statut"></span></p>
            </div>
            <br><br>


            <div id="infosDynamiques2"></div>

            
            <div id="infosDynamiques2"></div>
            
            
          
        </div>
    </div>
</div>
        


    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px auto; display: block; width: 90%;border-radius: 8px;text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Zone du tableau rechargée en AJAX --}}
    <div class="tab_info" id="zoneAffTab">
        @include('accueil.tableauAff.tableau')
    </div>

<script>
// ── Barre latérale ───────────────────────────────────────────────────────────

function ouvrirBarreLat(bouton) {
    // Récupération des attributs de base
    const type = bouton.getAttribute('info_type');
        const titre = bouton.getAttribute('info_titre');
        const nom = bouton.getAttribute('info_nom');
        const prenom = bouton.getAttribute('info_prenom');
        const email = bouton.getAttribute('info_email'); 
        const classe = bouton.getAttribute('info_classe');
        const description = bouton.getAttribute('info_description');
        const debut = bouton.getAttribute('info_debut');
        const fin = bouton.getAttribute('info_fin');
        const statut = bouton.getAttribute('info_statut');
        const entreprise = bouton.getAttribute('info_entreprise');
        const intitule = bouton.getAttribute('info_intitule');
        const statutEntreprise = bouton.getAttribute('info_statut_entreprise');
        const statutProf       = bouton.getAttribute('info_statut_prof');
        const remarqueProf = bouton.getAttribute('info_remarque_prof');
        const cheminCV = bouton.getAttribute('info_cv');
        const cheminLM = bouton.getAttribute('info_lm');

        
        const zoneTitre = document.querySelector("#titre");
        const zoneInfos = document.querySelector("#infosDynamiques");
        const zoneInfos2 = document.querySelector("#infosDynamiques2");

        const zoneStatut = document.getElementById("zoneStatut"); 



    
    const storageUrl = "{{ asset('storage/') }}/";
    let contenu = ""; 
    let contenu2 = ""; 

    // Logique selon le type d'élément cliqué
    if (type === 'stage') {

        zoneTitre.innerText = intitule;


                const idStage = bouton.getAttribute('info_idstage');
                const idEntreprise = bouton.getAttribute('info_identreprise');

                contenu += `<br><hr><br>
                <p><strong>Entreprise :</strong> ${entreprise}</p>
                <p><strong>Date :</strong> du ${debut} au ${fin}</p>
                <p><strong>Description :</strong><br>${description}</p>
                <div style="display: flex; justify-content: center; margin-top: 30px;">
                    <a href="{{ route('ajoutCandidature.Etape1_VU') }}?idStage=${idStage}&idEntreprise=${idEntreprise}" class="boutonAjout">Candidater</a>
                </div>
                `
               ;


                if(zoneStatut) zoneStatut.style.display = "none";

    } else if (type === 'candidatures') {
        // --- VUE CANDIDATURE ---
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
            

                contenu2 += `
                <br><hr><br>
                <h3>Documents joints :</h3>
                <div style="margin-top: 10px;">
                    ${cheminCV ? `<a href="${storageUrl}${cheminCV}" target="_blank" class="bouton_telecharger" >
                        <ion-icon name="document-text-outline"></ion-icon> Télécharger le CV
                    </a>` : '<p>Aucun CV joint</p>'}
                    
                    ${cheminLM ? `<a href="${storageUrl}${cheminLM}" target="_blank" class="bouton_telecharger" >
                        <ion-icon name="mail-outline"></ion-icon> Lettre de Motivation
                    </a>` : '<p>Aucune lettre jointe</p>'}
                </div><br>`;






                const idCandidature = bouton.getAttribute('info_id_candidature');

                contenu2 += `
                        <br><hr><br>
                        <div style="display: flex; justify-content: center;">
                            <a href="/candidature/supprimer/${idCandidature}" 
                            onclick="return confirm('Voulez-vous vraiment supprimer cette candidature ?')"
                            style="background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                                <ion-icon name="trash-outline"></ion-icon> Supprimer la candidature
                            </a>
                        </div>
                    `;

    }

    if(zoneInfos){
            zoneInfos.innerHTML = contenu;
            zoneInfos2.innerHTML = contenu2;
    } 
    
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