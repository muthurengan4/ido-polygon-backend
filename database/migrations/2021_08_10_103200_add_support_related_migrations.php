<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportRelatedMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('support_categories')) {

            Schema::create('support_categories', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('picture')->default("");
                $table->tinyInteger('status')->default(APPROVED);
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('support_tickets')) {

            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('support_category_id');
                $table->string('question');
                $table->text('message');
                $table->string('file')->default("");
                $table->tinyInteger('status')->default(APPROVED);
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('support_chats')) {

            Schema::create('support_chats', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('support_ticket_id');
                $table->integer('user_id');
                $table->text('message');
                $table->string('file')->default("");
                $table->string('type')->default(SUPPORT_CHAT_TYPE_USER_TO_SUPPORT);
                $table->tinyInteger('status')->default(APPROVED);
                $table->timestamps();
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_categories');
        Schema::dropIfExists('support_chats');
        Schema::dropIfExists('support_tickets');
    }
}
