<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned(); // category
            $table->bigInteger('user_id')->unsigned();    // author
            $table->string('slug')->unique();             // uri
            $table->string('title');
            $table->text('excerpt')->nullable();          // short text
            $table->text('content_raw');                  // text raw
            $table->text('content_html');                 // text format auto creat from raw
            $table->boolean('is_published')->default(false); // is published
            $table->timestamp('published_at')->nullable(); // date publish
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // Зовнішні ключі
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Додаємо onDelete для каскадного видалення

            $table->foreign('category_id')
                ->references('id')
                ->on('blog_categories')
                ->onDelete('cascade'); // Додаємо onDelete для каскадного видалення

            // Індекси
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
