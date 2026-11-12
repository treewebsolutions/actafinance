<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200906_052809_new_answers
 */
class m200906_052809_new_answers_a extends Migration
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
			$this->insert('answer', [
				'question_id' => $row['id'],
				'letter' => 'a',
				'correct' => strpos($row['corect'], 'a') !== false ? 1 : 0,
				'status' => $row['activ'],
				'deleted' => 0
			]);

			$a_id = Yii::$app->db->getLastInsertID();

			$this->insert('answer_translation', [
				'answer_id' => $a_id,
				'language_id' => 'ro-RO',
				'content' => $row['raspuns_a'],
				'deleted' => 0
			]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200906_052809_new_answers_a cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200906_052809_new_answers_a cannot be reverted.\n";

        return false;
    }
    */
}
