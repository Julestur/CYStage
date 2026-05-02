<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller
{
    public function discussion(Request $request) {
        if (!Session::get('connecte')) {
            return redirect('/')->withErrors(['error' => 'Veuillez vous connecter.']);
        }

        $grade         = Session::get('grade');
        $idUtilisateur = Session::get('id');
        $prenom        = Session::get('prenom');
        $profs         = DB::table('utilisateur')->where('idStatut', 3)->get();

        if ($grade === 'Etudiant') {
            $candidatures = DB::table('candidature as c')
                ->join('stage as s', 'c.idStage', '=', 's.idStage')
                ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                ->join('utilisateur as ue', function($join) {
                    $join->on('ue.idEntreprise', '=', 'c.idEntreprise')
                         ->where('ue.idStatut', '=', 4);
                })
                ->where('c.idUtilisateur', $idUtilisateur)
                ->select('c.idCandidature', 'c.idUtilisateur', 's.intitule', 'e.nom as nomEntreprise', 'ue.idUtilisateur as idUtilisateurEntreprise')
                ->get();

        } elseif ($grade === 'Entreprise') {
            $idEntreprise = DB::table('utilisateur')->where('idUtilisateur', $idUtilisateur)->value('idEntreprise');
            $candidatures = DB::table('candidature as c')
                ->join('stage as s', 'c.idStage', '=', 's.idStage')
                ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                ->where('c.idEntreprise', $idEntreprise)
                ->select('c.idCandidature', 'c.idUtilisateur', 's.intitule', 'u.nom', 'u.prenom')
                ->get();

        } elseif ($grade === 'Professeur') {
            $candidatures = DB::table('candidature as c')
                ->join('stage as s', 'c.idStage', '=', 's.idStage')
                ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                ->join('utilisateur as ue', function($join) {
                    $join->on('ue.idEntreprise', '=', 'c.idEntreprise')
                         ->where('ue.idStatut', '=', 4);
                })
                ->select('c.idCandidature', 'c.idUtilisateur', 'ue.idUtilisateur as idUtilisateurEntreprise', 's.intitule', 'e.nom as nomEntreprise', 'u.nom', 'u.prenom')
                ->get();

        } elseif ($grade === 'Admin') {
            $candidatures = DB::table('candidature as c')
                ->join('stage as s', 'c.idStage', '=', 's.idStage')
                ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                ->select('c.idCandidature', 'c.idUtilisateur', 's.intitule', 'e.nom as nomEntreprise', 'u.nom', 'u.prenom')
                ->get();
        }

        return view('messages.discussion', compact('candidatures', 'grade', 'prenom', 'idUtilisateur', 'profs'));
    }

   public function Envoyer_Message(Request $request) {
        $request->validate([
            'idCandidature' => 'required|integer',
            'idExpediteur'  => 'required|integer',
            'canal'         => 'required|string',
            'contenu'       => 'nullable|string|max:1000',
            'fichier'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
        ]);

        if (empty($request->contenu) && !$request->hasFile('fichier')) {
            return response()->json(['error' => 'Message ou fichier requis'], 422);
        }

        $cheminFichier = null;
        $nomFichier    = null;
        if ($request->hasFile('fichier')) {
            $nomFichier    = $request->file('fichier')->getClientOriginalName();
            $cheminFichier = $request->file('fichier')->store('Stockage/Messages', 'public');
        }

        DB::table('message')->insert([
            'idCandidature' => $request->idCandidature,
            'idExpediteur'  => $request->idExpediteur,
            'canal'         => $request->canal,
            'contenu'       => $request->contenu ?? '',
            'fichier'       => $cheminFichier,
            'nom_fichier' => $nomFichier,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function Recuperer_Messages($idCandidature, $canal) {
        $messages = DB::table('message as m')
            ->join('utilisateur as u', 'm.idExpediteur', '=', 'u.idUtilisateur')
            ->where('m.idCandidature', $idCandidature)
            ->where('m.canal', $canal)
            ->select('m.*', 'u.prenom', 'u.nom')
            ->orderBy('m.created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}