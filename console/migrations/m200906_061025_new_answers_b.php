<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200906_061025_new_answers_b
 */
class m200906_061025_new_answers_b extends Migration
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
				'letter' => 'b',
				'correct' => strpos($row['corect'], 'b') !== false ? 1 : 0,
				'status' => $row['activ'],
				'deleted' => 0
			]);

			$b_id = Yii::$app->db->getLastInsertID();

			$this->insert('answer_translation', [
				'answer_id' => $b_id,
				'language_id' => 'ro-RO',
				'content' => $row['raspuns_b'],
				'deleted' => 0
			]);
		}
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200906_061025_new_answers_b cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200906_061025_new_answers_b cannot be reverted.\n";

        return false;
    }
    */
}
