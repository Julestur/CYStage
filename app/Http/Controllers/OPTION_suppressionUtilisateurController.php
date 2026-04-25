<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OPTION_suppressionUtilisateurController extends Controller
{
    // ETAPE 1 : Affichage de la liste avec barre de recherche
    public function Vu_SuppressionAdmin_Etape1(Request $request)
    {
        $search = $request->input('search');
        $type = $request->query('type') ?? $request->query('choix') ?? 'utilisateur'; 

        if ($type === 'entreprise') {
            $query = DB::table('entreprise');
            if ($search) {
                $query->where('nom', 'LIKE', "%{$search}%");
            }
            $donnees = $query->get();
        } else {
            $query = DB::table('utilisateur as u')
                ->join('statut as s', 'u.idStatut', '=', 's.idStatut')
                ->select('u.*', 's.libelle as grade')
                ->whereIn('u.idStatut', [2, 3, 4]);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('u.nom', 'LIKE', "%{$search}%")
                      ->orWhere('u.prenom', 'LIKE', "%{$search}%")
                      ->orWhere('u.identifiant', 'LIKE', "%{$search}%");
                });
            }
            $donnees = $query->get();
        }

        if ($request->ajax()) {
            
            return view('OptionAccueil.suppresionUtilisateur1', compact('donnees', 'type', 'search'))->render();
        }

        return view('OptionAccueil.suppresionUtilisateur1', compact('donnees', 'type', 'search'));
    }

    // ETAPE 2 : Page de confirmation
    public function Vu_SuppressionAdmin_Etape2(Request $request)
    {
        $id = $request->query('id');
        $type = $request->query('type');

        if ($type === 'entreprise') {
            $item = DB::table('entreprise')->where('idEntreprise', $id)->first();
            $nomAffichage = $item->nom ?? 'Inconnu';
        } else {
            $item = DB::table('utilisateur')->where('idUtilisateur', $id)->first();
            $nomAffichage = ($item->prenom ?? '') . " " . ($item->nom ?? '');
        }

        return view('OptionAccueil.suppresionUtilisateur2', compact('item', 'type', 'id', 'nomAffichage'));
    }

    // ETAPE 3 : Traitement de la suppression
    public function Traitement_SuppressionAdmin_Etape1(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        if ($type === 'entreprise') {
            DB::table('utilisateur')->where('idEntreprise', $id)->update(['idEntreprise' => null]);
            DB::table('entreprise')->where('idEntreprise', $id)->delete();
        } else {
            DB::table('candidature')->where('idUtilisateur', $id)->delete();
            DB::table('utilisateur')->where('idUtilisateur', $id)->delete();
            
        }

        return redirect()->route('suppressionAdmin.Etape1_VU')->with('success', 'Suppression effectuée avec succès.');
    }
}