<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">



<div id="header-wrapper">
    <header>
               
        <div id="overlay"></div>

        <div id="profil">
            @if(Session::get('photo-profil'))
                <img src="{{ asset('images_profil/' . Session::get('photo-profil')) }}" alt="Photo de profil">
            @else
                <ion-icon name="person-circle-outline" style="font-size: 125px; color: white;"></ion-icon>
            @endif

            <div id="profil-info">
                <p>{{ Session::get('prenom') }}</p>
                <small>{{ Session::get('grade') }}</small> {{-- optionnel, supprime cette ligne si tu ne veux pas afficher le rôle --}}
            </div>
            
        </div>

        <div id="profil-deroulant">
            <p>profil</p>
            <ul>
                <li>
                    <a href="{{ route('accueil') }}">
                        <span>accueil</span>
                        <ion-icon name="school-outline"></ion-icon>
                    </a>
                </li>
                <li>
                    <a href="{{ route('aff.changementMDP') }}">
                        <span>changer mot de passe</span>
                        <ion-icon name="shield-half-outline"></ion-icon>
                    </a>
                </li>
                <li>
                    <a href="{{ route('aff.changementPDP') }}">
                        <span>changer photo de profil</span>
                        <ion-icon name="person-circle-outline"></ion-icon>
                    </a>
                </li>
                <li>
                    <a href="{{ route('messages.discussion') }}">
                        <span>Messages</span>
                        <ion-icon name="chatbubbles-outline"></ion-icon> 
                    </a>
                </li>
                @if(strtolower(Session::get('grade')) == 'admin')
                    <li>
                        <a href="{{ route('inscriptionAdmin.Etape1_VU') }}">
                            <span>ajouter profil</span>
                            <ion-icon name="add-circle-outline"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppressionAdmin.Etape1_VU') }}">
                            <span>supprimer profil</span>
                            <ion-icon name="remove-circle-outline"></ion-icon>
                        </a>
                    </li>
                @endif
                
                <li>
                    <a class="red" href="{{ route('logout') }}">
                        <span>deconnexion</span>
                        <ion-icon name="exit-outline"></ion-icon>
                    </a>
                </li>
            </ul>
        </div>


         <div class="titre-wrapper">
            <div class="titre-divider"></div>
            <h1 class="titre">Gestion des stages</h1>
        </div>

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="{{ asset('js/menuDeroulant.js') }}"></script>
    </header>
</div>