<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 10 March 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 15 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */



namespace diecoding\rbac\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use diecoding\rbac\models\Menu as MenuModel;

/**
 * Menu represents the model behind the search form about [[\diecoding\rbac\models\Menu]].
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 1.0.0
 */
class Menu extends MenuModel
{

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['id', 'parent', 'visible', 'order'], 'integer'],
      [['name', 'route', 'parent_name', 'icon', 'assign'], 'safe'],
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
   * Searching menu
   * @param  array $params
   * @return \yii\data\ActiveDataProvider
   */
  public function search($params)
  {
    $query = MenuModel::find()
    ->from(MenuModel::tableName() . ' t')
    ->joinWith(['menuParent' => function ($q) {
      $q->from(MenuModel::tableName() . ' parent');
    }]);

    $dataProvider = new ActiveDataProvider([
      'query' => $query
    ]);

    $sort = $dataProvider->getSort();
    $sort->attributes['menuParent.name'] = [
      'asc' => ['parent.name' => SORT_ASC],
      'desc' => ['parent.name' => SORT_DESC],
      'label' => 'parent',
    ];
    $sort->attributes['order'] = [
      'asc' => ['parent.order' => SORT_ASC, 't.order' => SORT_ASC],
      'desc' => ['parent.order' => SORT_DESC, 't.order' => SORT_DESC],
      'label' => 'order',
    ];
    $sort->defaultOrder = ['menuParent.name' => SORT_ASC];

    if (!($this->load($params) && $this->validate())) {
      return $dataProvider;
    }

    $query->andFilterWhere([
      't.id'      => $this->id,
      't.parent'  => $this->parent,
      't.visible' => $this->visible,
    ]);

    $query->andFilterWhere(['like', 'lower(t.name)', strtolower($this->name)])
    ->andFilterWhere(['like', 't.route', $this->route])
    ->andFilterWhere(['like', 't.icon', $this->icon])
    ->andFilterWhere(['like', 't.assign', $this->assign])
    ->andFilterWhere(['like', 'lower(parent.name)', strtolower($this->parent_name)]);

    return $dataProvider;
  }
}
