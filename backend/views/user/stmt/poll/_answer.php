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

<div class="row pd10" ng-repeat="question in model.questions">
    <span  ng-if="$index == 0 || answer.question[question.id - 1] == 1">
        <div class="col-md-12">
            <blockquote>
                <footer>{{question.value}}</footer>
            </blockquote>
        </div>

        <div class="col-md-12">
            <div class="btn-group" ng-model="answer.question[question.id]"  bs-radio-group>
                <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" ng-click="chgAnswer(question.id)" class="btn btn-default" ng-disabled="answer.fail"  value = "1"> Да</label>
                <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" ng-click="chgAnswer(question.id)" class="btn btn-default" ng-disabled="answer.fail"  value = "2"> Нет </label>
                <label ng-disabled="answer.fail" class="btn btn-default"><input type="radio" ng-click="chgAnswer(question.id)" class="btn btn-default" ng-disabled="answer.fail"  value = "3"> Затрудняюсь ответить</label>
            </div>
        </div>
    </span>
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