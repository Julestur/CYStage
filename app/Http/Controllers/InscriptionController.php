<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifCodeMail;

class InscriptionController extends Controller
{
    



    // ----------------------------------------------------------------------------------
    // Affichage de la première étape avec le formulaire d'inscription ------------------
    // ----------------------------------------------------------------------------------
   
    
    public function Vu_Inscription_Etape1() {
        
        return view('inscription.inscriptionEtape1');

    }







    // ----------------------------------------------------------------------------------
    // Traitement du formulaire et envoie de l'email avec le code  ----------------------
    // ----------------------------------------------------------------------------------
   

    public function Traitement_Inscription_Etape1(Request $request) {
        
        $email = $request->input('email');
        $pseudo = $request->input('pseudo');

        // On verifie que le mail et l'identifiant ne soient pas déjè utilisés
        $dejaUtilise = DB::table('utilisateur')->where('email', $email)->orWhere('identifiant', $pseudo)->first();
        if ($dejaUtilise) {
            return back()->with('erreur_mail', 'Email ou Identifiant déjà utilisé.')->withInput(); // Message d'erreur si c'est le cas 
        }

        // Verif si le mdp et la confirmation sont bien identique 
        if ($request->input('mot-de-passe') !== $request->input('confirmation-mot-de-passe')) {
            return back()->with('erreur_mdp', 'Les mots de passe ne correspondent pas.')->withInput();
        }

        // Création du code et ajout des infos a la session
        $codeVerif = rand(100000, 999999);
        Session::put('form_inscription', $request->all());
        Session::put('codeVerif', $codeVerif);

        dd(config('services.mailtrap-sdk'));
        // Envoie du message avec Mailable
        Mail::to($email)->send(new VerifCodeMail($codeVerif));

        return redirect()->route('inscription.Etape2_VU'); // redirection vers l'étape 2
    }








    // ----------------------------------------------------------------------------------
    // Affichage de la deuxième étape : valisation du code  -----------------------------
    // ----------------------------------------------------------------------------------
   

    public function Vu_Inscription_Etape2() {
        if (!Session::has('codeVerif')) 
            return redirect()->route('inscription.Etape1_VU');
        return view('inscription.inscriptionEtape2');
    }











    // ----------------------------------------------------------------------------------
    // Verification du code  ------------------------------------------------------------
    // ----------------------------------------------------------------------------------
   
    public function Traitement_Inscription_Etape2(Request $request) {
        
        if ($request->input('code_saisi') == Session::get('codeVerif')) {
            return $this->Finalisation_Inscription();
        }
        return back()->with('erreur', 'Code incorrect.');
    }







    // ----------------------------------------------------------------------------------
    // Ajout dans la BDD et finalisation de l'inscription  ------------------------------
    // ----------------------------------------------------------------------------------
   

    private function Finalisation_Inscription() {
        $data = Session::get('form_inscription');
        
        // Gestion Entreprise / Classe
        $idEntreprise = null;
        if ($data['grade'] === 'Entreprise' && !empty($data['nom_entreprise'])) {
            $idEntreprise = DB::table('entreprise')->updateOrInsert(['nom' => $data['nom_entreprise']], ['nom' => $data['nom_entreprise']]);
            $idEntreprise = DB::table('entreprise')->where('nom', $data['nom_entreprise'])->value('idEntreprise');
        }

        $idClasse = null;
        if ($data['grade'] === 'Etudiant' && !empty($data['classe'])) {
            $idClasse = DB::table('classe')->where('nom', $data['classe'])->value('idClasse');
        }

        $statutMapping = ['admin' => 1, 'Etudiant' => 2, 'Professeur' => 3, 'Entreprise' => 4];
        $statutVal = $statutMapping[$data['grade']] ?? 2;



        // Ajout des infos dans la BDD
        DB::table('utilisateur')->insert([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'identifiant' => $data['pseudo'],
            'mdp' => Hash::make($data['mot-de-passe']),
            'pdp' => 'profil.png',
            'mdp_tmp' => 'vide',
            'idStatut' => $statutVal,
            'idEntreprise' => $idEntreprise,
            'idClasse' => $idClasse,
            'estVerif' => 1
        ]);

        Session::forget(['codeVerif', 'form_inscription']);
        return redirect()->route('inscription.Etape3_VU');
    }













    // ----------------------------------------------------------------------------------
    // Affichage de la page de reussite de l'incription  --------------------------------
    // ----------------------------------------------------------------------------------
   
    
    public function Vu_Inscription_Etape3() {
        return view('inscription.inscriptionEtape3');
    }
}