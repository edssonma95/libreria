<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cover');
            $table->string('description');
            $table->date('relase_date');          
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('authors');
            $table->bigInteger('editorial_id')->unsigned();
            $table->foreign('editorial_id')->references('id')->on('editoriales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
