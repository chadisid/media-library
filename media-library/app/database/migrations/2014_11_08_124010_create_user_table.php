<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('users', function($table)
    {
      $table->increments('id');
      $table->string('username');
      $table->string('folder')->default('');
      $table->boolean('admin')->default(false);
      $table->char('password', 64);
      $table->timestamps();
      $table->rememberToken();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    Schema::drop('users');
	}

}
