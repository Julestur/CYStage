<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OPTION_ajoutUtilisateurController extends Controller
{


    // ----------------------------------------------------------------------------------
    // Affichage de la première étape avec le formulaire d'inscription ------------------
    // ----------------------------------------------------------------------------------
   
    
    public function Vu_InscriptionAdmin_Etape1() {
        
        return view('OptionAccueil.ajoutUtilisateur1');

    }



    // ----------------------------------------------------------------------------------
    // Traitement du formulaire ---------------------------------------------------------
    // ----------------------------------------------------------------------------------
   

    public function Traitement_InscriptionAdmin_Etape1(Request $requete)
    {



        // Création de messages d'erreurs en français
        $messages = [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            'mail.required'            => 'L\'adresse email est obligatoire.',
            'mail.email'               => 'Le format de l\'email est invalide.',
            'mail.unique'              => 'Cet email est déjà utilisé.',
            'pseudo.required'          => 'L\'identifiant est obligatoire.',
            'pseudo.unique'            => 'Cet identifiant est déjà pris.',
            'MDP.required'    => 'Le mot de passe est obligatoire.',
            'MDP.min'         => 'Le mot de passe doit faire au moins 8 caractères.',
            'MDP.confirmed'   => 'La confirmation ne correspond pas au mot de passe.',
            'grade.required'           => 'Veuillez choisir un statut.',
        ];


        // Création des conditions du formulaire 
        $requete->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'mail' => 'required|email|unique:utilisateur,email',
            'pseudo' => 'required|unique:utilisateur,identifiant',
            'MDP' => 'required|min:8|confirmed',
            'grade' => 'required'
        ], $messages);


        


        // Détermination du statut 
        $statuts = [
            'admin' => 1,
            'Etudiant' => 2,
            'Professeur' => 3,
            'Entreprise' => 4
        ];
        $statutVal = $statuts[$requete->grade] ?? 2;

        try {
            DB::beginTransaction(); 

            $idEntreprise = null;
            $idClasse = null;

            // Gestion Entreprise (si grade Entreprise)
            if ($statutVal == 4 && $requete->filled('nom_entreprise')) {
                // firstOrCreate cherche si l'entreprise existe, sinon la crée
                $entreprise = DB::table('entreprise')->where('nom', $requete->nom_entreprise)->first();
                if ($entreprise) {
                    $idEntreprise = $entreprise->idEntreprise;
                } else {
                    $idEntreprise = DB::table('entreprise')->insertGetId(['nom' => $requete->nom_entreprise]);
                }
            }

            // Gestion Classe (si grade Etudiant)
            if ($statutVal == 2 && $requete->filled('classe')) {
                $classe = DB::table('classe')->where('nom', $requete->classe)->first();
                if ($classe) {
                    $idClasse = $classe->idClasse;
                } else {
                    $idClasse = DB::table('classe')->insertGetId(['nom' => $requete->classe]);
                }
            }

            // Création de l'utilisateur
            DB::table('utilisateur')->insert([
                'nom' => $requete->nom,
                'prenom' => $requete->prenom,
                'email' => $requete->mail,
                'identifiant' => $requete->pseudo,
                'mdp' => Hash::make($requete['MDP']), 
                'pdp' => 'profil.png',
                'mdp_tmp' => 'vide',
                'idStatut' => $statutVal,
                'idEntreprise' => $idEntreprise,
                'idClasse' => $idClasse
            ]);

            DB::commit(); 

            return view('OptionAccueil.ajoutUtilisateur2', ['prenom' => $requete->prenom]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de l\'inscription : ' . $e->getMessage()])->withInput();
        }
    }
}
