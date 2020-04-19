<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenderPenawaransTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tender_penawarans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tender_rekanan_id')->nullable()->index();
			$table->string('no', 64)->nullable();
			$table->date('date')->nullable();
			$table->string('file_attachment', 191)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->integer('deleted_by')->nullable();
			$table->dateTime('inactive_at')->nullable();
			$table->integer('inactive_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tender_penawarans');
	}

}
