<?php
/**
 * 用户模型
 * models/User.php
 *
 * @author Ttall <ttall.su@gmail.com>
 * @link http://www.ttall.net/
 * @copyright Copyright © 2012-2015 ttall.net
 * @license http://www.ttall.net/license/
 */
class User extends CActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{user}}';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array( array('password', 'required'), array('username, password', 'length', 'max' => 32), array('created', 'safe'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('u_id, u_name, u_password, created', 'safe', 'on' => 'search'), );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
}
