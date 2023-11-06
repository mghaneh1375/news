<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->boolean('isVideoLink')->default(false);
            $table->string('titleEn')->nullable();
            $table->longText('textEn')->nullable();
            $table->string('slugEn')->nullable();
            $table->longText('metaEn')->nullable();
            $table->text('keywordEn', 255)->nullable();
            $table->string('seoTitleEn')->nullable();
            $table->unsignedInteger('site_id')->default(1);
            $table->index('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn(['site_id']);
            $table->dropColumn(['isVideoLink']);
            $table->dropColumn(['titleEn']);
            $table->dropColumn(['textEn']);
            $table->dropColumn(['slugEn']);
            $table->dropColumn(['metaEn']);
            $table->dropColumn(['keywordEn']);
            $table->dropColumn(['seoTitleEn']);
            
        });
    }
};
