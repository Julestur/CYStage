@extends('layouts.app')

@section('content')
<div class="page-dimension"> 
    <div class="contenu-principal">  
        <div class="accueil">  
            <h2 class="titre1">Changement de photo de profil</h2>
        </div>

        <hr id="redline-mdp">
        
        <form action="{{ route('changementPDP_GESTION') }}" method="POST" enctype="multipart/form-data" class="portail">
            @csrf
            <div id="drop-zone"> <ion-icon name="image-outline" id="icon-file-drop"></ion-icon>
                <input type="file" name="photo_profil" id="input-file" hidden required>
                </div>
            
            <div id="info-drop-zone" style="margin-top: 10px; color: #555;"></div>

            <p class="info">Veuillez déposer une photo au format .png ou .jpg</p>
            
            <div class="bouton">
                <input type="submit" value="Enregistrer" id="bouton-style-connexion" disabled>
            </div>
        </form>
    </div>
</div>

<br><br>
<br>
<br>
<br>
<br>


<script>

    // GESTION DU DRAG AND DROP
    let inputFile = document.getElementById('input-file');
    let dropZone = document.getElementById('drop-zone');
    let icon = document.getElementById('icon-file-drop');
    let infoDropzone = document.getElementById('info-drop-zone');
    let boutonEnvoi = document.getElementById('bouton-style-connexion');

    function gererFichier(files) {
        if (files.length > 0) {
            let file = files[0]; 
            let ext = file.name.split('.').pop().toLowerCase();
            
            if (!['png', 'jpg', 'jpeg'].includes(ext)) {
                icon.classList.add('error-shake');
                setTimeout(() => icon.classList.remove('error-shake'), 300);
                return;
            }

            infoDropzone.innerHTML = file.name;
            icon.name = "checkmark-outline"; 
            boutonEnvoi.disabled = false;
            boutonEnvoi.classList.add('enabled');

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputFile.files = dataTransfer.files;
        }
    }

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-hover');
    });

    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-hover'));

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-hover');
        gererFichier(e.dataTransfer.files);
    });

    dropZone.addEventListener('click', () => inputFile.click());
    inputFile.addEventListener('change', (e) => gererFichier(e.target.files));
</script>
@endsection