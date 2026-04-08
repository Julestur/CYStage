
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Changer votre mot de passe temporaire</h2>
    <form action="#" method="POST">
        @csrf
        <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Valider</button>
    </form>
</div>



<!-- Footer  -->
<footer class="footer">
    <p>&copy; {{ date('Y') }}, CYStage</p>
    <p id="footerLogo"><img id="logo" src="{{ asset('Images/LogoCyBlanc.png') }}"></p>
</footer>

</body>


<!-- Gestion du logo CY dans l'onglet  -->
<script> 
window.laravelAssets = {lightIcon: "{{ asset('Images/LogoCyNoir.png') }}",darkIcon: "{{ asset('Images/LogoCyBlanc.png') }}"};
</script>
<script src="{{ asset('js/iconOnglet.js') }}"></script>



@endsection