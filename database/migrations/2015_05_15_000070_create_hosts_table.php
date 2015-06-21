<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('hostname');
            $table->string('username');
            $table->enum('type', ['linux'])->default('linux');
            $table->string('keyhash')->nullable();
            $table->boolean('synced')->default('0');
            $table->boolean('enabled')->default('1');
            $table->timestamps();
            $table->index(['hostname', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hosts');
    }

}
