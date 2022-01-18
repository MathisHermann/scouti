<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapidMinerResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapid_miner_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_engine_requests_id');
            $table->foreignId('users_id')->nullable();
            $table->float('confidence');
            $table->float('Score');
            $table->string('score_tag');
            $table->string('agreement');
            $table->string('subjectivity');
            $table->string('irony');
            $table->text('Text');
            $table->string('Keyword')->nullable();
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
        Schema::dropIfExists('rapid_miner_results');
    }
}
