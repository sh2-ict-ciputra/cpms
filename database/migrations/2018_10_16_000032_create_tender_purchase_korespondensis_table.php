<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderPurchaseKorespondensisTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'tender_purchase_korespondensis';

    /**
     * Run the migrations.
     * @table tender_purchase_korespondensis
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('tender_rekanan_id')->nullable()->default(null);
            $table->string('no', 191)->nullable()->default(null);
            $table->string('type', 191)->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->timestamp('diundang_at')->nullable()->default(null);
            $table->string('tempat_undangan', 191)->nullable()->default(null);
            $table->timestamp('due_at')->nullable()->default(null);
            $table->integer('created_by')->nullable()->default(null);
            $table->integer('updated_by')->nullable()->default(null);
            $table->integer('deleted_by')->nullable()->default(null);
            $table->timestamp('inactive_at')->nullable()->default(null);
            $table->integer('inactive_by')->nullable()->default(null);

            $table->index(["tender_rekanan_id"], 'tender_purchase_korespondensis_tender_rekanan_id_index');
            $table->softDeletes();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}
