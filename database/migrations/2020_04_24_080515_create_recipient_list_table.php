<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipientlists', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('father');
            $table->string('age');
            $table->string('weight');
            $table->string('mobile');
            $table->string('address');
            $table->string('roger_bornona');
            $table->string('doctor_type');
            $table->string('gender');
            $table->integer('status');
            $table->timestamp('serve_date')->nullable();
            $table->string('doctor')->nullable();
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
        Schema::dropIfExists('recipientlists');
    }
}
