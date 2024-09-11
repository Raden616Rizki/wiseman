<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id');
			$table->uuid('user_id');
			$table->string('description', 255);
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->boolean('is_priority');
			$table->boolean('is_finished');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_activities');
    }
};
