<div class="row">
    <div class="col-md-12">

        <div class="row pd5">
            <div class="col-md-4">
                <strong class="name"> Госпитализация </strong>
            </div>
            <div class="col-md-8">
                <span class="value">

                    <div ng-switch on="pacient.FOMP">
                        <div ng-switch-when="3">
                             <span class="label label-tfoms-orange">Экстренная</span>
                        </div>
                        <div ng-switch-default>
                             <span class="label label-primary">Плановая</span>
                        </div>
                    </div>

                </span>
            </div>
        </div>

        <div class="row pd5">
            <div class="col-md-4">
                <strong class="name"> Дата начало госпитализации: </strong>
            </div>
            <div class="col-md-8">
                <span class="value">
                    {{pacient.DNGOSP}}
                </span>
            </div>
        </div>

        <div class="row pd5">
            <div class="col-md-4">
                <strong class="name"> Плановая дата окончания госпитализации: </strong>
            </div>
            <div class="col-md-8">
                <span class="value">
                    {{pacient.DPOGOSP}}
                </span>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-5">
            <label>Удовлетворены ли Вы сроками ожидания плановой госпитализации?</label>
        </div>

        <div class="col-md-5">
            <div class="btn-group" ng-model="data.question1" bs-radio-group>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Не удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="2"> Затрудняюсь ответить</label>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-5">
            <label>Оцените уровень Вашей удовлетворенности работой лечащего врача</label>
        </div>

        <div class="col-md-5">
            <div class="btn-group" ng-model="data.question2" bs-radio-group>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Не удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="2"> Затрудняюсь ответить</label>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-5">
            <label>Приходилось ли Вам использовать личные денежные средства на приобретение лекарств, оплачивать мед. услуги в период госпитализации?</label>
        </div>

        <div class="col-md-5">
            <div class="btn-group" ng-model="data.question3" bs-radio-group>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Да</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Нет</label>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-5">
            <label>Оцените уровень Вашей удовлетворенности качеством и доступностью мед. помощи в стационаре</label>
        </div>

        <div class="col-md-5">
            <div class="btn-group" ng-model="data.question4" bs-radio-group>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Не удовлетворен</label>
                <label class="btn btn-default"><input type="radio" class="btn btn-default" value="2"> Затрудняюсь ответить</label>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12 text-right">
        <button class="btn btn-sm btn-tfoms-red" type="button"> <i class="fa fa-" aria-hidden="true"></i> Нет ответа</button>
        <button class="btn btn-sm btn-default" type="button" ng-click="savePeople(data)"> <i class="fa fa-" aria-hidden="true"></i> Сохранить</button>
    </div>
</div>