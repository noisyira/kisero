<div class="row">
    <div class="col-md-12">
        <div class="mod-header">
            <ul class="ops"></ul>
            <h2 class="detail-title">Информация о застрахованном</h2>
        </div>
    </div>
</div>

<div class="row" style="padding-bottom: 20px;">
    <div class="col-md-1">
        <input type="checkbox" id="another_phone" ng-model="value.another_phone" value="1">
    </div>
    <div class="col-md-11">
        <label for="another_phone">Телефон указан неверно или принадлежит другому ЗЛ</label>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        
    </div>
</div>

<div class="row" style="padding-bottom: 20px;">
    <div class="col-md-12 text-right">
        <button class="btn btn-sm btn-tfoms-red" type="button"> <i class="fa fa-" aria-hidden="true"></i> Нет ответа</button>
        <button class="btn btn-sm btn-default" type="button" ng-click="savePeople(value)"> <i class="fa fa-" aria-hidden="true"></i> Сохранить</button>
    </div>
</div>

