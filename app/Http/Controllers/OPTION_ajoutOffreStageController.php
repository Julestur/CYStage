<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OPTION_ajoutOffreStageController extends Controller
{


    // ----------------------------------------------------------------------------------
    // Affichage de la première étape avec le formulaire d'inscription ------------------
    // ----------------------------------------------------------------------------------
   
    public function Vu_AjoutStage_Etape1() {
        
        $entreprises = DB::table('entreprise')->orderBy('nom', 'asc')->get();
        return view('OptionAccueil.ajoutStage1', compact('entreprises'));

    }



    // ----------------------------------------------------------------------------------
    // Traitement du formulaire ---------------------------------------------------------
    // ----------------------------------------------------------------------------------
   

    public function Traitement_AjoutStage_Etape1(Request $requete){
    // 1. On valide les données
        $requete->validate([
            'intitule'    => 'required',
            'dateDebut'   => 'required',
            'dateFin'     => 'required',
            'description' => 'required',
            'idEntreprise'=> 'required',
        ]);

        try {
            // 2. On insère dans la table 'stage'
            // VERIFIE BIEN LE NOM DE TES COLONNES DANS phpMyAdmin
            DB::table('stage')->insert([
                'intitule'      => $requete->intitule,
                'dateDebut'     => $requete->dateDebut,
                'dateFin'       => $requete->dateFin,
                'detail'        => $requete->description, 
                'idEntreprise'  => $requete->idEntreprise,
                'created_at'    => now(),
            ]);

            // 3. On redirige vers l'accueil avec un message de succès
            return redirect()->route('accueil')->with('success', 'Le stage a été ajouté !');

        } catch (\Exception $e) {
            // SI CA NE MARCHE PAS, ce dd() va nous dire exactement pourquoi (ex: colonne manquante)
            dd("Erreur SQL : " . $e->getMessage());
        }
    }


    public function Supprimer_Stage($id)
{
    try {
        // 1. On lance une transaction (si un truc plante, rien n'est supprimé)
        DB::beginTransaction();

        // 2. On supprime d'abord toutes les candidatures liées à ce stage
        // Cela libère la contrainte de clé étrangère
        DB::table('candidature')->where('idStage', $id)->delete();

        // 3. Maintenant, on peut supprimer le stage sans erreur
        $suppression = DB::table('stage')->where('idStage', $id)->delete();

        if ($suppression) {
            DB::commit(); // On valide définitivement les suppressions
            return redirect()->route('accueil')->with('success', 'Le stage et ses candidatures ont été supprimés !');
        }

        DB::rollBack();
        return redirect()->route('accueil')->with('error', 'Impossible de trouver ce stage.');

    } catch (\Exception $e) {
        DB::rollBack(); // En cas d'erreur SQL, on annule tout
        return redirect()->route('accueil')->with('error', 'Erreur critique : ' . $e->getMessage());
    }
}
}

