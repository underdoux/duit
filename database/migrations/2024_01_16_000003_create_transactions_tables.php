<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Income Transactions
        Schema::create('income_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('description');
            $table->string('reference_number')->nullable();
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('income_categories')->onDelete('cascade');
        });

        // Expense Transactions
        Schema::create('expense_transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('description');
            $table->string('reference_number')->nullable();
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });

        // Upcoming Income
        Schema::create('upcoming_income', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('description');
            $table->string('frequency')->default('one-time'); // one-time, weekly, monthly, yearly
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('income_categories')->onDelete('cascade');
        });

        // Upcoming Expenses
        Schema::create('upcoming_expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('description');
            $table->string('frequency')->default('one-time'); // one-time, weekly, monthly, yearly
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('upcoming_expenses');
        Schema::dropIfExists('upcoming_income');
        Schema::dropIfExists('expense_transactions');
        Schema::dropIfExists('income_transactions');
    }
};
