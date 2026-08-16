<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->foreignId('club_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->after('club_id')->constrained()->cascadeOnDelete();
            $table->string('title')->after('user_id');
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->foreignId('forum_topic_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->after('forum_topic_id')->constrained()->cascadeOnDelete();
            $table->text('body')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropConstrainedForeignId('club_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('title');
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('forum_topic_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('body');
        });
    }
};