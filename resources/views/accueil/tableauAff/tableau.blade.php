        <div class="zoneTitre">
            
            @if($choix == 'etudiant') <h2 class="titreInfo">Liste des étudiants</h2>
            @elseif($choix == 'stage') <h2 class="titreInfo">Liste des stages</h2>
            @elseif($choix == 'candidatures') <h2 class="titreInfo">Liste des candidatures</h2>
            @elseif($choix == 'prof') <h2 class="titreInfo">Liste des professeurs</h2>
            @elseif($choix == 'entreprise') <h2 class="titreInfo">Liste des entreprises</h2>
            @endif
        
        
        
        
        
        
        
        <a href="{{ url('/ajout-'.$choix) }}" class="boutonAjout">+ Ajouter</a>
        </div>

        <div class="contenu">
            <table class="tabInfo">
                <thead>
                    <tr>
                        @if($choix == 'etudiant') <th>Nom</th><th>Email</th><th>Classe</th>
                        @elseif($choix == 'stage') <th>Titre</th><th>Entreprise</th><th>Début</th>
                        @elseif($choix == 'candidatures') <th>Titre</th><th>Entreprise</th><th>Candidat</th><th>Statut</th>
                        @elseif($choix == 'prof') <th>Nom</th><th>Email</th>
                        @elseif($choix == 'entreprise') <th>Nom</th>

                        @endif
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donnees as $l)
                    <tr>
                        @if($choix == 'etudiant') <td>{{ $l->nom }} {{ $l->prenom }}</td><td>{{ $l->email }}</td><td>{{ $l->nomClasse }}</td>
                        @elseif($choix == 'stage') <td>{{ $l->intitule }}</td><td>{{ $l->nomEntreprise }}</td><td>{{ $l->dateDebut }}</td>
                        @elseif($choix == 'candidatures')
                            <td>{{ $l->intitule }}</td><td>{{ $l->nomEntreprise }}</td><td>{{ $l->prenom }} {{ $l->nom }}</td>
                            <td><ion-icon name="{{ $l->numStatut == 1 ? 'checkmark-circle' : ($l->numStatut == 3 ? 'close-circle' : 'send') }}-outline"></ion-icon></td>
                        @elseif($choix == 'prof') <td>{{ $l->nom }} {{ $l->prenom }}</td><td>{{ $l->email }}</td>
                        @elseif($choix == 'entreprise') <td>{{ $l->nom }}</td>
                            @endif
                        <td><button class="boutonDetails" onclick="ouvrirBarreLat(this)" data-titre="{{ $l->intitule ?? $l->nom }}">Détails</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>