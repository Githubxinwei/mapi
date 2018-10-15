angular.module('App').controller('settingsController',function ($scope,$http,Upload) {
    $scope.init = function () {
        $scope.getTablePath = '/site/get-table';
        $scope.getTableHttp();
    };
    $scope.getTableHttp = function () {
        $http.get($scope.getTablePath).then(function (result) {
            $scope.tableItems = result.data.data;
        });
    };
    $scope.init();
    $scope.delTable = function (table) {
        if(table != undefined){
            $scope.delPath = '/site/del-table-data?table='+table+'&type=yes';
        }else{
            $scope.delPath = '/site/del-table-data';
        }
        $scope.delHttp();
    };
    $scope.delHttp = function () {
        $http.get($scope.delPath).then(function (result) {
              if(result.data.status == 'success'){
                  Message.success(result.data.data);
              }else{
                  Message.error(result.data.data);
              }
        });
    };
    $scope.addMenu = function () {
        $http.get('/site/set-module').then(function (result) {
            Message.success('执行成功');
        });
    };
    $scope.uploadCover = function (file, text,name,attr) {
        $.loading.show();
        if (file) {
            $scope.upload(file, text,name,attr);
        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };

    $scope.upload = function (file,text,name,attr) {
        Upload.upload({
            url    : '/excel/upload',
            method :'POST',
            data   : {UploadXslForm: {xslFile: file},'text':text,'name':name,'attr':attr, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $.loading.hide();
            } else {
                Message.warning(result.data.data);
                $.loading.hide();
            }
        });
    };



    $scope.privateUploadCover = function (file, text) {
        if (file) {
            $scope.privateUpload(file, text);
            $.loading.show();
        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
            $.loading.hide();
        }
    };

    $scope.privateUpload = function (file) {
        Upload.upload({
            url    : '/excel/excel-charge',
            method :'POST',
            data   : {UploadXslForm: {xslFile: file}, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $.loading.hide();
            } else {
                Message.warning(result.data.data);
                $.loading.hide();
            }
        });
    };

    $scope.uploadCovers = function (file, text,name,attr) {
        $.loading.show();
        if (file) {
            $scope.uploads(file, text,name,attr);
        } else {
            if (file != null)Message.warning('格式不正确');
            $scope.uploading = false;
        }
    };

    $scope.uploads = function (file,text,name,attr) {
        Upload.upload({
            url    : '/excel/uploads',
            method :'POST',
            data   : {UploadXslForm: {xslFile: file},'text':text,'name':name,'attr':attr, _csrf_backend: $('#_csrf').val()}
        }).then(function (result) {
            if (result.data.status == 'success') {
                Message.success(result.data.data);
                $.loading.hide();
            } else {
                Message.warning(result.data.data);
                $.loading.hide();
            }
        });
    };

    $scope.exchangeNumber = function () {
        $http.get('/site/set-exchange-number?numberOne='+$scope.numberOne+'&numberTwo='+$scope.numberTwo).then(function (result) {
            Message.success(result.data.data);
        });
    }

}).controller('dbController',function ($scope,$http) {
    $.loading.show();
    $scope.init = function () {
        $scope.table        = 'cloud_user';
        $scope.pageInitUrl = '/site/table-data?name='+$scope.table;
        $scope.getModel();
        $scope.tableInitDetail();
    };
    $scope.getModel = function () {
        $http.get('/site/list-model?name='+$scope.table).then(function (result) {
            $scope.table      = result.data.data.table;
            $scope.tableLists = result.data.table;
        });
    };

    $scope.tableInitDetail = function () {
        $http.get($scope.pageInitUrl).then(function (result) {
            $scope.items      = result.data.data;
            $scope.pages      = result.data.pages;
            $.loading.hide();
        });
    };

    $scope.tableList = function (name) {
        $.loading.show();
        $scope.table        = name;
        $scope.pageInitUrl  = '/site/table-data?name='+$scope.table;
        $scope.getModel();
        $scope.tableInitDetail();
    };

    $scope.init();
    $scope.replacementPages = function (urlPages) {
        $.loading.show();
        $scope.pageInitUrl = urlPages;
        $scope.tableInitDetail();
    };
});
