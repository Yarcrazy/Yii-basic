<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property int $creator_id
 * @property int $updater_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Task[] $createdTasks
 * @property Task[] $updatedTasks
 * @property TaskUser[] $taskUsers
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
  const RELATION_CREATED_TASKS = 'createdTasks';

  public $password;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'user';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['username', 'creator_id', 'created_at'], 'required'],
      [['creator_id', 'updater_id', 'created_at', 'updated_at'], 'integer'],
      [['username', 'password','password_hash', 'auth_key'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'username' => 'Username',
      'password_hash' => 'Password Hash',
      'auth_key' => 'Auth Key',
      'creator_id' => 'Creator ID',
      'updater_id' => 'Updater ID',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCreatedTasks()
  {
    return $this->hasMany(Task::className(), ['creator_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUpdatedTasks()
  {
    return $this->hasMany(Task::className(), ['updater_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getTaskUsers()
  {
    return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getAccessedTasks()
  {
    return $this->hasMany(Task::className(), ['id' => 'task_id'])->via('taskUsers');
  }

  /**
   * {@inheritdoc}
   * @return \app\models\query\UserQuery the active query used by this AR class.
   */
  public static function find()
  {
    return new \app\models\query\UserQuery(get_called_class());
  }

  /**
   * @param int|string $id
   * @return User|null|IdentityInterface
   */
  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  /**
   * @return int|string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getAuthKey()
  {
    return $this->auth_key;
  }

  /**
   * @param mixed $token
   * @param null $type
   * @return void|IdentityInterface
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {

  }

  /**
   * @param string $authKey
   * @return bool
   */
  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }

  public function beforeSave($insert)
  {
    if (parent::beforeSave($insert)) {
      if ($this->isNewRecord) {
        $this->auth_key = Yii::$app->security->generateRandomString();
      }
      return true;
    }
    return false;
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return User::findOne(['username' => $username]);
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return $this->password === $password;
  }
}
