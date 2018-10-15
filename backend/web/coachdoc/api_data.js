define({ "api": [
  {
    "type": "get",
    "url": "/coach/v1/class/view?accesstoken=666",
    "title": "上课详情",
    "version": "1.0.0",
    "name": "____",
    "group": "class",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/class/view?accesstoken=666\n{\n       \"id\":\"30018\"，             //上课ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>上课详情 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/class/view?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"id\": \"30018\",                  //上课ID\n        \"member_id\": \"48324\",           //会员ID\n        \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/20700255.JPG?e=1501923581&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:E1PpeGYJWxXfBO8aQzrujopiZ-4=\",//会员头像\n        \"name\": \"马婷婷\",                //会员姓名\n        \"sex\": \"女\",                    //性别\n        \"age\": \"\",                      //年龄（数据库中有的出生日期未填写）\n        \"mobile\": \"13783592200\",        //手机\n        \"course_name\": \"PT常规课\",       //课程名称\n        \"product_name\": \"PT常规课\",      //课种名称\n        \"coach\": \"冯帅旗\",               //教练\n        \"start\": \"11月29日 12:30\",       //开课时间\n        \"class_length\": 60,             //课程时长\n        \"card_name\": \"两年白金瑜伽卡\",    //会员卡名\n        \"card_number\": \"20700255\",      //会员卡号\n        \"sell_coach\": \"冯帅旗\",          //办理私教\n        \"money_amount\": 0,              //课程金额\n        \"course_amount\": 26,            //总节数\n        \"overage_section\": \"\",          //剩余节数\n        \"deadline_time\": \"2019-08-10\",  //到期日期\n        \"cancel_time\": \"\",              //取消时间（取消详情使用，其他状态可忽略)\n        \"cancel_reason\": \"\",            //取消原因（取消详情使用，其他状态可忽略)\n        \"month_cancel\": 0               //本月取消次数（取消详情使用，其他状态可忽略)\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ClassController.php",
    "groupTitle": "class"
  },
  {
    "type": "get",
    "url": "/coach/v1/class/in?accesstoken=666",
    "title": "上课打卡",
    "version": "1.0.0",
    "name": "_____",
    "group": "class",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/class/in?accesstoken=666\n{\n       \"id\":\"30018\"，             //上课ID\n       \"member_id\":\"48324\"        //会员ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>上课打卡 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/class/in?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n \"code\":1,               //成功标识\n \"status\": \"success\",    //请求状态\n \"message\": \"打卡成功\"，  //返回信息\n \"data\": \"\",\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"打卡失败\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ClassController.php",
    "groupTitle": "class"
  },
  {
    "type": "get",
    "url": "/coach/v1/class/out?accesstoken=666",
    "title": "下课打卡",
    "version": "1.0.0",
    "name": "______",
    "group": "class",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/class/out?accesstoken=666\n{\n       \"id\":\"30018\"，             //上课ID\n       \"member_id\":\"48324\"        //会员ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>下课打卡 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1//class/out?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n \"code\":1,               //成功标识\n \"status\": \"success\",    //请求状态\n \"message\": \"打卡成功\"，  //返回信息\n \"data\": \"\",\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"打卡失败\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ClassController.php",
    "groupTitle": "class"
  },
  {
    "type": "get",
    "url": "/coach/v1/class/index?accesstoken=666",
    "title": "上课列表/上课搜索",
    "version": "1.0.0",
    "name": "_________",
    "group": "class",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/class/index?accesstoken=666\n{\n     \"status\":\"1\"，             //上课状态（1:未上课 2:取消预约 3:上课中 4:下课 5:过期（爽约）） 例：已下课列表 /coach/v1/class/index?accesstoken=666&status=4\n     \"keyword\":\"张\"             //上课搜索关键词  上课搜索接口调用 例如：/coach/v1/class/index?accesstoken=666&keyword=邵乐石\n     \"member_id\": \"62226\"       //会员上课记录，取消记录，过期（爽约）记录 例：会员ID为62226的取消列表 /coach/v1/class/index?accesstoken=666&member_id=62226&status=2\n     \"per-page\":2               //每页显示数，默认20\n     \"page\":2                   //第几页\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>上课列表/上课搜索/会员的上课列表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\":1,\n    \"data\": [\n        {\n            \"id\": \"36780\",                  //上课ID,获取上课详情用此ID /coach/v1/class/view?accesstoken=666&id=36780\n            \"member_id\": \"62226\",           //会员ID\n            \"card_number\": \"42848\",         //会员卡号\n            \"start\": \"12月06日 16:26\",       //上课开始时间\n            \"status\": \"4\",                  //上课状态（1:未上课 2:取消预约 3:上课中 4:下课 5:过期）\n            \"is_read\": \"0\",                 //是否已读（0未读1已读）\n            \"name\": \"邵乐石\",                //会员姓名\n            \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/1550081506425328.JPG?e=1506428928&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:QpqgwNb09s87KQ-OXdU4pMFvmC0=\",//会员头像\n            \"course_name\": \"PT常规课\",       //课程名称\n            \"class_length\": \"60\",           //课程时长\n            \"time\": \"19:03\"                 //预约时间\n        },\n        {\n            \"id\": \"36668\",\n            \"member_id\": \"61528\",\n            \"card_number\": \"65928\",\n            \"start\": \"12月06日 16:26\",\n            \"status\": \"4\",\n            \"is_read\": \"0\",\n            \"name\": \"秦亚坤\",\n            \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/6914541505644993.JPG?e=1505648593&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:G4NtiQPzYoOAieb9CsOUQ-az3AE=\",\n            \"course_name\": \"PT常规课\",\n            \"class_length\": \"60\",\n            \"time\": \"19:03\"\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666&per-page=2&page=2\"//当前页\n        },\n        \"first\": {\n            \"href\": \"http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666&per-page=2&page=1\"//第一页\n        },\n        \"prev\": {\n            \"href\": \"http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666&per-page=2&page=1\"//上一页\n        },\n        \"next\": {\n            \"href\": \"http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666&per-page=2&page=3\"//下一页\n        },\n        \"last\": {\n            \"href\": \"http://apiqa.aixingfu.net/coach/v1/class/index?accesstoken=666&per-page=2&page=118\"//最后页\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": 236,      //总数\n        \"pageCount\": 118,       //总页数\n        \"currentPage\": 2,       //当前页\n        \"perPage\": 2            //每页显示数\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ClassController.php",
    "groupTitle": "class"
  },
  {
    "type": "get",
    "url": "/coach/v1/leave/index?accesstoken=666",
    "title": "请假列表",
    "version": "1.0.0",
    "name": "____",
    "group": "leave",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/leave/index?accesstoken=666\n{\n     \"member_id\": \"62226\"       //会员请假记录 例：会员ID为62226的请假列表 /coach/v1/leave/index?accesstoken=666&member_id=62226\n     \"per-page\":2               //每页显示数，默认20\n     \"page\":2                   //第几页\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>请假列表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/13</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/leave/index?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\":1,\n    \"data\": [\n        {\n            \"id\": \"337\",                    //请假ID，获取请假详情用此ID /coach/v1/leave/view?accesstoken=666&id=337\n            \"member_id\": \"62448\",           //会员ID\n            \"card_number\": \"61000185\",      //会员卡号\n            \"name\": \"贾利玲\",                //会员姓名\n            \"leave_property\": 2,            //请假类型(1普通请假 2特殊请假)\n            \"leave_end_time\": \"12月13日\",    //结束日期\n            \"is_read\": 0,                   //是否已读 0未读 1已读\n            \"time\": \"19:03\"                 //提交时间\n        },\n        {\n            \"id\": \"327\",\n            \"member_id\": \"62432\",\n            \"card_number\": \"61000185\",\n            \"name\": \"潘梦娇\",\n            \"leave_property\": 2,\n            \"leave_end_time\": \"12月12日\",\n            \"is_read\": 0,\n            \"time\": \"19:03\"\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://127.0.0.3/coach/v1/leave/index?accesstoken=GqK3hyjiw4yYtETRMibdr8z77666ocIz&per-page=2&page=2\"//当前页\n        },\n        \"first\": {\n            \"href\": \"http://127.0.0.3/coach/v1/leave/index?accesstoken=GqK3hyjiw4yYtETRMibdr8z77666ocIz&per-page=2&page=1\"//第一页\n        },\n        \"prev\": {\n            \"href\": \"http://127.0.0.3/coach/v1/leave/index?accesstoken=GqK3hyjiw4yYtETRMibdr8z77666ocIz&per-page=2&page=1\"//上一页\n        },\n        \"next\": {\n            \"href\": \"http://127.0.0.3/coach/v1/leave/index?accesstoken=GqK3hyjiw4yYtETRMibdr8z77666ocIz&per-page=2&page=3\"//下一页\n        },\n        \"last\": {\n            \"href\": \"http://127.0.0.3/coach/v1/leave/index?accesstoken=GqK3hyjiw4yYtETRMibdr8z77666ocIz&per-page=2&page=7\"//最后页\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": 13,       //总数\n        \"pageCount\": 7,         //总页数\n        \"currentPage\": 2,       //当前页\n        \"perPage\": 2            //每页显示数\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/LeaveController.php",
    "groupTitle": "leave"
  },
  {
    "type": "get",
    "url": "/coach/v1/leave/view?accesstoken=666",
    "title": "请假详情",
    "version": "1.0.0",
    "name": "______",
    "group": "leave",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/leave/view?accesstoken=666\n{\n       \"id\":\"48324\"，             //请假ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>请假详情 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/13</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/leave/view?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"id\": \"634\",                            //请假ID\n        \"member_id\": \"62213\",                   //会员ID\n        \"name\": \"张若鑫\",                        //会员姓名\n        \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/493781506316094.JPG?e=1506319694&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dsb5wPUdvHoQF74WdfhSjgL4ypQ=\",//会员头像\n        \"sex\": \"女\",                            //性别\n        \"age\": 29,                              //年龄\n        \"mobile\": \"18703656666\",                //手机\n        \"member_card\": \"WD T24MD\",              //会员卡名称\n        \"card_number\": \"61000066\",              //会员卡号\n        \"leave_property\": 2,                    //请假类型(1普通请假 2特殊请假)\n        \"leave_remain\": \"2次\",                  //剩余几次或几天\n        \"status\": \"未批准\",                      //请假状态\n        \"leave_start_time\": \"2017-12-06\",       //开始日期\n        \"leave_end_time\": \"2018-01-04\",         //结束日期\n        \"leave_length\": \"30\",                   //请假天数\n        \"note\": \"事假.顾问郭强\"                  //请假原因\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/LeaveController.php",
    "groupTitle": "leave"
  },
  {
    "type": "get",
    "url": "/coach/v1/manage/coach-list?accesstoken=666",
    "title": "教练列表",
    "version": "1.0.0",
    "name": "____",
    "group": "manage",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/manage/coach-list?accesstoken=666\n{\n     \"per-page\":2              //每页显示数，默认20\n     \"page\":2                  //第几页\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练列表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/manage/coach-list?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n\n{\n    \"code\":1,\n    \"data\": [\n        {\n            \"id\": \"1811\",       //教练ID\n            \"pic\": \"\",          //头像\n            \"name\": \"陈鸣芳\",    //姓名\n            \"age\": null,        //年龄\n            \"work_time\": 3      //工作年限\n        },\n        {\n            \"id\": \"1674\",\n            \"pic\": \"\",\n            \"name\": \"杨东洋\",\n            \"age\": null,\n            \"work_time\": null\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/coach-list?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&page=2&per-page=2\"\n        },\n        \"first\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/coach-list?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&page=1&per-page=2\"\n        },\n        \"prev\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/coach-list?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&page=1&per-page=2\"\n        },\n        \"next\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/coach-list?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&page=3&per-page=2\"\n        },\n        \"last\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/coach-list?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&page=9&per-page=2\"\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": 236,      //总数\n        \"pageCount\": 118,       //总页数\n        \"currentPage\": 2,       //当前页\n        \"perPage\": 2            //每页显示数\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ManageController.php",
    "groupTitle": "manage"
  },
  {
    "type": "get",
    "url": "/coach/v1/manage/sale&accesstoken=666",
    "title": "业绩报表",
    "version": "1.0.0",
    "name": "_____",
    "group": "manage",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/manage/sale?accesstoken=666\n{\n     \"start\":1501639313              //开始日期(unix时间戳)\n     \"end\":1504108800                //结束日期(unix时间戳)\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>业绩报表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/manage/sale?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"data\": [\n        {\n            \"coach_name\": \"冯帅旗\",              //教练姓名\n            \"name\": \"袁帅\",                      //会员姓名\n            \"status\": \"成交\",                    //状态\n            \"product_name\": \"MFT格斗健身课程\",    //课程名称\n            \"course_amount\": 10,                //课程节数\n            \"money_amount\": \"3300.00\"           //成交金额\n        },\n        {\n            \"coach_name\": \"冯帅旗\",\n            \"name\": \"李文慧\",\n            \"status\": \"成交\",\n            \"product_name\": \"PT常规课\",\n            \"course_amount\": 5,\n            \"money_amount\": \"1450.00\"\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/sale?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=1\"\n        },\n        \"next\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/sale?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=2\"\n        },\n        \"last\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/sale?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=658\"\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": 1315,\n        \"pageCount\": 658,\n        \"currentPage\": 1,\n        \"perPage\": 2\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ManageController.php",
    "groupTitle": "manage"
  },
  {
    "type": "get",
    "url": "/coach/v1/manage/table&accesstoken=666",
    "title": "上课量报表",
    "version": "1.0.0",
    "name": "______",
    "group": "manage",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/manage/table?accesstoken=666\n{\n     \"start\":1501639313              //开始日期(unix时间戳)\n     \"end\":1504108800                //结束日期(unix时间戳)\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>上课量报表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/manage/table?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"data\": [\n        {\n            \"coach_name\": \"杨东洋\",        //教练姓名\n            \"name\": \"张章云\",              //会员姓名\n            \"mobile\": \"13803837888\",      //会员手机号\n            \"num\": \"1\"                    //上课数量\n        },\n        {\n            \"coach_name\": \"李乾坤\",\n            \"name\": \"康伟\",\n            \"mobile\": \"18530010725\",\n            \"num\": \"1\"\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/table?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=1\"\n        },\n        \"next\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/table?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=2\"\n        },\n        \"last\": {\n            \"href\": \"http://127.0.0.3/coach/v1/manage/table?accesstoken=pcWRga4CUeK6mDZMEYqN90EM8u5TjqLL_1514095464&start=1&end=12000000000&per-page=2&page=138\"\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": \"275\",\n        \"pageCount\": 138,\n        \"currentPage\": 1,\n        \"perPage\": 2\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ManageController.php",
    "groupTitle": "manage"
  },
  {
    "type": "get",
    "url": "/coach/v1/manage/count&accesstoken=666",
    "title": "上课量统计",
    "version": "1.0.0",
    "name": "_______",
    "group": "manage",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/manage/count?accesstoken=666\n{\n     \"type\":y              //日期类别：d 日、w 周 、m 月 、s 季度 、y 年\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练上课量统计 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/manage/count?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n   \"code\": 1,                                       //成功返回标识\n   \"status\": \"success\",                             //成功返回标识\n   \"message\": \"\",\n   \"data\": [\n       {\n           \"name\": \"唐成\",                  //教练姓名\n           \"num\": \"20\"                     //上课节数\n       },\n       {\n           \"name\": \"刘琳娜\",\n           \"num\": \"10\"\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ManageController.php",
    "groupTitle": "manage"
  },
  {
    "type": "get",
    "url": "/coach/v1/manage/assign?accesstoken=666",
    "title": "分配私教",
    "version": "1.0.0",
    "name": "__________",
    "group": "manage",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/manage/assign?accesstoken=666\n{\n     \"member_id\":90117              //会员ID\n     \"coach_id\":1812                //教练ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>分配私教 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/manage/assign?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"分配成功\",\n    \"data\": \"\"\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"私教不存在\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/ManageController.php",
    "groupTitle": "manage"
  },
  {
    "type": "get",
    "url": "/coach/v1/member/index?accesstoken=666",
    "title": "会员列表",
    "version": "1.0.0",
    "name": "____",
    "group": "member",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/member/index?accesstoken=666\n{\n     \"manage\":0                //(可选)团队会员列表:/coach/v1/member/index?accesstoken=666&manage=1\n     \"per-page\":2              //每页显示数，默认20\n     \"page\":2                  //第几页\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员列表 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/13</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/member/index?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\":1,\n    \"data\": [\n        {\n            \"id\": \"48324\",                         //会员ID\n            \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/20700255.JPG?e=1501923581&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:E1PpeGYJWxXfBO8aQzrujopiZ-4=\",//头像\n            \"name\": \"马婷婷\",                      //姓名\n            \"sex\": \"女\",                          //性别\n            \"age\": null,                          //年龄\n            \"mobile\": \"13783592200\",              //手机号码\n            \"id_card\": \"413026198606210029\"       //身份证号\n        },\n        {\n            \"id\": \"50060\",\n            \"pic\": null,\n            \"name\": \"催佳慧\",\n            \"sex\": \"女\",\n            \"age\": null,\n            \"mobile\": \"13837134704\",\n            \"id_card\": null\n        }\n    ],\n    \"_links\": {\n        \"self\": {\n            \"href\": \"http://127.0.0.3/coach/v1/member/index?accesstoken=9Bjx_a8hxzh4DKeQzqrscp3h5g6TeL8Y&per-page=2&page=11\"\n        },\n        \"first\": {\n            \"href\": \"http://127.0.0.3/coach/v1/member/index?accesstoken=9Bjx_a8hxzh4DKeQzqrscp3h5g6TeL8Y&per-page=2&page=1\"\n        },\n        \"prev\": {\n            \"href\": \"http://127.0.0.3/coach/v1/member/index?accesstoken=9Bjx_a8hxzh4DKeQzqrscp3h5g6TeL8Y&per-page=2&page=10\"\n        },\n        \"next\": {\n            \"href\": \"http://127.0.0.3/coach/v1/member/index?accesstoken=9Bjx_a8hxzh4DKeQzqrscp3h5g6TeL8Y&per-page=2&page=12\"\n        },\n        \"last\": {\n            \"href\": \"http://127.0.0.3/coach/v1/member/index?accesstoken=9Bjx_a8hxzh4DKeQzqrscp3h5g6TeL8Y&per-page=2&page=12\"\n        }\n    },\n    \"_meta\": {\n        \"totalCount\": 24,\n        \"pageCount\": 12,\n        \"currentPage\": 11,\n        \"perPage\": 2\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/coach/v1/member/view?accesstoken=666",
    "title": "会员详情",
    "version": "1.0.0",
    "name": "_____",
    "group": "member",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/member/view?accesstoken=666\n{\n       \"id\":\"48324\"，             //会员ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员详情 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/13</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/member/view?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"id\": \"48324\",                         //会员ID\n        \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/20700255.JPG?e=1501923581&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:E1PpeGYJWxXfBO8aQzrujopiZ-4=\",//头像\n        \"name\": \"马婷婷\",                      //姓名\n        \"sex\": \"女\",                          //性别\n        \"age\": null,                          //年龄\n        \"mobile\": \"13783592200\",              //手机号码\n        \"id_card\": \"413026198606210029\"       //身份证号\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/coach/v1/member/course-list?id=92447&accesstoken=666",
    "title": "私教列表",
    "version": "1.0.0",
    "name": "______",
    "group": "member",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/member/course-list?id=92447&accesstoken=666\n{\n       \"id\":\"30018\"，             //会员ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>私教列表 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsports.club <span><strong>创建时间：</strong></span>2017/12/14</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1//member/course-list?id=92447&accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n   \"code\": 1,\n   \"status\": \"success\",\n   \"message\": \"\",\n   \"data\": [\n       {\n       \"id\": \"50744\",                 //私教课订单id\n       \"product_name\": \"PT常规课\",    //私教课程名字\n       \"overage_section\": \"0\"         //剩余节数\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                           //失败表示\n    \"status\": \"error\",                   //请求状态\n    \"message\": \"该会员还没有购买课程\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/coach/v1/member/course-info?id=50744&accesstoken=666",
    "title": "私教详情",
    "version": "1.0.0",
    "name": "__________",
    "group": "member",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "  GET /coach/v1/member/course-info?id=50744&accesstoken=666\n{\n       \"id\":\"50744\"，             //私教订单ID\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>私教详情 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsports.club <span><strong>创建时间：</strong></span>2017/12/14</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1//member/course-info?id=50744&accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n   \"code\": 1,\n   \"status\": \"success\",\n   \"message\": \"\",\n   \"data\": {\n       \"product_name\": \"PT常规课\",    //课程名称\n       \"seller\": \"邹恒\",              //卖课教练\n       \"buy_time\": \"1494547200\",      //卖课时间\n       \"money_amount\": \"0.00\",        //办理金额\n       \"class_length\": 60,            //课程时长\n       \"course_amount\": 3,            //总节数\n       \"overage_section\": \"0\",        //剩余节数\n       \"invalid_time\": \"1497484800\"   //到期日期\n   }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                        //失败表示\n    \"status\": \"error\",                //请求状态\n    \"message\": \"无此课程\"             //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/coach/v1/message/index?accesstoken=666",
    "title": "消息首页",
    "version": "1.0.0",
    "name": "____",
    "group": "message",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/message/index?accesstoken=666",
          "type": "json"
        }
      ]
    },
    "description": "<p>消息首页 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/13</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/message/index?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"about\": {\n            \"count\": 0,//未读预约消息数\n            \"content\": \"\",//预约消息\n            \"time\": \"\"\n        },\n        \"cancel\": {\n            \"count\": 24,//未读取消消息数\n            \"content\": \"崔佳慧取消了11月25日 16:20的PT常规课\",//取消消息\n            \"time\": \"16:20\"\n        },\n        \"leave\": {\n            \"count\": 12,//未读请假消息数\n            \"content\": \"冯敏于11月29日 12:47请假\",//请假消息\n            \"time\": \"12:47\"\n        }\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MessageController.php",
    "groupTitle": "message"
  },
  {
    "type": "get",
    "url": "/coach/v1/message/count?accesstoken=666",
    "title": "未读消息数",
    "version": "1.0.0",
    "name": "_____",
    "group": "message",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/message/count?accesstoken=666",
          "type": "json"
        }
      ]
    },
    "description": "<p>未读消息数 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/14</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/message/count?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"count\": 36 //未读消息数\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/MessageController.php",
    "groupTitle": "message"
  },
  {
    "type": "post",
    "url": "/coach/v1/site/login",
    "title": "私教登录",
    "version": "1.0.0",
    "name": "____",
    "group": "site",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/site/login\n{\n     \"mobile\":\"17796655023\"，   //手机号码\n     \"password\":\"123456\"        //密码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>私教登录 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/8</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/site/login"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\":1,               //成功标识\n \"status\": \"success\",    //请求状态\n \"message\": \"请求成功\"，  //返回信息\n \"data\": {\n     \"accesstoken\": \"Z1MaRW6N2zmu2FrnebBHhU_zVTHU_Qov\", //accesstoken,登录后的操作每次请求都需要加上?accesstoken=Z1MaRW6N2zmu2FrnebBHhU_zVTHU_Qov\n     \"manage\": true                                     //是否私教经理\n    }\n  }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"手机号码或密码错误.;\"  //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/SiteController.php",
    "groupTitle": "site"
  },
  {
    "type": "post",
    "url": "/coach/v1/site/login-code",
    "title": "验证码登录",
    "version": "1.0.0",
    "name": "_____",
    "group": "site",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/site/login\n{\n     \"mobile\":\"17796655023\"，   //手机号码\n     \"code\":\"123456\"            //验证码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>验证码登录 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/8</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/site/login-code"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\":1,               //成功标识\n \"status\": \"success\",    //请求状态\n \"message\": \"请求成功\"，  //返回信息\n \"data\": {\n     \"accesstoken\": \"Z1MaRW6N2zmu2FrnebBHhU_zVTHU_Qov\", //accesstoken\n     \"manage\": true                                     //是否私教经理\n    }\n  }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"手机号码不存在.;\"  //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/SiteController.php",
    "groupTitle": "site"
  },
  {
    "type": "post",
    "url": "/coach/v1/site/find-pwd",
    "title": "找回密码",
    "version": "1.0.0",
    "name": "________",
    "group": "site",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "newpwd",
            "description": "<p>新密码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/site/find-pwd\n{\n     \"mobile\":\"17796655023\"，   //手机号码\n     \"code\":\"123456\",           //验证码\n     \"newpwd\":\"123456\"          //新密码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>找回密码找回密码 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/8</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/site/find-pwd"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\":1,               //成功标识\n \"status\": \"success\",    //请求状态\n \"message\": \"请求成功\"，  //返回信息\n \"data\": {\n     \"accesstoken\": \"Z1MaRW6N2zmu2FrnebBHhU_zVTHU_Qov\", //accesstoken\n     \"manage\": true                                     //是否私教经理\n    }\n  }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"手机号码不存在\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/SiteController.php",
    "groupTitle": "site"
  },
  {
    "type": "post",
    "url": "/coach/v1/user/send-advice?accesstoken=123",
    "title": "提交建议",
    "version": "1.0.0",
    "name": "____",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>建议内容</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/user/send-advice?accesstoken=123\n{\n     \"content\":\"对酒当歌\"，         //建议内容\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>生成验证码 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/send-advice?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\": 1,               //成功返回标识\n \"status\": \"success\",     //成功返回标识\n \"message\": \"\",\n \"data\": \"提交成功\"       //成功返回标识\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"\",\n \"data\": \"提交失败\"       //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/reset-password?accesstoken=123",
    "title": "重置密码",
    "version": "1.0.0",
    "name": "____",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>旧密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "rePassword",
            "description": "<p>新密码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/user/reset-password?accesstoken=123\n{\n     \"mobile\":\"15078796678\"，         //手机\n     \"code\":\"123456\"，                //验证码 \n     \"password\":\"******\",             //旧密码\n     \"rePressword\":\"******\"，         //新密码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>重置密码 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/reset-password?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\": 1,               //成功返回标识\n \"status\": \"success\",     //成功返回标识\n \"message\": \"\",\n \"data\": \"重置密码成功\"   //成功返回标识\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "｛\n  \"code\": 0,              //失败标识\n  \"status\": \"error\",      //失败标识\n  \"message\": \"旧密码错误\" //错误描述\n｝",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/sell-list?type=d&accesstoken=123_1514736000",
    "title": "卖课排行榜",
    "version": "1.0.0",
    "name": "_____",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>日期类别：d 日、w 周 、m 月 、s 季度 、y 年</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/sell-list?type=d&accesstoken=123_1514736000",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练上课量报表 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/sell-list?type=d&accesstoken=123_1514736000"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n   \"code\": 1,                    //成功返回标识\n   \"status\": \"success\",          //成功返回标识\n   \"message\": \"\",\n   \"data\": [\n     {\n      \"name\": \"张双利\",          //私教\n      \"member_number\": \"1\",      //会员量\n      \"course_number\": \"25\",     //卖课节数\n      \"total_money\": \"3000.00\"   //成交金额\n     },\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/coach-achievement?start=1483200000&end=1514735999&accesstoken=123_1514736000",
    "title": "教练业绩报表",
    "version": "1.0.0",
    "name": "______",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start",
            "description": "<p>筛选开始时间(unix时间戳)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end",
            "description": "<p>筛选截止时间(unix时间戳)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/coach-achievement?start=1483200000&end=1514735999&accesstoken=123_1514736000",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练业绩报表 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/coach-achievement?start=1483200000&end=1514735999&accesstoken=123_1514736000"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n   \"code\": 1,                    //成功返回标识\n   \"status\": \"success\",          //成功返回标识\n   \"message\": \"\",\n   \"data\": [\n     {\n      \"username\": \"范留芙\",        //会员姓名\n      \"product_name\": \"PT游泳课\",  //课程名称\n      \"course_amount\": \"25\",       //卖课节数\n       \"unit_price\": \"400\",        //课时费\n      \"money_amount\": \"10000.00\"   //成交金额\n     },\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/get-my-profile?accesstoken=123",
    "title": "私教端我的接口",
    "version": "1.0.0",
    "name": "_______",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/get-my-profile?accesstoken=123",
          "type": "json"
        }
      ]
    },
    "description": "<p>私教端我的接口 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/get-my-profile?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n    \"code\": 1,\n    \"status\": \"success\",\n    \"message\": \"\",\n    \"data\": {\n        \"name\": \"唐成\",\n        \"password\": \"$2y$13$67W3A.kyzKQNpqhE8vl0q.9rY3FBeCq4Jshi9.HWt/zX3WStTc0kK\",\n        \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/fe54addc64bc996b03f0533828b156d3.jpg?e=1513244095&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:dC_n5LTESJ2T9iodIqENDYe3TDM=\",\n        \"signature\": \"依依东望\",\n        \"nickname\": \"司马懿\",\n        \"venue\": \"艾搏尊爵汇馆\"\n    }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "｛\n  \"code\": 0,             //失败标识\n  \"status\": \"error\",     //失败标识\n  \"message\": \"数据错误\", //失败信息\n  \"data\": \"\"             //空值\n｝",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/get-advice?accesstoken=123",
    "title": "获取提交的建议",
    "version": "1.0.0",
    "name": "_______",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/get-advice?accesstoken=123",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取提交的建议 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/get-advice?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\": 1,               //成功返回标识\n \"status\": \"success\",     //成功返回标识\n \"message\": \"\",\n \"data\": \"虎啸龙吟\"       //建议内容\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"\",\n \"data\": \"暂无数据\"       //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/take-num?type=m&accesstoken=123_1514736000",
    "title": "教练上课量统计",
    "version": "1.0.0",
    "name": "________",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>日期类别：d 日、w 周 、m 月 、s 季度 、y 年</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/take-num?type=m&accesstoken=123_1514736000",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练上课量统计 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/take-num?type=m&accesstoken=123_1514736000"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n   \"code\": 1,                                       //成功返回标识\n   \"status\": \"success\",                             //成功返回标识\n   \"message\": \"\",\n   \"data\": [\n       {\n           \"product_name\": \"0元WD游泳课程2A\",       //课程名字\n           \"num\": \"20\"                              //上课节数\n       },\n       {\n           \"product_name\": \"PT游泳课\",             //课程名字\n           \"num\": \"10\"                             //上课节数\n       }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "POST",
    "url": "/coach/v1/user/update-profile?accesstoken=123",
    "title": "更新昵称、个人签名",
    "version": "1.0.0",
    "name": "_________",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "nickname",
            "description": "<p>昵称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "signature",
            "description": "<p>签名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /coach/v1/user/update-profile?accesstoken=123\n{\n     \"nickname\" :\"糖糖\"，             //昵称\n     \"signature\":\"平安\",             //签名\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>更新昵称、个人签名 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/update-profile?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n \"code\": 1,               //成功返回标识\n \"status\": \"success\",     //成功返回标识\n \"message\": \"\",\n \"data\": \"成功  \"         //成功返回信息\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "｛\n  \"code\": 0,             //失败标识\n  \"status\": \"error\",     //失败标识\n  \"message\": \"\",\n  \"data\": \"数据错误\"     //失败信息\n｝",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "post",
    "url": "/coach/v1/user/upload-pic?accesstoken=123",
    "title": "上传头像",
    "version": "1.0.0",
    "name": "__________",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "file",
            "description": "<p>头像</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数(accesstoken)",
          "content": "POST /coach/v1/user/upload-pic?accesstoken=123\n{\n     \"file\":\"f27f12d5baccd71fea60daa8420618f6.jpg\"，    //头像\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>上传头像 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/upload-pic?accesstoken=123"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n     \"code\":1，                      //成功返回标识\n     \"status\":\"success\"，            //成功返回标识\n     \"message\":\"\"，\n     \"data\":\"http://oo0oj2qmr.bkt.clouddn.com/50d639171566a619a2119f33968a232c.jpg?e=1513148802&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:4fTTQVy1lNcyv_kry_Ccpmd7hM0=\"，       //头像地址\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "｛\n  \"code\": 0,             //失败标识\n  \"status\": \"error\",     //失败标识\n  \"message\": \"上传失败\", //失败信息\n  \"data\": \"\"             //空值\n｝",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/user/token-course-list?start=1483200000&end=1514735999&accesstoken=123_1514736000",
    "title": "教练上课量报表",
    "version": "1.0.0",
    "name": "________________",
    "group": "user",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "accesstoken",
            "description": "<p>获准许可</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start",
            "description": "<p>筛选开始时间(unix时间戳)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end",
            "description": "<p>筛选截止时间(unix时间戳)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/user/token-course-list?start=1483200000&end=1514735999&accesstoken=123_1514736000",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练上课量报表 <br/> <span><strong>作    者：</strong></span>焦冰洋<br/> <span><strong>邮    箱：</strong></span>jiaobingyang@itsprts.club <span><strong>创建时间：</strong></span>2017/12/15</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/coach/v1/user/token-course-list?start=1483200000&end=1514735999&accesstoken=123_1514736000"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "{\n   \"code\": 1,                                       //成功返回标识\n   \"status\": \"success\",                             //成功返回标识\n   \"message\": \"\",\n   \"data\": [\n     {\n      \"username\": \"张双利\",\n      \"mobile\": \"13526800271\",\n      \"token_num\": \"8\",\n      \"total_money\": \"3000.00\"\n     },\n   ]\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n \"code\": 0,               //失败返回标识\n \"status\": \"error\",       //失败返回标识\n \"message\": \"暂无数据\",   //失败返回标识\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/coach/v1/work/index?accesstoken=666",
    "title": "工作首页",
    "version": "1.0.0",
    "name": "________",
    "group": "work",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "请求参数",
          "content": "GET /coach/v1/work/index?accesstoken=666",
          "type": "json"
        }
      ]
    },
    "description": "<p>工作首页工作首页 <br/> <span><strong>作    者：</strong></span>张晓兵<br/> <span><strong>邮    箱：</strong></span>zhangxiaobing@itsprts.club <span><strong>创建时间：</strong></span>2017/12/12</p>",
    "sampleRequest": [
      {
        "url": "http://apiqa.aixingfu.net/coach/v1/work/index?accesstoken=666"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "返回值详情（成功）",
          "content": "\n{\n   \"code\": 1,\n   \"status\": \"success\",\n   \"message\": \"\",\n   \"data\": {\n       \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/5263061513056966.png?e=1513060566&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:0_9FCxb1L5iTVP9h0wqy0ZqTet0=\",//banner图片\n       \"news\": [                                             //滚动消息\n                {\n                    \"id\": \"37775\",                                  //上课ID\n                    \"content\": \"邵乐石预约了12月07日15:30的PT常规课\"   //内容\n                },\n                {\n                    \"id\": \"37044\",\n                    \"content\": \"赵媛媛预约了12月06日19:04的PT常规课\"\n                },\n                {\n                    \"id\": \"36780\",\n                    \"content\": \"邵乐石预约了12月06日16:26的PT常规课\"\n                }\n       ],\n       \"num\": {\n           \"total\": 23,                             //总人数\n           \"man\": 4,                                //男\n          \"woman\": 18                               //女\n       }\n   }\n}",
          "type": "json"
        },
        {
          "title": "返回值详情（失败）",
          "content": "{\n    \"code\": 0,                   //失败表示\n    \"status\": \"error\",           //请求状态\n    \"message\": \"\"    //失败原因\n}",
          "type": "json"
        }
      ]
    },
    "filename": "api/modules/coach/v1/controllers/WorkController.php",
    "groupTitle": "work"
  }
] });
