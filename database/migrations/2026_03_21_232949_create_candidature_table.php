<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidature', function (Blueprint $table) {
            
            $table->id('idCandidature'); 
            $table->integer('statut')->default(0);
            
        
        
            $table->foreignId('idStage')->constrained('stage', 'idStage');
            $table->foreignId('idEntreprise')->nullable()->constrained('entreprise', 'idEntreprise');
            $table->foreignId('idUtilisateur')->nullable()->constrained('utilisateur', 'idUtilisateur');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidature');
    }
};
