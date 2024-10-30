<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_memos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id');
			$table->string('message', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_memos');
    }
};
