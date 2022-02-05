<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthRelatedMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users')) {

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->string('name');
                $table->string('first_name')->default('');
                $table->string('middle_name')->default('');
                $table->string('last_name')->default('');
                $table->string('username')->nullable();
                $table->string('email')->unique();
                $table->text('about')->nullable();
                $table->enum('gender',['male','female','others'])->default('male');
                $table->string('cover')->default(asset('cover.jpg'));
                $table->string('picture')->default(asset('placeholder.jpeg'));
                $table->string('password');
                $table->string('mobile')->default('');
                $table->string('address')->default('');
                $table->tinyInteger('user_type')->default(0);
                $table->tinyInteger('is_document_verified')->default(0);
                $table->string('payment_mode')->default(CARD);
                $table->string('token');
                $table->string('token_expiry');
                $table->string('device_token')->nullable();
                $table->enum('device_type', ['web', 'android', 'ios'])->default('web');
                $table->enum('login_by', ['manual','facebook','google', 'instagram', 'apple', 'linkedin'])->default('manual');
                $table->string('social_unique_id')->default('');
                $table->tinyInteger('registration_steps')->default(0);
                $table->tinyInteger('is_document_approved')->default(USER_DOCUMENT_PENDING);
                $table->tinyInteger('is_push_notification')->default(YES);
                $table->tinyInteger('is_email_notification')->default(YES);
                $table->tinyInteger('is_email_verified')->default(0);
                $table->string('verification_code')->default('');
                $table->string('verification_code_expiry')->default('');
                $table->timestamp('email_verified_at')->nullable();
                $table->integer('is_verified')->default(USER_EMAIL_VERIFIED);
                $table->tinyInteger('is_verified_badge')->default(NO);
                $table->tinyInteger('status')->default(1);
                $table->string('wallet_address')->default("");
                $table->tinyInteger('total_projects')->default(0);
                $table->tinyInteger('used_projects')->default(0);
                $table->tinyInteger('remaining_projects')->default(0);
                $table->string('timezone')->default('Asia/Kolkata');
                $table->rememberToken();
                $table->timestamps();
            
            });

        }

        if(!Schema::hasTable('user_documents')) {

            Schema::create('user_documents', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->integer('user_id');
                $table->integer('document_id');
                $table->string('document_file');
                $table->string('document_file_front')->default('');
                $table->string('document_file_back')->default('');
                $table->tinyInteger('is_verified')->default(0)->comment('0 - pending, 1 - approved, 2 - declined');
                $table->string('uploaded_by')->default('user')->comment('user | admin');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        
        }

        if(!Schema::hasTable('user_cards')) {

            Schema::create('user_cards', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('user_id');
                $table->string('card_holder_name')->default("");
                $table->string('card_type');
                $table->string('customer_id');
                $table->string('last_four');
                $table->string('card_token');
                $table->integer('is_default')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('admins')) {

            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('picture');
                $table->string('about');
                $table->string('timezone')->default('Asia/Kolkata');
                $table->tinyInteger('status')->default(1);
                $table->rememberToken();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_documents');
        Schema::dropIfExists('user_cards');
        Schema::dropIfExists('admins');
    }
}
