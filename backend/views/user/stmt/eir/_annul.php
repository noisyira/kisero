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
                <strong class="name"> Плановая дата начало госпитализации: </strong>
            </div>
            <div class="col-md-8">
                <span class="value">
                    {{pacient.DPGOSP}}
                </span>
            </div>
        </div>

        <div class="row pd5">
            <div class="col-md-4">
                <strong class="name"> Дата аннулирования направления: </strong>
            </div>
            <div class="col-md-8">
                <span class="value">
                    {{pacient.DANUL}}
                </span>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row" style="padding-bottom: 20px;">
    <div class="col-md-8">

        <div class="col-md-1">
            <input type="checkbox" id="another_phone" ng-model="value.another_phone" value="1">
        </div>
        <div class="col-md-11">
            <label for="another_phone">Телефон указан неверно или принадлежит другому ЗЛ</label>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-2" style="padding-top: 5px;">
        <label>Причина неявки пациента</label>
    </div>

    <div class="col-md-3">
        <oi-select
                id="vnimanie"
                class="dropList"
                oi-options="item.id as item.name for item in panul"
                ng-model="value.panul"
                placeholder=" — Выберите —"
        ></oi-select>
    </div>

    <div class="col-md-3">
        <textarea class="form-control"
                  rows="1"
                  ng-model="value.comment"
                  placeholder="Напишите комментарий ..."
        ></textarea>
    </div>

    <div class="col-md-4 text-right">
        <button class="btn btn-sm btn-tfoms-red" type="button"> <i class="fa fa-" aria-hidden="true"></i> Нет ответа</button>
        <button class="btn btn-sm btn-default" type="button" ng-click="savePeople(value)"> <i class="fa fa-" aria-hidden="true"></i> Сохранить</button>
    </div>
</div>