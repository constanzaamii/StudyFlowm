<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('semester', 20);
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->decimal('final_grade', 3, 2)->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'subject_id', 'semester']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('enrollments');
    }
};
