<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionRelatedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('picture')->default(asset('placeholder.jpeg'))->after('description');
            $table->string('min_staking_balance')->after('picture')->default("1000");
            $table->string('allowed_tokens')->after('min_staking_balance')->default(0.00);
            $table->dropColumn('amount');
            $table->dropColumn('plan');
            $table->dropColumn('plan_type');
            $table->dropColumn('no_of_projects');
            $table->dropColumn('is_free');
            $table->dropColumn('is_popular');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->string('allowed_tokens')->after('payment_id')->default(0.00);
            $table->dropColumn('amount');
            $table->dropColumn('payment_mode');
            $table->dropColumn('no_of_projects');
            $table->dropColumn('expiry_date');
            $table->dropColumn('is_autorenewal');
            $table->dropColumn('plan');
            $table->dropColumn('plan_type');
        });

        Schema::table('project_payments', function (Blueprint $table) {
            $table->string('used_tokens')->after('user_id')->default(0.00);
            $table->dropColumn('from_wallet_address');
            $table->dropColumn('from_payment_id');
            $table->dropColumn('to_wallet_address');
            $table->dropColumn('to_payment_id');
            $table->dropColumn('purchased');
            $table->dropColumn('confirmed');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('picture');
            $table->dropColumn('min_staking_balance');
            $table->dropColumn('allowed_tokens');
            $table->float('amount')->default(0.00);
            $table->integer('plan')->default(1);
            $table->string('plan_type')->default(PLAN_TYPE_MONTH);
            $table->integer('no_of_projects')->default(1);
            $table->tinyInteger('is_free')->default(0);
            $table->tinyInteger('is_popular')->default(0);
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->dropColumn('allowed_tokens');
            $table->float('amount')->default(0.00);
            $table->string('payment_mode')->default(COD);
            $table->integer('no_of_projects')->default(1);
            $table->datetime('expiry_date')->nullable();
            $table->tinyInteger('is_autorenewal')->default(YES);
            $table->integer('plan')->default(1);
            $table->string('plan_type')->default(PLAN_TYPE_MONTH);
        });

        Schema::table('project_payments', function (Blueprint $table) {
            $table->dropColumn('used_tokens');
            $table->string('from_wallet_address');
            $table->string('from_payment_id');
            $table->string('to_wallet_address');
            $table->string('to_payment_id');
            $table->string('purchased')->default(0.00);
            $table->string('confirmed')->default(0.00);
        });
        
    }
}
