<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_voting_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('option');
			$table->integer('total')->default('0');
			$table->uuid('voting_id');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_voting_options');
    }
};
