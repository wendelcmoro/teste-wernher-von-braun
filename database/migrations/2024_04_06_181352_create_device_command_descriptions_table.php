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
        Schema::create(
            'device_command_descriptions', function (Blueprint $table) {
                $table->id();
                $table->string('op_description');
                $table->string('description');
                $table->string('expected_response');
                $table->string('data_format');
                $table->bigInteger('device_command_id')->unsigned();
                $table->foreign('device_command_id')->references('id')->on('device_commands')->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_command_descriptions');
    }
};
