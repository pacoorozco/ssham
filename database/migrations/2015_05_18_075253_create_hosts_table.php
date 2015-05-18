<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hosts', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('hostname')->unique();
                        $table->string('username')->default('root')->unique();                
                        $table->enum('type', ['linux'])->default('linux');
                        $table->string('keyHash')->nullable();
                        $table->boolean('synced')->default('0');
                        $table->boolean('enabled')->default('1');
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
		Schema::drop('hosts');
	}

}
