<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoreRelatedMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('subscriptions')) {

            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->string('title');
                $table->text('description');
                $table->float('amount')->default(0.00);
                $table->integer('plan')->default(1);
                $table->string('plan_type')->default(PLAN_TYPE_MONTH);
                $table->integer('no_of_projects')->default(1);
                $table->tinyInteger('is_free')->default(0);
                $table->tinyInteger('is_popular')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
            
        }

        if(!Schema::hasTable('subscription_payments')) {
            
            Schema::create('subscription_payments', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('subscription_id');
                $table->integer('user_id');
                $table->string('payment_id')->default("");
                $table->float('amount')->default(0.00);
                $table->string('payment_mode')->default(COD);
                $table->integer('is_current_subscription')->default(0);
                $table->integer('no_of_projects')->default(1);
                $table->datetime('expiry_date')->nullable();
                $table->datetime('paid_date')->nullable();
                $table->tinyInteger('status')->default(UNPAID);
                $table->tinyInteger('is_cancelled')->default(0);
                $table->text('cancel_reason')->nullable("");
                $table->tinyInteger('is_autorenewal')->default(YES);
                $table->integer('plan')->default(1);
                $table->string('plan_type')->default(PLAN_TYPE_MONTH);
                $table->softDeletes();
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('projects')) {

            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->integer('user_id');
                $table->string('name');
                $table->text('description');
                $table->string('pool_contract_address')->default("");
                $table->string('picture')->default(asset('placeholder.jpg'));
                $table->string('token_symbol');
                $table->string('total_tokens')->default(0.00);
                $table->string('allowed_tokens')->default(0.00);
                $table->float('exchange_rate')->default(0.00);
                $table->string('website')->nullable();
                $table->string('twitter_link')->nullable();
                $table->string('facebook_link')->nullable();
                $table->string('telegram_link')->nullable();
                $table->string('medium_link')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('access_type')->default('private');
                $table->integer('total_users_participated')->default(0);
                $table->string('total_tokens_purchased')->default(0.00);
                $table->string('contract_address')->default("");
                $table->string('decimal_points')->default(18);
                $table->string('uploaded_by')->default('user')->comment('user | admin');
                $table->tinyInteger('payment_status')->default(0);
                $table->tinyInteger('status')->default(PENDING);
                $table->string('publish_status')->default(PROJECT_PUBLISH_STATUS_INITIATED);
                $table->timestamps();
            });
            
        }

        if(!Schema::hasTable('project_payments')) {

            Schema::create('project_payments', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('project_id');
                $table->integer('user_id');
                $table->string('from_wallet_address');
                $table->string('from_payment_id');
                $table->string('to_wallet_address');
                $table->string('to_payment_id');
                $table->float('purchased')->default(0.00);
                $table->float('confirmed')->default(0.00);
                $table->tinyInteger('status')->default(PENDING);
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('token_payments')) {

            Schema::create('token_payments', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('user_id');
                $table->string('from_wallet_address');
                $table->string('from_payment_id');
                $table->string('to_wallet_address');
                $table->string('to_payment_id');
                $table->string('purchased')->default(0.00);
                $table->string('confirmed')->default(0.00);
                $table->tinyInteger('status')->default(PENDING);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('invested_projects')) {

            Schema::create('invested_projects', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('project_id');
                $table->integer('user_id');
                $table->string('from_payment_id');
                $table->string('from_wallet_address');
                $table->string('to_wallet_address');
                $table->string('to_payment_id');
                $table->string('purchased')->default(0.00);
                $table->string('confirmed')->default(0.00);
                $table->string('claim_token')->default("");
                $table->string('claim_payment_id')->default("");
                $table->string('claim_wallet_address')->default("");
                $table->string('claim_payment_status')->default(UNPAID);
                $table->tinyInteger('status')->default(PENDING);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('project_owner_transactions')) {

            Schema::create('project_owner_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(rand());
                $table->integer('user_id');
                $table->integer('project_id');
                $table->string('from_wallet_address');
                $table->string('from_payment_id');
                $table->string('to_wallet_address')->default("");
                $table->string('to_payment_id')->default("");
                $table->string('total')->default(0.00);
                $table->tinyInteger('status')->default(PENDING);
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('project_stacks')) {

            Schema::create('project_stacks', function (Blueprint $table) {
                $table->id();
                $table->string('unique_id')->default(uniqid());
                $table->integer('user_id');
                $table->integer('project_id');
                $table->string('wallet_address');
                $table->string('transaction_id');
                $table->string('stacked')->default(0.00);
                $table->string('unstacked')->default(0.00);
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
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_payments');
        Schema::dropIfExists('token_payments');
        Schema::dropIfExists('project_owner_transactions');
        Schema::dropIfExists('project_stacks');
    }
}
