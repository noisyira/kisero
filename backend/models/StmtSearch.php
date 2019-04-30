<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Stmt;

/**
 * StmtSearch represents the model behind the search form about `backend\models\Stmt`.
 */
class StmtSearch extends Stmt
{
    public $user;
    public $send;
    public $group;
    public $theme;
    public $org;
    public $fio;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'statement'], 'integer'],
            [['user', 'send', 'group', 'theme', 'org', 'fio', 'statement_date', 'tip_statement', 'stage_statement', 'theme_statement',
                'theme_statement_description', 'status'], 'safe'],
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
        $query = Stmt::find();
//        ->where('status != :status', [':status' => 'Закрыт'])
//        $query->orWhere('statement != :statement', [':statement' => 2]);
//        $query->orWhere(['statement' => 2,'status' => '2']);

        $query->where(['NOT IN', 'status', ['0', '1', '3', '6']]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);

        // add conditions that should always apply here
        $query->joinWith(['group', 'theme', 'send', 'org', 'operator', 'call', 'stage', 'stmt_status', 'deffered']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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

        /* Таблица mn_company {Организация} */
        $dataProvider->sort->attributes['org'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_company.name' => SORT_ASC],
            'desc' => ['mn_company.name' => SORT_DESC],
        ];

        /* Таблица stmt_deffered {ФИО} */
        $dataProvider->sort->attributes['fio'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['stmt_deffered.fam' => SORT_ASC],
            'desc' => ['stmt_deffered.fam' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
            //   'statement_date' => $this->statement_date,
        ]);
        
        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $query->andFilterWhere(['like', 'tip_statement', $this->tip_statement])
            ->andFilterWhere(['like', 'stage_statement', $this->stage_statement])
            ->andFilterWhere(['like', 'theme_statement', $this->theme_statement])
            ->andFilterWhere(['like', 'theme_statement_description', $this->theme_statement_description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'mn_send_statement.name', $this->send])
            ->andFilterWhere(['like', 'mn_company.name', $this->org])
            ->andFilterWhere(['like', 'stmt_deffered.fam', $this->fio])
            ->andFilterWhere(['like', 'mn_group_statement.name', $this->group])
            ->andFilterWhere(['like', 'mn_statement.theme_statement', $this->theme])
        ;

        return $dataProvider;
    }

    public function searchSstu($params)
    {
        $query = Stmt::find();

        $query->where(['status' => '3']);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        $query->andWhere(['tip_statement' => 1]);
        $query->andWhere(['stmt.company' => 1]);
        $query->andWhere(['>=' ,'YEAR(statement_date)', 2018]);

        // add conditions that should always apply here
        $query->joinWith(['group', 'theme', 'send', 'org', 'operator', 'sstu', 'stage', 'stmt_status', 'deffered']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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

        /* Таблица mn_company {Организация} */
        $dataProvider->sort->attributes['org'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_company.name' => SORT_ASC],
            'desc' => ['mn_company.name' => SORT_DESC],
        ];

        /* Таблица stmt_deffered {ФИО} */
        $dataProvider->sort->attributes['fio'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['stmt_deffered.fam' => SORT_ASC],
            'desc' => ['stmt_deffered.fam' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
            //   'statement_date' => $this->statement_date,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $query->andFilterWhere(['like', 'tip_statement', $this->tip_statement])
            ->andFilterWhere(['like', 'stage_statement', $this->stage_statement])
            ->andFilterWhere(['like', 'theme_statement', $this->theme_statement])
            ->andFilterWhere(['like', 'theme_statement_description', $this->theme_statement_description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'mn_send_statement.name', $this->send])
            ->andFilterWhere(['like', 'mn_company.name', $this->org])
            ->andFilterWhere(['like', 'stmt_deffered.fam', $this->fio])
            ->andFilterWhere(['like', 'mn_group_statement.name', $this->group])
            ->andFilterWhere(['like', 'mn_statement.theme_statement', $this->theme])
        ;

        return $dataProvider;
    }

    public function dateRange($date, $type)
    {
        return Yii::$app->formatter->asDate(explode(' - ', $date)[$type]);
    }
}
