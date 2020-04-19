<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpkPengembaliansTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'spk_pengembalians';

    /**
     * Run the migrations.
     * @table spk_pengembalians
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('spk_id');
            $table->integer('termin');
            $table->decimal('percent', 5, 2)->nullable()->default(null);
            $table->integer('created_by')->nullable()->default(null);
            $table->integer('updated_by')->nullable()->default(null);
            $table->integer('deleted_by')->nullable()->default(null);
            $table->timestamp('inactive_at')->nullable()->default(null);
            $table->integer('inactive_by')->nullable()->default(null);
            $table->string('status', 1)->nullable()->default('0');

            $table->index(["spk_id"], 'spk_pengembalians_spk_id_index');
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
