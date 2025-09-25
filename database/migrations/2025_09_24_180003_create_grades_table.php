<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('evaluation_type', 100);
            $table->decimal('grade', 3, 2);
            $table->decimal('weight', 3, 2);
            $table->date('evaluation_date');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('grades');
    }
};
