<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Stmt;
use yii\helpers\VarDumper;

/**
 * StmtSearch represents the model behind the search form about `backend\models\Stmt`.
 */
class StmtSearchArchive extends Stmt
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
            [['user', 'send', 'group', 'theme', 'fio', 'org', 'statement_date', 'tip_statement', 'stage_statement', 'theme_statement',
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
//            ->where('status != :status', [':status' => 'Закрыт'])
        $query->where('status = :status', [':status' => 3]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);

        if(!$params['StmtSearchArchive']['statement_date'])
        {
            $query->andWhere('YEAR(statement_date) = '. date("Y"));
        }

        if (isset($params['StmtSearchArchive']['org']))
        {
            $query->andWhere(['stmt.company' => $params['StmtSearchArchive']['org']]);
        }

        // add conditions that should always apply here
        $query->joinWith(['group', 'theme', 'send', 'org', 'operator', 'call', 'stage', 'stmt_status', 'deffered']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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

        /* Таблица mn_statement {Тема обращения} */
        $dataProvider->sort->attributes['theme'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['mn_statement.theme_statement' => SORT_ASC],
            'desc' => ['mn_statement.theme_statement' => SORT_DESC],
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
          //  'company' => $this->org,
  //          'statement_date' => $this->statement_date,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $query->andFilterWhere(['=', 'tip_statement', $this->group])
            ->andFilterWhere(['like', 'stage_statement', $this->stage_statement])
            ->andFilterWhere(['=', 'mn_statement.key_statement', $this->theme])
            ->andFilterWhere(['like', 'theme_statement_description', $this->theme_statement_description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['=', 'mn_company.id', $this->org])
            ->andFilterWhere(['like', 'mn_send_statement.name', $this->send])
            ->andFilterWhere(['like', 'stmt_deffered.fam', $this->fio])
        //    ->andFilterWhere(['like', 'mn_statement.theme_statement', $this->theme])
        ;

        return $dataProvider;
    }

    /**
     * Отчёт таблица 1.1
     * Гпруппировка по обращению
     */
    public function searchReportTotal($params)
    {
        $query = Stmt::find();

        $query->select([
                'stmt.theme_statement',
                'COUNT(case when stmt.company = 1 AND stmt.form_statement = 0 then \'\' end) as \'t-voice\'',
                'COUNT(case when stmt.company = 1 AND stmt.form_statement = 1 then \'\' end) as \'t-write\'',
                'COUNT(case when stmt.company = 1 then \'\' end) as \'t-total\'',

                'COUNT(case when stmt.company != 1 AND stmt.form_statement = 0 then \'\' end) as \'smo-voice\'',
                'COUNT(case when stmt.company != 1 AND stmt.form_statement = 1 then \'\' end) as \'smo-write\'',
                'COUNT(case when stmt.company != 1 then \'\' end) as \'smo-total\'',
                'mn_statement.k',
                'COUNT(id) AS total'
            ]);
        $query->where('status = :status', [':status' => 3]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        if($params->statement_date)
        {
            $start_date = $this->dateRange($params->statement_date, 0);
            $end_date = $this->dateRange($params->statement_date, 1);

            $query->andWhere(['between', 'statement_date', $start_date, $end_date]);
        }
        $query->joinWith(['theme']);
        $query->groupBy(['stmt.theme_statement', 'mn_statement.k']);
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        if($params->statement_date)
        {
            $dt = explode(' - ', $params->statement_date);

            $query->andFilterWhere(['between', 'statement_date', $dt[0], $dt[1]]);
        }


        $this->filterReport($query)
        ;

        return $dataProvider;
    }

    /**
     * Отчёт таблица 1.1
     * Гпруппировка по обращению
     */
    public function searchReportAll($params)
    {
        $query = Stmt::find();

        /* $query->select */
        $this->reportSelect($query);

        $query->where('status = :status', [':status' => 3]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        if($params->statement_date)
        {
            $start_date = $this->dateRange($params->statement_date, 0);
            $end_date = $this->dateRange($params->statement_date, 1);

            $query->andWhere(['between', 'statement_date', $start_date, $end_date]);
        }
        $query->joinWith(['theme']);
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $this->filterReport($query)
        ;

        return $dataProvider;
    }


    /**
     * Отчёт таблица 1.1
     * обращений по телефону горячей линии
     */
    public function searchReportTotalCalls($params)
    {
        $query = Stmt::find();

        /* $query->select */
        $this->reportSelect($query);

        $query->where(['status' => 3, 'statement' => 2]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        if($params->statement_date)
        {
            $start_date = $this->dateRange($params->statement_date, 0);
            $end_date = $this->dateRange($params->statement_date, 1);

            $query->andWhere(['between', 'statement_date', $start_date, $end_date]);
        }
        $query->joinWith(['theme']);
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $this->filterReport($query)
        ;

        return $dataProvider;
    }


    /**
     * Отчёт таблица 1.1
     * обращений по сети Интернет
     */
    public function searchReportTotalInternet($params)
    {
        $query = Stmt::find();

        /* $query->select */
        $this->reportSelect($query);

        $query->where(['status' => 3, 'statement' => 9]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        if($params->statement_date)
        {
            $start_date = $this->dateRange($params->statement_date, 0);
            $end_date = $this->dateRange($params->statement_date, 1);

            $query->andWhere(['between', 'statement_date', $start_date, $end_date]);
        }
        $query->joinWith(['theme']);
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        $this->filterReport($query)
        ;

        return $dataProvider;
    }

    /**
     * Отчёт таблица 1.1
     * обращений Жалобы
     */
    public function searchReportTotalPlaint($params)
    {
        $query = Stmt::find();

        /* $query->select */
        $this->reportSelect($query);
        $query->joinWith(['theme']);
        $query->where(['tip_statement' => 1]);
        $query->andWhere(['NOT IN', 'user_o', [44, 47]]);
        if($params->statement_date)
        {
            $start_date = $this->dateRange($params->statement_date, 0);
            $end_date = $this->dateRange($params->statement_date, 1);

            $query->andWhere(['between', 'statement_date', $start_date, $end_date]);
        }
        $query->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stmt.id' => $this->id,
            'statement' => $this->statement,
        ]);

        if($this->statement_date)
        {
            $start_date = $this->dateRange($this->statement_date, 0);
            $end_date = $this->dateRange($this->statement_date, 1);

            $query->andFilterWhere(['between', 'statement_date', $start_date, $end_date]);
        }

        // Фильтр
        $this->filterReport($query);

        return $dataProvider;
    }


    public function dateRange($date, $type)
    {
        return Yii::$app->formatter->asDate(explode(' - ', $date)[$type]);
    }

    /**
     * @param $query
     */
    public function reportSelect($query)
    {
        return $query->select([
            'COUNT(case when stmt.company = 1 AND stmt.form_statement = 0 then \'\' end) as \'t-voice\'',
            'COUNT(case when stmt.company = 1 AND stmt.form_statement = 1 then \'\' end) as \'t-write\'',
            'COUNT(case when stmt.company = 1 then \'\' end) as \'t-total\'',

            'COUNT(case when stmt.company != 1 AND stmt.form_statement = 0 then \'\' end) as \'smo-voice\'',
            'COUNT(case when stmt.company != 1 AND stmt.form_statement = 1 then \'\' end) as \'smo-write\'',
            'COUNT(case when stmt.company != 1 then \'\' end) as \'smo-total\'',
            'COUNT(stmt.id) AS total'
        ]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function filterReport($query)
    {
        return $query->andFilterWhere(['like', 'tip_statement', $this->group])
            ->andFilterWhere(['like', 'stage_statement', $this->stage_statement])
            ->andFilterWhere(['like', 'theme_statement', $this->theme_statement])
            ->andFilterWhere(['like', 'theme_statement_description', $this->theme_statement_description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['=', 'company', $this->org])
            ->andFilterWhere(['like', 'mn_send_statement.name', $this->send])
            ->andFilterWhere(['like', 'stmt_deffered.fam', $this->fio])
            ->andFilterWhere(['like', 'mn_statement.theme_statement', $this->theme]);
    }
}
