<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->uuid('custom_uuid')->nullable();
            $table->efficientUuid('efficient_uuid')->nullable();
            $table->string('title');
        });
    }
}
