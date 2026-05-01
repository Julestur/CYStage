@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/accueilAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">

<div class="page-dimension">
    <div class="accueil">
        <h2 class="titre1">Messages</h2>
    </div>

    <div style="padding: 20px;">
        @forelse($candidatures as $c)
        <div class="carre_style" style="margin-bottom: 10px; cursor:pointer;" onclick="toggleConversations({{ $c->idCandidature }})">
            <div class="contenu_carte">
                <h3>{{ $c->intitule }}</h3>
                <p>{{ $c->nomEntreprise ?? '' }} — {{ $c->prenom ?? '' }} {{ $c->nom ?? '' }}</p>
            </div>
            <ion-icon name="chatbubbles-outline" class="style_icone"></ion-icon>
        </div>

        <div id="conv_{{ $c->idCandidature }}" style="display:none; margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 10px;">

            @if($grade === 'Etudiant')
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_entreprise', 'Entreprise')" class="bouton_choix">
                    Discuter avec l'entreprise
                </button>
            
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_profs', 'Professeur')" class="bouton_choix" style="margin-left:10px;">
                    Discuter avec le professeur
                </button>

            @elseif($grade === 'Entreprise')
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_entreprise', 'Étudiant')" class="bouton_choix">
                    Discuter avec l'étudiant
                </button>
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'entreprise_profs', 'Professeurs')" class="bouton_choix" style="margin-left:10px;">
                    Discuter avec les professeurs
                </button>

            @elseif($grade === 'Professeur')
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_profs', 'Étudiant')" class="bouton_choix">
                    Discuter avec l'étudiant
                </button>
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'entreprise_profs', 'Entreprise')" class="bouton_choix" style="margin-left:10px;">
                    Discuter avec l'entreprise
                </button>

            @elseif($grade === 'Admin')
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_entreprise', 'Étudiant ↔ Entreprise')" class="bouton_choix">
                    Étudiant ↔ Entreprise
                </button>
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'etudiant_profs', 'Étudiant ↔ Profs')" class="bouton_choix" style="margin-left:10px;">
                    Étudiant ↔ Profs
                </button>
                <button onclick="ouvrirChat({{ $c->idCandidature }}, {{ $idUtilisateur }}, 'entreprise_profs', 'Entreprise ↔ Profs')" class="bouton_choix" style="margin-left:10px;">
                    Entreprise ↔ Profs
                </button>
            @endif

            {{-- Zone de chat --}}
            <div id="chat_{{ $c->idCandidature }}" style="display:none; margin-top: 20px;">
                <p id="titre_chat_{{ $c->idCandidature }}" style="font-weight:bold; margin-bottom:10px;"></p>
                <div id="messages_{{ $c->idCandidature }}" 
                     style="height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; margin-bottom: 10px;">
                </div>
               <div style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; background: white;">
                    <textarea id="input_{{ $c->idCandidature }}" rows="2" placeholder="Votre message..." 
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; resize:none; box-sizing: border-box;"></textarea>
                    
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                        <label for="fichier_{{ $c->idCandidature }}" 
                            style="cursor:pointer; background: #f0f0f0; padding: 8px 12px; border-radius: 8px; border: 1px solid #ccc; display: flex; align-items: center; gap: 5px;">
                            <ion-icon name="attach-outline"></ion-icon>
                            <span id="nomFichier_{{ $c->idCandidature }}">Joindre un fichier</span>
                        </label>
                        <input type="file" id="fichier_{{ $c->idCandidature }}" accept=".pdf,.jpg,.jpeg,.png"
                            style="display:none;"
                            onchange="afficherNomFichier({{ $c->idCandidature }})">
                        
                        <button onclick="envoyerMessage({{ $c->idCandidature }})"
                            style="margin-left:auto; background-color: #699ff5; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                            <ion-icon name="send-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <p style="text-align:center; color:grey;">Aucune candidature disponible pour la messagerie.</p>
        @endforelse
    </div>
</div>

<script>
    let chatActif = {};
    let intervalActif = null;
    const monId = {{ $idUtilisateur }};

    function toggleConversations(idCandidature) {
        const zone = document.getElementById('conv_' + idCandidature);
        zone.style.display = zone.style.display === 'none' ? 'block' : 'none';
    }

    function ouvrirChat(idCandidature, idExpediteur, canal, nomInterlocuteur) {
        const zone  = document.getElementById('chat_' + idCandidature);
        const titre = document.getElementById('titre_chat_' + idCandidature);

        zone.style.display  = 'block';
        titre.innerText     = 'Conversation : ' + nomInterlocuteur;

        chatActif[idCandidature] = { idExpediteur, canal };

        chargerMessages(idCandidature);

        if (intervalActif) clearInterval(intervalActif);
        intervalActif = setInterval(() => chargerMessages(idCandidature), 5000);
    }

    function afficherNomFichier(idCandidature) {
    const input = document.getElementById('fichier_' + idCandidature);
    const label = document.getElementById('nomFichier_' + idCandidature);
    label.innerText = input.files[0] ? input.files[0].name : 'Joindre un fichier';
}

function chargerMessages(idCandidature) {
    const { canal } = chatActif[idCandidature];
    const zone = document.getElementById('messages_' + idCandidature);

    const etaitEnBas = zone.scrollTop + zone.clientHeight >= zone.scrollHeight - 50;
    fetch(`/message/recuperer/${idCandidature}/${canal}`)
        .then(r => r.json())
        .then(messages => {
            const zone = document.getElementById('messages_' + idCandidature);
            zone.innerHTML = messages.length === 0 
                ? '<p style="color:grey; text-align:center;">Aucun message pour l\'instant.</p>'
                : messages.map(m => {
                    const estMoi = m.idExpediteur == monId;
                    
                    // Affichage du fichier
                    let fichierHtml = '';
                    if (m.fichier) {
                        const ext = m.fichier.split('.').pop().toLowerCase();
                        const url = `/storage/${m.fichier}`;
                        if (['jpg', 'jpeg', 'png'].includes(ext)) {
                            fichierHtml = `<br><img src="${url}" style="max-width: 200px; border-radius: 8px; margin-top: 5px;">`;
                        } else {
                            fichierHtml = `<br><a href="${url}" target="_blank" 
                                style="color: ${estMoi ? 'white' : '#699ff5'}; display:flex; align-items:center; gap:4px; margin-top:5px;">
                                <ion-icon name="document-outline"></ion-icon> ${m.nom_fichier ?? 'Télécharger le fichier'}
                            </a>`;
                        }
                    }

                    return `
                        <div style="margin-bottom: 10px; text-align: ${estMoi ? 'right' : 'left'};">
                            <span style="background: ${estMoi ? '#699ff5' : '#e0e0e0'}; 
                                         color: ${estMoi ? 'white' : 'black'};
                                         padding: 8px 12px; border-radius: 12px; display: inline-block; max-width: 70%; text-align:left;">
                                <strong>${m.prenom} ${m.nom}</strong><br>
                                ${m.contenu ? m.contenu : ''}
                                ${fichierHtml}
                                <br><small style="opacity:0.7;">${new Date(m.created_at).toLocaleTimeString()}</small>
                            </span>
                        </div>
                    `;
                }).join('');

            if (etaitEnBas) {
                zone.scrollTop = zone.scrollHeight;
            }
        });
}

function envoyerMessage(idCandidature) {
    const { idExpediteur, canal } = chatActif[idCandidature];
    const input   = document.getElementById('input_' + idCandidature);
    const fichier = document.getElementById('fichier_' + idCandidature);
    const contenu = input.value.trim();

    // Il faut au moins un message ou un fichier
    if (!contenu && !fichier.files[0]) return;

    const formData = new FormData();
    formData.append('idCandidature', idCandidature);
    formData.append('idExpediteur',  idExpediteur);
    formData.append('canal',         canal);
    formData.append('contenu',       contenu);
    if (fichier.files[0]) {
        formData.append('fichier', fichier.files[0]);
    }

    fetch('/message/envoyer', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(() => {
        input.value = '';
        fichier.value = '';
        document.getElementById('nomFichier_' + idCandidature).innerText = 'Joindre un fichier';
        chargerMessages(idCandidature);
    });
}
</script>
@endsection
