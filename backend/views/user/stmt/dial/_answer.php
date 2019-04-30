<div class="row pd10">
    <div class="col-md-12">
        <div class="col-md-1">
            <input type="checkbox" id="fail" ng-model="answer.fail" ng-click="DialFail()">
        </div>
        <div class="col-md-11">
            <label for="fail">Гражданин отказался отвечать на вопросы</label>
        </div>
    </div>
</div>

<div class="row pd10">
    <div class="col-md-12">
        <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Вы проходили диспансеризацию? </strong></p>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.result" ng-click="clear()" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default">
                <input type="radio" class="btn btn-default" ng-disabled="answer.fail" value = "1"> Уже прошел
            </label>
            <label ng-disabled="answer.fail" class="btn btn-default">
                <input type="radio" class="btn btn-default" ng-disabled="answer.fail" value = "2"> Не проходил
            </label>

<!--            <label ng-disabled="answer.fail" class="btn btn-default">-->
<!--                <input type="radio" class="btn btn-default" ng-disabled="answer.fail" value = "3"> Отказываюсь-->
<!--            </label>-->
<!--            <label ng-disabled="answer.fail" class="btn btn-default">-->
<!--                <input type="radio" class="btn btn-default" ng-disabled="answer.fail" value = "4"> Затрудняюсь ответить-->
<!--            </label>-->

        </div>
    </div>
</div>

<!-- сценарий: Диспанцеризация пройдена -->
<div class="row" ng-if="answer.result == 1">
    <div class="col-md-12">

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][5]}} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.1]" bs-radio-group ng-repeat="item in answerList[1][5]">
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][6]}} </strong></p>

            </div>

            <div class="col-md-12">
                <oi-select
                    id="vnimanie"
                    class="dropList"
                    oi-options="item.value as item.text for item in answerList[1][6]"
                    ng-model="answer.question[1.2]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][7]}} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.4]" ng-repeat="item in answerList[1][7]" bs-radio-group>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][8] }} </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="answer1_5"
                    class="dropList"
                    oi-options="item.value as item.text for item in answerList[1][8]"
                    ng-model="answer.question[1.5]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][9] }} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.6]" bs-radio-group ng-repeat="item in answerList[1][9]" >
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[1][10] }} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.7]" bs-radio-group ng-repeat="item in answerList[1][10]" >
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i><strong> {{ questionList[1][11] }} </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="itog1_1"
                    class="dropList"
                    oi-options="item.value as item.text for item in answerList[1][11]"
                    ng-model="answer.question[1.9]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

    </div>
</div>

<!-- сценарий: Планирует пройти -->
<div class="row" ng-if="answer.result == 2">
    <div class="col-md-12">
        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{questionList[2][1]}} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[2.1]" bs-radio-group ng-repeat="item in answerList[2][1]" >
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>


        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[2][2] }} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[2.2]" bs-radio-group ng-repeat="item in answerList[2][2]" >
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[2][3] }} </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                        id="q23"
                        class="dropList"
                        oi-options="item.value as item.text for item in answerList[2][3]"
                        ng-model="answer.question[2.3]"
                        placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> {{ questionList[2][4] }} </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[2.4]" bs-radio-group ng-repeat="item in answerList[2][4]" >
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "{{item.value}}"> {{item.text}} </label>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- сценарий: Отказ от диспансеризации -->
<div class="row" ng-if="answer.result == 3">
    <div class="col-md-12">
        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> По какой причине отказывается? </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="conduct"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in prichina"
                    ng-model="answer.question[3.1]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>
    </div>
</div>

<div class="row pd10">
    <div class="col-md-12">
        <strong> Комментарий  </strong>
    </div>

    <div class="col-md-12">
        <textarea class="form-control"
                  style="margin-top: 5px;"
                  ng-model="answer.description"
                  placeholder="Комментарий"
        ></textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <button class="btn btn-sm btn-tfoms-red" type="button" ng-click="notAnswer()"> <i class="fa fa-times" aria-hidden="true"></i> Нет ответа</button>
        <button class="btn btn-sm btn-default" type="button" ng-click="reCall()"> <i class="fa fa-retweet" aria-hidden="true"></i> Перезвонить</button>
    </div>

    <div class="col-md-6 text-right">
        <button class="btn btn-sm btn-tfoms-green" type="button" ng-disabled="(((answer.result == 5) || (answer.result == 12) ) && (answer.fail != true)) || !answer" ng-click="save()"> <i class="fa fa-check" aria-hidden="true"></i> Сохранить</button>
        <button class="btn btn-sm btn-default" type="button" ng-click="nextCall()"> <i class="fa fa-arrow-right" aria-hidden="true"></i> Следующий</button>
    </div>
</div>