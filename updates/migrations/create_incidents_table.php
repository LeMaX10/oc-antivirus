<?php

use October\Rain\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class create_incidents_table
 */
class create_incidents_table extends Migration
{
    /**
     *
     */
    private const TABLE = 'lemax10_antivirus_incident';

    /**
     *
     */
    public function up()
    {
        Schema::create(static::TABLE, static function (Blueprint $table): void {
            $table->increments('id');
            $table->char('hash', 32);
            $table->text('path');
            $table->json('changes');
            $table->string('resolve_strategy');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     *
     */
    public function down()
    {
        Schema::dropIfExists(static::TABLE);
    }
}
