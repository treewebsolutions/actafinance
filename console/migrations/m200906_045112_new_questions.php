<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200906_045112_new_questions
 */
class m200906_045112_new_questions extends Migration
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
			->from('toate')
			->orderBy(['id' => SORT_ASC]);

		foreach ($data->each(100) as $row) {
			// Insert new rows
			$this->insert('question', [
				'id' => $row['id'],
//				'chapter_id' => $row['tip_i'],
				'image' => str_replace('images/', '', $row['imagine']),
				'status' => $row['activ'],
				'deleted' => 0
			]);
			$this->insert('question_translation', [
				'question_id' => $row['id'],
				'language_id' => 'ro-RO',
				'content' => $row['text'],
				'deleted' => 0
			]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200906_045112_new_questions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200906_045112_new_questions cannot be reverted.\n";

        return false;
    }
    */
}
