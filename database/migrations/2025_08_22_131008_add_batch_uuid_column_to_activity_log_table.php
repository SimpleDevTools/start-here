<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Smpita\ConfigAs\ConfigAs;

class AddBatchUuidColumnToActivityLogTable extends Migration
{
    public function up(): void
    {
        $connection = ConfigAs::nullableString('activitylog.database_connection');
        $tableName = ConfigAs::string('activitylog.table_name');

        Schema::connection($connection)->table($tableName, function (Blueprint $table) {
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }

    public function down(): void
    {
        $connection = ConfigAs::string('activitylog.database_connection');
        $tableName = ConfigAs::string('activitylog.table_name');

        Schema::connection($connection)->table($tableName, function (Blueprint $table) {
            $table->dropColumn('batch_uuid');
        });
    }
}
