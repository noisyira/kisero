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
        <div class="btn-group" ng-model="answer.type" ng-click="clear()" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "1"> Уже прошел</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "2"> Планирую </label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "3"> Отказываюсь</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "4"> Затрудняюсь ответить</label>
        </div>
    </div>
</div>

<!-- сценарий: Диспанцеризация пройдена -->
<div class="row" ng-if="answer.type == 1">
    <div class="col-md-12">

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Сколько раз посетили поликлинику для прохождения диспансеризации? </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.1]" bs-radio-group>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "one"> 1 </label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "two"> 2 </label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "three"> 3</label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "more"> более 3 </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Как Вы оцениваете отношение к Вам медицинских работников? </strong></p>

            </div>

            <div class="col-md-12">
                <oi-select
                    id="vnimanie"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in vnimanie"
                    ng-model="answer.question[1.2]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Пришлось ли Вам, столкнуться в поликлинике с отсутствием необходимых специалистов, диагностических процедур при проведении диспансеризации? </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.4]" bs-radio-group>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "yes"> Да </label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "no"> Нет </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Если да, то были ли Вы направлены в другое лечебное учреждение и с какой целью? </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="answer1_5"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in sendMO"
                    ng-model="answer.question[1.5]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Были ли у Вас выявлены новые заболевания при проведении диспансеризации? </strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.6]" bs-radio-group>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "yes"> Да </label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "no"> Нет </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Ознакомлены ли Вы с результатом диспансеризации ( с Вашей группой здоровья, результатами обследования и выявленными заболеваниями )?</strong></p>
            </div>

            <div class="col-md-12">
                <div class="btn-group" ng-model="answer.question[1.7]" bs-radio-group>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "yes"> Да </label>
                    <label ng-disabled="answer.fail1" class="btn btn-default"><input type="radio" class="btn btn-default" value = "no"> Нет </label>
                </div>
            </div>
        </div>

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i><strong> Суммируя вышесказанное, удовлетворенны ли Вы в целом медицинским обслуживанием в поликлинике при проведении диспансеризации?</strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="itog1_1"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in resultList"
                    ng-model="answer.question[1.9]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>

    </div>
</div>

<!-- сценарий: Планирует пройти -->
<div class="row" ng-if="answer.type == 2">
    <div class="col-md-12">
        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong> Период прохождения диспанцеризации </strong></p>
            </div>

            <div class="col-md-12">
                <oi-select
                    id="yearRange"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in yearRange"
                    ng-model="answer.question[2.1]"
                    placeholder=" — Выберите —"
                ></oi-select>
            </div>
        </div>
    </div>
</div>

<!-- сценарий: Отказ от диспансеризации -->
<div class="row" ng-if="answer.type == 3">
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

        <div class="row pd10">
            <div class="col-md-12">
                <p><i class="fa fa-hashtag" aria-hidden="true"></i> <strong>Другая причина </strong></p>
            </div>

            <div class="col-md-12">
                <textarea class="form-control"
                          style="margin-top: 5px;"
                          ng-model="answer.question[3.2]"
                          placeholder="Причина отказа от диспансеризации"
                ></textarea>
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