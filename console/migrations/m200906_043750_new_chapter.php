<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200906_043750_new_chapter
 */
class m200906_043750_new_chapter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
	{
		// Fetch data from some tables using Query Builder
		$data = (new Query)
			->select([
				'*',
			])
			->from('tipuri_intrebari')
			->orderBy(['id' => SORT_ASC]);

		foreach ($data->each(100) as $row) {
			// Insert new rows
			$this->insert('chapter', [
				'id' => $row['id'],
				'status' => $row['activ'],
				'rate' => $row['ratie'],
				'deleted' => 0
			]);
			$this->insert('chapter_translation', [
				'chapter_id' => $row['id'],
				'language_id' => 'ro-RO',
				'content' => $row['nume'],
				'deleted' => 0
			]);
		}
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200906_043750_new_chapter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200906_043750_new_chapter cannot be reverted.\n";

        return false;
    }
    */
}
