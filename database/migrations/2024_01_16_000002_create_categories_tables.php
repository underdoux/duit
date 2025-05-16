<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('color')->default('#2563EB');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('color')->default('#EF4444');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });

        // Create default income categories
        $incomeCategories = [
            ['name' => 'Salary', 'color' => '#2563EB'],
            ['name' => 'Investments', 'color' => '#10B981'],
            ['name' => 'Freelance', 'color' => '#F59E0B'],
            ['name' => 'Rental', 'color' => '#6366F1'],
            ['name' => 'Other', 'color' => '#EC4899'],
        ];

        // Create default expense categories
        $expenseCategories = [
            ['name' => 'Housing', 'color' => '#EF4444'],
            ['name' => 'Food', 'color' => '#F59E0B'],
            ['name' => 'Transportation', 'color' => '#6366F1'],
            ['name' => 'Utilities', 'color' => '#10B981'],
            ['name' => 'Entertainment', 'color' => '#EC4899'],
        ];

        foreach ($incomeCategories as $category) {
            DB::table('income_categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,
            ]));
        }

        foreach ($expenseCategories as $category) {
            DB::table('expense_categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,
            ]));
        }
    }

    public function down()
    {
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('income_categories');
    }
};
