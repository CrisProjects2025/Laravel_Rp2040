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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();          
            
            $table->string('body');
            //$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();  // Stores current timestamp
            //$table->integer('Light');  // Stores light intensity as an integer
            //$table->string('open_or_close')->default('unknown');  // Stores status (open/close/unknown)
            //$table->string('mode')->default('manual');  // Stores the mode (manual/automatic/off)
            //$table->float('temperature', 5, 2)->default(0);  // Stores temperature with 2 decimal places
            //$table->float('humidity', 5, 2)->default(0);  // Stores humidity with 2 decimal places
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
