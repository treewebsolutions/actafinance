<?php

namespace common\validators;

use yii\validators\Validator;

class PIDValidator extends Validator
{
	/**
	 * @param \yii\base\Model $model
	 * @param string $attribute
	 * @return bool|void
	 */
	public function validateAttribute($model, $attribute)
	{
		// CNP must have 13 characters
		if(strlen($model->$attribute) != 13) {
			$this->addError($model, $attribute, 'PID is invalid.');
			return false;
		}
		$cnp = str_split($model->$attribute);
//		unset($model->$attribute);
		$hashTable = array( 2 , 7 , 9 , 1 , 4 , 6 , 3 , 5 , 8 , 2 , 7 , 9 );
		$hashResult = 0;
		// All characters must be numeric
		for($i=0 ; $i<13 ; $i++) {
			if(!is_numeric($cnp[$i])) {
				return false;
			}
			$cnp[$i] = (int)$cnp[$i];
			if($i < 12) {
				$hashResult += (int)$cnp[$i] * (int)$hashTable[$i];
			}
		}
		unset($hashTable, $i);
		$hashResult = $hashResult % 11;
		if($hashResult == 10) {
			$hashResult = 1;
		}
		// Check Year
		$year = ($cnp[1] * 10) + $cnp[2];
		switch( $cnp[0] ) {
			case 1  : case 2 : { $year += 1900; } break; // cetateni romani nascuti intre 1 ian 1900 si 31 dec 1999
			case 3  : case 4 : { $year += 1800; } break; // cetateni romani nascuti intre 1 ian 1800 si 31 dec 1899
			case 5  : case 6 : { $year += 2000; } break; // cetateni romani nascuti intre 1 ian 2000 si 31 dec 2099
			case 7  : case 8 : case 9 : {                // rezidenti si Cetateni Straini
			$year += 2000;
			if($year > (int)date('Y')-14) {
				$year -= 100;
			}
		} break;
			default : {
				return false;
			} break;
		}
		if ($year > 1800 && $year < 2099 && $cnp[12] != $hashResult) {
			$this->addError($model, $attribute, 'PID is invalid.');
			return false;
		}

		return ($year > 1800 && $year < 2099 && $cnp[12] == $hashResult);
	}
}