<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelationshipUserUserDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('idUser')->nullable()->after('id');
            $table->foreign('idUser')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_detail', function (Blueprint $table) {
            $table->dropForeign(['idUser']);
            $table->dropColumn('idUser');
        });
    }
}
