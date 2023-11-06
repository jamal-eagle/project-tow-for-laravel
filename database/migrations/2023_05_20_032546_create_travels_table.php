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



        Schema::create('travels', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('manager_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('discription');
            $table->String('address');
            $table->unsignedInteger('participaints_num');
            $table->unsignedInteger('price');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
