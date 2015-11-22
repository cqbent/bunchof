
var app = angular.module('dynamic-grid', []);

app.controller('DynamicGridController', ["$scope","$attrs", function($scope, $attrs){
    $scope.options = [
        {id:0, label:'Custom'},
        {id:1, label:'970x250',width:970, height:250},
        {id:2, label:'970x90',width:970, height:90},
        {id:3, label:'728x90',width:728, height:90},
        {id:4, label:'468x60',width:468, height:60},
        {id:5, label:'336x280',width:336, height:280},
        {id:6, label:'320x100',width:320, height:100},
        {id:7, label:'320x50',width:320, height:50},
        {id:8, label:'300x1050',width:300, height:1050},
        {id:9, label:'300x600',width:300, height:600},
        {id:10, label:'300x250',width:300, height:250},
        {id:11, label:'250x250',width:250, height:250},
        {id:12, label:'234x60',width:234, height:60},
        {id:13, label:'200x200',width:200, height:200},
        {id:14, label:'180x150',width:180, height:150},
        {id:15, label:'160x600',width:160, height:600},
        {id:16, label:'125x125',width:125, height:125},
        {id:17, label:'120x600',width:120, height:600},
        {id:18, label:'120x240',width:120, height:240},
    ];

	$scope.elements = [];
	$scope.editing = {};
	$scope.newElement = {auto:$scope.options[0]};
	$scope.name = $attrs['dynamicGrid'];
    $scope.textareacontent = $attrs['gridValue'];
    $scope.minValue = 0;
    $scope.maxValue = 999999999;
	$scope.gridId = $attrs["gridId"];
	
    var sortArray = function(col){
        return _.sortBy(col, function(el){ return el.maxWidth*-1});
    };

    var elementsToString = function(col){
        var trans = [];
        _.forEach(col, function(val, index){
            trans.push({'w':val.maxWidth,'sw':val.width,'sh':val.height});
        });
        return trans;
    };

	$scope.acceptAddRow = function(){
        if ($scope.isValidForm()) {
            $scope.newElement.auto = $scope.newElement.auto.id;
            $scope.elements.push(angular.copy($scope.newElement));
            $scope.cancelAddRow();
            $scope.elements = sortArray($scope.elements);
            $scope.textareacontent = JSON.stringify(elementsToString($scope.elements));
        }
	};

	$scope.cancelAddRow = function(){
        $scope.newElement = {auto:$scope.options[0]};
	};
	
	$scope.showEdit = function(index){
        $scope.elements[index].editing = true;
		$scope.editing[index] = angular.copy($scope.elements[index]);
        $scope.editing[index].auto = $scope.options[$scope.elements[index].auto];
	};
	
	$scope.acceptEdit = function(index){
        if ($scope.isValidForm(index)) {
            $scope.editing[index].auto = $scope.editing[index].auto.id;
            $scope.elements[index] = $scope.editing[index];
            $scope.cancelEdit(index);
            $scope.elements = sortArray($scope.elements);
            $scope.textareacontent = JSON.stringify(elementsToString($scope.elements));
        }
	};

    $scope.cancelEdit = function(index){
        $scope.elements[index].editing = false;
        $scope.editing[index] = {auto:$scope.options[0]};
    }

	$scope.remove = function(index){
		$scope.elements.splice(index,1);
        $scope.textareacontent = JSON.stringify(elementsToString($scope.elements));
	};
	
	$scope.autoValue = function(editMode, index){
        var element;
		if (editMode){
            element = $scope.editing[index];
		} else {
			element = $scope.newElement;
		}
        if (element.auto && element.auto.width) {
            element.width = element.auto.width;
            element.height = element.auto.height;
        } else {
            element.auto = $scope.options[0];
        }
	};

    $scope.loadData = function(){
      try {
          var elements = JSON.parse($scope.textareacontent);
          $scope.elements = [];
          angular.forEach(elements, function(value, index){
              value = {'maxWidth':value.w, 'width':value.sw, 'height':value.sh};
              if (isValidElement(value)) {
                  var autoIndex = _.findIndex($scope.options, {width: value.width, height: value.height});
                  value.auto = autoIndex == -1 ? 0 : autoIndex;
                  $scope.elements.push(value);
              }
          });
          $scope.elements = sortArray($scope.elements);
      }  catch (e){}
    };

    $scope.isValidForm = function(index){
        var element;
        if (!angular.isUndefined(index)){
            element = $scope.editing[index];
        } else {
            element = $scope.newElement;
        }
        return isValidElement(element);
    };

    var isValidElement = function(element){
        return ((_.isNumber(element.maxWidth) && element.maxWidth<=$scope.maxValue && element.maxWidth>=$scope.minValue)
            && (_.isNumber(element.width) && element.width<=$scope.maxValue && element.width>=$scope.minValue)
            && (_.isNumber(element.height) && element.height<=$scope.maxValue && element.height>=$scope.minValue));
    }
	
	$scope.loadData();
}]);

app.directive('dynamicGrid', function(){
	return {
		restrict: 'EA',
		scope:true,
		//templateUrl: '../templates/grid.html',
		controller: 'DynamicGridController',
		template: '<div><table class="table table-responsive"><thead><tr><th>Screen width</th><th>Size</th><th>Width</th><th>Height</th><th></th></tr></thead><tbody class="table-striped"><tr data-ng-repeat="element in elements"><td><span data-ng-if="!element.editing">{{element.maxWidth}}</span><input type="number" data-ng-model="editing[$index].maxWidth" data-ng-if="element.editing" required="required" class="form-control" max="{{maxValue}}" min="{{minValue}}"/></td><td><span data-ng-if="!element.editing">{{options[element.auto].label}}</span><span  data-ng-if="element.editing"><select data-ng-model="editing[$index].auto" data-ng-options="opt as opt.label for opt in options" data-ng-change="autoValue(true,$index)" required="required" class="form-control"></select></span></td><td><span data-ng-if="!element.editing">{{element.width}}</span>					<input type="number" data-ng-model="editing[$index].width" required="required" data-ng-if="element.editing" ng-disabled="editing[$index].auto.width" ng-readonly="editing[$index].auto.width" class="form-control" max="{{maxValue}}" min="{{minValue}}"/>				</td>				<td>					<span data-ng-if="!element.editing">{{element.height}}</span>					<input type="number" data-ng-model="editing[$index].height" required="required" data-ng-if="element.editing" ng-disabled="editing[$index].auto.width" ng-readonly="editing[$index].auto.width" class="form-control" max="{{maxValue}}" min="{{minValue}}"/>				</td>				<td data-ng-if="element.editing">					<a data-ng-click="acceptEdit($index)"><i class="glyphicon glyphicon-ok"></i></a>					<a data-ng-click="cancelEdit($index)"><i class="glyphicon glyphicon-remove"></i></a>				</td>				<td data-ng-if="!element.editing">					<a data-ng-click="showEdit($index)"><i class="glyphicon glyphicon-pencil"></i></a>					<a data-ng-click="remove($index)"><i class="glyphicon glyphicon-trash"></i></a>				</td>			</tr>			<tr>				<td><input type="number" data-ng-model="newElement.maxWidth" required="required" class="form-control" max="{{maxValue}}" min="{{minValue}}"/></td>                <td><select data-ng-model="newElement.auto" data-ng-options="opt as opt.label for opt in options" data-ng-change="autoValue(false)" required="required" class="form-control"></select></td>				<td><input type="number" data-ng-model="newElement.width" ng-disabled="newElement.auto.width" ng-readonly="newElement.auto.width" required="required" class="form-control" max="{{maxValue}}" min="{{minValue}}"/></td>				<td><input type="number" data-ng-model="newElement.height" ng-disabled="newElement.auto.width" ng-readonly="newElement.auto.width" required="required" class="form-control" max="{{maxValue}}" min="{{minValue}}"/></td>				<td>					<a data-ng-click="acceptAddRow()"><i class="glyphicon glyphicon-ok"></i></a>					<a data-ng-click="cancelAddRow()"><i class="glyphicon glyphicon-remove"></i></a>				</td>			</tr>		</tbody>	</table>    <div class="form-group">	    <textarea class="form-control" name="{{name}}" id="{{gridId}}" data-ng-model="textareacontent" style="display:none">{{textareacontent}}</textarea>    </div></div>'
	}
});
