<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRouteMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_route_mapping', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_saas_module_list')->nullable();
            $table->string('permission', 150)->nullable();
            $table->string('route', 150)->nullable();
            $table->string('method', 45)->nullable();
            $table->timestamps();
            $table->tinyInteger('mapping_active')->nullable()->default(0);
            $table->charset = "utf8";
            $table->collation = "utf8_general_ci";

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_route_mapping');
    }
}
