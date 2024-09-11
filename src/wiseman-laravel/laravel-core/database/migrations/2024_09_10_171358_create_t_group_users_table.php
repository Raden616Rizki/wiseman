<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_group_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id');
			$table->uuid('user_id');
			$table->boolean('is_admin');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_group_users');
    }
};
