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
        
    if (\Illuminate\Support\Facades\Session::get('grade') == 'Entreprise') {
        $requete->merge([
            'idEntreprise' => \Illuminate\Support\Facades\Session::get('idEntreprise')
        ]);
    }

        $validator = \Illuminate\Support\Facades\Validator::make($requete->all(), [
            'intitule'    => 'required',
            'dateDebut'   => 'required',
            'dateFin'     => 'required',
            'description' => 'required',
            'idEntreprise'=> 'required',
        ]);

        if ($validator->fails()) {
            dd("🚨 LA VALIDATION A ÉCHOUÉ ! Voici l'erreur exacte :", $validator->errors()->all(), "Données envoyées :", $requete->all());
        }
        try {
            // On insère dans la table 'stage'
            DB::table('stage')->insert([
                'intitule'      => $requete->intitule,
                'dateDebut'     => $requete->dateDebut,
                'dateFin'       => $requete->dateFin,
                'detail'        => $requete->description, 
                'idEntreprise'  => $requete->idEntreprise,
                'created_at'    => now(),
            ]);

            // On redirige vers l'accueil avec un message de succès
            return redirect()->route('accueil')->with('success', 'Le stage a été ajouté !');

        } catch (\Exception $e) {
            dd("Erreur SQL : " . $e->getMessage());
        }
    }


    public function Supprimer_Stage($id)
{
    try {

        DB::beginTransaction();

        DB::table('candidature')->where('idStage', $id)->delete();

        $suppression = DB::table('stage')->where('idStage', $id)->delete();

        if ($suppression) {
            DB::commit();
            return redirect()->route('accueil')->with('success', 'Le stage et ses candidatures ont été supprimés !');
        }

        DB::rollBack();
        return redirect()->route('accueil')->with('error', 'Impossible de trouver ce stage.');

    } catch (\Exception $e) {
        DB::rollBack(); 
        return redirect()->route('accueil')->with('error', 'Erreur critique : ' . $e->getMessage());
    }
}
}

