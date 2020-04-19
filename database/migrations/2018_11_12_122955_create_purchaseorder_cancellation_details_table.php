<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseorderCancellationDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchaseorder_cancellation_details', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('purchaseorder_cancellation_id')->nullable();
			$table->integer('purchaseorder_detail_id')->nullable();
			$table->integer('quantity')->nullable();
			$table->string('description', 191)->nullable();
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
		Schema::drop('purchaseorder_cancellation_details');
	}

}
