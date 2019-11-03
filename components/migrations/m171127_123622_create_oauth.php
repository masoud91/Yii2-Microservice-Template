<?php

namespace micro\components\migrations;

class m171127_123622_create_oauth extends \yii\mongodb\Migration
{

    public function up()
    {
        $this->createCollection('sample');
        $this->insert('oauth_clients', [
            'key' => 'value',
        ]);
    }

    public function down()
    {
        $this->dropCollection('sample');
        return true;
    }
}
