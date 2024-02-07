<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('avatar', 255)->nullable()->after('email');
            $table->string('mobile_no', 255)->nullable()->after('avatar');
            $table->enum('gender', [1, 2])->comment('1=Male,2=Female')->after('mobile_no');
            $table->enum('status', [1, 2])->comment('1=Active,2=Inactive')->after('password');
            $table->string('created_by', 255)->nullable()->after('remember_token');
            $table->string('updated_by', 255)->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
