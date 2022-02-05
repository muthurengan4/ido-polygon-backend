<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLookupRelatedMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('static_pages')) {

            Schema::create('static_pages', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->string('title')->unique();
                $table->text('description');
                $table->enum('type',['about','privacy','terms','refund','cancellation','faq','help','contact','others'])->default('others');
                $table->string('section_type')->nullable();
                $table->tinyInteger('status')->default(APPROVED);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('settings')) {

            Schema::create('settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key');
                $table->text('value');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });  
        }

        if(!Schema::hasTable('faqs')) {

            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->string('question');
                $table->text('answer');
                $table->tinyInteger('status')->default(YES);            
                $table->timestamps();
            });
        }
        
        if(!Schema::hasTable('documents')) {
            
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->string('name');
                $table->string('image_type')->default('jpg');
                $table->string('picture')->default(asset('document.jpg'));
                $table->text('description')->nullable();
                $table->tinyInteger('is_required')->default(1);
                $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('static_pages');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('documents');
    }
}
