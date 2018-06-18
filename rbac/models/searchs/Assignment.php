<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 14 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AssignmentSearch represents the model behind the search form about Assignment.
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class Assignment extends Model
{
  public $id;
  public $username;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['id', 'username'], 'safe'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id'       => Yii::t('diecoding', 'ID'),
      'username' => Yii::t('diecoding', 'Username'),
    ];
  }

  /**
   * Create data provider for Assignment model.
   * @param array $params
   * @param \yii\db\ActiveRecord $class
   * @param string $usernameField
   * @return \yii\data\ActiveDataProvider
   */
  public function search($params, $class, $usernameField)
  {
    $query = $class::find();
    $query->where(['status' => $class::STATUS_ACTIVE]);
    $dataProvider = new ActiveDataProvider([
      'query' => $query,
    ]);

    if (!($this->load($params) && $this->validate())) {
      return $dataProvider;
    }

    $query->andFilterWhere(['like', $usernameField, $this->username]);

    return $dataProvider;
  }
}
