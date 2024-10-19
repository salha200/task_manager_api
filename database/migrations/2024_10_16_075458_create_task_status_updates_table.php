<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStatusUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('task_status_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('previous_status');
            $table->string('new_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_status_updates');
    }
}
