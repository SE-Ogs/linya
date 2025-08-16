<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_unbans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_ban_id')->constrained('user_bans')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->foreignId('unbanned_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('unbanned_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_unbans');
    }
};
