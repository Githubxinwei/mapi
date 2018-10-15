(function(window, document) {
	var viewWidth = 720;
	var pick = viewWidth / 100;
	var mode = "resize"

	function change() {
		var view = document.documentElement.clientWidth || window.innerWidth;

		if(view >= 720) {
			document.getElementsByTagName('html')[0].style.fontSize = 100 + 'px';
			return;
		}
		document.getElementsByTagName('html')[0].style.fontSize = view / pick + 'px';
		// console.log(document.getElementsByTagName('html')[0].style.fontSize);
	}
	window.addEventListener(mode, change, !1);
	window.document.addEventListener('DOMContentLoaded', change, !1);
})(window, document);

$(function(){
	
	$('#memberName').val('');
	$('#memberPhone').val('');
    //获取url后面的参数
    function getRequest() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]] = decodeURI(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
    //获取url上的参数
    getRequest();
    var hostUrl = location.host;//域名
    // console.log('域名',location.host)
    // console.log(getRequest());
    
    var memberName = getRequest().memberName;//获取邀请人name
	console.log(memberName);
    var memberId = getRequest().member_id;//获取会员id
    var coachId = getRequest().coach_id;//获取教练id
    //name中文乱码需要后台解决
    $('.friendName').text(getRequest().memberName);
	
	//兴趣标签点击事件
	$('.interestList').on('click',function(e){
		var ind = $(this).attr("data-index");
		// console.log($(this).hasClass('selectedBox'))
		if(ind != 5){
			$('.noInterest').removeClass('selectedBox');
			if(!$(this).hasClass('selectedBox')){
				$(this).addClass('selectedBox');
			}else{
				$(this).removeClass('selectedBox');
			}
		}else{
			$(this).addClass('selectedBox').siblings('.interestList').removeClass('selectedBox');
		}
	});
	
	

	//点击提交
	$('.submitBtn').on('click',function(){
		var interestArr = [];
		var interestList = $('.interestList');//所有的兴趣标签
		interestList.each(function(index,item){
			// console.log(item);
			if($(this).hasClass('selectedBox')){
				var ind = $(this).attr("data-index");
				interestArr.push(ind);
			}
		})
		var name = $('#memberName').val().replace(/\s/g, "");//姓名
		var mobile = $('#memberPhone').val();//手机号
		var data = {
			coach_id:coachId,//教练id
			member_id:memberId,     //分享会员id
			mobile:mobile,            //要报名人员手机号
		    name :name,             //要报名人员姓名
		    interest :interestArr        //兴趣标签
		}
		// console.log('data',data)
		
		if(!name){
			jqtoast('请输入姓名');
			return;
		}
		var myreg=/^[1][3,4,5,7,8][0-9]{9}$/; 
		// console.log(myreg.test(parseInt(mobile)))
		if(!myreg.test(mobile)){
			jqtoast('请输入正确的手机号');
			return;
		}
		
		$.post('http://'+ hostUrl +'/v1/member-template/share-registration',data,function(res){
			if(res.status == "success"){
				jqalert({
		            title:'提交成功',
		            content:'我们将尽快与你联系，如有疑问，请联系客服。',
		            yesfn: function() {
		            	//点击确定时的回调
			            jqtoast('提交成功');
			        },
		       });
			}else {
                jqtoast(res.message);
			}
		});
		
	});
});
