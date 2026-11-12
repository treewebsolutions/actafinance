<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200906_061031_new_answers_c
 */
class m200906_061031_new_answers_c extends Migration
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
				'letter' => 'c',
				'correct' => strpos($row['corect'], 'c') !== false ? 1 : 0,
				'status' => $row['activ'],
				'deleted' => 0
			]);

			$c_id = Yii::$app->db->getLastInsertID();

			$this->insert('answer_translation', [
				'answer_id' => $c_id,
				'language_id' => 'ro-RO',
				'content' => $row['raspuns_c'],
				'deleted' => 0
			]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200906_061031_new_answers_c cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200906_061031_new_answers_c cannot be reverted.\n";

        return false;
    }
    */
}
