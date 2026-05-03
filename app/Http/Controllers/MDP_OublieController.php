<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReinitialisationMDP;
use Illuminate\Support\Facades\Hash;


class MDP_OublieController extends Controller {

    public function sendResetLink(Request $request) {
        $email = $request->input('email');
        $user = DB::table('utilisateur')->where('email', $email)->first();

        if (!$user) {
            return back()->with('erreur', 'Adresse e-mail introuvable.');
        }

        $token = Str::random(64);
        DB::table('changement_m_d_p')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        // On génère le lien vers la route 'password.reset' définie au-dessus
        $url = route('password.reset', ['token' => $token, 'email' => $email]);

        try {
            Mail::to($email)->send(new ReinitialisationMDP($url));
            return back()->with('confirmation', 'Le lien de récupération a été envoyé par mail.');
        } catch (\Exception $e) {
            return back()->with('erreur', "Erreur lors de l'envoi du mail.");
        }
    }

    public function showResetForm($token, Request $request) {
        // On récupère l'email dans l'URL pour l'envoyer à la vue
        $email = $request->query('email');

        // On utilise ton nom de fichier : reinitMDP_VU
        return view('gestionMDP.reinitMDP_VU', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function updatePassword(Request $request) {
        
        // Création de messages d'erreurs qui vont etre affichés sur la page 
        $message = [
        'password.required' => 'Le nouveau mot de passe est obligatoire.',
        'password.min'      => 'Le mot de passe doit faire au moins 8 caractères.',
        'password.confirmed'=> 'Les deux mots de passe ne sont pas identiques.',
        'email.required'    => 'L\'adresse e-mail est introuvable.',
        'email.email'       => 'Le format de l\'adresse e-mail n\'est pas valide.',
    ];
    
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', 
        ],$message);

        $resetData = DB::table('changement_m_d_p')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData) {
            return back()->with('erreur', 'Le lien est invalide ou a expiré.');
        }

        

        $user = DB::table('utilisateur')->where('email', $request->email)->first();
        

if (!$user) {
        dd("Utilisateur non trouvé pour l'email : " . $request->email);
    }

        if ($request->password == $user->mdp) {
            return back()->with('erreur', 'Le nouveau mot de passe doit être différent de l\'ancien.');
        }

        DB::table('utilisateur')
            ->where('email', $request->email)
            ->update([
            'mdp' => Hash::make($request->password), // On crypte le nouveau MDP
            'mdp_tmp' => 'vide' // On remet le MDP temporaire à 'vide' pour éviter les conflits
    ]);

        DB::table('changement_m_d_p')->where('email', $request->email)->delete();


        return redirect()->route('login')->with('confirmation', 'Mot de passe modifié avec succès !');
    }
}
