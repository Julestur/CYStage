<div class="zoneTitre">
    @if($choix == 'etudiant')
        <h2 class="titreInfo">Liste des étudiants</h2>
        <a href="{{ route('inscriptionAdmin.Etape1_VU') }}" class="boutonAjout">+ Ajouter</a>
    
    @elseif($choix == 'stage' || $choix == 'mes_offres')
        <h2 class="titreInfo">{{ $choix == 'mes_offres' ? 'Mes offres' : 'Liste des stages' }}</h2>
        @if(in_array(Session::get('grade'), ['Admin', 'Entreprise']))
            <a href="{{ route('ajoutStage.Etape1_VU') }}" class="boutonAjout">+ Ajouter</a>
        @endif
    @elseif($choix == 'candidatures')
        <h2 class="titreInfo">Liste des candidatures</h2>
    @elseif($choix == 'prof')
        <h2 class="titreInfo">Liste des professeurs</h2>
        <a href="{{ route('inscriptionAdmin.Etape1_VU') }}" class="boutonAjout">+ Ajouter</a>
    @elseif($choix == 'entreprise')
        <h2 class="titreInfo">Liste des entreprises</h2>
        <a href="{{ route('inscriptionAdmin.Etape1_VU') }}" class="boutonAjout">+ Ajouter</a>
    @elseif($choix == 'professionnel')
        <h2 class="titreInfo">Liste des professionnels</h2>
        <a href="{{ route('inscriptionAdmin.Etape1_VU') }}" class="boutonAjout">+ Ajouter</a>
    @endif
</div>

<div class="contenu">
    <table class="tabInfo">
        <thead>
            <tr>
                @if($choix == 'etudiant') <th>Nom</th><th>Email</th><th>Classe</th>
                {{-- Ligne corrigée ci-dessous --}}
                @elseif($choix == 'stage' || $choix == 'mes_offres') <th>Titre</th><th>Entreprise</th><th>Début</th>
                @elseif($choix == 'candidatures') <th>Titre</th><th>Entreprise</th><th>Candidat</th><th>Statut</th>
                @elseif($choix == 'prof') <th>Nom</th><th>Email</th>
                @elseif($choix == 'entreprise') <th>Nom</th>
                @elseif($choix == 'professionnel') <th>Nom</th><th>Email</th><th>Entreprise</th>
                @endif
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($donnees as $l)
            <tr>
                @if($choix == 'etudiant') <td>{{ $l->nom }} {{ $l->prenom }}</td><td>{{ $l->email }}</td><td>{{ $l->nomClasse }}</td>
                {{-- Lignes corrigées ci-dessous avec l'opérateur ?? pour éviter l'erreur Undefined property --}}
                @elseif($choix == 'stage' || $choix == 'mes_offres') <td>{{ $l->intitule }}</td><td>{{ $l->nomEntreprise ?? 'Mon entreprise' }}</td><td>{{ $l->dateDebut }}</td>
                @elseif($choix == 'candidatures')
                <td>{{ $l->intitule }}</td>
                <td>{{ $l->nomEntreprise ?? 'Mon entreprise' }}</td>
                <td>{{ $l->prenom }} {{ $l->nom }}</td>
                <td>
                    @if(($l->statut_entreprise ?? 0) == 1 && ($l->statut_prof ?? 0) == 1)
                        <ion-icon name="checkmark-circle-outline" style="color:green"></ion-icon> Acceptée
                    @elseif(($l->statut_entreprise ?? 0) == 1)
                        <ion-icon name="time-outline" style="color:orange"></ion-icon> Attente prof
                    @elseif(($l->statut_prof ?? 0) == 1)
                        <ion-icon name="time-outline" style="color:orange"></ion-icon> Attente entreprise
                    @else
                        <ion-icon name="send-outline" style="color:grey"></ion-icon> En attente
                    @endif
                </td>
                @elseif($choix == 'prof') <td>{{ $l->nom }} {{ $l->prenom }}</td><td>{{ $l->email }}</td>
                @elseif($choix == 'entreprise') <td>{{ $l->nom }}</td>
                @elseif($choix == 'professionnel') <td>{{ $l->nom }} {{ $l->prenom }}</td><td>{{ $l->email }}</td><td>{{ $l->nomEntreprise ?? 'Mon entreprise' }}</td>
                @endif
                <td>
                    <button class="boutonDetails" 
                        onclick="ouvrirBarreLat(this)"
                        info_type = "{{$choix}}"
                        info_nom="{{ $l->nom ?? '' }}"
                        info_prenom="{{ $l->prenom ?? '' }}"
                        info_classe="{{ $l->nomClasse ?? '' }}"
                        info_email="{{ $l->email ?? '' }}"                                
                        info_debut="{{ $l->dateDebut ?? 'N/A' }}"
                        info_fin="{{ $l->dateFin ?? 'N/A' }}"
                        info_statut="{{ $l->numStatut ?? '' }}"
                        info_entreprise="{{ $l->nomEntreprise ?? '' }}"
                        info_intitule="{{ $l->intitule ?? '' }}"
                        info_id_candidature="{{ $l->idCandidature ?? '' }}"
                        info_idstage="{{ $l->idStage ?? '' }}"
                        info_identreprise="{{ $l->idEntreprise ?? '' }}"

                        @if($choix == 'candidatures')
                            info_cv="{{ $l->CV ?? '' }}" 
                            info_lm="{{ $l->LettreMotivation ?? '' }}"
                            info_description="{{ $l->stageDetail ?? 'Aucune description disponible.' }}"
                            info_statut_entreprise="{{ $l->statut_entreprise ?? 0 }}"
                            info_statut_prof="{{ $l->statut_prof ?? 0 }}" 
                            info_remarque_prof="{{ $l->Remarque_Prof ?? '' }}"
                            info_convention="{{ $l->Convention ?? '' }}"                                        
                            info_verif_convention_entreprise="{{ $l->estVerif_Convention_Entreprise ?? 0 }}"    
                            info_verif_convention_prof="{{ $l->estVerif_Convention_Prof ?? 0 }}"
                        @endif

                        {{-- Ligne corrigée ci-dessous --}}
                        @if($choix == 'stage' || $choix == 'mes_offres')
                            info_description="{{ $l->detail ?? 'Aucune description disponible.' }}"
                        @endif>
                        Détails
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
