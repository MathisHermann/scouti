<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchEngineRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_engine_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->nullable();
            $table->text('keywords');
            $table->text('keywords_sorted');
            $table->text('industry');
            $table->boolean('successful');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_engine_requests');
    }
}
