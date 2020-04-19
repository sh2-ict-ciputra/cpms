<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaserequestCancellationDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchaserequest_cancellation_details', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('purchaserequest_cancellation_id');
			$table->integer('purchaserequest_detail_id');
			$table->integer('quantity');
			$table->string('description', 191);
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
		Schema::drop('purchaserequest_cancellation_details');
	}

}
