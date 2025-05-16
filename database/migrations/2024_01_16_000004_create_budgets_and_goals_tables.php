<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Budgets
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('period')->default('monthly'); // monthly, quarterly, yearly
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });

        // Financial Goals
        Schema::create('financial_goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('target_amount', 10, 2);
            $table->decimal('current_amount', 10, 2)->default(0);
            $table->date('target_date');
            $table->string('status')->default('in_progress'); // in_progress, completed, cancelled
            $table->text('description')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
        });

        // Goal Transactions
        Schema::create('goal_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('type'); // deposit, withdrawal
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('goal_id')->references('id')->on('financial_goals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });

        // Budget Categories
        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->decimal('spent', 10, 2)->default(0);
            $table->timestamps();
            $table->foreignId('budget_id')->references('id')->on('budgets')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_categories');
        Schema::dropIfExists('goal_transactions');
        Schema::dropIfExists('financial_goals');
        Schema::dropIfExists('budgets');
    }
};
