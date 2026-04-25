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
    <button type="button" onclick="chargerContenu('professionnel')" class="bouton_choix {{ $choix == 'professionnel' ? 'actif' : '' }}">
        <ion-icon name="business-outline"></ion-icon> Professionnels
    </button>
    
    </div>









    <!-- GESTION DE LA BARRE LATERALE -->
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


        </div>
    </div>















    <div class="tab_info" id="zoneAffTab">
        @include('accueil.tableauAff.tableau')
    </div>
</div>



    <script> 
    // JS pour gérer l'apparition de la barre latérale 
    function ouvrirBarreLat(bouton) {
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

        const cheminCV = bouton.getAttribute('info_cv'); // Récupère le chemin du CV
        const cheminLM = bouton.getAttribute('info_lm');

        
        const zoneTitre = document.querySelector("#titre");
        const zoneInfos = document.querySelector("#infosDynamiques");
        const zoneInfos2 = document.querySelector("#infosDynamiques2");

        const zoneStatut = document.getElementById("zoneStatut"); 
        const statutElt = document.getElementById("statut");

        const storageUrl = "{{ asset('storage/') }}/";
        let contenu = ""; 
        let contenu2 = ""; 


        if (type === 'etudiant') {

            zoneTitre.innerText = `${prenom} ${nom}`;

            contenu = ` <br><hr><br>
                <p><strong>Identité :</strong> ${prenom} ${nom}</p>
                <p><strong>Rôle :</strong> Étudiant</p>
                <p><strong>Email :</strong> ${email}</p>
                <p><strong>Classe :</strong> ${classe}</p>
            `;
            if(zoneStatut) zoneStatut.style.display = "none";







        } else if (type === 'prof') {

            zoneTitre.innerText = `${prenom} ${nom}`;

            contenu = ` <br><hr><br>
                <p><strong>Identité :</strong> ${prenom} ${nom}</p>
                <p><strong>Rôle :</strong> Professeur</p>
                <p><strong>Email :</strong> ${email}</p>
            `;
            if(zoneStatut) zoneStatut.style.display = "none";






        } else if (type === 'candidatures') {
            
            zoneTitre.innerText = intitule;

            contenu = `<br><hr><br>
                ${prenom ? `<p><strong>Candidat :</strong> ${prenom} ${nom}</p>` : ''}
                <p><strong>Entreprise :</strong> ${entreprise}</p>
                <p><strong>Date :</strong> du ${debut} au ${fin}</p>
                <p><strong>Description :</strong><br>${description}</p>
            `;
            

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


                statutElt.innerText = (statut == 1) ? "Validée" : (statut == 3 ? "Refusée" : "En cours");
                statutElt.style.color = (statut == 1) ? "green" : (statut == 3 ? "red" : "orange");
                statutElt.style.fontWeight = "bold";      
                statutElt.style.padding = "0px 3px";    
                statutElt.style.fontSize = "1rem";
                if(zoneStatut) zoneStatut.style.display = "block";



            
            
            
            
            } else if (type === 'stage') {
            
                zoneTitre.innerText = intitule;


                const idStage = bouton.getAttribute('info_idstage');
                const idEntreprise = bouton.getAttribute('info_identreprise');

                contenu = `<br><hr><br>
                <p><strong>Entreprise :</strong> ${entreprise}</p>
                <p><strong>Date :</strong> du ${debut} au ${fin}</p>
                <p><strong>Description :</strong><br>${description}</p>
                <div style="display: flex; justify-content: center; margin-top: 30px;">
                    <a href="{{ route('ajoutCandidature.Etape1_VU') }}?idStage=${idStage}&idEntreprise=${idEntreprise}" class="boutonAjout">Candidater</a>
                </div>
                `;


                if(zoneStatut) zoneStatut.style.display = "none";






            } else {
                if(zoneStatut) zoneStatut.style.display = "none";
            }
        
        if(zoneInfos){
            zoneInfos.innerHTML = contenu;
            zoneInfos2.innerHTML = contenu2;
        } 
        document.getElementById("barreLatérale").style.width = "500px";
    }
    


    // FONCTION POUR FERMER LA BARRE LATERALE
    function fermerBarreLat() {
        document.getElementById("barreLatérale").style.width = "0";
    }

    // GESTION DES CLICS HORS DE LA BARRE LATERALE 
    window.addEventListener('click', function(event) {
        const barre = document.getElementById("barreLatérale");
        if (!barre || barre.style.width !== "500px") return;
            const estUnBoutonDetails = event.target.closest('.boutonDetails');
        if (!barre.contains(event.target) && !estUnBoutonDetails) {
            fermerBarreLat();
        }
    });

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