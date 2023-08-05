<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('users');
            $table->string('stripe_id')->nullable();
            $table->string('stripe_plan')->nullable();
            $table->string('stripe_subscription')->nullable();
            $table->string('stripe_status')->default('Trial');
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('default_payment_method')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->string('cancel_at_period_end')->nullable();
            $table->timestamp('trial_ending_mail_sent_at')->nullable();
            $table->timestamp('trial_ended_mail_sent_at')->nullable();
            $table->text('extra_billing_information')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
