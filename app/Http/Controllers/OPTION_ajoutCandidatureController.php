<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OPTION_ajoutCandidatureController extends Controller
{


    // ----------------------------------------------------------------------------------
    // Affichage de la première étape avec le formulaire d'inscription ------------------
    // ----------------------------------------------------------------------------------
   
    public function Vu_AjoutCandidature_Etape1() {
        
        return view('OptionAccueil.ajoutCandidature1');

    }



    // ----------------------------------------------------------------------------------
    // Traitement du formulaire ---------------------------------------------------------
    // ----------------------------------------------------------------------------------
   

    public function Traitement_AjoutCandidature_Etape1(Request $requete){


        // Création de messages d'erreurs en frnacais
        $messages = [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            
            'mail.required'            => 'L\'adresse email est obligatoire.',
            'mail.email'               => 'Le format de l\'email est invalide.',
            
            'CV.required'           => 'Veuillez déposer votre CV.',
            'CV.file'               => 'Le transfert du CV a échoué.',
            'CV.mimes'              => 'Le CV doit être au format PDF.',
            'CV.max'                => 'Le CV ne doit pas dépasser 2 Mo.',
            
            'lettreMotiv.required'  => 'Veuillez déposer votre lettre de motivation.',
            'lettreMotiv.file'      => 'Le transfert de la lettre a échoué.',
            'lettreMotiv.mimes'     => 'La lettre doit être au format PDF.',
            'lettreMotiv.max'       => 'La lettre ne doit pas dépasser 2 Mo.',
            

            'CV.uploaded' => 'Le fichier est trop lourd pour le serveur (max 2 Mo).',
            'lettreMotiv.uploaded' => 'Le fichier est trop lourd pour le serveur (max 2 Mo).',
        ];

        $requete->validate([
        'nom'          => 'required|string|max:255',
        'prenom'       => 'required|string|max:255',
        'mail'         => 'required|email|max:255',
        'CV'           => 'required|file|mimes:pdf|max:2048',
        'lettreMotiv'  => 'required|file|mimes:pdf|max:2048',
        'idStage'      => 'required|integer',
        'idEntreprise' => 'required|integer',
        ], $messages);

        $Chemin_CV = null;
        $Chemin_LM = null;

        try {
        DB::beginTransaction();

        $utilisateur = DB::table('utilisateur')
                ->where('email', $requete->mail)
                ->first();

        if (!$utilisateur) {
            return back()->withErrors(['mail' => "Aucun compte trouvé pour l'adresse : " . $requete->mail])->withInput();
        }

        // Stockage
        $Chemin_CV = $requete->file('CV')->store('Stockage/CV', 'public');
        $Chemin_LM = $requete->file('lettreMotiv')->store('Stockage/Lettre_Motiv', 'public');

        // Insertion
        DB::table('candidature')->insert([
            'statut'                    => 2,
            'CV'                        => $Chemin_CV,
            'estVerif_CV'               => 0,
            'LettreMotivation'          => $Chemin_LM,
            'estVerif_LettreMotivation' => 0,
            'Convention'                => 'En attente',
            'estVerif_Convention'       => 0,
            'Remarque_Entreprise'       => '',
            'Remarque_Prof'             => '',
            'idStage'                   => $requete->idStage,
            'idEntreprise'              => $requete->idEntreprise,
            'idUtilisateur'             => $utilisateur->idUtilisateur, 
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        DB::commit();

        // On redirige vers l'accueil avec un message
        return redirect()->route('accueil')->with('success', 'Candidature enregistrée !');

            } catch (\Exception $e) {
                DB::rollBack();
                // En cas de pépin, on veut voir l'erreur au lieu d'un écran blanc
                dd("Erreur technique : " . $e->getMessage());
            }
    }


    public function Supprimer_Candidature($id){

        try {
            $candidature = DB::table('candidature')->where('idCandidature', $id)->first();

            if ($candidature) {
        $fichiersASupprimer = [];

        if (!empty($candidature->CV)) {
            $fichiersASupprimer[] = $candidature->CV;
        }

        if (!empty($candidature->LettreMotivation)) {
            $fichiersASupprimer[] = $candidature->LettreMotivation;
        }

        if (!empty($fichiersASupprimer)) {
            Storage::disk('public')->delete($fichiersASupprimer);
        }
            DB::table('candidature')->where('idCandidature', $id)->delete();
        
        return redirect()->route('accueil', ['choix' => 'candidatures'])->with('success', 'Supprimé !');
    }
            
            return back()->with('error', 'Candidature introuvable.');

        } catch (\Exception $e) {
            dd("Erreur lors de la suppression : " . $e->getMessage());
        }
    }
}

