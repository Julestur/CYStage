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

    DB::table('entreprise')->insert([
        'idEntreprise' => 1,
        'nom' => 'CY Tech',
    ]);



    DB::table('entreprise')->insert([
        'idEntreprise' => 2,
        'nom' => 'Thalès',
    ]);

    DB::table('utilisateur')->insert([
        'nom' => 'Jules',
        'prenom' => 'Jules',
        'email' => 'ju2ju@outlook.fr',
        'identifiant' => 'jules',
        'mdp' => Hash::make('jules'),
        'idStatut' => 1,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    DB::table('utilisateur')->insert([
        'nom' => 'Jules2',
        'prenom' => 'Jules2',
        'email' => 'ju2ju2@outlook.fr',
        'identifiant' => 'jules2',
        'mdp' => Hash::make('jules2'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);

        DB::table('utilisateur')->insert([
        'nom' => 'Estezeub',
        'prenom' => 'Estezeub',
        'email' => 'Estezeub@outlook.fr',
        'identifiant' => 'Estezeub',
        'mdp' => Hash::make('Estezeub'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


        DB::table('utilisateur')->insert([
        'nom' => 'Tuyau',
        'prenom' => 'Tuyau',
        'email' => 'Tuyau@outlook.fr',
        'identifiant' => 'Tuyau',
        'mdp' => Hash::make('Tuyau'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


        DB::table('utilisateur')->insert([
        'nom' => 'Jul',
        'prenom' => 'Jul',
        'email' => 'jul@outlook.fr',
        'identifiant' => 'jul',
        'mdp' => Hash::make('jul'),
        'idStatut' => 2,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);



    DB::table('utilisateur')->insert([
        'nom' => 'Jul2',
        'prenom' => 'Jul2',
        'email' => 'jul2@outlook.fr',
        'identifiant' => 'jul2',
        'mdp' => Hash::make('jul2'),
        'idStatut' => 3,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);


    DB::table('utilisateur')->insert([
        'nom' => 'Jules3',
        'prenom' => 'Jules3',
        'email' => 'ju2ju3@outlook.fr',
        'identifiant' => 'jules3',
        'mdp' => Hash::make('jules3'),
        'idStatut' => 3,
        'idClasse' => 3,
        'pdp' => 'profil.png',
        'mdp_tmp' => '',
    ]);



        DB::table('stage')->insert([
        'idStage' => '1',
        'intitule' => 'Pragramation Python',
        'detail' => 'Stage de plusieurs semaine pour apprendre le dev python ',
        'dateDebut' => '2026-03-01',
        'dateFin' => '2026-05-01',
        'idEntreprise' => 1,
    ]);




        DB::table('stage')->insert([
        'idStage' => '2',
        'intitule' => 'Pragramation Python',
        'detail' => 'Stage de plusieurs semaine pour apprendre le dev python ',
        'dateDebut' => '2026-03-01',
        'dateFin' => '2026-05-01',
        'idEntreprise' => 2,
    ]);


    DB::table('candidature')->insert([
        'idStage' => '1',
        'idEntreprise' => '1',
        'idUtilisateur' => '1',
    ]);



     DB::table('candidature')->insert([
        'idStage' => '2',
        'idEntreprise' => '2',
        'idUtilisateur' => '2',
    ]);




    }
}
