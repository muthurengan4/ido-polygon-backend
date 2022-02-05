<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_forms', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->default(uniqid());
            $table->string('title')->default("");
            $table->string('name')->default("");
            $table->string('email')->default("");
            $table->string('mobile')->default("");
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(CONTACT_FORM_INITIATED);
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
        Schema::dropIfExists('contact_forms');
    }
}
