<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hoTen', 191);
            $table->string('sdt', 191);
            $table->string('gioiTinh', 191)->nullable();
            $table->date('ngaySinh')->nullable();
            $table->string('diaChi', 191)->nullable();
            $table->string('CDDD', 191)->nullable();
            $table->date('ngayCapCCCD')->nullable();
            $table->string('noiCapCCCD', 191)->nullable();
            $table->longText('anhDaiDien')->nullable();
            $table->bigInteger('gCoin')->nullable()->default(0);
            $table->bigInteger('vip')->nullable()->default(1);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('user_detail');
    }
}
