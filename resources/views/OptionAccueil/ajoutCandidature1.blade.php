

@extends('layouts.app')

@section('content')

<div class="page-dimension">
    <div class="contenu-principal">
        <h2 class="titre1">Ajout d'une candidature</h2>
        <hr id="redline-mdp">


        <form action="{{ route('ajoutCandidature.Etape1_GESTION') }}" method="POST" class="portail" enctype="multipart/form-data">
            @csrf 
            
            <!-- AFFICHAGE DES ERREURS -->
            @if ($errors->any())
                    <div style="color: #fa021b; background-color: #f8d7da; padding: 10px; margin-bottom: 20px; border-radius: 5px; font-size: 14px;">
                        <ul style="margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
            <input type="hidden" name="idStage" value="{{ request('idStage') }}"> 
            <input type="hidden" name="idEntreprise" value="{{ request('idEntreprise') }}">

        

            <input type="text" name="nom" style="font-size:20px;" placeholder="Nom" class="contenu-portail" value="{{ old('nom') }}" required>
            <hr>
            <input type="text" name="prenom" style="font-size:20px;" placeholder="Prénom" class="contenu-portail" value="{{ old('prenom') }}" required>
            <hr>
            <input type="email" name="mail" style="font-size:20px;" placeholder="Email" class="contenu-portail" value="{{ old('mail') }}" required>
            <hr>
            
            <br><br>
            <br><br>
            
            <div class = "zone_DragAndDrop"> <ion-icon name="document-text-outline" class="icon-file-drop"></ion-icon>
                <input type="file" name="CV" class="input-file" hidden required accept=".pdf">
                <p class="prompt-text">Glissez votre <strong>CV</strong> ici ou cliquez (.PDF)</p>    
            </div>
            
            <div class="info-drop-zone" style="margin-top: 10px; color: #555;"></div>
            @error('CV')
                <div style="color: #fa021b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
            @enderror

            <br><br>
            <br><br>
            <br><br>


            <div class="zone_DragAndDrop"> <ion-icon name="document-text-outline" class="icon-file-drop"></ion-icon>
                <input type="file" name="lettreMotiv" class="input-file" hidden required accept=".pdf">
                <p class="prompt-text">Glissez votre <strong>Lettre de motivation</strong> ici (.PDF)</p>    
            </div>
            
            <div class="info-drop-zone" style="margin-top: 10px; color: #555;"></div>
            @error('CV')
                <div style="color: #fa021b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        

            <div class="bouton">
                <input type="submit" value="Ajouter la candidature" id="bouton-style-connexion">
            </div>

            <br><br><br><br><br><br>
        </form>
                
    </div>
</div>


<script>
    // JS POUR GERER LE DRAG AND DROP

    // La fonction permet de traiter les deux zones 
    document.addEventListener("DOMContentLoaded", function() {
    
        // Boucle pour recupéré les infos
        document.querySelectorAll('.zone_DragAndDrop').forEach(zone => {
            const ajout_fichier = zone.querySelector('.input-file');
            const icon = zone.querySelector('.icon-file-drop');
            const texte_zone = zone.querySelector('.prompt-text');
            
            const infoZone = zone.nextElementSibling; 

            zone.addEventListener('click', () => ajout_fichier.click());

            ajout_fichier.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    validerEtAfficher(e.target.files[0], zone, icon, infoZone, texte_zone);
                }
            });

            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.style.borderColor = "#28a745"; 
                zone.style.backgroundColor = "#f0fff0"; 
                zone.style.transform = "scale(1.02)";
            });

            zone.addEventListener('dragleave', () => {
                resetStyleZone(zone);
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                resetStyleZone(zone);

                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    
                    // On injecte le fichier dans l'input pour Laravel
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    ajout_fichier.files = dt.files;

                    validerEtAfficher(file, zone, icon, infoZone, texte_zone);
                }
            });
    });




    // Fonction de réinitialisation du style visuel
    function resetStyleZone(zone) {
        zone.style.borderColor = ""; 
        zone.style.backgroundColor = "";
        zone.style.transform = "scale(1)";
    }




    // Fonction de validation et d'affichage
    function validerEtAfficher(file, zone, icon, infoZone, texte_zone) {
        const ext = file.name.split('.').pop().toLowerCase();

        // Affichage du succès
        if (texte_zone) texte_zone.style.opacity = "0.5";
        
        if (infoZone && infoZone.classList.contains('info-drop-zone')) {
            infoZone.innerHTML = "Fichier : "+file.name ;
        }

        // Changement d'icône
        icon.setAttribute('name', 'checkmark-circle');
        icon.style.color = "#28a745";
    }
});
</script>
@endsection