<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/accueilStyle.css') }}">




<header>
    <div id="overlay-header"></div>
    
    <div id="overlay"></div>

    <div id="profil">
        @if(Session::get('photo-profil'))
            {{-- Attention : vérifie que le dossier est bien public/Images/ --}}
            <img src="{{ asset('Images/' . Session::get('photo-profil')) }}" alt="Photo de profil">
        @else
            <ion-icon name="person-circle-outline" style="font-size: 125px; color: white;"></ion-icon>
        @endif
        <p>{{ Session::get('prenom') }}</p>
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
                <a href="{{ route('password.temp') }}">
                    <span>changer mot de passe</span>
                    <ion-icon name="shield-half-outline"></ion-icon>
                </a>
            </li>
            
            @if(strtolower(Session::get('grade')) == 'admin')
                <li>
                    <a href="#">
                        <span>ajouter profil</span>
                        <ion-icon name="add-circle-outline"></ion-icon>
                    </a>
                </li>
                <li>
                    <a href="#">
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


    <h1 class="titre"> GESTION DES STAGES </h1>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('js/menuDeroulant.js') }}"></script>
</header>