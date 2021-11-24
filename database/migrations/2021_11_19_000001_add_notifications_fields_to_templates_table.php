<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationsFieldsToTemplatesTable extends Migration
{

    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('mail_theme')->nullable();
            $table->string('mail_markdown')->nullable();
            $table->boolean('is_default')->default(true);
        });
    }

    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('mail_theme');
            $table->dropColumn('mail_markdown');
            $table->dropColumn('is_default');
        });
    }
}
