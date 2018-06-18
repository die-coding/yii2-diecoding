<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 06 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use diecoding\rbac\models\User as UserMain;

/**
* User represents the model behind the search form about `diecoding\rbac\models\User`.
*/
class User extends UserMain
{
  /**
  * @inheritdoc
  */
  public function rules()
  {
    return [
      [['id', 'status', 'created_at', 'updated_at'], 'integer'],
      [['username', 'auth_key', 'password_hash', 'password_reset_token', /* 'email', */ 'display_name'], 'safe'],
    ];
  }

  /**
  * @inheritdoc
  */
  public function scenarios()
  {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  /**
  * Creates data provider instance with search query applied
  *
  * @param array $params
  *
  * @return ActiveDataProvider
  */
  public function search($params, $class)
  {
    $query = $class::find();
    $query->where(['status' => $class::STATUS_ACTIVE]);

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
      $query->where('1=0');
      return $dataProvider;
    }

    $query->andFilterWhere([
      'id' => $this->id,
      'status' => $this->status,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ]);

    $query->andFilterWhere(['like', 'username', $this->username])
    ->andFilterWhere(['like', 'auth_key', $this->auth_key])
    ->andFilterWhere(['like', 'password_hash', $this->password_hash])
    ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
    ->andFilterWhere(['like', 'display_name', $this->display_name])
    ->andFilterWhere(['like', 'last_login', $this->last_login]);
    // ->andFilterWhere(['like', 'email', $this->email]);

    return $dataProvider;
  }
}
