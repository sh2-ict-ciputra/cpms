<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTenderPurchaseRequestItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tender_purchase_request_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('tender_purchase_request_id')->nullable();
			$table->integer('purchase_request_detail_id')->nullable();
			$table->integer('itempekerjaan');
			$table->integer('item_id');
			$table->integer('item_satuan_id');
			$table->integer('brand_id');
			$table->string('recomended_supplier', 191)->nullable();
			$table->integer('quantity');
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
		Schema::drop('tender_purchase_request_items');
	}

}
