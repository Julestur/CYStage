@extends('layouts.app')

@section('content')
<div class="page-dimension">
    <div class="contenu-principal"> 
            
        <!-- AFFICHAGE DE LA BARRE DE CHOIX AVEC LES BOUTTONS -->
        <div class="accueil">  
            <h2 class="titre1">Suppression d'un utilisateur</h2>
        </div>
        <hr id="redline-mdp">

        <!-- AFFICHAGE DE LA BARRE DE CHOIX AVEC LES BOUTTONS -->
        <div class="barre_choix">
            <button type="button" onclick="chargerContenu('utilisateur')" class="bouton_choix {{ $type == 'utilisateur' ? 'actif' : '' }}">
                <ion-icon name="people-outline"></ion-icon> Utilisateurs
            </button>
            <button type="button" onclick="chargerContenu('entreprise')" class="bouton_choix {{ $type == 'entreprise' ? 'actif' : '' }}">
                <ion-icon name="business-outline"></ion-icon> Entreprises
            </button>
        </div>
        <br>

        <!-- AFFICHAGE DE LA BARRE DE RECHERCHE -->
        <form method="GET" action="{{ route('suppressionAdmin.Etape1_VU') }}" class="form_recherche">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" class="barreRecherche" name="search" value="{{ $search }}" placeholder="Rechercher par nom...">
            
            <button type="submit" class="bouton_barreRecherche" >
                <ion-icon name="search-outline"></ion-icon></ion-icon> Rechercher
            </button>

            @if($search)
                <a href="{{ route('suppressionAdmin.Etape1_VU', ['type' => $type]) }}">Réinitialiser</a>
            @endif
        </form>




        <!-- AFFICHAGE DES PROFILS DANS LE TABLEAU EN FONCTION DES CHOIX -->
        <div id="zoneAffTab">
        <table class="tabInfo">
            <thead>
                <tr style="background-color: #f4f4f4;">
                    <th style="padding: 10px;">Nom</th>
                    @if($type == 'utilisateur')
                        <th style="padding: 10px;">Situation</th>
                    @endif
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donnees as $d)
                    <tr>
                        <td style="padding: 10px;">
                            {{ $type == 'entreprise' ? $d->nom : $d->prenom . ' ' . $d->nom }}
                        </td>
                        @if($type == 'utilisateur')
                            <td style="padding: 10px;">{{ $d->grade }}</td>
                        @endif
                        <td style="padding: 10px;">
                            <a href="{{ route('suppressionAdmin.Etape2_VU', ['id' => ($type == 'entreprise' ? $d->idEntreprise : $d->idUtilisateur), 'type' => $type]) }}" 
                            style="color: #e74c3c; font-weight: bold; text-decoration: none;">
                            <ion-icon name="trash-outline"></ion-icon> Supprimer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 20px; text-align: center;">Aucun résultat trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <br><br><br><br><br><br>
        </div>
</div>
</div>
@endsection












<script>
function chargerContenu(choix) {
    // On récupère la zone à modifier
    const zone = document.getElementById('zoneAffTab');
    if(!zone) return;

    zone.style.opacity = '0.5';


    fetch('?type=' + choix, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.text();
    })
    .then(html => {
        // On extrait uniquement le tableau du HTML reçu
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const nouveauContenu = doc.getElementById('zoneAffTab');

        if (nouveauContenu) {
            zone.innerHTML = nouveauContenu.innerHTML;
        }


        zone.style.opacity = '1';

        document.querySelectorAll('.bouton_choix').forEach(btn => btn.classList.remove('actif'));
        const boutonCible = document.querySelector(`button[onclick*="${choix}"]`);
        if (boutonCible) boutonCible.classList.add('actif');


        window.history.pushState(null, '', '?type=' + choix);
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);

        window.location.href = '?type=' + choix;
    });
}
</script>
