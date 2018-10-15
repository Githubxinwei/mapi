$(function(){
	//checkBox插件引用函数
	function iCheck(){
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	}
	//checkBox插件引
	iCheck();
	//动态计算模态框，让其居中
	function mtk(){
		var h = $(window).height();
		var w = $(window).width();
		var mW = $('#mutaikuang').outerWidth();
		var mH = $('#mutaikuang').outerHeight();
		var top = (h-mH)/2+'px';
		var left = (w-mW)/2+'px';
		$('#mutaikuang').css({'marginTop':top,'marginLeft':left});
	}
	//模态框
	$('#tmk').on('click',function(e){
		mtk();
	});
	//返回上一页
	$('.h50 h6').on('click',function(){
		window.history.back(-1);
	});
	$('.backBut').on('click',function(){
		window.history.back(-1);
	});
	//添加新课程
	//动态创建CourseList容器
	function createCourse(text){
		var w1 ='<div class="courseList"><h4 class="f20 mB10">课程次数限制</h4><div class=" lRight"><p>'
		var w2 = text
		var w3 = '次数限制</p><div class="checkbox i-checks" style="width: 200px;"><label><input type="checkbox" value=""> <i></i>不限</label></div></div><input class="form-control" type="text" placeholder="日几次 / 周几次 / 月几次 /寄几次 / 年几次" />'
		var w4 = '<div class=" lRight"><p>'
		var w2 = text
		var w6 = '课程总节数</p><div class="checkbox i-checks" style="width: 200px;"><label><input type="checkbox" value=""> <i></i>不限</label></div></div>'
		var w7 ='<input class="form-control" type="text" placeholder="共几节课" />'
		$(w1+w2+w3+w4+w2+w6+w7).appendTo($('.courseLists'));
		iCheck();
	}
	//动态创建服务
	function createServe(content){
		var con1 = '<div class="checkbox i-checks" style="width: 200px;"><label><input type="checkbox" value=""> <i></i>';
		var con2 = content;
		var con3 = '</label></div>'
		$(con1 + con2 + con3).appendTo($('#serveLists'));
		iCheck();
	}
	//点击后让添加课程按钮不可点，增加课程输入框显示
	$('#addBtn').on('click', function() {
		$(this).attr('disabled','disabled')
		$('#addSelect').addClass('dis_block');
	});
	//让添加课程按钮可点，增加课程输入框消失
	function isShow(){
		$('#addBtn').removeAttr('disabled');
		$('#addSelect').removeClass('dis_block');
	};
	//点击确认按钮添加表单
	$('#courseConfirm').on('click',function(){
		isShow();
		var textSelect = $('#addCourse').children('option:selected').text();
		createCourse(textSelect);
	});
	// 点击确定按钮添加服务种类
	$('#courseTrue').on('click',function(){
		isShow();
		var textCon = $('#serveText').val();
		// alert(textCon);
		if(textCon!=''){
			createServe(textCon);
		}
	});
	//点击取消按钮恢复原状
	$('#courseCancel').on('click',function(){
		isShow();
	});
});
