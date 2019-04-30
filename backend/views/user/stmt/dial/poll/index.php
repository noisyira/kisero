<div class="row pd5">
    <div class="col-md-12">
        <div class="col-md-1">
            <input type="checkbox" id="fail" ng-model="answer.fail">
        </div>
        <div class="col-md-11">
            <label for="fail">Гражданин отказался отвечать на вопросы</label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Как часто Вы пользуетесь услугами Контакт-Центра </strong>
    </div>

    <div class="col-md-12">
        <oi-select
            class="dropList"
            oi-options="item.id as item.name for item in list1"
            ng-model="answer.use_call"
            ng-disabled="answer.fail"
        ></oi-select>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Каким образом Вы связались с Контакт-Центром </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.type" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "0"> беспланый номер <br> 8-800-707-11-35</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "1"> телефон горячей линии <br> 94-11-35 </label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value = "2"> воспользовались услугами <br> «Электронного секретаря»</label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Удовлетворены ли вы обслуживанием Контакт-Центром </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.service" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Да</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Частично </label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="2"> Нет </label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="3"> Другое</label>
        </div>
    </div>

    <div class="col-md-12" ng-show="answer.service == 3">
                    <textarea class="form-control"
                              style="margin-top: 5px;"
                              ng-model="answer.service_reason"
                              placeholder="Укажите причину"
                              ng-disabled="answer.fail"
                    ></textarea>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Полнота предоставленной информации </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.info" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> удовлетворен</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> не удовлетворен </label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Вежливость, приветливость оператора  </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.comity" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> удовлетворен</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> не удовлетворен </label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Время ожидание ответа оператора  </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.waiting" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> удовлетворен</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> не удовлетворен </label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Получили ли Вы ответы на свои вопросы  </strong>
    </div>

    <div class="col-md-12">
        <div class="btn-group" ng-model="answer.get" bs-radio-group>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Да</label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Нет </label>
            <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" class="btn btn-default" value="2"> не полностью </label>
        </div>
    </div>
</div>

<div class="row pd5">
    <div class="col-md-12">
        <strong> Коментарий  </strong>
    </div>

    <div class="col-md-12">
                    <textarea class="form-control"
                              style="margin-top: 5px;"
                              ng-model="answer.description"
                              placeholder="Комментраий"
                    ></textarea>
    </div>
</div>