/**
 * Created by DELL on 2017/6/7.
 * 手机端迈步购卡页面控制器
 */

var app = angular.module('App',['starter.services']).controller('purchaseCardCtrl',function($http,$scope,$rootScope,$interval,dialogsManager,$location){

    //扇叶窗
    $(function(){
        $('.js-category').click(function(){
            $parent = $(this).parent('li');
            if($parent.hasClass('js-show')){
                $parent.removeClass('js-show');
                $(this).children('i').removeClass('icon-35').addClass('icon-74');
            }else{
                $parent.siblings().removeClass('js-show');
                $parent.addClass('js-show');
                $(this).children('i').removeClass('icon-74').addClass('icon-35');
                $parent.siblings().find('i').removeClass('icon-35').addClass('icon-74');
            }
        });

    });

    //选择所属公司
    $scope.getCompany = function(){
        $http.get('/purchase-card/get-only-venue').then(function(result){
            $scope.companyDatas = result.data;
        });
    }

    $scope.getCompany();
    //选择公司场馆
    $scope.selectCompany = function(id,name,pid){
        $('#company').removeClass('js-show');
        $('#companyI').removeClass('icon-35').addClass('icon-74');
        $scope.venueId = id;
        $scope.companyName = name;
        $scope.createId = pid;
        $scope.cardLists();
    }

    //选择卡种
    $scope.selectCard = function(id,name){
        $('#cardKind').removeClass('js-show');
        $('#cardKindI').removeClass('icon-35').addClass('icon-74');
        $scope.cardId = id;
        $scope.selectMemberCardName = name;
        $http.get('/purchase-card/deal?cardId=' + $scope.cardId ).then(function(result){
            $scope.introMessName = result.data.name;
            $scope.introMess = result.data.intro;
            localStorage.setItem('contractData',JSON.stringify({
                name:result.data.name,contractContent:result.data.intro
            }));
        });
    }
    

    //获取场馆所有卡的种类
    $scope.cardLists = function(){
        if($scope.venueId != undefined && $scope.venueId != null && $scope.venueId != ''){
            $http({
                url: "/purchase-card/card-category?venueId="+ $scope.venueId,
                method: 'GET'
            }).then(function (date) {
                $scope.cardItems = date.data;
            });
        }else{
            $scope.showMessage = function () {
                dialogsManager.showMessage("请先选择公司!");
            }
            $scope.showMessage();
            return;
        }

    }

    //获取新会员入会协议
    // $scope.getDealName = function(){
    //     $http.get('/contract/get-deal-name').then(function(result){
    //         if(result.data != '' || result.data != null || result.data != undefined){
    //             $scope.newMemberProtocol = result.data;
    //             $scope.newIntroMess      = result.data.intro;
    //             localStorage.setItem('newContractData',JSON.stringify({
    //                 name:result.data.name,contractContent:result.data.intro
    //             }));
    //         }else{
    //             $scope.showMessage = function () {
    //                 dialogsManager.showMessage("请先添加新会员入会协议");
    //             }
    //             $scope.showMessage();
    //             return;
    //         }
    //     });
    // }

    $scope.getDealName = function () {
        $scope.newMemberProtocol = {
            name : '迈步会员卡协议',
            intro: '<p class="MsoNormal"><span lang="EN-US" style="text-indent: 0cm; font-size: 12pt;">一、</span><span style="text-indent: 0cm; font-size: 12pt; font-family: 宋体;">会员卡</span><br></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">迈步健身采用会员制度服务模式，只有取得会员方能享受会馆的各项专业健身服务及相关配套服务。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">取得会员资格应办理入会手续，并已知晓会馆有关健身的规则与警示，承诺遵守会馆的相关规定。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">3、</span><span style="font-size: 12pt; font-family: 宋体;">因经营管理需要，会馆可能会针对不同客户办理不同类别的电子会员卡，但无论您持有何种电子会员卡，都将在享受会员权利的同时，受到本协议的约束。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">4、</span><span style="font-size: 12pt; font-family: 宋体;">本协议所称“会员”是指年满</span><span lang="EN-US" style="font-size: 12pt;">16</span><span style="font-size: 12pt; font-family: 宋体;">周岁未满</span><span lang="EN-US" style="font-size: 12pt;">60</span><span style="font-size: 12pt; font-family: 宋体;">周岁身体健康并向会馆缴纳了全部会费，能如实提供和陈述个人信息，取得本会馆发放的电子会员卡的人士。因此您有义务对入会时提供资料的真实性、合法性负责。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">二、</span><span style="font-size: 12pt; font-family: 宋体;">电子会员卡取得的方式</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">原始取得，指的是“迈步健身”客户端或是在微信公众账号“迈步健身”注册、填写真实、有效、全面的个人资料，办理入会手续并缴纳会费后取得会员资格。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">继受或转让取得，指在本协议约定的特殊事由出现时，通过办理一定的手续，由非会员的第三方取得会员资格，原会员资格终止。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">三、</span><span style="font-size: 12pt; font-family: 宋体;">电子会员卡的管理</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">会员卡仅供会员本人使用（体验卡除外），一经售出，概不退换。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">2、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">年卡会员卡因个人原因确实无法继续使用会员卡，经市场营销部同意可将会员资格转让，但每张卡只限转让一次，同时须交纳</span></b><b><span lang="EN-US" style="font-size: 12pt;">300</span></b><b><span style="font-size: 12pt; font-family: 宋体;">元办理会员资料变更及换发新卡。其他卡种不能转让或变相出售。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">3、</span><span style="font-size: 12pt; font-family: 宋体;">任何未经门店同意并办理变更手续的会员私自向非会员转让会籍资格的行为均无效，会馆将不予接待，并取消其会籍资格。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">4、</span><span style="font-size: 12pt; font-family: 宋体;">严重违反会馆规章制度者，会馆有权取消会籍资格并不予退换剩余费用。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">5、</span><span style="font-size: 12pt; font-family: 宋体;">期满后。若会员逾期不续费缴费，会馆后台自动取消该会员的会员权限，会员资格终止。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">6、</span><span style="font-size: 12pt; font-family: 宋体;">迈步会员卡原则上不接受停卡（购买年卡用户可享受一次停卡机会）特殊情况如：怀孕、受伤请在微信公众号留言，需提供医院证明。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">7、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">会员卡生效日期：有效期从购卡付款成功后开始计时。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">四、</span><span style="font-size: 12pt; font-family: 宋体;">会员权利</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">依照所持卡的类别享受相应的会馆对该类持卡会员作出的全部服务承诺；私教详见私教服务协议；</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">针对会馆的服务，会员有提出批评、投诉及改进建议的权利；</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">3、</span><span style="font-size: 12pt; font-family: 宋体;">期满后会员有优先续约的权利；</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">4、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">会员在付款当月可以申请开具全额订单发票，过期不予开具。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">五、</span><span style="font-size: 12pt; font-family: 宋体;">会员义务</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">如实向迈步健身微信后台提供个人信息资料，并在资料发生变动后计时通知后台。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">进入健身会馆时凭电子会员卡，随时接受工作人员验证和抽查。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">3、</span><span style="font-size: 12pt; font-family: 宋体;">严禁未预约的课程，强行进入操房上操课。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">4、</span><span style="font-size: 12pt; font-family: 宋体;">严禁携带</span><span lang="EN-US" style="font-size: 12pt;">16</span><span style="font-size: 12pt; font-family: 宋体;">岁以下儿童进入健身区域，对于擅自进入健身区域造成伤害或损失的，本会馆概不负责。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">5、</span><span style="font-size: 12pt; font-family: 宋体;">严禁健身区域内吸烟、进食。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">6、</span><span style="font-size: 12pt; font-family: 宋体;">严禁携带宠物进入健身会馆。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">7、</span><span style="font-size: 12pt; font-family: 宋体;">严禁心肺功能疾病、脊椎病、皮肤病及一切传染病患者进入健身会馆，有以上疾病的患者隐瞒病情取得会员资格的，健身会馆有权终止其资格，并保留追究其法律责任的权利。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">8、</span><span style="font-size: 12pt; font-family: 宋体;">为了您的健身安全，请穿着运动服饰、运动鞋参加运动，不得赤裸上身或穿着不得体的服饰进行运动。否则工作人员有权劝离并取消当日运动权利。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">9、</span><span style="font-size: 12pt; font-family: 宋体;">运动前严禁饮酒或饮用含酒精类饮料，因违反本条规定造成的人身伤害等意外情况，本会馆概不负责。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">10、</span><span style="font-size: 12pt; font-family: 宋体;">禁止会员在会馆内销售任何商品，不得参与任何营利性健身指导，违反本条规定的，迈步健身会馆有权取消其会员资格。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">11、</span><span style="font-size: 12pt; font-family: 宋体;">未征得本会馆负责人同意，禁止在会馆内拍照、摄像或录音。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">12、</span><span style="font-size: 12pt; font-family: 宋体;">本会馆原则上不接受</span><span lang="EN-US" style="font-size: 12pt;">60</span><span style="font-size: 12pt; font-family: 宋体;">岁以上老人入会，能出具真实有效的健康证明者除外，但因隐瞒或错报个人健康信息发生的一切责任都由本人承担。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">13、</span><span style="font-size: 12pt; font-family: 宋体;">会员在健身时必须正确使用各种健身器械，否则出现任何人身伤害会馆概不负责。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">14、</span><span style="font-size: 12pt; font-family: 宋体;">会员应自觉爱惜使用室内各项设施、设备，使用后归放原位，禁止将室内各项设施、设备擅自带出场馆，如有损坏、丢失追究相关人员责任，照价赔偿。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">15、</span><span style="font-size: 12pt; font-family: 宋体;">严禁在会馆内大声喧哗，使用污秽语言以及一切违法活动。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">16、</span><span style="font-size: 12pt; font-family: 宋体;">健身会馆内禁止吸烟、吐痰、乱扔垃圾，请自觉维护会馆的环境卫生。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">六、</span><span style="font-size: 12pt; font-family: 宋体;">权利保留</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">健身会馆营业时间原则上</span><span lang="EN-US" style="font-size: 12pt;">24</span><span style="font-size: 12pt; font-family: 宋体;">小时，营业时间有变动，会在无线公众账号后台以及门店做显著位置公示，会员须遵守该营业时间。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">因国家政策或者法律规定的规定，会馆有权合理修改营业时间并在店内公示，恕不另行通知会员，该公示即视为通知。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">3、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">因经营管理需要，会馆有权调整、增减部分项目，该行为不视为违约，且在店内公示后即为通知，无需另行报告给会员本人。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">4、</span><span style="font-size: 12pt; font-family: 宋体;">因器械、设备（设施）检修、维护，会馆有权在某一时间段或项目或类项目采取停用或限用设施。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">5、</span><span style="font-size: 12pt; font-family: 宋体;">其他出于会员安全及集体利益的需要，会馆有权采取必要措施以恢复经营秩序。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">七、</span><span style="font-size: 12pt; font-family: 宋体;">免责条款</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal"><b><span style="font-size: 12pt; font-family: 宋体;">出现下列情形的，会馆不予承担任何责任</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">1、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">遇不可抗力（如战争、自然灾害等）造成会馆营业终止或会员会籍不能继续，致使会员遭受损失。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">2、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">会员所受损害是因其自身故意或过失造成的。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">3、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">会员所受损害是会馆工作人员以外的任何第三方的故意行为或过失导致的。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">4、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">非会员不听劝阻，擅自进入或强行进入会员区域造成损害的，由其自身或致害方承担责任。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">5、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">受害方严重违反会馆制定的规章制度所造成损害的。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">6、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">未交由会馆保管而由会员或会员随同人员保管的贵重物品发生损毁、灭失、遗失的。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">7、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">因会员资料或个人保管信息发生变动未及时通知会馆，从而造成损失或会员权利受限的。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">8、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">未听从工作人员指导，擅自使用或违反操作规程使用器械，设备造成自身受伤的，由自身负责；造成他人受损或会馆财产损毁的，其本人应承担全部赔偿责任，对此本会馆不承担任何责任。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><b><span lang="EN-US" style="font-size: 12pt;">9、</span></b><b><span style="font-size: 12pt; font-family: 宋体;">因会员自身行为不当或会员之间的争议产生的人身和财产损失，本会馆不承担责任。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">八、</span><span style="font-size: 12pt; font-family: 宋体;">安全提示</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">会员在锻炼前，应先做必要的热身练习，以免受伤。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">过度锻炼及违规锻炼均有受伤的可能，所以您在运动前对自身的身体情况进行判断，并保持运动强度和时间的适当。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">3、</span><span style="font-size: 12pt; font-family: 宋体;">对违反本协议约定从而造成物品丢失及人员伤亡的，本会馆不承担任何法律责任。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">4、</span><span style="font-size: 12pt; font-family: 宋体;">健身馆内任何运动项目及器械设施的均有严格的操作方法和程序，请务必在专业人员的指导下或者安全手册下进行操作，如有违背致使人员受损，会馆不承担任何责任。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">5、</span><span style="font-size: 12pt; font-family: 宋体;">会员进行任何单位或器械的操作均应在知晓操作方法和注意事项后进行，否则因此方法任何损害，本健身会馆均不承担任何责任。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">九、</span><span style="font-size: 12pt; font-family: 宋体;">特别声明</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal"><b><span style="font-size: 12pt; font-family: 宋体;">在法律允许的范围内，为更好地服务会员之需要，会馆有权利对相关内容进行修改，且修改后的条款自通知会员或在会馆网站、ＡＰＰ、微信公众号或其他传播渠道显著位置公示后，即对全体会员产生约束力。</span></b><b><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></b></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">十、</span><span style="font-size: 12pt; font-family: 宋体;">会员承诺</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">1、</span><span style="font-size: 12pt; font-family: 宋体;">本人保证所提供的入会资料及个人信息真实有效。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">2、</span><span style="font-size: 12pt; font-family: 宋体;">本人身体健康且没有协议约定的不适合进行运动的疾病。</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt; color: red;">3、</span><span style="font-size: 12pt; font-family: 宋体;">本人已阅读、理解并同意上述条文，<span style="color: red;">对本协议中加黑部分我已充分注意并完全同意。</span></span><span lang="EN-US" style="font-size: 12pt; color: red;"><o:p></o:p></span></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">十一、</span><span style="font-size: 12pt; font-family: 宋体;">其他</span><span lang="EN-US" style="font-size: 12pt;"><o:p></o:p></span></p><p></p><p class="MsoNormal" style="margin-left: 0cm; text-indent: 0cm;"><span lang="EN-US" style="font-size: 12pt;">十二、</span><span style="font-size: 12pt; font-family: 宋体;">如果会员和会馆因协议的履行发生争议，双方首先应当协商解决，如果协商不成的，任何一方均可以向郑州市金水区人民法院起诉解决争议。</span></p>'
        }
    };
    //初始提交状态
    $scope.submitButtonFlag = false;

    $scope.confirm  = function(){
        var absurl = $location.absUrl();
        $scope.url = absurl + '/contract';
        $scope.submitData = function(){
                return{
                        name            :$scope.name        != undefined && $scope.name        != "" ? $scope.name       : null,
                        idCard          :$scope.idCard        != undefined && $scope.idCard        != "" ? $scope.idCard       : null,
                        idAddress       :$scope.idAddress        != undefined && $scope.idAddress        != "" ? $scope.idAddress       : null,
                        nowAddress      :$scope.nowAddress        != undefined && $scope.nowAddress        != "" ? $scope.nowAddress       : null,
                        mobile          :$scope.mobile        != undefined && $scope.mobile        != "" ? parseInt($scope.mobile)       : null,
                        code            :$scope.code        != undefined && $scope.code        != "" ? parseInt($scope.code)      : null,
                        newCode         :$scope.newCode        != undefined && $scope.newCode        != "" ? parseInt($scope.newCode)       : null,
                        cardId          :$scope.cardId        != undefined && $scope.cardId        != "" ? parseInt($scope.cardId)      : null,
                        companyId       :$scope.createId,
                        venueId         :$scope.venueId,
                        url              :$scope.url,
                        _csrf_backend   :$('#_csrf').val()
                }
            }

            var buyCardName = /^([a-zA-Z0-9\u4e00-\u9fa5 ]+)$/;
            if($scope.name == '' || $scope.name == null || $scope.name == undefined || !(buyCardName.test($scope.name))){
                $scope.showMessage = function () {
                    dialogsManager.showMessage("请输入正确的姓名");
                }
                $scope.showMessage();
                return;
            }

        if($scope.idAddress == '' || $scope.idAddress == null || $scope.idAddress == undefined || !(buyCardName.test($scope.idAddress))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入合法的身份证地址!");
            }
            $scope.showMessage();
            return false;
        }

        if($scope.nowAddress == '' || $scope.nowAddress == null || $scope.nowAddress == undefined || !(buyCardName.test($scope.nowAddress))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请输入合法的现居住地址!");
            }
            $scope.showMessage();
            return false;
        }
            //验证身份证号
            var idCardP = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/;
            if ($scope.idCard == null || $scope.idCard == "" || !(idCardP.test($scope.idCard))) {
                $scope.showMessage = function () {
                    dialogsManager.showMessage("请输入18位有效身份证号");
                }
                $scope.showMessage();
                return ;
            }
            //手机号验证
            var $pattern = /^1[34578]\d{9}$/;
            if ($scope.mobile == null || $scope.mobile == undefined || !($pattern.test($scope.mobile))) {
                $scope.showMessage = function () {
                    dialogsManager.showMessage("请填写正确的手机号!");
                }
                $scope.showMessage();
                return false;
            }



            //验证验证码
            if (parseInt($scope.newCode) != parseInt($scope.code)) {
                $scope.showMessage = function () {
                    dialogsManager.showMessage("验证码输入错误");
                }
                $scope.showMessage();
                return ;
            }

        if(parseInt($scope.mobile) != parseInt($scope.oldMobile)){
            $scope.showMessage = function () {
                dialogsManager.showMessage("您的验证码和手机号不匹配！");
            }
            $scope.showMessage();
            return;
        }


        if($scope.venueId == undefined || $scope.venueId == null || $scope.venueId == ''){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请选择公司");
            }
            $scope.showMessage();
            return ;
        }

            if ($scope.cardId == null || $scope.cardId == "" || $scope.cardId == undefined) {
                $scope.showMessage = function () {
                    dialogsManager.showMessage("请选择卡类型");
                }
                $scope.showMessage();
                return ;
            }
        if(!$scope.submitButtonFlag){
            $scope.submitButtonFlag = true;
            //手机号去重
            $http.get('/potential-members/get-mobile-info?mobile='+ $scope.mobile +'&companyId=49').then(function(response){
            if(response.data.status == "error"){
                $scope.submitButtonFlag = false;
                $scope.showMessage = function () {
                    dialogsManager.showMessage("该用户已登记!");
                }
                $scope.showMessage();
                return;
            }else{
                $http({
                    url     : '/purchase-card/sell-card',
                    method  : 'POST',
                    data    : $.param($scope.submitData()),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).then(function (result){
                    if(result.data.status == 'success'){
                        $scope.showMessage = function () {
                            dialogsManager.showMessage("您已提交购卡的所需信息，请及时缴费!");
                        }
                        $scope.showMessage();
                        location.href = '/purchase-card/complete';
                    }else{
                        $scope.submitButtonFlag = false;
                        $scope.showMessage = function () {
                            dialogsManager.showMessage(result.data.message);
                        }
                        $scope.showMessage();
                        return;
                    }
                });
            }
        });
        }
    }



    //注册验证码
    $scope.paracont  = "获取验证码";
    $scope.disabled  = false;

    $scope.getCode = function(){
        var $pattern = /^1[34578]\d{9}$/;
        if(!($pattern.test($scope.mobile))){
            $scope.showMessage = function () {
                dialogsManager.showMessage("请填写正确的手机号!");
            }
            $scope.showMessage();
            return ;
        }else{
            $scope.oldMobile = $scope.mobile;
            $http({
                url     : '/v1/api-member/create-code',
                method  : 'POST',
                data    : $.param({'mobile' : $scope.mobile}),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function (result){
                $scope.code = result.data.data;
            });

            /**
             * @云运动 - 后台 - 注册页面
             * @author Zhu Mengke <zhumengke@itsports.club>
             * @create 2017/4/5
             * @inheritdoc
             */
            //注册验证码倒计时
            var second = 60,
                timePromise = undefined;

            timePromise = $interval(function(){
                if(second<=0){
                    $interval.cancel(timePromise);
                    timePromise = undefined;

                    second = 60;
                    $scope.paracont  = "获取验证码";
                    $scope.disabled  = false;
                }else{
                    $scope.paracont = second + "秒后重新获取";
                    $scope.disabled =true;
                    second--;
                }
            },1000,100);
        }
    };
}).controller('contractCtrl',function($scope,$http,$rootScope,$location){
    var contractData = localStorage.getItem('contractData');
    var $memberIdArr = angular.fromJson(contractData);
    $scope.getContractData = $memberIdArr.contractContent;
    $scope.contractName = $memberIdArr.name;
}).controller('newContractCtrl',function($scope,$http,$rootScope,$location){
    var newContractData = localStorage.getItem('newContractData');
    var $newContract = angular.fromJson(newContractData);
    $scope.getNewContractData = $newContract.contractContent;
    $scope.newContractName = $newContract.name;
}).filter('to_Html', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
);
