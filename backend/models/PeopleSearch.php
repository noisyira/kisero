<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\People;

/**
 * PeopleSearch represents the model behind the search form about `backend\models\People`.
 */
class PeopleSearch extends People
{

    public $total = 100;
    public $smo;
    public $mo;
    public $from_age;
    public $to_age;
    public $equal;
    public $RN;
    public $range = 50;

    public $list;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sexMan', 'typeDoc', 'statusMan', 'regKladrId', 'activPolesId', 'liveKladrId', 'cr', 'from_age',
            'to_age', 'equal', 'RN', 'lostman', 'livetemp', 'refugee', 'FFOMS', 'wid', 'pr_rab', 'total', 'range', 'smo', 'mo'], 'integer'],
            [['ENP', 'Name', 'sName', 'pName', 'dateMan', 'pbMan', 'nationMan', 'serDoc', 'numDoc', 'dateDoc', 'addressReg', 'dateReg', 'addressLive', 'dateDeath', 'snils', 'okato_mj', 'InsDate', 'crdate', 'proxy_man', 'contact', 'doc_date_end', 'orgdoc', 'okato_reg', 'kladr_reg', 'kladr_live'], 'safe'],
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

    public function attributeLabels()
    {
        return [
            'total' => 'Кол-во',
            'smo' => 'СМО',
            'mo' => 'Мед. организация',
            'okato_mj' => 'Место проживания',
            'equal' => '50/50',
            'RN' => '50/50',
            'from_age' => 'Возраст (от)',
            'to_age' =>  'Возраст (до)',
            'range' => 'Соотношение к СМО: (%)'
        ];
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
        $this->load($params);

        // Список людей которые планирую пройти диспансеризацию;
        $subquery = People::find();
            $subquery->select(['ENP']);
            $subquery->joinWith(['reestr']);
            $subquery->where('LEN(contact) > 8');
            $subquery->andWhere(['reestr.orgId' => 1 ]);

            if(!empty($this->from_age))
            {
                $date = new \DateTime('01-01-'.date('Y'));
                $d = $date->sub(new \DateInterval('P'.$this->from_age.'Y'));

                $subquery->andWhere(['<=', 'dateMan', $d->format('d-m-Y')]);
            }

            if(!empty($this->to_age))
            {
                $date = new \DateTime('01-01-'.date('Y'));
                $d = $date->sub(new \DateInterval('P'.$this->to_age.'Y'));

                $subquery->andWhere(['>=', 'dateMan', $d->format('d-m-Y')]);
            }

            $subquery->orderBy('newId()');
            $subquery->limit( intval($this->total * ($this->range/100)) );

        // Список людей которые планирую пройти диспансеризацию;
        $subquery1 = People::find();
            $subquery1->select(['ENP']);
            $subquery1->joinWith(['reestr']);
            $subquery1->where('LEN(contact) > 8');
            $subquery1->andWhere(['reestr.orgId' => 2 ]);

            if(!empty($this->from_age))
            {
                $date = new \DateTime('01-01-'.date('Y'));
                $d = $date->sub(new \DateInterval('P'.$this->from_age.'Y'));

                $subquery1->andWhere(['<=', 'dateMan', $d->format('d-m-Y')]);
            }

            if(!empty($this->to_age))
            {
                $date = new \DateTime('01-01-'.date('Y'));
                $d = $date->sub(new \DateInterval('P'.$this->to_age.'Y'));

                $subquery1->andWhere(['>=', 'dateMan', $d->format('d-m-Y')]);
            }

            $subquery1->orderBy('newId()');
            $subquery1->limit( intval($this->total * ( (100 - $this->range) /100)) );

        $query = People::find();
        $query->joinWith(['reestr']);

        if(empty($this->smo))
        {
            $query->where(['IN', 'ENP', $subquery]);
            $query->orWhere(['IN', 'ENP', $subquery1]);
        }

        $query->limit($this->total);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> false,
            'pagination' => false
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $subquery->andFilterWhere([
            'reestr.orgId' => $this->smo,
            'okato_reg' => $this->okato_mj,

        ]);
        // grid filtering conditions
        $subquery1->andFilterWhere([
            'reestr.orgId' => $this->smo,
            'okato_reg' => $this->okato_mj,

        ]);

        return $dataProvider;
    }
}
