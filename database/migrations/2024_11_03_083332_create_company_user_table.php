<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('company_user', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('company_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->primary(['user_id', 'company_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_user');
    }
};
