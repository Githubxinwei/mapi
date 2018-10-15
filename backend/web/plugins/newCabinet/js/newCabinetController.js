/**
 *后台柜子管理
 * @author 苏雨
 * @create 2017/6/2
 */
var app = angular.module('App').controller('newCabinetCtrl', function($scope, $http, $location, $rootScope, $interval) {
	$scope.init1 = function () {
		$scope.num = 0; //柜子
		$scope.addCabinetMonthHtml = '';
		$scope.btnAddMuchMonth();
	};
	$scope.cabinetDetailOpen = true;
	/**
	 *将时间戳转为日期
	 * @create 2017/5/29
	 */
	$scope.getMyDate = function(str) {
		str = parseInt(str);
		if(str != "" || str != null) {
			var oDate = new Date(str);
			var oYear = oDate.getFullYear();
			var oMonth = oDate.getMonth() + 1;
			oMonth = oMonth >= 10 ? oMonth : '0' + oMonth;
			var oDay = oDate.getDate();
			oDay = oDay >= 10 ? oDay : '0' + oDay;
			var theDate = oYear + "-" + oMonth + "-" + oDay;
		} else {
			theDate = "";
		}
		return theDate
	};
    $scope.backPre = function () {
        location.href ="/new-cabinet/index?c=88class='templetA'";
    }
    //新增1229
    $scope.init2 =function () {
        $scope.num = 0;
        $scope.addMoresMothHtml = '';
        $scope.btnAddMoreMonth();
    }
    $scope.btnAddMoreMonth = function() {
        $scope.htmlAttr = 'addCabinetMonth';
        $scope.num  = $scope.num + 1;
        $http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
            $scope.addMoresMothHtml = result.data.html;
        });
    }
	//新增2018/1/6
	$scope.init3 =function () {
		$scope.num = 0;
		$scope.modifyPluginsHtml = '';
		$scope.addPlugins();
	}
	$scope.addPlugins = function() {
		$scope.htmlAttr = 'modifyPlugins';
		$scope.num  = $scope.num + 1;
		$http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
			$scope.modifyPluginsHtml = result.data.html;
		});
	}
    //更衣柜列表数据
    /**
     * author: 程丽明
     * create: 2017-06-06
     * 函数描述: 获取所有的更衣柜列表
     * */
    $scope.getWardrobeDate = function(){
        $http.get('/cabinet/filter-data').then(function(result){
        })
    }
    $scope.getWardrobeDate();
	$scope.backPre = function() {
		location.href = "/new-cabinet/index?c=88class='templetA'";
	}

	//更衣柜列表数据
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 获取所有的更衣柜列表
	 * */
	$scope.getWardrobeDate = function() {
		$http.get('/cabinet/filter-data').then(function(result) {})
	}
	$scope.getWardrobeDate();

	//点击添加区域按钮
	/**
	 * author: 程丽明
	 * create: 2017-08-16
	 * 函数描述: id为addArea的模态框打开，并初始化区域名字的输入框
	 * */
	$scope.addArea = function() {
		$('#addArea').modal('show');
		$scope.areaName = '';
		$scope.addAreaButtonFlag = false;
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 获取大上海所有的场馆并调用获取所有柜子的函数
	 * */
	//获取大上海所有的场馆
	$scope.allVenueLists = function() {
		$http.get('/cabinet/venue-cabinet').then(function(result) {
			$scope.venues = result.data;
			$scope.venueList = '';
			$scope.allCabinetTypeData();
		});

	};
	$scope.allVenueLists();
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 获取所有的柜子 传入一个场馆列表，
	 * 即上边获取场馆函数里，从数据库种取出的场馆列表字段
	 * */
	//获取所有的柜子
	$scope.allCabinetTypeData = function() {
		$.loading.show();
		$http.get('/cabinet/get-cabinet-list?venueId=' + $scope.venueList).then(function(result) {
			$scope.allCabinet = result.data;
			$.loading.hide();
		})
	}

	//根据不同的场馆返回场馆的柜子
	$scope.selectVenue = function() {
		if($scope.venueList != '') {
			$scope.allCabinetTypeData();
		}
	}
	//获取所有的场馆
	$scope.getAllVenue = function () {
		$http.get('/site/get-auth-venue').then(function (response) {
			$scope.venueLists = response.data;
			if(!response.data) {
			    Message.error('系统错误，请刷新重试或联系工作人员！')
            }
        })
    };
    $scope.getAllVenue();
    //选择场馆
    $scope.venueSelectChange = function (venueId) {
        $scope.venueList = venueId;
        $scope.allCabinetTypeData();
    };
	/**
	 * author: 张亚鑫
	 * create: 2017-11-15
	 * 函数描述: 删除柜子功能，GET方法向后台传一个柜子id，后台进行删除并返回状态，并且不能跳转分页，要待在用户原来的分页上
	 * param:  要删除的柜子的id
	 * */
	//删除柜子
	$scope.CabinetDelete = function(id) {
		$http.get('/cabinet/cabinet-delete?id=' + id).then(function(result) {
			if(result.data.status == 'success') {
				$scope.replacementPages('/cabinet/home-data?typeId=' + $scope.cabinetypeId + '&cabinetNum=&cabinetModel=&cabinetType=&customerName=&cabinetEndRent=&sortType=&sortName=&page=' + $scope.cabinetDetailPage + '&per-page=8');
				Message.success('删除柜子成功！')
			} else {
				Message.warning('删除失败，请重试！')
			}
			$('#noBoundCabinet').modal('hide');
		})
	};
	/**
	 * author: 张亚鑫
	 * create: 2017-11-15
	 * 函数描述: 删除区域的功能，GET方法向后台传一个区域的id，后台进行删除并返回状态，删除后调用获取区域列表柜子列表的函数刷新列表。
	 * param: id:要删除的区域的id;
	 *        num:已经租出去的柜子的数量;(根据需求，如果存在已经租出去的柜子的区域，是不能被删除的)
	 *        event:event对象,为了取消冒泡
	 * */
	//删除区域
	$scope.deleteContentBoxButton = function(id, num, event) {
		var evt = event ? event : window.event;
		evt.stopPropagation();
		if(num == 0) {
			$http.get('/cabinet/cabinet-type-delete?id=' + id).then(function(result) {
				if(result.data.status == 'success') {
					Message.success('删除成功！')
					$scope.allCabinetTypeData();
				} else {
					Message.warning(result.data.data);
				}
			})
		} else {
			Message.warning('存在已租出去的柜子，无法删除区域！')
		}
	}
	/**点击到期时间模态框获取到期会员数据**/
	$scope.getMemberCabinetData = function() {
		$('#expirationRemindingModal').modal('show');
		$scope.pageInitUrls = '/cabinet/member-come-due';
		$scope.getCabinetSearchData();
		$scope.cabinetTypeDatas();
	};
	$scope.getCabinetSearchData = function() {
		$http.get($scope.pageInitUrls).then(function(response) {
			if(response.data.data != "" && response.data.data != undefined && response.data.data.length != undefined) {
				$scope.datas = response.data.data;
				$scope.cabinet = response.data.cabinetPages;
				$scope.hua = response.data.huang;
				$scope.cabinetNoDataShow = false;
			} else {
				$scope.datas = response.data.data;
				$scope.cabinet = response.data.cabinetPages;
				$scope.cabinetNoDataShow = true;
			}
		});
	}
	$scope.initPaths = function() {
		$scope.searchParam = $scope.searchCabinetData();
		$scope.pageInitUrls = '/cabinet/member-come-due?' + $.param($scope.searchParam);
	};
	$scope.replaceCabinetPages = function(urlPages) {
		$scope.pageInitUrls = urlPages;
		$scope.getCabinetSearchData();

	};
	/**获取选中的柜子id**/
	$scope.getCabinet = function() {
		$scope.cabinetTypeId = $scope.cabinetData;
	}
	/**获取选中的时间**/
	$scope.getDay = function() {
		$scope.day = $scope.cabinetDay;
	}
	/**处理搜索数据***/
	$scope.searchCabinetData = function() {
		return {
			cabinetTypeId: $scope.cabinetTypeId != undefined ? $scope.cabinetTypeId : null, //区域id
			day: $scope.day != undefined ? $scope.day : null //当天当日当月
		}
	};

	/**跳转页***/
	$scope.skipPage = function(value) {
		if(value != undefined) {
			$scope.searchParam = $scope.searchCabinetData();
			$scope.pageInitUrls = '/cabinet/member-come-due?' + $.param($scope.searchParam) + '&page=' + value;
			$scope.getCabinetSearchData();
		}
	};

	/**搜索方法***/
	$scope.searchCabinetS = function() {
		$scope.initPaths();
		$scope.getCabinetSearchData();
	};
	/**获取区域下拉框***/
	$scope.cabinetTypeDatas = function() {
		$http({
			method: "get",
			url: "/cabinet/area-select"
		}).then(function(result) {
			$scope.cabinetType = result.data.cabinet;
		}, function(error) {
			Message.error("请联系工作人员");
		});
	}

	/**点击批量发送会员短信**/
	$scope.sendCabinet = function() {
		$scope.sendCabinetData = function() {
			return {
				cabinetTypeId: $scope.cabinetTypeId != undefined ? $scope.cabinetTypeId : null, //区域id
				day: $scope.day != undefined ? $scope.day : null //当天当日当月
			}
		}
		$http({
			url: '/cabinet/send-cabinet-data',
			method: 'POST',
			data: $.param($scope.sendCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(res) {
			if(res.data.status == 'success') {
				Message.success('发送成功');
				$scope.getCabinetSearchData();
			} else {
				Message.warning(res.data.data)
			}
		})

	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 添加新的区域，POST向数据库传输需要添加的区域的名称，完成后关闭模态框并调用获取区域列表的函数。
	 * */
	//添加新的区域
	$scope.addNewArea = function() {
		if(!$scope.addAreaButtonFlag) {
			var buyCardName = /^([a-zA-Z0-9\u4e00-\u9fa5 ]+)$/;
			if($scope.areaName == '' || $scope.areaName == null || $scope.areaName == undefined || !(buyCardName.test($scope.areaName))) {
				Message.warning("请正确的区域名称!");
				return;
			}
			$scope.AddNewAreaData = function() {
				return {
					cabinetTypeName: $scope.areaName != undefined && $scope.areaName != '' ? $scope.areaName : null,
					_csrf_backend: $('#_csrf').val()
				}
			}
			$scope.addAreaButtonFlag = true;
			$http({
				url: "/cabinet/add-cabinet-type",
				method: 'POST',
				data: $.param($scope.AddNewAreaData()),
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				}
			}).then(function(result) {
				if(result.data.status == 'success') {
					Message.success(result.data.data);
					//执行关闭模态框
					$scope.allCabinetTypeData();
					$('#addArea').modal('hide');
				} else {
					$scope.addAreaButtonFlag = false;
				}
			});
		}
	}

	/****分页***/
	$scope.replacementPages = function(urlPages) {
		$scope.pageInitUrl = urlPages;
		$scope.getDate($scope.statusList);
	};
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 从区域列表点击区域，进入柜子详细列表
	 * param:  typeId:区域的id
	 *         typeName:区域的名字
	 * */
	// 柜子种类列表进入柜子详细列表的点击事件
	$scope.cabinetBox = function(typeId, typeName) {
		$scope.cabinetypeId = typeId;
		$scope.cabinetTypeName = typeName;
		$scope.searchClass();
		$('.listBox').show();
		$('.contentBox').hide();
		$('.listBoxType').hide();
		$scope.cabinetDetailOpen = false;
	}

	$scope.initPath = function() {
		$scope.searchParams = $scope.search();
		$scope.pageInitUrl = '/cabinet/home-data?' + $.param($scope.searchParams);
	};

	/**点击区域，出现柜子列表***/
	$scope.searchClass = function(status) {
		if(status == undefined){
			status = 8;
		}
		$scope.initPath();
		if($scope.nowPages) {
            $scope.pageInitUrl = '/cabinet/home-data?' + $.param($scope.searchParams) + '&page=' + $scope.nowPages + '&per-page=8';
        }
		$scope.getDate(status);
	};
	/**搜索方法（搜索栏）***/
	$scope.searchCabinet = function() {
		$scope.initPath();
		$scope.getDate();
	};
	/******Enter键搜索*******/
	$scope.enterSearchs = function(e) {
		var keyCode = window.event ? e.keyCode : e.which;
		if(keyCode == 13) {
			$scope.searchCabinet();
		}
	};
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 分页数据信息获取并返回
	 * */
	//  分页数据信息
	$scope.getDate = function(status) {
		$.loading.show();
		$http.get($scope.pageInitUrl+'&pageSize='+status).then(function(result) {
			$scope.cabinetDetailPage = result.data.now.toString();
			if(result.data.data.length != 0) {
				$scope.allCabinetLists = result.data.data;
				$scope.pages = result.data.pages;
				$scope.nowPages = result.data.now;
				$scope.dataInfo = false;
				$scope.searchData = false;
			} else {
				$scope.allCabinetLists = result.data.data;
				$scope.pages = result.data.pages;
				if($scope.searchParams != null) {
					$scope.searchData = true;
					$scope.dataInfo = false;
				} else {
					$scope.dataInfo = true;
				}
			}
			$scope.cabinetCurrentTime = new Date().getTime();
			$scope.statusList = status;
			if(status == 60){
				$scope.isMatrix = true;
				$scope.isList   = false;
				$('#show-list-id').removeClass('active');
				$('#show-matrix-id').addClass('active');
			}else{
				$scope.isList = true;
				$scope.isMatrix = false;
				$('#show-list-id').addClass('active');
				$('#show-matrix-id').removeClass('active');
			}
			$.loading.hide();
		})
	};
	/**主界面(字段)搜索**/
	/**
	 * author: 程丽明
	 * create: 2017-06-06
	 * 函数描述: 点击主页列表表格表头，进行排序
	 * param:  attr:表头的数据信息
	 *         sort:固定的参数,用来规定是正序还是倒序
	 * */
	$scope.changeSort = function(attr, sort) {
		$scope.sortType = attr;
		$scope.switchSort(sort);
		$scope.searchClass();
	};

	$scope.switchSort = function(sort) {
		if(!sort) {
			sort = 'DES';
		} else if(sort == 'DES') {
			sort = 'ASC';
		} else {
			sort = 'DES'
		}
		$scope.sort = sort;
	};
	/**页面搜索（搜索栏）***/
	$scope.search = function() {
		return {
			typeId: $scope.cabinetypeId != undefined ? $scope.cabinetypeId : null,
			cabinetNum: $scope.cabinetNum != undefined ? $scope.cabinetNum : null,
			cabinetModel: $scope.cabinetModel != undefined ? $scope.cabinetModel : null,
			cabinetType: $scope.cabinetType != undefined ? $scope.cabinetType : null,
			customerName: $scope.customerName != undefined ? $scope.customerName : null,
			cabinetEndRent: $scope.cabinetEndRent != undefined ? $scope.cabinetEndRent : null,
			sortType: $scope.sortType != undefined ? $scope.sortType : null,
			sortName: $scope.sort != undefined ? $scope.sort : null,
			keyword: $scope.keyword != undefined ? $scope.keyword : null
		}
	};

	$scope.cabinetDeposit = '';
	/**
	 * author: 杨慧磊
	 * create: 2017-12-20
	 * 描述: 新增无限增加多月设置
	 * 无限增加多月设置
	 * */
	$scope.btnAddMuchMonth = function() {
		$scope.htmlAttr = 'addCabinetMonth';
		$scope.num  = $scope.num + 1;
		$http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
			$scope.addCabinetMonthHtml = result.data.html;
		});
	}
	/**
	 * author: 杨慧磊
	 * create: 2017-12-22
	 * 新增: $('div.myDiv').remove()
	 * 描述: 点击新增普通衣柜按钮初始化input框个数
	 *  新增更衣柜[初始化设置]
	 **/
	$scope.addCabinetModal = function() {
		$scope.giveMonthNum = '';
		$scope.cabinetSize = '';
		$scope.cabinetType = '';
		$scope.addCabinetNum = '';
		$scope.halfMonthMoney = null;
		$scope.cabinetPrefix = '';
		$scope.cabinetNumStart = '';
		$scope.cabinetDeposit = null; //押金
		$scope.cabinetMoney = null; //初始化多月设置金额
		$scope.muchMonth = ''; //初始化多月设置月数
		$scope.giveMonth = ''; //初始化多月设置赠送月数
		$scope.dis = ''; //初始化多月设置折扣
		$scope.addCabinetButtonFlag = false; //加载动画不显示
		$('div.myDiv').remove();  //初始化新增普通衣柜
	}
	/**
	 * author: 杨慧磊
	 * create: 2017-12-22
	 * 新增: 多月设置流程控制
	 * 描述: $.each()遍历获取
	 * 新增: 多月设置流程控制
	 * 描述: 通过遍历判断用户输入的月数的值不重复
	 *  函数: 判断多月设置月数是否重复
	 **/

	function isRepeat(jsonMonth){
	    var filter = {};
		for(var i in jsonMonth) {
			if (filter[jsonMonth[i]]) {
                  return true;
			}
			filter[jsonMonth[i]] = true;
		}
		return false;
	}
	//新增柜子
	$scope.addComplete = function() {
	    console.log('$scope.cabinetDeposit',$scope.cabinetDeposit);
		//流程控制
		if($scope.cabinetSize == null || $scope.cabinetSize == undefined || $scope.cabinetSize == "") {
			Message.warning("请选择柜子型号!");
			return;
		}
		if($scope.cabinetPrefix == undefined || $scope.cabinetPrefix == '' || $scope.cabinetPrefix == null || $scope.cabinetNumStart == undefined || $scope.cabinetNumStart == '' || $scope.cabinetNumStart == null) {
			Message.warning("请输入完整的柜号!");
			return;
		}
		if($scope.cabinetType == null || $scope.cabinetType == undefined || $scope.cabinetType == "") {
			Message.warning("请选择柜子类别!");
			return;
		}
		if($scope.addCabinetNum == null || $scope.addCabinetNum == undefined || $scope.addCabinetNum == "") {
			Message.warning("请输入柜子数量!");
			return;
		}
		if($scope.cabinetType == 2) {
			if($scope.halfMonthMoney == null || $scope.halfMonthMoney == undefined || $scope.halfMonthMoney == "") {
				Message.warning("请输入单月金额!");
				return;
			}
		}
		if($scope.cabinetType == 2) {
			if($scope.cabinetDeposit == null || $scope.cabinetDeposit == undefined || $scope.cabinetDeposit == '') {
				Message.warning("请输入押金金额!");
				return;
			}
		}
        // 获取新增多月设置的值
		if($scope.cabinetType == 2) {
			var $muchMoneyBox = $('#addMuchPlugins').children('div.clearfix');
			var jsonMonth = [];
			var jsonMoney = {};
			var jsonGiveMonth = {};
			var jsonDis = {};
			var notPass = false;
			var notFormat = false;
			$muchMoneyBox.each(function (index, item) {
				var cabinet_month = $(this).find('input[name="cabinet_month"]').val();
				var cabinet_money = $(this).find('input[name="cabinet_money"]').val();
				var give_month = $(this).find('input[name="give_month"]').val();
				var cabinet_dis = $(this).find('input[name="cabinet_dis"]').val();
				//判断折扣是否格式化
				if(cabinet_dis != '' && cabinet_dis != undefined && cabinet_dis != null){
					if(/^0\.[123456789]+$/.test(cabinet_dis) || /^0\.[123456789]+(\/0\.[123456789]+)+$/.test(cabinet_dis)){
						notFormat = false;
					}else{
						notFormat = true;
					}
				}
				if (cabinet_month != '' && cabinet_month != null && cabinet_money != '' && cabinet_money != null) {
					jsonMonth.push(cabinet_month);
					jsonMoney[cabinet_month] = cabinet_money;
					jsonGiveMonth[cabinet_month] = give_month;
					jsonDis[cabinet_month] = cabinet_dis;
				} else {
					//如果有一条不符合就不能通过
					notPass = true;
					return;
				}
			});
			//如果多月设置为空
			// if (notPass == true) {
			// 	Message.warning('多月设置必填项不能为空');
			// 	return;
			// }
			//如果多月设置月数重复
			if (isRepeat(jsonMonth)) {
				Message.warning('多月设置月份不能"重复"');
				return;
			}
			//如果折扣没有格式化
			if(notFormat == true){
				Message.warning('折扣不符合格式');
				return;
			}
		}
		//新增柜子提交的数据
		$scope.addCabinetData = function() {
			return {
				_csrf_backend  : $('#_csrf').val(),
				cabinetPrefix  : $scope.cabinetPrefix != undefined && $scope.cabinetPrefix != '' ? $scope.cabinetPrefix : null, //编号
				cabinetNumStart: $scope.cabinetNumStart != undefined && $scope.cabinetNumStart != '' ? $scope.cabinetNumStart : null, //起始柜号
				cabinetModel   : $scope.cabinetSize != undefined && $scope.cabinetSize != "" ? $scope.cabinetSize : null, //柜子型号
				cabinetType    : $scope.cabinetType != undefined && $scope.cabinetType != "" ? $scope.cabinetType : null, //柜子类别
				cabinetNum     : $scope.addCabinetNum != undefined && $scope.addCabinetNum != "" ? $scope.addCabinetNum : null, //柜子数量
				monthRentPrice : $scope.halfMonthMoney != undefined && $scope.halfMonthMoney != "" ? $scope.halfMonthMoney : null, //单月金额
				cabinetTypeId  : $scope.cabinetypeId != undefined && $scope.cabinetypeId != '' ? $scope.cabinetypeId : null, //柜子类型ID
				deposit        : $scope.cabinetDeposit != undefined && $scope.cabinetDeposit != '' ? $scope.cabinetDeposit : null, //柜子押金
				giveMonth      : jsonGiveMonth != undefined && jsonGiveMonth != '' ? jsonGiveMonth : null,  //多月设置赠送月数
			    cabinetMonth   : jsonMonth != undefined && jsonMonth != '' ? jsonMonth : null,    //多月设置月份
				cabinetMoney   : jsonMoney != undefined && jsonMoney != '' ? jsonMoney : null,  //多月设置金额
				cabinetDis     : jsonDis   != undefined && jsonDis   != '' ? jsonDis   : null,  // 多月设置折扣
			}
		};
		//显示提交按钮动画
		$scope.addCabinetButtonFlag = true;
		//发送客户端数据
		$http({
			url: "/cabinet/add-venue-cabinet",
			method: 'POST',
			data: $.param($scope.addCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			if(response.data.status == 'success') {
				Message.success(response.data.data);
				//执行关闭模态框
				$scope.searchClass();
				$scope.allCabinetTypeData();
				$('#addCabinet').modal('hide');
			} else {
				$scope.addCabinetButtonFlag = false;
			}
		});
	}

	//点击显示更衣柜详情
	$scope.tdClick = function(status, object,type) {
		var statusTyle = parseInt(status);
		$scope.cabinetCashPledge = parseInt(object.deposit);
		$scope.cabinetInfoItem = object;
		$('#isBind').attr('data-page',type);
		if(statusTyle == 1) {
			$('#noBoundCabinet').modal('show');
			//新增多月设置金额详情
			var muchMonthMoneyDetail = object.cabinet_money;
			var container = [];
			var tmp;
			var tmpArr = JSON.parse(muchMonthMoneyDetail);
		    $.each(tmpArr,function(key, item){
				tmp = '月数: '+key+' 金额: '+item+'元';
				container.push(tmp);
			});
			$scope.muchMonthDetails = container;
			$scope.cabinetDetail = object;
		} else if(statusTyle == 2) {
            $scope.showBox = 1;
            $scope.isShowBox = true;
			$('#boundCabinet').modal('show');
			$scope.cabinetDetail = object;
			//获取会员消费记录
			$scope.cabinetRentUrl = "/cabinet/member-consum-list?memberId="+object.member_id+"&cabinetId="+object.id+"&type=cabinet";
			$scope.memberConsumList($scope.cabinetRentUrl);
		}
	}
	/**
	 * author: 苏雨                杨慧磊
	 * create: 2017-09-30         2018/1/6
	 * 函数描述: 未绑定详情页面中的修改按钮，点击后进行分析判断
	 * */
	//点击未绑定页面中的修改按钮
	$scope.editUnBinding = function(id,type) {
		$scope.editCompleteFlag = false;
		$scope.editCabinetId = id;
		$('#boundCabinet').modal('hide');
		if($scope.cabinetInfoItem.deposit == undefined || $scope.cabinetInfoItem.deposit == '') {
			$scope.editCabinetDeposit = '';
		} else {
			$scope.editCabinetDeposit = parseFloat($scope.cabinetInfoItem.deposit);
		}
		if($scope.cabinetInfoItem.cabinetModel == undefined || $scope.cabinetInfoItem.cabinetModel == '') {
			$scope.editOneMonthPrice = '';
		} else {
			$scope.editOneMonthPrice = $scope.cabinetInfoItem.monthRentPrice;
		}
		if($scope.cabinetInfoItem.yearRentPrice == undefined || $scope.cabinetInfoItem.yearRentPrice == '') {
			$scope.editOneYearPrice = '';
		} else {
			$scope.editOneYearPrice = parseFloat($scope.cabinetInfoItem.yearRentPrice);
		}
		if($scope.cabinetInfoItem.cabinetModel == undefined || $scope.cabinetInfoItem.cabinetModel == '') {
			$scope.editCabinetSize = '';
		} else {
			$scope.editCabinetSize = $scope.cabinetInfoItem.cabinetModel;
		}
		if($scope.cabinetInfoItem.cabinetType == undefined || $scope.cabinetInfoItem.cabinetType == '') {
			$scope.editCabinetType = '';
		} else {
			$scope.editCabinetType = $scope.cabinetInfoItem.cabinetType;
		}
		$http.get('/cabinet/modify-one-cabinet?cabinetId=' + id).success(function(data){
			var cm  = JSON.parse(data.data.cabinet_month);
			var cmy = JSON.parse(data.data.cabinet_money);
			var gm  = JSON.parse(data.data.give_month);
			var md  = JSON.parse(data.data.cabinet_dis);
			if(cm != '' && cm != null && cm != undefined && cmy != '' && cmy != null && cmy != undefined && gm != '' && gm != null && gm != undefined && md != '' && md != null && md != undefined){
				var $max = cm.length;
				var i = -1;
				for(var n = 0; n < $max; n++){
					$scope.htmlAttr = 'modifyPlugins';
					$scope.num  = $scope.num + 1;
					$http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
						var $html = result.data.html;
						var $dom = $($html).get(0);
						i++;
						$($dom).find('input[name="cabinet_month"]').val(parseInt(cm[i]));
						$($dom).find('input[name="cabinet_money"]').val(parseFloat(cmy[cm[i]]));
						$($dom).find('input[name="give_month"]').val(gm[cm[i]]);
						$($dom).find('input[name="cabinet_dis"]').val(md[cm[i]]);
						$('#modify').append($dom);
					})
				}
				$('#boundCabinet').modal('hide');
			}else{
				return true;
			}
		});
		$('#noBoundCabinet').modal('hide');
		$('#isBind').attr('data-type',type);
	};
	/**
	 * @修改模态关闭初始化多月设置数据
	 *
	 **/
	$('#revise').on('hidden.bs.modal', function (e) {
		$('#modify').empty();
	})
	/**
	 * author: 程丽明          杨慧磊
	 * create: 2017-06-09     2018/1/6
	 * 函数描述: 新增柜子，并把表单里填写的数据发送至后台
	 *         cabinetModel:柜子尺寸
	 *         cabinetType:柜子类别
	 *         deposit:柜子押金
	 *         cabinetId:柜子的id
	 *         monthRentPrice:单月金额
	 *         yearRentPrice:年金额
	 * */
	//未绑定用户时显示
	$scope.editComplete = function() {
		/**
		 * @获取多月设置的值
		 **/
		if($scope.editCabinetSize == null || $scope.editCabinetSize == '' || $scope.editCabinetSize == undefined) {
			Message.warning("请选择柜子型号!");
			return;
		}
		if($scope.editCabinetType == null || $scope.editCabinetType == '' || $scope.editCabinetType == undefined) {
			Message.warning("请选择柜子类型!");
			return;
		}
		if($scope.editCabinetType == '2') {
			if($('#editCabinetDeposit555').val() == '') {
				Message.warning("请输入柜子押金!");
				return;
			}
		}
		if($scope.editOneMonthPrice == null || $scope.editOneMonthPrice == '' || $scope.editOneMonthPrice == undefined) {
			Message.warning("请输入单月金额!");
			return;
		}
		var $muchMoneyBox = $('#modify').children('div.leiGe');
		var jsonMonth = [];
		var jsonMoney = {};
		var jsonGiveMonth = {};
		var jsonDis = {};
		var notPass = false;
		var notFormat = false;
		$muchMoneyBox.each(function (index, item) {
			var cabinet_month = $(this).find('input[name="cabinet_month"]').val();
			var cabinet_money = $(this).find('input[name="cabinet_money"]').val();
			var give_month = $(this).find('input[name="give_month"]').val();
			var cabinet_dis = $(this).find('input[name="cabinet_dis"]').val();
			//判断折扣是否格式化
			if(cabinet_dis != '' && cabinet_dis != undefined && cabinet_dis != null){
				if(/^0\.[123456789]+$/.test(cabinet_dis) || /^0\.[123456789]+(\/0\.[123456789]+)+$/.test(cabinet_dis)){
					notFormat = false;
				}else{
					notFormat = true;
				}
			}
			if (cabinet_month != '' && cabinet_month != null && cabinet_money != '' && cabinet_money != null) {
				jsonMonth.push(cabinet_month);
				jsonMoney[cabinet_month] = cabinet_money;
				jsonGiveMonth[cabinet_month] = give_month;
				jsonDis[cabinet_month] = cabinet_dis;
			} else {
				//如果有一条不符合就不能通过
				notPass = true;
				return;
			}
		});
		//如果多月设置为空
		// if (notPass == true) {
		// 	Message.warning('多月设置必填项不能为空');
		// 	return;
		// }
		//如果多月设置月数重复
		if (isRepeat(jsonMonth)) {
			Message.warning('多月设置月份不能"重复"');
			return;
		}
		//如果折扣没有格式化
		if(notFormat == true){
			Message.warning('折扣不符合格式');
			return;
		}
		$scope.editCompleteData = function() {
			return {
				cabinetModel   : $scope.editCabinetSize,
				cabinetType    : $scope.editCabinetType,
				deposit        : $('#editCabinetDeposit555').val(),
				cabinetId      : $scope.editCabinetId,
				monthRentPrice : $scope.editOneMonthPrice,
				yearRentPrice  : $scope.editOneYearPrice,
				giveMonth      : jsonGiveMonth != undefined && jsonGiveMonth != '' ? jsonGiveMonth : null,  //多月设置赠送月数
				cabinetMonth   : jsonMonth != undefined && jsonMonth != '' ? jsonMonth : null,    //多月设置月份
				cabinetMoney   : jsonMoney != undefined && jsonMoney != '' ? jsonMoney : null,  //多月设置金额
				cabinetDis     : jsonDis   != undefined && jsonDis   != '' ? jsonDis   : null,  // 多月设置折扣
				_csrf_backend  : $('#_csrf').val() //csrf防止跨站
			}
		};
		$scope.editCompleteFlag = true;
		var type = $('#isBind').attr('data-type');
		var page = $('#isBind').attr('data-page');
		$http({
			url: "/cabinet/cabinet-update",
			method: 'POST',
			data: $.param($scope.editCompleteData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(result) {
			if(result.data.status == "success") {
				Message.success(result.data.data);
				// $scope.editCabinetInit();
				// $scope.searchClass();
				$('#revise').modal('hide'); //执行关闭模态框
				// $('#boundCabinet').modal('hide');
				if(type == 'isBind'){
					// $('#boundCabinet').modal('show');
				}else{
					// $('#noBoundCabinet').modal('show');
				}
				// console.log('page',page);
				if(page == 'matrix'){
					$scope.searchClass(60);
				}else{
					$scope.searchClass();
				}
                // $scope.getDate($scope.statusList);
			} else {
				Message.warning(result.data.data);
				$scope.editCompleteFlag = false;
			}
		});

	};
	//点击更衣柜详情中的点击绑定用户
	$scope.clickBinding = function(object) {
		$('#noBoundCabinet').modal('hide');
		$scope.bindingMember(object);
	};
	/**
	 * author: 程丽明       杨慧磊(修改)
	 * create: 2017-07-17  2017/12/22
	 * 函数描述: 绑定用户到柜子上，点击后打开模态框
	 * 修改:  声明全局变量
	 * */
	//点击绑定用户柜子的数据
	var currentCabinetData;
	//点击绑定用户柜子实时赠送月数
	var giveCurMonth;
	//点击绑定用户柜子实时折扣
	var rootCurDis;
	//点击绑定用户柜子租赁月数对应的金额
	var muchMonthMoney;
	//绑定用户
	$scope.bindingMember = function(object) {
		currentCabinetData = object;
		$scope.bindingCabinetCompleteFlag = false;
		$scope.cabinetCashPledge = parseInt(object.deposit != null && object.deposit != undefined && object.deposit != '' ? object.deposit : 0);
		$scope.containerId = object.id;
		$scope.containerNumber = object.cabinet_number;
		$scope.cabinetIsType = object.cabinetModel;
		$scope.cabinetOneMonMoney = object.monthRentPrice;
		$scope.bindingMemberPreCabinetDetail = object;
		//重置模态框
		$scope.selectedDis = '1';
		$scope.theAmountPayable = 0;
		$scope.display = 0;
		$('#bindingUser').modal('show');
	};
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 查找会员，搜索会员，避免被冻结的会员绑定更柜
	 * */
	$scope.searchMember = function() {
		$scope.giveMonthBingCabinet = 0;
		$scope.cabinetEnd = '';
		$scope.cabinetDays = '';
		$scope.startRentCabinet = '';
		if($scope.keywords != '') {
			$http.get('/cabinet/search-member?phone=' + $scope.keywords).then(function(result) {
				if(result.data != 'null') {
					$scope.memberDetail = result.data;
					$http.get('/user/member-card?memberId=' + $scope.memberDetail.member_id).then(function(response) {
						$scope.memberCardRentInvalidTime = response.data.invalid_time;
					});
					$scope.keywords = '';
					$('#bindingCabinet').modal('show');
					$('#bindingUser').modal('hide');
					$scope.getTheAmountPayable();
				} else {
					Message.warning("该用户不是会员或者该会员被冻结!");
					return;
				}
			})
		} else {
			Message.warning("输入有误,请重新输入!");
			return;
		}
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 获取租柜的到期日期，以免超出会员卡到期日期
	 * */
	//获取到期日期
	$scope.getEndDate = function(value) {
		$http.get('/cabinet/calculate-date?numberMonth=' + value + '&startRent=' + $scope.startRentCabinet).then(function(result) {
			$scope.cabinetEnd = (result.data).replace(/\"/g, "");
			$scope.endTime = new Date($scope.cabinetEnd).getTime() / 1000;
			if(parseInt($scope.memberCardRentInvalidTime) < $scope.endTime) {
				Message.warning("温馨提示: 您的租柜到期日期在会员卡到期日期之后!");
			}
		})
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-07
	 * 函数描述: 获取柜子的到期时间，如果大于十二个月就过滤成年
	 * */
	//获取柜子到期时间
	$scope.getCabinetEndDate = function() {
		if($scope.cabinetDays != undefined && $scope.cabinetDays != '' && $scope.cabinetDays != null && $scope.startRentCabinet != undefined && $scope.startRentCabinet != '' && $scope.startRentCabinet != null) {
			if(giveCurMonth != 0 || giveCurMonth != undefined || giveCurMonth != null){
				var num = $scope.cabinetDays + parseInt(giveCurMonth);
				$scope.getEndDate(num);
			}else{
				$scope.getEndDate($scope.cabinetDays);
			}
		}else{
			$scope.cabinetEnd = null;
		}
	}
	/**
	 *  createat : 2017/12/23
	 *  author   :  杨慧磊
	 *  params   : cabinetDays        租赁月数
	 *             yearRentPrice      年租价格
	 *             cabinetOneMonMoney 月租价格
	 *             cabinetCashPledge  押金
	 * */
	//根据月数获取租赁柜子具体金额不算押金
	$scope.getTheAmountPayable = function() {
		if($scope.cabinetDays != undefined && $scope.cabinetDays != '' && $scope.cabinetDays != null && $scope.cabinetOneMonMoney != undefined && $scope.cabinetOneMonMoney != '' && $scope.cabinetOneMonMoney != null) {
			if(giveCurMonth != undefined && giveCurMonth != null){
				if(muchMonthMoney != 0){
					return parseFloat(muchMonthMoney);
				}else{
					 return $scope.cabinetDays * $scope.cabinetOneMonMoney;
				}
			}else{
				return $scope.cabinetDays * $scope.cabinetOneMonMoney;
			}
		} else {
			return 0;
		}
	};
	//实时计算
	$scope.rentCabinet = function(start) {
		$scope.startRentCabinet = start;
		$scope.getCabinetEndDate();
	}
	//输入租赁月数实时计算赠送月数和租赁金额
	$scope.rentCabinetDays = function(start) {
		//初始化输入月数
		$scope.cabinetDays = start;
		//初始化折扣
		$scope.selectedDis = '1';
		//根据输入月数获取赠送月数
		if(start == null || start == ''){
			$scope.giveMonthBingCabinet = 0;
			giveCurMonth = 0;
		}else if(currentCabinetData.give_month != null){
			var regexp = new RegExp('"'+start+'":\\s"\\d+"','g');
			var pregRe = currentCabinetData.give_month.match(regexp);
			if(pregRe != null){
				var endRe = pregRe[0];
				$scope.giveMonthBingCabinet = endRe.slice(endRe.indexOf(':')+3).replace('"','');
                giveCurMonth = $scope.giveMonthBingCabinet;
			}else{
				$scope.giveMonthBingCabinet = 0;
				giveCurMonth = 0;
			}
		}else{
			$scope.giveMonthBingCabinet = 0;
			giveCurMonth = 0;
		}
	    //根据输入月数获取折扣
	    //定义数组容器
		var disArr = [];
		if(start == null || start == ''){
			//如果不存在对应的折扣
			$scope.dises = disArr;
			$scope.display = 0;
		}else if(currentCabinetData.cabinet_dis != null){
			var regdis = new RegExp('"'+start+'":\\s"\\d+(\\.\\d+)?(\\/\\d+(\\.\\d+)?)*"','g');
			var regdisRe = currentCabinetData.cabinet_dis.match(regdis);
			if(regdisRe != null){
				var disstr = regdisRe[0].slice(regdisRe[0].indexOf(':')+3).replace('"','');
				if(disstr.indexOf('/') > -1){
					//如果存在'/'
					$scope.dises = disstr.split('/');
					$scope.display = 1;
				}else{
					//如果折扣数量是1
					disArr[0] = disstr;
					$scope.dises = disArr;
					$scope.display = 1;
				}
			}else{
				//如果不存在对应的折扣
				$scope.dises   = disArr;
				$scope.display = 0;
			}
		}else{
			$scope.dises   = disArr;
			$scope.display = 0;
		}
		//实时计算租金
		if(start == null || start == ''){
			muchMonthMoney = 0;
		}else if(currentCabinetData.cabinet_money != null){
			var regMoney = new RegExp('"'+start+'":\\s"\\d+"','g');
			var regMoneyRe = currentCabinetData.cabinet_money.match(regMoney);
			if(regMoneyRe != null){
				var regMonneyEndRe = regMoneyRe[0];
				muchMonthMoney = regMonneyEndRe.slice(regMonneyEndRe.indexOf(':')+3).replace('"','');
			}else{
				muchMonthMoney = 0;
			}
		}else{
			muchMonthMoney = 0;
		}
		//实时计算金额加押金
		if(start == '' || start == null || start == 0){
			$scope.theAmountPayable = 0;
		}else if($scope.selectedDis != null && $scope.selectedDis != undefined && $scope.selectedDis != ''){
			$scope.theAmountPayable = $scope.getTheAmountPayable() * parseFloat($scope.selectedDis) + $scope.cabinetCashPledge;
		}else{
			$scope.theAmountPayable = $scope.getTheAmountPayable() + $scope.cabinetCashPledge;
		}
		//实时计算到期日期
		$scope.getCabinetEndDate();
	}
	//用户选择对应的折扣实时获取租赁金额
	$scope.getCurrentRootMoney = function(curDis){
		if($scope.getTheAmountPayable() == 0){
			$scope.theAmountPayable = 0;
		}else{
			$scope.theAmountPayable = $scope.getTheAmountPayable()*parseFloat(curDis) + $scope.cabinetCashPledge;
		}
	}
	//计算绑定用户应交金额
	$scope.getPayableMoney = function() {
		$scope.rentCabinetDays();
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 租柜绑定，传入id
	 * param:  memberId:会员id
	 *         cabinetRentStart:开始时间
	 *         cabinetRentEnd:到期时间
	 *         price:单日价格
	 *         cabinetId:柜子id
	 *         deposit:定金
	 * */
	//绑定柜子
	$scope.bindingCabinetComplete = function(id) {
        $scope.bindingCabinetDataInit = function() {
			$scope.startRentCabinet = '';
			$scope.theAmountPayable = '';
			$scope.cabinetEnd = '';
			$scope.cabinetDays = '';
		}
		$scope.bindingCabinetData = function() {
			return {
				_csrf_backend: $('#_csrf').val(),
				memberId: $scope.memberDetail.id != undefined && $scope.memberDetail.id != "" ? $scope.memberDetail.id : null, //会员id
				cabinetRentStart: $scope.startRentCabinet != undefined && $scope.startRentCabinet != "" ? $scope.startRentCabinet : null, //时间
				cabinetRentEnd: $scope.cabinetEnd != undefined && $scope.cabinetEnd != "" ? $scope.cabinetEnd : null, //到期时间
				price: $scope.theAmountPayable != undefined && $scope.theAmountPayable != "" ? $scope.theAmountPayable : null, //单日金额
				cabinetId: $scope.containerId != undefined && $scope.containerId != "" ? parseInt($scope.containerId) : null, //柜子ID
				deposit: $scope.cabinetCashPledge,
                give_month:$scope.giveMonthBingCabinet//赠送的月数
			}
		}
		if($scope.startRentCabinet == null || $scope.startRentCabinet == undefined || $scope.startRentCabinet == '') {
			Message.warning("请输入租柜日期!");
			return;
		}
		if($scope.cabinetDays == null || $scope.cabinetDays == undefined || $scope.cabinetDays == '') {
			Message.warning("请输入租柜月数!");
			return;
		}
		$scope.bindingCabinetCompleteFlag = true;
		$http({
			url: "/cabinet/bind-member",
			method: 'POST',
			data: $.param($scope.bindingCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(result) {
			if(result.data.status == 'success') {
				Message.success(result.data.data);
				$scope.bindingCabinetDataInit();
				$scope.getDate();
				$scope.allCabinetTypeData();
				$('#bindingCabinet').modal('hide');
			} else {
				$scope.bindingCabinetCompleteFlag = false;
				Message.warning(result.data.data);
			}
		});
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 柜子的退租，退租的分析准备，时间上的处理
	 * param: id:柜子id
	 *        memCabinetId:会员柜子id
	 *        cabinetDetail:被租的柜子的详情
	 * */
	//退租
	$scope.quitCabinet = function(id, memCabinetId, cabinetDetail) {
		$scope.quitCabinetCompleteFlag = false;
		$scope.containerId = id;
		$scope.memCabinetId = memCabinetId;
		$scope.cabinetDetail = cabinetDetail;
		$('#boundCabinet').modal('hide');
		$scope.cabinetCashPledge = parseInt(cabinetDetail.deposit != null && cabinetDetail.deposit != undefined ? cabinetDetail.deposit : 50);
		var currentTime = new Date().getTime();
		var endTime = new Date(($scope.getMyDate(cabinetDetail.end_rent * 1000) + " " + "23:59:59")).getTime();
		$scope.endNextWeek = endTime + 7 * 24 * 60 * 60 * 1000;
        /**
         * 退柜管理-退柜设置-获取退柜配置
         * 1.有设置配置,调用配置
         * 2.没有,则退全部押金
         */
        $http.get('/cabinet/get-quit-cabinet-value').then(function (result) {
            $scope.quitCabinetValue = result.data;
            var setDays = $scope.quitCabinetValue.setDays;
            var setCost = $scope.quitCabinetValue.setCost;
            var dateType = $scope.quitCabinetValue.dateType;
            if(currentTime < endTime) {
                $scope.depositRefund = $scope.cabinetCashPledge;return ;
            }
            if (setDays == null || setDays == '' || setDays == undefined) {
                //没有设置天数限制
                if (setCost == null || setCost == '' || setCost == undefined) {
                    $scope.depositRefund = $scope.cabinetCashPledge;
                } else {
                    var long = 1;
                    switch (dateType) {
                        case 'everyDay'     : long = 1;break;
                        case 'everyWeek'    : long = 7;break;
                        case 'everyMonth'   : long = 30;break;
                    }
                    //计算超出的日期
                    var overTime = Math.ceil(parseInt(parseInt(currentTime) - parseInt(endTime))/(24 * 60 * 60 * 1000));
                    var overDay = Math.ceil(overTime/long);
                    var deductMoney = parseInt(overDay*setCost);
                    $scope.depositRefund = parseInt(parseInt($scope.cabinetCashPledge) - parseInt(deductMoney));
                    if (parseInt($scope.depositRefund) < 0) {
                        $scope.depositRefund = 0;
                    }
                }
            } else {
                //设置了天数限制
                var overTime = Math.ceil(parseInt(parseInt(currentTime) - parseInt(endTime))/(24 * 60 * 60 * 1000));
                if (overTime <= parseInt(setDays)) {
                    $scope.depositRefund = $scope.cabinetCashPledge;
                } else {
                    if (setCost == null || setCost == '' || setCost == undefined) {
                        $scope.depositRefund = $scope.cabinetCashPledge;
                    } else {
                        var long = 1;
                        switch (dateType) {
                            case 'everyDay'     :
                                long = 1;
                                break;
                            case 'everyWeek'    :
                                long = 7;
                                break;
                            case 'everyMonth'   :
                                long = 30;
                                break;
                        }
                        var overDay = Math.ceil((parseInt(overTime) - parseInt(setDays)) / parseInt(long));
                        var deductMoney = parseInt(parseInt(overDay)*setCost);
                        $scope.depositRefund = parseInt(parseInt($scope.cabinetCashPledge) - parseInt(deductMoney));
                        if (parseInt($scope.depositRefund) < 0) {
                            $scope.depositRefund = 0;
                        }
                    }
                }
            }

        })
	}
	var currentTime = new Date().getTime();
	$scope.currentDate = $scope.getMyDate(currentTime);
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 柜子的退租，时间处理完后，进行退租
	 * param: quiteDate:退租的时间
	 *        memCabinetId:会员柜子id
	 *        price:柜子单日金额
	 *        memberId:会员的id
	 *        cabinetId:要退租的柜子的id
	 * */
	//退柜完成
	$scope.quitCabinetComplete = function(endTime) {
		$scope.quitCabinetData = function() {
			return {
				_csrf_backend: $('#_csrf').val(),
				quiteDate: $scope.currentDate, //退租时间
				memCabinetId: parseInt($scope.memCabinetId), //会员柜子id
				price: $scope.depositRefund,
				memberId: parseInt($scope.cabinetDetail.member_id),
				cabinetId: parseInt($scope.cabinetDetail.id)
			}
		}
		$scope.quitCabinetCompleteFlag = true;
		$http({
			url: "/cabinet/quite-cabinet",
			method: 'POST',
			data: $.param($scope.quitCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(result) {
			if(result.data.status == 'success') {
				$scope.searchClass();
				$scope.allCabinetTypeData();
				Message.success(result.data.data);
				$('#backCabinet').modal('hide');
			} else {
				$scope.quitCabinetCompleteFlag = false;
				Message.warning(result.data.data);
			}
		});
	}
	/**
	 * author: 程丽明  杨慧磊(修改)
	 * create: 2017-06-09
	 * 函数描述: 续租柜子的处理
	 * param: cabinetDetail:被租的柜子的详情
	 * 描述:  续租
	 * */
	//声明全局变量柜子的数据
	var reCabinetData;
	//声明全局变量续柜赠送月数
	var reGiveMonth;
	//声明全局变量续柜金额
	var reMuchMoney;
	//声明全局变量防止折扣链式反应
	var disContainer;
	$scope.renewCabinet = function(cabinetDetail) {
		$scope.renewCabinetCompleteFlag = false;
		$scope.reletPrice = 0;
		$scope.reletMonth = '';
		$scope.giveReletMonthNum = 0;
		$scope.cabinetDetail = cabinetDetail;
		/*新增*/
		//初始化折扣
		$scope.redisplay = 0;
		$scope.reDis = '1';
		//获取续租柜子数据
		reCabinetData = cabinetDetail;
		//初始化折扣
		$scope.reDis = '';
		$scope.cabinetreletEndDate = $scope.getMyDate($scope.cabinetDetail.end_rent * 1000);
		$http.get('/user/member-card?memberId=' + $scope.cabinetDetail.member_id).then(function(response) {
			$scope.memberCardInvalidTime = response.data.invalid_time;
		});
		$('#boundCabinet').modal('hide');
	}
	//当月的输入框变化时获取柜子到期时间
	$scope.reletMonthChange = function(reletMonth) {
		//初始化折扣
		$scope.reDis = '1';
		if(reletMonth == null || reletMonth == '' || reletMonth == 0){
			//初始化赠送月数
			$scope.giveReletMonthNum = 0;
			//初始化续柜初始到期时间
			$scope.cabinetreletEndDate = $scope.getMyDate($scope.cabinetDetail.end_rent * 1000);
		}else{
			//实时计算获取续柜到期日期和赠送月数
			if($scope.reletMonth != undefined && $scope.reletMonth != '' && $scope.reletMonth != null && $scope.cabinetDetail.end_rent != undefined && $scope.cabinetDetail.end_rent != '' && $scope.cabinetDetail.end_rent != null && reCabinetData.give_month != null) {
				//获取租赁月数(包含赠送月数)
				var reRentreg = new RegExp('"'+$scope.reletMonth+'":\\s"\\d+"','g');
				var reRentRe = reCabinetData.give_month.match(reRentreg);
				if(reRentRe != null){
                    var reEndRe = reRentRe[0];
					reGiveMonth = reEndRe.slice(reEndRe.indexOf(':')+3).replace('"','');
                    $scope.giveReletMonthNum = reGiveMonth;
				}else{
					reGiveMonth = 0;
					$scope.giveReletMonthNum = 0;
				}
                // console.log('"'+$scope.reletMonth+'":\\s"\\d+"');
				//获取续柜时间
				var totalTime = $scope.reletMonth + parseFloat(reGiveMonth);
				//AjAX获取续柜到期时间
				$http.get('/cabinet/calculate-date?numberMonth=' + totalTime + '&startRent=' + $scope.cabinetreletEndDate).then(function(result) {
					$scope.cabinetreletEndDate = (result.data).replace(/\"/g, "");
				});
			}else{
				reGiveMonth = 0;
				$scope.giveReletMonthNum = 0;
			}
		}
		//租赁月数对应的金额
		if(reletMonth == null || reletMonth == ''){
			reMuchMoney = 0;
		}else if(reCabinetData.cabinet_money != null){
			var regReMoney = new RegExp('"'+reletMonth+'":\\s"\\d+"','g');
			var regReMoneyRe = reCabinetData.cabinet_money.match(regReMoney);
			if(regReMoneyRe != null){
				var endMoney = regReMoneyRe[0];
				reMuchMoney = endMoney.slice(endMoney.indexOf(':')+3).replace('"','');
			}else{
				reMuchMoney = $scope.reletMonth * $scope.cabinetDetail.monthRentPrice;
			}
		}else{
			reMuchMoney = $scope.reletMonth * $scope.cabinetDetail.monthRentPrice;
		}
		//实时计算续租金额
		if($scope.reletMonth != undefined && $scope.reletMonth != '' && $scope.reletMonth != null && $scope.cabinetDetail.monthRentPrice != undefined && $scope.cabinetDetail.monthRentPrice != '' && $scope.cabinetDetail.monthRentPrice != null) {
			if(reGiveMonth != undefined && reGiveMonth != null){
				$scope.reletPrice = reMuchMoney;
				disContainer = $scope.reletPrice;
			}else{
				$scope.reletPrice = reMuchMoney;
				disContainer = $scope.reletPrice;
			}
		}else{
			$scope.reletPrice = 0;
			disContainer = 0;
		}
		//定义数组容器
		var redisArr = [];
		//获取续租折扣
		if(reletMonth == null || reletMonth == ''){
			//如果不存在对应的折扣
			$scope.redises = redisArr;
			$scope.redisplay = 0;
		}else if(reCabinetData.cabinet_dis != null){
			var reRegdis = new RegExp('"'+reletMonth+'":\\s"\\d+(\\.\\d+)?(\\/\\d+(\\.\\d+)?)*"','g');
			var reRegdisRe = reCabinetData.cabinet_dis.match(reRegdis);
			if(reRegdisRe != null){
				var disRestr = reRegdisRe[0].slice(reRegdisRe[0].indexOf(':')+3).replace('"','');
				if(disRestr.indexOf('/') > -1){
					//如果存在'/'
					$scope.redises = disRestr.split('/');
					$scope.redisplay = 1;
				}else{
					//如果折扣数量是1
					redisArr[0] = disRestr;
					$scope.redises = redisArr;
					$scope.redisplay = 1;
				}
			}else{
				//如果不存在对应的折扣
				$scope.redises   = redisArr;
				$scope.redisplay = 0;
			}
		}else{
			//如果不存在对应的折扣
			$scope.redises   = redisArr;
			$scope.redisplay = 0;
		}
	}
	//选择折扣获取续费最终金额
	$scope.getReDis = function(reDis){
		if($scope.reletPrice != 0 && $scope.reletPrice != undefined && $scope.reletPrice != null && $scope.reletPrice != ''){
			$scope.reletPrice = disContainer * parseFloat(reDis);
		}else{
			$scope.reletPrice = 0;
		}
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 续租柜子完成，向数据库传输数据
	 * param: memCabinetId:会员柜子id
	 *        memberId:会员的id
	 *        renewDate:续租的日期
	 *        renewNumDay:续租的月数
	 *        renewRentPrice:续租的价格
	 * */
	//续租完成
	$scope.renewCabinetComplete = function() {
		var isHuileiExpire;
		$scope.renewCabinetData = function() {
			return {
				_csrf_backend: $('#_csrf').val(),
				memCabinetId: parseInt($scope.cabinetDetail.memberCabinetId), //会员柜子id
				memberId: parseInt($scope.cabinetDetail.member_id),
				renewDate: $scope.cabinetreletEndDate, //续组日期
				renewNumDay: $scope.reletMonth, //续组月数
				renewRentPrice: $scope.reletPrice, //续组价格
                give_month:$scope.giveReletMonthNum//续租的赠送的月数
			}
		}
		if($scope.reletMonth == null || $scope.reletMonth == '' || $scope.reletMonth == undefined) {
			Message.warning('请输入续租的月数!');
			return;
		}
		//如果到期日期大于会员卡到期时间(提示)
		if($scope.memberCardInvalidTime != undefined) {
				if(parseInt($scope.memberCardInvalidTime) * 1000 < new Date($scope.cabinetreletEndDate).getTime()) {
					Message.warning('温馨提示: 续柜到期时间在会员卡到期之后');
					isHuileiExpire = 'yes';
				}
			}else{
				isHuileiExpire = 'no'
		}
		//阻断提交
		if(isHuileiExpire == 'yes'){
			return;
		}
		//展现提交动画
		$scope.renewCabinetCompleteFlag = true;
		//提交
		$http({
			url: "/cabinet/renew-cabinet",
			method: 'POST',
			data: $.param($scope.renewCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(result) {
			if(result.data.status == 'success') {
				Message.success(result.data.data);
				$scope.reletMonth = '';
				$scope.reletPrice = '';
				$scope.getDate();
				$scope.allCabinetTypeData();
				$('#renewCabinet').modal('hide');
			} else{
				$scope.renewCabinetCompleteFlag = false;
				Message.warning("服务器错误!");
			}
		})
	}
    /**
     * author: 杨慧磊
     * create: 2017-12-30
     * 函数描述: 新增柜子详情-修改
     * 描述:     无
     * */
    //柜子类型管理列表
    $scope.getCabinetTypeLister = function(){
        $http.get('/cabinet/get-all-cabinet-type?cabinetTypeId=' + $scope.cabinetypeId).success(function(data){
           var large  = data.large;
           var middle = data.middle;
           var small  = data.small;
            $scope.lf = large.form.length;
            $scope.lt = large.temp.length;
            $scope.mf = middle.form.length;
            $scope.mt = middle.temp.length;
            $scope.sf = small.form.length;
            $scope.st = small.temp.length;
            if($scope.lf != 0){
				$scope.largeFormCount   = $scope.lf;
				$scope.largeFormNumber  = large.form[0].cabinet_number + " - " + large.form[$scope.lf - 1].cabinet_number;
				$scope.largeFirstNumber = large.form[0].cabinet_number;
				$scope.largeFormMoney   = large.form[0].monthRentPrice;
				$scope.largeFormDeposit = large.form[0].deposit;
				$scope.largeFormId      = large.form[0].id;
				$.grep(large.form, function(v,k){
					if(v.status == 2){
						$scope.largeFormStatus = 2;
					}
				});
            }
            if($scope.lt != 0){
				$scope.largeTempCount  = $scope.lt;
				$scope.largeTempNumber = large.temp[0].cabinet_number + " - " + large.temp[$scope.lt - 1].cabinet_number;
				$scope.largeScondNumber= large.temp[0].cabinet_number;
				$scope.largeTempId     = large.temp[0].id;
				$.grep(large.temp, function(v,k){
					if(v.status == 2){
						$scope.largeTempStatus = 2;
					}
				});
            }
            if($scope.mf != 0){
				$scope.MidFormCount   = $scope.mf;
				$scope.MidFormNumber  = middle.form[0].cabinet_number + " - " + middle.form[$scope.mf - 1].cabinet_number;
				$scope.MidFirstNumber = middle.form[0].cabinet_number;
				$scope.MidFormMoney   = middle.form[0].monthRentPrice;
				$scope.MidFormDeposit = middle.form[0].deposit;
				$scope.MidFormId      = middle.form[0].id;
				$.grep(middle.form, function(v,k){
					if(v.status == 2){
						$scope.MidFormStatus = 2;
					}
				});
            }
            if($scope.mt != 0){
				$scope.MidTempCount   = $scope.mt;
				$scope.MidTempNumber  = middle.temp[0].cabinet_number + " - " + middle.temp[$scope.mt - 1].cabinet_number;
				$scope.MidScondNumber = middle.temp[0].cabinet_number;
				$scope.MidTempId      = middle.temp[0].id;
				$.grep(middle.temp, function(v,k){
					if(v.status == 2){
						$scope.MidTempStatus = 2;
					}
				});
			}
            if($scope.sf != 0){
				$scope.smallFormCount  = $scope.sf;
				$scope.smallFormNumber = small.form[0].cabinet_number + " - " + small.form[$scope.sf - 1].cabinet_number;
				$scope.smallFirstNumber= small.form[0].cabinet_number;
				$scope.smallFormMoney  = small.form[0].monthRentPrice;
				$scope.smallFormDeposit= small.form[0].deposit;
				$scope.smallFormId     = small.form[0].id;
				$.grep(small.form, function(v,k){
					if(v.status == 2){
						$scope.smallFormStatus = 2;
					}
				});
            }
            if($scope.st != 0){
				$scope.smallTempCount  = $scope.st;
				$scope.smallTempNumber = small.temp[0].cabinet_number + " - " + small.temp[$scope.st - 1].cabinet_number;
				$scope.smallScondNumber= small.temp[0].cabinet_number;
				$scope.smallTempId     = small.temp[0].id;
				$.grep(middle.temp, function(v,k){
					if(v.status == 2){
						$scope.smallTempStatus = 2;
					}
				});
			}
        });
   }
    $scope.reletEndDate = function(){
        if($scope.reletMonth != undefined && $scope.reletMonth !='' && $scope.reletMonth != null && $scope.cabinetDetail.end_rent != undefined && $scope.cabinetDetail.end_rent !='' && $scope.cabinetDetail.end_rent != null ){
            if($scope.reletMonth >= 12){
                var numYears = Math.floor($scope.reletMonth/12);
                if($scope.reletGiveMonthNum !== 0){
                    $scope.reletMonth = numYears*2 + $scope.reletMonth;
                }
            }
            $http.get('/cabinet/calculate-date?numberMonth='+ $scope.reletMonth +'&startRent='+ $scope.cabinetreletEndDate).then(function(result){
                $scope.cabinetreletEndDate = (result.data).replace(/\"/g, "");
            });
        }
    }
    /**
     * author: 程丽明
     * create: 2017-06-09
     * 函数描述: 续租柜子完成，向数据库传输数据
     * param: memCabinetId:会员柜子id
     *        memberId:会员的id
     *        renewDate:续租的日期
     *        renewNumDay:续租的月数
     *        renewRentPrice:续租的价格
     * */
    //续租完成
    $scope.renewCabinetComplete = function(){
        $scope.renewCabinetData = function(){
            return {
                _csrf_backend: $('#_csrf').val(),
                memCabinetId :parseInt($scope.cabinetDetail.memberCabinetId),      //会员柜子id
                memberId:parseInt($scope.cabinetDetail.member_id),
                renewDate   :$scope.cabinetreletEndDate, //续组日期
                renewNumDay:$scope.reletMonth,      //续组月数
                renewRentPrice:$scope.reletPrice,  //续组价格
                give_month:$scope.giveReletMonthNum//续租的赠送的月数
            }
        }
        if($scope.reletMonth == null || $scope.reletMonth == ''|| $scope.reletMonth== undefined){
            Message.warning('请输入续租的月数!');
            return;
        }
        // if(parseInt($scope.memberCardRentInvalidTime) < $scope.endTime){
        //     Message.warning("您的租柜到期日期在会员卡到期日期之后!");
            // return;
        // }
        $scope.renewCabinetCompleteFlag = true;
        $http({
            url: "/cabinet/renew-cabinet",
            method: 'POST',
            data: $.param( $scope.renewCabinetData()),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result){
            if(result.data.status == 'success'){
                Message.success(result.data.data);
                $scope.reletMonth = '';
                $scope.reletPrice= '';
                $scope.getDate();
                $scope.allCabinetTypeData();
                $('#renewCabinet').modal('hide');
            }else{
                $scope.renewCabinetCompleteFlag = false;
                Message.warning(result.data.data);
            }
        })
    }
    /**
     * author: 程丽明
     * create: 2017-06-07
     * 函数描述: 冻结柜子
     * param: id:要被冻结的柜子的id
     * */
    //冻结柜子
    $scope.freezeCabinet = function(id){
        $http.get('/cabinet/frozen-cabinet?cabinetId='+ id).then(function(result){
            if(result.data.status == 'success'){
                Message.success(result.data.data);
                $scope.getDate();
            }else if(result.data.status == 'error'){
                Message.warning(result.data.data);
            }
        })
    }
    /**
     * author: 程丽明
     * create: 2017-06-09
     * 函数描述: 取消冻结柜子
     * param: id:取消冻结的柜子的id
     * */
    //取消冻结
    $scope.cancelFreezeCabinet  = function(id){
        $http.get('/cabinet/frozen-cabinet?cabinetId='+ id +'&status='+2).then(function(result){
            if(result.data.status == 'success'){
                Message.success(result.data.data);
                $scope.getDate();
            }else if(result.data.status == 'error'){
                Message.warning(result.data.data);
            }
        })
    }
    //柜子类型管理详情
    $scope.showCabinetDetail = function(t,c,n,m,i,tn,cn,status){
        $.loading.show();
		$scope.cabinetXingHao        = t;
		$scope.cabinetLeiBie         = c;
		$scope.cabinetHaoMa          = n;
		$scope.cabinetShuLiang       = m;
		$scope.id                    = i;
		$scope.model                 = tn;
		$scope.type                  = cn;
		$scope.modifyStatus          = status;
        $('#ManageDetailModal').modal('show');
        window.setTimeout('$.loading.hide()',200);
    }
	/**
	 * @柜子类型修改
	 * 根据柜子ID,类型,类别获取柜子详情
	 * @param : id(最小柜子ID)
	 *         count(柜子数量)
	 *         number(柜子名称)
	 *         model(柜子类型)
	 *         type(柜子类别)
	 **/
    $scope.CabinetTypeModify = function(id, count, number, model, type){
        $.loading.show();
		$scope.modifyCabinetPrefix   = number.match(/\D+/)[0];
		$scope.modifyCabinetNumStart = number.match(/\d+/)[0];
		$scope.modifyCabinetNum      = count;
		$scope.modifyCabinetSize     = model;
		$scope.modifyCabinetType     = type;
		$scope.$id                   = id;
		$http.get('/cabinet/get-cabinet-type-detail?cabinetId=' + id).success(function(data){
			if(data.status == 'ok'){
				if(data.data.cabinet_type == 2 && data.data.cabinet_month != ""){
					var cm  = JSON.parse(data.data.cabinet_month) ? JSON.parse(data.data.cabinet_month) : 0;
					var cmy = JSON.parse(data.data.cabinet_money) ? JSON.parse(data.data.cabinet_money) : 0;
					var gm  = JSON.parse(data.data.give_month) ? JSON.parse(data.data.give_month) : 0;
					var md  = JSON.parse(data.data.cabinet_dis) ? JSON.parse(data.data.cabinet_dis) : 0;
					$scope.modifyMuchMonth    = parseInt(cm[0]) ? parseInt(cm[0]) : '' ;
					$scope.modifyCabinetMoney = parseFloat(cmy[cm[0]]) ? parseFloat(cmy[cm[0]]) : '' ;
					$scope.modifyGiveMonth    = gm[cm[0]] ? gm[cm[0]] : '' ;
					$scope.modifyDis          = md[cm[0]] ? md[cm[0]] : '' ;
					$scope.modifyHalfMonthMoney = parseFloat(data.data.monthRentPrice);
					$scope.modifyCabinetDeposit = parseFloat(data.data.deposit);
				    var $max = cm.length;
					var i = 0;
					for(var n = 1; n < $max; n++){
						$scope.htmlAttr = 'addCabinetMonth';
						$scope.num  = $scope.num + 1;
						$http.get('/rechargeable-card-ctrl/add-venue?attr='+$scope.htmlAttr+'&num='+$scope.num).then(function (result) {
						var $html = result.data.html;
						var $dom = $($html).get(0);
							i++;
							$($dom).find('input[name="cabinet_month"]').val(parseInt(cm[i]));
							$($dom).find('input[name="cabinet_money"]').val(parseFloat(cmy[cm[i]]));
							$($dom).find('input[name="give_month"]').val(gm[cm[i]]);
							$($dom).find('input[name="cabinet_dis"]').val(md[cm[i]]);
							$('#modifyMuchPlugins').append($dom);
						})
					}
				}else{
					return true;
				}
			}else{
				Message.error('系统错误');
			}
		});
        $('#typeClass').modal('show');
        $('#ManageDetailModal').modal('hide');
        window.setTimeout('$.loading.hide()',200);
    }
    //柜子类型修改完成
    $scope.typeModifyComplete = function(id,count){
			//流程控制
			if($scope.modifyCabinetSize == null || $scope.modifyCabinetSize == undefined || $scope.modifyCabinetSize == "") {
				Message.warning("请选择柜子型号!");
				return;
			}
			if($scope.modifyCabinetType == null || $scope.modifyCabinetType == undefined || $scope.modifyCabinetType == "") {
				Message.warning("请选择柜子类别!");
				return;
			}
		    if($scope.modifyCabinetPrefix == undefined || $scope.modifyCabinetPrefix == '' || $scope.modifyCabinetPrefix == null) {
			    Message.warning("请输入柜号前缀!");
			    return;
		    }
			if($scope.modifyCabinetType == 2) {
				if($scope.modifyHalfMonthMoney == null || $scope.modifyHalfMonthMoney == undefined || $scope.modifyHalfMonthMoney == "") {
					Message.warning("请输入单月金额!");
					return;
				}
			}
			if($scope.modifyCabinetType == 2) {
				if($scope.modifyCabinetDeposit == null || $scope.modifyCabinetDeposit == undefined || $scope.modifyCabinetDeposit == '') {
					Message.warning("请输入押金金额!");
					return;
				}
			}
			//获取多月设置的值
			if($scope.modifyCabinetType == 2) {
				var $muchMoneyBox = $('#modifyMuchPlugins').children('div.clearfix');
				var jsonMonth = [];
				var jsonMoney = {};
				var jsonGiveMonth = {};
				var jsonDis = {};
				var notPass = false;
				var notFormat = false;
				$muchMoneyBox.each(function (index, item) {
					var cabinet_month = $(this).find('input[name="cabinet_month"]').val();
					var cabinet_money = $(this).find('input[name="cabinet_money"]').val();
					var give_month = $(this).find('input[name="give_month"]').val();
					var cabinet_dis = $(this).find('input[name="cabinet_dis"]').val();
					//判断折扣是否格式化
					if(cabinet_dis != '' && cabinet_dis != undefined && cabinet_dis != null){
						if(/^0\.[123456789]+$/.test(cabinet_dis) || /^0\.[123456789]+(\/0\.[123456789]+)+$/.test(cabinet_dis)){
							notFormat = false;
						}else{
							notFormat = true;
						}
					}
					if (cabinet_month != '' && cabinet_month != null && cabinet_money != '' && cabinet_money != null) {
						jsonMonth.push(cabinet_month);
						jsonMoney[cabinet_month] = cabinet_money;
						jsonGiveMonth[cabinet_month] = give_month;
						jsonDis[cabinet_month] = cabinet_dis;
					} else {
						//如果有一条不符合就不能通过
						notPass = true;
						return;
					}
				});
				//如果多月设置为空
				// if (notPass == true) {
				// 	Message.warning('多月设置必填项不能为空');
				// 	return;
				// }
				//如果多月设置月数重复
				if (isRepeat(jsonMonth)) {
					Message.warning('多月设置月份不能"重复"');
					return;
				}
				//如果折扣没有格式化
				if(notFormat == true){
					Message.warning('折扣不符合格式');
					return;
				}
			}
		    $scope.CabinetDetailFlag = true;
		    //通过柜子类别,柜子型号,柜子数量判断柜号是否和其他类别,类型的柜号重复
		    $scope.getDifferent = function(){
				return {
					cabinetSize   : $scope.modifyCabinetSize,
					cabinetType   : $scope.modifyCabinetType,
					cabinetNum    : $.trim($scope.modifyCabinetPrefix)+$.trim($scope.modifyCabinetNumStart),
					id            : id,
					cabinetTypeId : $scope.cabinetypeId
			     };
			}
			//新增柜子提交的数据
			$scope.modifyCabinetData = function() {
				return {
					_csrf_backend  : $('#_csrf').val(),
					cabinetId      : id,
					cabinetModel   : $scope.modifyCabinetSize != undefined && $scope.modifyCabinetSize != "" ? $scope.modifyCabinetSize : null, //柜子型号
					cabinetType    : $scope.modifyCabinetType != undefined && $scope.modifyCabinetType != "" ? $scope.modifyCabinetType : null, //柜子类别
					cabinetNum     : count, //柜子数量
					cabinetPrefix  : $.trim($scope.modifyCabinetPrefix), //柜子前缀
					cabinetNumStart: $.trim($scope.modifyCabinetNumStart), //柜子起始号码
					cabinetTypeId  : $scope.cabinetypeId != undefined && $scope.cabinetypeId != '' ? $scope.cabinetypeId : null, //柜子类型ID
					monthRentPrice : $scope.modifyHalfMonthMoney != undefined && $scope.modifyHalfMonthMoney != "" && $scope.modifyCabinetType == 2 ? $scope.modifyHalfMonthMoney : null, //单月金额
					deposit        : $scope.modifyCabinetDeposit != undefined && $scope.modifyCabinetDeposit != "" && $scope.modifyCabinetType == 2 ? $scope.modifyCabinetDeposit : null, //柜子押金
					giveMonth      : jsonGiveMonth != undefined && jsonGiveMonth != '' ? jsonGiveMonth : null,  //多月设置赠送月数
					cabinetMonth   : jsonMonth != undefined && jsonMonth != '' ? jsonMonth : null,    //多月设置月份
					cabinetMoney   : jsonMoney != undefined && jsonMoney != '' ? jsonMoney : null,  //多月设置金额
					cabinetDis     : jsonDis   != undefined && jsonDis   != '' ? jsonDis   : null,  // 多月设置折扣
				}
			};
		//判断柜号重复
		$http({
			method  :"POST",
			url     : "/cabinet/get-different-cabinet-number",
			data    : $.param($scope.getDifferent()),
			headers : {"Content-Type" : "application/x-www-form-urlencoded"}
		}).success(function (data){
			if(data.status == 'ok'){
				//提交
				$http({
					method : "POST",
					url    : "/cabinet/modify-cabinet",
					data   : $.param($scope.modifyCabinetData()),
					headers: {"Content-Type" : "application/x-www-form-urlencoded"}
				}).success(function(data){
					if(data.status == 'success'){
						Message.success(data.data);
						$('#typeClass').modal('hide');
						$scope.CabinetDetailFlag = false;
						$scope.getCabinetTypeLister();
					}else{
						Message.error(data.data);
					}
				});
			}else if(data.status == 'error'){
				Message.warning('柜号被占用');
				$scope.CabinetDetailFlag = false;
			}else{
				Message.error('系统错误');
			}
		});
    }
	//类型管理批量删除
	$scope.deleteCabinetType = function(id,count) {
		Sweety.remove({
				url              : '/cabinet/delete-cabinet-type?cabinetId=' + id + '&cabinetNum=' + count,
				http             : $http,
				title            : '确定要删除吗?',
				text             : '删除后所有信息无法恢复',
				confirmButtonText: '确定',
				data             : {
					action: 'unbind'
				}
			}, function () {
				$scope.getCabinetTypeLister();
		});
	}
	//修改模态关闭初始化数据
	$('#typeClass').on('hidden.bs.modal', function (e) {
		$scope.modifyCabinetSize     = '';
		$scope.modifyCabinetType     = '';
		$scope.modifyCabinetPrefix   = '';
		$scope.modifyCabinetNumStart = null;
		$scope.modifyCabinetNum      = null;
		$scope.modifyHalfMonthMoney  = null;
		$scope.modifyCabinetDeposit  = null;
		$scope.modifyMuchMonth       = '';
		$scope.modifyCabinetMoney    = null;
		$scope.modifyGiveMonth       = '';
		$scope.modifyDis             = '';
		$('.myDiv').remove();
	})
	/**
	 * author: 程丽明
	 * create: 2017-06-07
	 * 函数描述: 冻结柜子
	 * param: id:要被冻结的柜子的id
	 * */
	//冻结柜子
	$scope.freezeCabinet = function(id) {
		$http.get('/cabinet/frozen-cabinet?cabinetId=' + id).then(function(result) {
			if(result.data.status == 'success') {
                $('#boundCabinet').modal('hide');
				Message.success(result.data.data);
				$scope.getDate();
			} else if(result.data.status == 'error') {
				Message.warning(result.data.data);
			}
		})
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 取消冻结柜子
	 * param: id:取消冻结的柜子的id
	 * */
	//取消冻结
	$scope.cancelFreezeCabinet = function(id) {
		$http.get('/cabinet/frozen-cabinet?cabinetId=' + id + '&status=' + 2).then(function(result) {
			if(result.data.status == 'success') {
				Message.success(result.data.data);
				$scope.getDate();
			} else if(result.data.status == 'error') {
				Message.warning(result.data.data);
			}
		})
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 调柜事件 点击后获取所有未租的柜子
	 * param: cabinetDetail:要调柜的柜子的详情
	 * */
	//获取所有未租的柜子
	$scope.allUnLeasedCabinet = function() {
		$http.get($scope.unLeasedCabinetUrl).then(function(result) {
			$scope.unLeasedCabinetLists = result.data;
		})
	}
	//点击调柜按钮调柜
	$scope.switchCabinet = function(cabinetDetail) {
		$scope.completeSwitchCabinetBtnFlag = false;
		$('.listCabinetStyle').eq(0).addClass('bgGrey').siblings('.listCabinetStyle').removeClass('bgGrey');
		$('#boundCabinet').modal('hide')
		$scope.oldCabinetId = cabinetDetail.id;
		$scope.memCabinetId = cabinetDetail.memberCabinetId;
		$scope.oldCabinetDetail = cabinetDetail;
		var id = $scope.allCabinet[0].id;
		$scope.oldTypeName = $scope.allCabinet[0].type_name;
		$scope.unLeasedCabinetUrl = '/cabinet/get-all-cabinet?typeId=' + id;
		$scope.allUnLeasedCabinet();
	}
	//调柜类型选择
	$scope.cabinetStyleList = function(id, ind, object) {
		$scope.oldTypeName = object.type_name;
		$('.listCabinetStyle').eq(ind).addClass('bgGrey').siblings('.listCabinetStyle').removeClass('bgGrey');
		$scope.unLeasedCabinetUrl = '/cabinet/get-all-cabinet?typeId=' + id;

		$scope.allUnLeasedCabinet();
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-13
	 * 函数描述: 调柜选择时间，根据调整的选择，进行时间和金额的运算
	 * param: selectCabinet:选择的柜子
	 * */
	//调柜选择按钮
	$scope.selectSwitchCabinetBtn = function(selectCabinet) {
	    console.log('selectCabinet',selectCabinet);
		var oldObjectCabinet = $scope.oldCabinetDetail;
		console.log('oldObjectCabinet',oldObjectCabinet);
		var oldCabinetEndTime = $scope.getMyDate(oldObjectCabinet.end_rent * 1000);
		$scope.selectCabinetDetail = selectCabinet;
		$scope.switchMonthNum = selectCabinet.give_month;
		var currentDate = new Date().getTime();
		var cabinetEndDate = (oldObjectCabinet.end_rent) * 1000;
		var $cabinetModel = oldObjectCabinet.cabinetModel; //当前柜子 3
		var $cabinet_model = selectCabinet.cabinet_model; //调换柜子型号 1
		//大柜型号 1  中柜型号 2     小柜型号3
		//同柜子调换
		if(parseInt($cabinetModel) == parseInt($cabinet_model)) {
			$scope.selectSwitchCabinetPrice = 0;
			$scope.switchCabinetEndDate = oldCabinetEndTime;
		}
		//大柜调小柜剩余钱变成小柜的日期
		/*if(parseInt($cabinetModel) < parseInt($cabinet_model)) {
			var yearCabinetMoney = parseInt($scope.selectCabinetDetail.yearRentPrice);
			var monthCabinetMoney = parseInt($scope.selectCabinetDetail.monthRentPrice);
			var priceSpread = Math.abs(oldObjectCabinet.monthRentPrice - selectCabinet.monthRentPrice) * Math.floor((cabinetEndDate - currentDate) / (1000 * 24 * 60 * 60));
			//大调小剩余的钱数够大于新柜一年的钱
			if(yearCabinetMoney != null && parseInt(priceSpread) >= parseInt(yearCabinetMoney)) {
				//先判断可以购买几年
				var numYear = Math.floor(priceSpread / yearCabinetMoney);
				//判断当前的钱可以购买多少个月
				var numMonth = Math.floor((priceSpread - numYear * yearCabinetMoney) / monthCabinetMoney);
				//判断剩余的钱还可以买多少天
				var daysMoney = (priceSpread - numYear * yearCabinetMoney - numMonth * monthCabinetMoney);
				var numDay = Math.ceil((daysMoney / monthCabinetMoney) * 30);
				var months = (numYear + $scope.switchMonthNum) * 12 + numMonth
				//获取调柜到期的日期
				$http.get('/cabinet/calculate-date?numberMonth=' + months + '&startRent=' + oldCabinetEndTime).then(function(result) {
					var endDate = (result.data).replace(/\"/g, "");
					$scope.switchCabinetEndDate = $scope.getMyDate(new Date(endDate + " " + "23:59:59").getTime() + numDay * 24 * 60 * 60 * 1000);
				});
			} else {
				//没有年租金时计算可以租多少好个月
				var MonthNumber = Math.floor(priceSpread / monthCabinetMoney);
				//减除月剩余多少天
				var dayNumber = Math.ceil((priceSpread - MonthNumber * monthCabinetMoney) / monthCabinetMoney * 30);
				$http.get('/cabinet/calculate-date?numberMonth=' + MonthNumber + '&startRent=' + oldCabinetEndTime).then(function(result) {
					var endDate = (result.data).replace(/\"/g, "");
					var endTime = endDate + " " + "23:59:59";
					$scope.switchCabinetEndDate = $scope.getMyDate(new Date(endDate + " " + "23:59:59").getTime() + dayNumber * 24 * 60 * 60 * 1000);
				});
			}
			// var switchCabinetEndDate = Math.floor(priceSpread/(oldObjectCabinet.monthRentPrice/30))*24*60*60*1000 + cabinetEndDate;
			//  $scope.switchCabinetEndDate = $scope.getMyDate(switchCabinetEndDate);
			$scope.selectSwitchCabinetPrice = 0;
		}*/
        //大柜调下一级柜子剩余钱变成柜子的日期
       if (parseInt($cabinetModel) < parseInt($cabinet_model)) {
            //计算原柜子剩余的钱
            var freeMoney = parseFloat(parseFloat(oldObjectCabinet.monthRentPrice) / 30 * parseInt(oldObjectCabinet.surplusDay));
            //计算现柜子的能够使用的天数
            var nowDay = parseInt(freeMoney / (parseFloat(selectCabinet.monthRentPrice) / 30));
            var timestamp = Math.round(new Date()) + nowDay *24*60*60*1000;
            $scope.selectSwitchCabinetPrice = 0;
            $scope.switchCabinetEndDate = $scope.getMyDate(timestamp);
       }
		//小柜调大柜补交金额
		if(parseInt($cabinetModel) > parseInt($cabinet_model)) {
			var currentNext = $scope.getMyDate(currentDate) + " " + "23:59:59";
			var currentNextTime = new Date(currentDate).getTime();
			$scope.selectSwitchCabinetPrice = (Math.abs(oldObjectCabinet.monthRentPrice - selectCabinet.monthRentPrice) / 30 * Math.floor((cabinetEndDate - currentDate) / (1000 * 24 * 60 * 60))).toFixed(2);
			$scope.switchCabinetEndDate = oldCabinetEndTime;
		}
	}
	/**
	 * author: 程丽明
	 * create: 2017-06-09
	 * 函数描述: 调柜事件 完成数据运算后，向数据库传输数据
	 * param:  memCabinetId :会员柜子id
	 *         memberId:会员id
	 *         cabinetId :柜子id
	 *         originalCabinetId:老柜子id
	 *         price:应补金额
	 *         changeCabinDate:调柜的日期
	 * */
	//完成调柜
	$scope.completeSwitchCabinetBtn = function() {
		$scope.completeSwitchCabinetData = function() {
			return {
				_csrf_backend: $('#_csrf').val(), //csrf防止跨站
				memCabinetId: parseInt($scope.cabinetDetail.memberCabinetId), // 会员柜子id
				memberId: parseInt($scope.cabinetDetail.member_id),
				cabinetId: parseInt($scope.selectCabinetDetail.id), // 新柜子id
				originalCabinetId: parseInt($scope.oldCabinetId), //老柜子id
				price: $scope.selectSwitchCabinetPrice, //应补金额
				changeCabinetDate: $scope.switchCabinetEndDate //调柜的日期
			}
		}
		$scope.completeSwitchCabinetBtnFlag = true;
		$http({
			url: "/cabinet/change-cabinet",
			method: 'POST',
			data: $.param($scope.completeSwitchCabinetData()),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(result) {
			if(result.data.status == 'success') {
				Message.success(result.data.data);
				$('#checkCabinetSuccess').modal('hide');
				$('#checkCabinet').modal('hide');
				$scope.getDate();
			} else {
				$scope.completeSwitchCabinetBtnFlag = false;
				Message.warning(result.data.data);
			}
		})
	}
	$scope.init1();
	$scope.init2();
	$scope.init3();
	// 从柜子详细列表返回到柜子种类列表的点击事件
	$('.backHistoryBox').click(function() {
		$('.listBox').hide();
		$('.contentBox').show();
	});
	// 续柜日期插件的js
	$("#dateRenrw").datetimepicker({
		minView: "month", //设置只显示到月份
		format: 'yyyy-mm-dd ',
		language: 'zh-CN',
		autoclose: true,
		todayBtn: true, //今日按钮
	});
	// 租柜日期插件的js
	$("#dataCabinet").datetimepicker({
		minView: "month", //设置只显示到月份
		format: 'yyyy-mm-dd',
		language: 'zh-CN',
		autoclose: true,
		todayBtn: true, //今日按钮
	});
//	1229新增
	$scope.backShaPage =function () {
		location.href = "/new-cabinet/index?";
	}
	$(".type-management").click(function(){
		$(".disNone").hide();
		$(".listBoxType").show();
	});
    /**
     * @desc: 更柜管理-退柜设置-设置退柜配置
     * @author: 付钟超 <fuzhongchao@itsports.club>
     * @create: 2018/01/30
     */
    //更柜管理-退柜设置-点击显示退柜设置模态框
    $scope.showQuitCabinetSetting = function (num) {
        if (num == 1) {
            $('#quitCabinetSetting').modal('show');
            $http.get('/cabinet/get-quit-cabinet-value').then(function (result) {
                $scope.quitCabinetValue = result.data;
                $scope.setDays = $scope.quitCabinetValue.setDays;
                $scope.setCost = $scope.quitCabinetValue.setCost;
                if ($scope.quitCabinetValue.dateType == null || $scope.quitCabinetValue.dateType=='' || $scope.quitCabinetValue.dateType==undefined) {
                    $('#getDateType').val('everyDay');
                } else {
                    $('#getDateType').val($scope.quitCabinetValue.dateType);
                }

            })

        }
        if (num == 2) {
            $('#quitCabinetSetting').modal('hide');
        }
    }
    //更柜管理-退柜设置-获取表单数据
    $scope.getQuitCabinetData = function () {
        return {
            setDays     : $scope.setDays,               //设置到期退款天数
            dateType    : $('#getDateType').val(),      //设置超期扣除类型(日,周,月)
            setCost     : $scope.setCost,               //设置超期扣除金额
        };
    }
    //更柜管理-退柜设置-提交表单数据
    $scope.setQuitCabinetHttp = function () {
        $http({
            url         : '/cabinet/set-quit-cabinet',
            method      : 'POST',
            data        : $.param($scope.getQuitCabinetData()),
            headers     : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function (result) {
            if(result.data.status == 'success') {
                Message.success('保存成功');
                $scope.showQuitCabinetSetting(2);
            }else {
                Message.warning('保存失败');
            }
        })
    }
    //获取节点
    $scope.classFunc = function () {
        $(document).ready(function () {
            $('.showBoxTitle').on('click',function () {
                $('.showBoxTitle').removeClass('activeBox');
                $(this).addClass('activeBox');
            })
        });
    }
    //显示绑定柜子的消费情况,切换显示
    $scope.showBox = 1;
    $scope.isShowBox = true;
    $scope.showBoxClick = function (n) {
		switch(n) {
			case 1:
				$scope.showBox = 1;
                $scope.isShowBox = true;
				break;
			case 2:
                $scope.showBox = 2;
                $scope.isShowBox = false;
                break;
			default:
                $scope.showBox = 1;
                $scope.isShowBox = true;
		}
    }
	//获取会员绑定柜子的消费记录
	$scope.memberConsumList = function (url) {
		$http.get(url).then(function (result) {
                $scope.memberConsumListData = result.data.data;
                $scope.recordPages          = result.data.page;
        })
    }

});
