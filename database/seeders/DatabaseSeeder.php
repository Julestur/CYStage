<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
/*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
*/

    DB::table('statut')->insert([
        ['idStatut' => 1, 'libelle' => 'Admin'],
        ['idStatut' => 2, 'libelle' => 'Etudiant'],
        ['idStatut' => 3, 'libelle' => 'Professeur'],
        ['idStatut' => 4, 'libelle' => 'Entreprise'],
    ]);

    DB::table('classe')->insert([
        ['idClasse' => 1,'nom' => 'Pré ING1'],
        ['idClasse' => 2,'nom' => 'Pré ING2'],
        ['idClasse' => 3,'nom' => 'ING1 GM'],
        ['idClasse' => 4,'nom' => 'ING1 GI'],
        ['idClasse' => 5,'nom' => 'ING2 GM'],
        ['idClasse' => 6,'nom' => 'ING2 GI'],
        ['idClasse' => 7,'nom' => 'ING3 GM'],
        ['idClasse' => 8,'nom' => 'ING3 GI'],
        ]);



    # --------------------------------------------------------------------------------------------------------------
    # ------------ ENTREPRISES -------------------------------------------------------------------------------------
    # --------------------------------------------------------------------------------------------------------------




    DB::table('entreprise')->insert([
        'idEntreprise' => 1,
        'nom' => 'CY Tech',
    ]);


    DB::table('entreprise')->insert([
        'idEntreprise' => 2,
        'nom' => 'Thalès',
    ]);

    DB::table('entreprise')->insert([
        'idEntreprise' => 3,
        'nom' => 'Capgemini',
    ]);




    # --------------------------------------------------------------------------------------------------------------
    # ------------ UTILISATEURS ------------------------------------------------------------------------------------
    # --------------------------------------------------------------------------------------------------------------



    // ADMINISTRATEUR
    DB::table('utilisateur')->insert([
        'nom' => 'Admin',
        'prenom' => 'Admin',
        'email' => 'Admin@Admin.fr',
        'identifiant' => 'Admin',
        'mdp' => Hash::make('Admin'),
        'idStatut' => 1,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);

    // ETUDIANT
    DB::table('utilisateur')->insert([
        'nom' => 'Ehrmann',
        'prenom' => 'Esteban',
        'email' => 'Ehrmann.Esteban@CYStage.fr',
        'identifiant' => 'Esteban',
        'mdp' => Hash::make('Esteban'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);

    // ETUDIANT
    DB::table('utilisateur')->insert([
        'nom' => 'Preto',
        'prenom' => 'Tilio',
        'email' => 'Tilio.Preto@CYStage.fr',
        'identifiant' => 'Tilio',
        'mdp' => Hash::make('Tilio'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    // ETUDIANT
    DB::table('utilisateur')->insert([
        'nom' => 'Turchi',
        'prenom' => 'Jules',
        'email' => 'Turchi.Jules@CYStage.fr',
        'identifiant' => 'Jules',
        'mdp' => Hash::make('Jules'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);

    // ETUDIANT
    DB::table('utilisateur')->insert([
        'nom' => 'Etudiant',
        'prenom' => 'Etudiant',
        'email' => 'Etudiant@Etudiant.fr',
        'identifiant' => 'Etudiant',
        'mdp' => Hash::make('Etudiant'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    // PROF
    DB::table('utilisateur')->insert([
        'nom' => 'Prof',
        'prenom' => 'Prof',
        'email' => 'Prof@Prof.fr',
        'identifiant' => 'Prof',
        'mdp' => Hash::make('Prof'),
        'idStatut' => 3,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    // ENTREPRISE
    DB::table('utilisateur')->insert([
        'nom' => 'Entreprise',
        'prenom' => 'Entreprise',
        'email' => 'Entreprise@Entreprise.fr',
        'identifiant' => 'Entreprise',
        'mdp' => Hash::make('Entreprise'),
        'idStatut' => 4,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    # --------------------------------------------------------------------------------------------------------------
    # ------------ STAGE -------------------------------------------------------------------------------------------
    # --------------------------------------------------------------------------------------------------------------



        DB::table('stage')->insert([
        'idStage' => '1',
        'intitule' => 'Pragramation Python',
        'detail' => 'Stage de plusieurs semaine pour apprendre le dev python ',
        'dateDebut' => '2026-03-01',
        'dateFin' => '2026-05-01',
        'idEntreprise' => 2,
    ]);


        DB::table('stage')->insert([
        'idStage' => '2',
        'intitule' => 'Stage dev web',
        'detail' => 'Stage de plusieurs semaine pour apprendre le dev web ',
        'dateDebut' => '2026-03-01',
        'dateFin' => '2026-05-01',
        'idEntreprise' => 3,
    ]);







    DB::table('candidature')->insert([
        'CV' => 'Stockage/CV/EstebanCV.pdf',
        'LettreMotivation' => 'Stockage/Lettre_Motiv/EstebanLettre.pdf',
        'idStage' => '1',
        'idEntreprise' => '1',
        'idUtilisateur' => '2',
    ]);



    DB::table('candidature')->insert([
        'CV' => 'Stockage/CV/TilioCV.pdf',
        'LettreMotivation' => 'Stockage/Lettre_Motiv/TilioLettre.pdf',
        'idStage' => '2',
        'idEntreprise' => '2',
        'idUtilisateur' => '3',
    ]);


    DB::table('candidature')->insert([
        'CV' => 'Stockage/CV/JulesCV.pdf',
        'LettreMotivation' => 'Stockage/Lettre_Motiv/JulesLettre.pdf',
        'idStage' => '2',
        'idEntreprise' => '2',
        'idUtilisateur' => '4',
    ]);

    }
}