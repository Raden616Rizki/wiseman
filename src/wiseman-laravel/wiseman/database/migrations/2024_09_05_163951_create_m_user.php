<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 100)->comment('Fill with username of user');
            $table->string('email', 50)->comment('Fill with user email for login');
            $table->string('password', 255)->comment('Fill with user password for login');
            $table->string('phone', 25)->comment('Fill with user phone number')->nullable();
            $table->string('photo_profile', 100)->comment('Fill with user photo profile')->nullable();
            $table->timestamp('updated_security')
                ->comment('Fill with timestamp when user update password / email')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
