<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Statement;

/**
 * StatementSearch represents the model behind the search form about `backend\models\Statement`.
 */
class StatementSearch extends Statement
{
    public $user;
    public $send;
    public $group;
    public $theme;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'send_user', 'statement', 'erz_uid'], 'integer'],
            [['user', 'send', 'group', 'theme', 'channel_id', 'statement_date', 'tip_statement', 'theme_statement',
        'theme_statement_description', 'f_name', 'name', 'l_name', 'dt', 'status'], 'safe'],

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
    public function search($params)
    {
        $query = Statement::find()
            ->where('status = :status', [':status' => 'Завершен']);

        $query->joinWith(['user', 'group', 'theme', 'send']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        /* Таблица Login */
        $dataProvider->sort->attributes['user'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['Login.fam' => SORT_ASC],
            'desc' => ['Login.fam' => SORT_DESC],
        ];

        /* Таблица mn_send_statement {Сбособ добавления обращения} */
        $dataProvider->sort->attributes['send'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_send_statement.name' => SORT_ASC],
            'desc' => ['mn_send_statement.name' => SORT_DESC],
        ];

        /* Таблица mn_group_statement {Тип обращения} */
        $dataProvider->sort->attributes['group'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_group_statement.name' => SORT_ASC],
            'desc' => ['mn_group_statement.name' => SORT_DESC],
        ];

        /* Таблица mn_statement {Тема обращения} */
        $dataProvider->sort->attributes['theme'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_statement.theme_statement' => SORT_ASC],
            'desc' => ['mn_statement.theme_statement' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'statement.id' => $this->id,
            'user_id' => $this->user_id,
            'send_user' => $this->send_user,
            'statement' => $this->statement,
            'statement_date' => $this->statement_date,
            'erz_uid' => $this->erz_uid,
            'dt' => $this->dt,
        ]);

        $query->andFilterWhere(['like', 'channel_id', $this->channel_id])
            ->andFilterWhere(['like', 'tip_statement', $this->tip_statement])
            ->andFilterWhere(['like', 'theme_statement', $this->theme_statement])
            ->andFilterWhere(['like', 'theme_statement_description', $this->theme_statement_description])
            ->andFilterWhere(['like', 'f_name', $this->f_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'l_name', $this->l_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'Login.fam', $this->user])
            ->andFilterWhere(['like', 'mn_send_statement.name', $this->send])
            ->andFilterWhere(['like', 'mn_group_statement.name', $this->group])
            ->andFilterWhere(['like', 'mn_statement.theme_statement', $this->theme])
        ;

        return $dataProvider;
    }
}
