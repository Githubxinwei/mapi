define({ "api": [
  {
    "type": "get",
    "url": "/authority/update-status",
    "title": "品牌名称 - 停用",
    "version": "1.0.0",
    "name": "_______",
    "group": "Authority",
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
            "type": "int",
            "optional": false,
            "field": "organId",
            "description": "<p>品牌名称id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"organId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>品牌名称-停用<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/authority/update-status</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/update-status"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"修改失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/get-auth",
    "title": "获取权限列表信息",
    "version": "1.0.0",
    "name": "________",
    "group": "Authority",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>权限管理 - 获取权限列表信息<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/authority/get-auth</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/get-auth"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'name' => '业务水吧管理员'\n'company_id' => '2'\n'oname' => '我爱运动'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/get-company",
    "title": "权限管理 - 列表",
    "version": "1.0.0",
    "name": "_________",
    "group": "Authority",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>权限管理 - 列表<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/5<br/> <span><strong>调用方法：</strong></span>/authority/get-company</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/get-company"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"1\",\"name\":\"我爱运动\"}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/auth-role",
    "title": "获取权限模板下拉列表",
    "version": "1.0.0",
    "name": "__________",
    "group": "Authority",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>权限设置 - 获取权限模板下拉列表<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/authority/auth-role</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/auth-role"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"1\",\"role_id\":\"2\",\"name\":\"迈步会务权限\",\"orgName\": \"我爱运动\",}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/get-brand",
    "title": "关联其他品牌-获取公司",
    "version": "1.0.0",
    "name": "___________",
    "group": "Authority",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>关联其他品牌-获取公司<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/authority/get-brand</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/get-brand"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"1\",\"name\":\"我爱运动\"}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "post",
    "url": "/authority/auth-brand",
    "title": "保存全局权限设置",
    "version": "1.0.0",
    "name": "_____________",
    "group": "Authority",
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
            "type": "int",
            "optional": false,
            "field": "roleId",
            "description": "<p>角色ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "syncRoleId",
            "description": "<p>同步角色ID</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "authId",
            "description": "<p>顶级模块ID</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "moduleId",
            "description": "<p>模块ID</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "modFuncId",
            "description": "<p>功能ID</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "companyId",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"roleId\": \"2\",\n     \"syncRoleId\": \"3\",\n     \"authId\": \"[\"1\",\"2\",\"3\"]\",\n     \"moduleId\": \"[\"1\",\"2\",\"3\"]\",\n     \"modFuncId\": \"[\"1\",\"2\",\"3\"]\",\n     \"companyId\": \"[\"1\",\"2\",\"3\"]\",\n     \"venueId\": \"[\"1\",\"2\",\"3\"]\",\n     \"_csrf_backend\":\"\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>权限设置 - 保存全局权限设置<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/6<br> <span><strong>调用方法：</strong></span>/authority/auth-brand</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/auth-brand"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"保存成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"保存失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/get-auth-by-role",
    "title": "权限设置-获取角色已有权限",
    "version": "1.0.0",
    "name": "_____________",
    "group": "Authority",
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
            "type": "int",
            "optional": false,
            "field": "roleId",
            "description": "<p>角色id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"roleId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>权限设置-获取角色已有权限<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/authority/get-auth-by-role</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/get-auth-by-role"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{id: \"2\", role_id: \"6\", create_id: \"1\", create_at: \"1498190778\",…}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "get",
    "url": "/authority/get-venue",
    "title": "关联其他品牌-获取公司下的场馆",
    "version": "1.0.0",
    "name": "_______________",
    "group": "Authority",
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
            "type": "int",
            "optional": false,
            "field": "companyId",
            "description": "<p>公司id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"companyId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取公司下的场馆<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/5<br/> <span><strong>调用方法：</strong></span>/authority/get-venue</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/authority/get-venue"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"8\",\"name\":\"大上海\"}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/AuthorityController.php",
    "groupTitle": "Authority"
  },
  {
    "type": "post",
    "url": "/cabinet/add-venue-cabinet",
    "title": "新增柜子",
    "version": "1.0.0",
    "name": "____",
    "group": "Cabinet",
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
            "type": "int",
            "optional": false,
            "field": "organizationId",
            "description": "<p>场馆id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cabinetTypeId",
            "description": "<p>柜子类型id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetNum",
            "description": "<p>柜子数量</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "dayRentPrice",
            "description": "<p>日租用价格</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "monthRentPrice",
            "description": "<p>月租用价格</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "halfYearRentPrice",
            "description": "<p>半年租价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "yearRentPrice",
            "description": "<p>一年租价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetModel",
            "description": "<p>柜子型号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetType",
            "description": "<p>柜子类别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/add-venue-cabinet\n{\n      organizationId  :12,   // 场馆id\n      cabinetTypeId  :12    //柜子类型id\n      cabinetNum：12        //柜子数量\n      dayRentPrice：300    //日租价\n      monthRentPrice：700  //月租价\n      halfYearRentPrice：1100 //半年租价\n      yearRentPrice：2800    //一年租价\n      _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>新增柜子 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/add-venue-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //添加成功状态\n      \"data\":“添加成功”    //添加成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //添加失败\n     'data'=>“绑定失败”   //添加失败信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "Cabinet"
  },
  {
    "type": "post",
    "url": "/cabinet/add-cabinet-type",
    "title": "新增柜子类型",
    "version": "1.0.0",
    "name": "______",
    "group": "Cabinet",
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
            "field": "cabinetTypeName",
            "description": "<p>柜子类型名字</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /cabinet/add-cabinet-type\n{\n      cabinetTypeName  :男大柜   //柜子类型名字\n      _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>新增柜子类型区域 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/8</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/add-cabinet-type"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //添加成功状态\n      \"data\":“添加成功”    //添加成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //添加失败\n     'data'=>“绑定失败”   //添加失败信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "Cabinet"
  },
  {
    "type": "post",
    "url": "/cabinet/bind-member",
    "title": "柜子绑定会员",
    "version": "1.0.0",
    "name": "______",
    "group": "Cabinet",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetRentStart",
            "description": "<p>柜子起租日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetRentEnd",
            "description": "<p>柜子到期日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "price",
            "description": "<p>租用价格</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetId",
            "description": "<p>柜子id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/renew-cabinet\n{\n      memberId  :12,   //会员id\n     cabinetRentStart  :2017-3-15 //起租日期\n     cabinetRentEnd：2017-3-29   //到期日期\n     price：300 //续组价格\n     cabinetId：//柜子id\n     _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>给柜子绑定会员 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/bind-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //绑定成功状态\n      \"data\":“绑定成功信息” //绑定成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //绑定失败\n     'data'=>“绑定失败”   //绑定失败\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "Cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/get-all-cabinet",
    "title": "获取所有未租柜子",
    "version": "1.0.0",
    "name": "________",
    "group": "Cabinet",
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
            "field": "typeId",
            "description": "<p>柜子类型id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/get-all-cabinet\n{\n     typeId:13,     //柜子类型id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取所有未租柜子 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/get-all-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'id':126,                    //柜子id\n      \"cabinet_number\":11111111 ， //柜子编号\n      \"dayRentPrice\":12           //日租金\n      \"monthRentPrice\":12          //月租金\n      \"halfYearRentPrice\":12       //半年租金\n      \"yearRentPrice\":12          //月租金\n      \"cabinet_model\":1           //柜子类型：(1:大柜2:中柜3:小柜)\n\t     \"cabinet_type\":1\t\t    //\t柜子类别：(1:临时2:正式)\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "Cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/search-member",
    "title": "手机号搜索会员信息",
    "version": "1.0.0",
    "name": "_________",
    "group": "Cabinet",
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
            "field": "phone",
            "description": "<p>手机号</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/search-member\n{\n     phone:15537312038,     //手机号\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>手机号搜索会员信息 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/search-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'id':126,               //会员ID\n      \"name\":666 ，       //会员姓名\n      \"mobile\":15537312038  //会员电话\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "Cabinet"
  },
  {
    "type": "post",
    "url": "/corporate-alliance/apply",
    "title": "公司联盟-申请通店",
    "version": "1.0.0",
    "name": "____",
    "group": "CorporateAlliance",
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
            "field": "beApply",
            "description": "<p>申请公司</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startApply",
            "description": "<p>通店开始日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endApply",
            "description": "<p>通店结束日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"beApply\": \"我爱运动\",\n     \"startApply\": \"2017-06-27\",\n     \"endApply\": \"2017-08-27\",\n     \"_csrf_backend\":\"\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>公司联盟 - 申请通店<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/27<br> <span><strong>调用方法：</strong></span>/corporate-alliance/apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"申请成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"申请失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "CorporateAlliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/apply-details",
    "title": "获取通店详情",
    "version": "1.0.0",
    "name": "______",
    "group": "CorporateAlliance",
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
            "type": "int",
            "optional": false,
            "field": "applyRecordId",
            "description": "<p>申请记录id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"applyRecordId\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取通店详情<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/28<br/> <span><strong>调用方法：</strong></span>/corporate-alliance/apply-details</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/apply-details"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n  'name' => '我爱运动'\n  'status' => '2'\n  'start_apply' => '1496472588'\n  'end_apply' => '1496572588'\n   'note' => '申请通店'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "CorporateAlliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/get-apply",
    "title": "获取申请列表信息",
    "version": "1.0.0",
    "name": "________",
    "group": "CorporateAlliance",
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
            "field": "keywords",
            "description": "<p>搜索的字段</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"keywords\": \"我爱运动\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取申请列表信息<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/27<br/> <span><strong>调用方法：</strong></span>/corporate-alliance/get-apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/get-apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n  'name' => '我爱运动'\n  'status' => '2'\n  'start_apply' => '1496472588'\n  'end_apply' => '1496572588'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "CorporateAlliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/overdue-apply",
    "title": "公司联盟-过期",
    "version": "1.0.0",
    "name": "__",
    "group": "Corporate_Alliance",
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
            "field": "applyRecordId",
            "description": "<p>申请记录id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"applyRecordId\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>公司联盟-过期<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/30<br> <span><strong>调用方法：</strong></span>/corporate-alliance/overdue-apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/overdue-apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"申请已过期\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"操作失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/cancel-apply",
    "title": "公司联盟-取消申请",
    "version": "1.0.0",
    "name": "____",
    "group": "Corporate_Alliance",
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
            "field": "applyRecordId",
            "description": "<p>申请记录id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"applyRecordId\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>公司联盟-取消申请<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/27<br> <span><strong>调用方法：</strong></span>/corporate-alliance/cancel-apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/cancel-apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"取消成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"取消失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/apply-record",
    "title": "获取申请记录",
    "version": "1.0.0",
    "name": "______",
    "group": "Corporate_Alliance",
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
            "type": "int",
            "optional": false,
            "field": "applyRecordId",
            "description": "<p>申请记录id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"applyRecordId\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>公司联盟-获取申请记录<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/28<br/> <span><strong>调用方法：</strong></span>/corporate-alliance/apply-record</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/apply-record"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n  'name' => '我爱运动'\n  'status' => '2'\n  'start_apply' => '1496472588'\n  'end_apply' => '1496572588'\n   'note' => '申请通店'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/pending",
    "title": "待处理数据条数",
    "version": "1.0.0",
    "name": "_______",
    "group": "Corporate_Alliance",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>待处理数据条数<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/03<br/> <span><strong>调用方法：</strong></span>/corporate-alliance/pending</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/pending"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\":\"2\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\":\"\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/get-company",
    "title": "获取查询品牌名称",
    "version": "1.0.0",
    "name": "________",
    "group": "Corporate_Alliance",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取查询品牌名称<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/29<br/> <span><strong>调用方法：</strong></span>/corporate-alliance/get-company</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/get-company"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n    \"data\":{\n      'id' => '1'\n      'name' => '我爱运动'\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\":{}\"}}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/corporate-alliance/pass-apply",
    "title": "公司联盟-通过申请",
    "version": "1.0.0",
    "name": "_________",
    "group": "Corporate_Alliance",
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
            "field": "applyRecordId",
            "description": "<p>申请记录id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"applyRecordId\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>通过申请<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/27<br> <span><strong>调用方法：</strong></span>/corporate-alliance/pass-apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/pass-apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"通过成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"通过失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "post",
    "url": "/corporate-alliance/not-pass-apply",
    "title": "公司联盟-拒绝申请",
    "version": "1.0.0",
    "name": "___________",
    "group": "Corporate_Alliance",
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
            "field": "recordId",
            "description": "<p>申请记录id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "notApplyLength",
            "description": "<p>不可申请时长</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"recordId\": \"2\",\n     \"notApplyLength\": \"30\",\n     \"note\": \"很抱歉\",\n     \"_csrf_backend\":\"\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>公司联盟 - 拒绝申请<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/27<br> <span><strong>调用方法：</strong></span>/corporate-alliance/not-pass-apply</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/corporate-alliance/not-pass-apply"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"拒绝成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"拒绝失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CorporateAllianceController.php",
    "groupTitle": "Corporate_Alliance"
  },
  {
    "type": "get",
    "url": "/contract/deal",
    "title": "*合同管理 增加帅选功能",
    "version": "1.0.0",
    "name": "__________________",
    "group": "Deal",
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
            "type": "int",
            "optional": false,
            "field": "nowVenueId",
            "description": "<p>场馆id （新增参数）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "nowCompanyId",
            "description": "<p>公司id   （新增参数）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "get  /contract/deal\n{\n     \"nowVenueId \"=>12，      //场馆id （新增）\n     \"nowCompanyId\"=>17,      //公司id  （新增）\n}\n{get} /contract/deal",
          "type": "json"
        }
      ]
    },
    "description": "<p>合同管理 增加帅选功能，按照公司和场馆 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/15</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/contract/deal"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>保存状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回保存状态的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/ContractController.php",
    "groupTitle": "Deal"
  },
  {
    "type": "get",
    "url": "/purchase-card/get-member-by-id",
    "title": "获取会员",
    "version": "1.0.0",
    "name": "____",
    "group": "GetMember",
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
            "optional": false,
            "field": "venueId",
            "description": "<p>int 场馆ID</p>"
          }
        ]
      }
    },
    "description": "<p>售卡管理 - 获取场馆售卖的卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/15<br> <span><strong>调用方法：</strong></span>/purchase-card/get-member-by-id</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/purchase-card/get-member-by-id"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "\n{\"id\": \"1\",\n  \"username\": \"lihua\",\n   \"password\" : '123456',\n   \"mobile\"   : '15345679876',\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PurchaseCardController.php",
    "groupTitle": "GetMember"
  },
  {
    "type": "get",
    "url": "/rechargeable-card-ctrl/get-the-goods",
    "title": "指定类别商品",
    "version": "1.0.0",
    "name": "______",
    "group": "GoodsType",
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
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>商品类型id</p>"
          }
        ]
      }
    },
    "description": "<p>获取指定类别商品 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/7/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/rechargeable-card-ctrl/get-the-goods"
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
            "description": "<p>返回请求成功数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n 'id:\"商品id\"\n 'goods_name':\"商品名称,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "GoodsType"
  },
  {
    "type": "get",
    "url": "/rechargeable-card-ctrl/get-donation-type",
    "title": "指定场馆商品类型",
    "version": "1.0.0",
    "name": "________",
    "group": "GoodsType",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取指定场馆商品类型 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/rechargeable-card-ctrl/get-donation-type"
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
            "description": "<p>返回请求成功数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n 'id':\"商品类型id\",\n 'goods_type':\"商品类别名称\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "GoodsType"
  },
  {
    "type": "get",
    "url": "/jurisdiction/get-module-functional-data-all",
    "title": "权限设置 - 获取所有权限",
    "version": "1.0.0",
    "name": "_____________",
    "group": "Jurisdiction",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>权限设置 - 获取所有权限<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/jurisdiction/get-module-functional-data-all</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/jurisdiction/get-module-functional-data-all"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{id: \"1\", name: \"系统首页\", module: [,…]}, {id: \"2\", name: \"前台管理\",…}, {id: \"3\", name: \"中心管理\",…}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/JurisdictionController.php",
    "groupTitle": "Jurisdiction"
  },
  {
    "type": "get",
    "url": "/new-league/group-class",
    "title": "*团课排课主界面课表",
    "version": "1.0.0",
    "name": "_________",
    "group": "League",
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
            "field": "weekStart",
            "description": "<p>指定周开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "weekEnd",
            "description": "<p>指定周结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "organizationId",
            "description": "<p>指定场馆ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "classroomId",
            "description": "<p>教室ID（新增参数）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courseId",
            "description": "<p>课程id   变更(新增参数）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startCourse",
            "description": "<p>课程开始时间（新增参数）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "endCourse",
            "description": "<p>课程结束时间（新增参数）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n'classroomId' => int '12'         //  教室ID    （新增参数）\n'weekStart' => string '2017-4-16' // 指定周开始时间\n'weekEnd' => string '2017-4-18'   // 指定周结束时间\n'organizationId ' => int '30'     // 场馆id\n'courseId ' => int'16            // 课程id      变更参数（新增参数）\n'startCourse' => string '20:58' //课程开始时间 （新增参数）\n'endCourse' => string '21:58' //课程结束时间    （新增参数）\n}\nget  /new-league/group-class",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课主界面 课程表遍历 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/group-class"
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
            "description": "<p>返回指定条件课程列表信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'data'=>'课程列表信息'}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "League"
  },
  {
    "type": "get",
    "url": "/new-league/get-course",
    "title": "*指定场馆低级课程信息",
    "version": "1.0.0",
    "name": "___________",
    "group": "League",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "Request Example",
          "content": "get  /new-league/get-course",
          "type": "json"
        }
      ]
    },
    "description": "<p>指定场馆低级课程信息展示 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/get-course"
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
            "description": "<p>返回所有底级课程信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功字段解释:",
          "content": "{\n'id' :  '60' ，           //课程id\n'name' :  '阴瑜伽 ，      //课程名称\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "League"
  },
  {
    "type": "post",
    "url": "/menu/sub-module",
    "title": "新增子菜单",
    "version": "1.0.0",
    "name": "_____",
    "group": "Menu",
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
            "field": "subName",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subEName",
            "description": "<p>菜单英文名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subIcon",
            "description": "<p>菜单图标</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subUrl",
            "description": "<p>菜单地址</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "topId",
            "description": "<p>顶级菜单ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"subName\": \"会员管理\",\n     \"subEName\": \"member\",\n     \"subIcon\": \"mm\",\n     \"subUrl\": \"fheuhfrfbghbtuh\",\n     \"topId\": \"2\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>新增子菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/sub-module</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/sub-module"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'菜单名称不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/get-top-module",
    "title": "获取顶级菜单",
    "version": "1.0.0",
    "name": "______",
    "group": "Menu",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>菜单管理 - 获取顶级菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/get-top-module</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/get-top-module"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'name' => string '会员管理'\n'e_name' => string 'member'\n'create_at' => '1496472588'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "/menu/top-module",
    "title": "新增-新增顶级菜单",
    "version": "1.0.0",
    "name": "_________",
    "group": "Menu",
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
            "field": "topName",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "topEName",
            "description": "<p>菜单英文名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "topIcon",
            "description": "<p>菜单图标</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"topName\": \"会员管理\",\n     \"topEName\": \"member\",\n     \"topIcon\": \"mm\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>新增-新增顶级菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/top-module</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/top-module"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'菜单名称不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/del-module",
    "title": "删除顶级菜单、子菜单",
    "version": "1.0.0",
    "name": "__________",
    "group": "Menu",
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
            "type": "int",
            "optional": false,
            "field": "moduleId",
            "description": "<p>菜单ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"moduleId\": \"3\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>删除顶级菜单、子菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/del-module</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/del-module"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"删除成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "/menu/save-func",
    "title": "子菜单 - 保存功能",
    "version": "1.0.0",
    "name": "__________",
    "group": "Menu",
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
            "type": "int",
            "optional": false,
            "field": "subId",
            "description": "<p>菜单ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "funcId",
            "description": "<p>功能ID (以数组的形式发送)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"subId\": \"3\",\n     \"funcId\": \"[1,2,3]\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>子菜单 - 保存功能<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/18<br/> <span><strong>调用方法：</strong></span>/menu/save-func</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/save-func"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"保存成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "/menu/update-top",
    "title": "修改 - 修改顶级菜单",
    "version": "1.0.0",
    "name": "___________",
    "group": "Menu",
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
            "field": "topNameUp",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "topENameUp",
            "description": "<p>菜单英文名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "topIconUp",
            "description": "<p>菜单图标</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "topId",
            "description": "<p>菜单id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"topNameUp\": \"会员管理\",\n     \"topENameUp\": \"member\",\n     \"topIconUp\": \"mm\",\n     \"topId\": \"2\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>修改 - 顶级菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/update-top</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/update-top"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "/menu/update-sub",
    "title": "菜单管理 - 修改子菜单",
    "version": "1.0.0",
    "name": "____________",
    "group": "Menu",
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
            "field": "subNameUp",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subENameUp",
            "description": "<p>菜单英文名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subIconUp",
            "description": "<p>菜单图标</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "subUrlUp",
            "description": "<p>菜单地址</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subId",
            "description": "<p>菜单ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"subNameUp\": \"会员管理\",\n     \"subENameUp\": \"member\",\n     \"subIconUp\": \"mm\",\n     \"subUrlUp\": \"fheuhfrfbghbtuh\",\n     \"subId\": \"3\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>菜单管理 - 修改子菜单<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/update-sub</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/update-sub"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/get-all-func",
    "title": "菜单管理 - 获取所有功能",
    "version": "1.0.0",
    "name": "_____________",
    "group": "Menu",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>菜单管理-获取所有功能<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/18<br/> <span><strong>调用方法：</strong></span>/menu/get-all-func</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/get-all-func"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'id' => '1'\n'name' => '新增'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/get-func-data",
    "title": "子菜单 - 获取子菜单的功能",
    "version": "1.0.0",
    "name": "______________",
    "group": "Menu",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>子菜单 - 获取子菜单的功能<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/18<br/> <span><strong>调用方法：</strong></span>/menu/get-func-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/get-func-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n[\"3\",\"4\"]",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/get-module-data",
    "title": "修改-获取需要修改的菜单信息",
    "version": "1.0.0",
    "name": "______________",
    "group": "Menu",
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
            "field": "moduleId",
            "description": "<p>菜单ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"moduleId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>修改-获取需要修改的菜单信息<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/18<br/> <span><strong>调用方法：</strong></span>/menu/get-module-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/get-module-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'name' => string '会员管理'\n'e_name' => string 'member'\n'create_at' => '1496472588'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/menu/get-sub-module",
    "title": "菜单管理 - 查询子菜单以及功能",
    "version": "1.0.0",
    "name": "________________",
    "group": "Menu",
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
            "type": "int",
            "optional": false,
            "field": "topId",
            "description": "<p>菜单ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"topId\": \"3\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>菜单管理-查询子菜单以及功能<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/menu/get-sub-module</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/menu/get-sub-module"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'name' => string '会员管理'\n'e_name' => string 'member'\n'create_at' => '1496472588'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MenuController.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "/new-league/card-automatic-thaw",
    "title": "卡自动解冻",
    "version": "1.0.0",
    "name": "_____",
    "group": "NewLeague",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isRequestMember",
            "description": "<p>是否是 会员所持卡的自动解冻</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "get   /new-league/card-automatic-thaw\n{\n     \"memberId\"=>12，                // 会员id\n     \"isRequestMember\"=>\"isMember\",  //是否是针对某个会员的自动解冻\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>卡自动解冻 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/10/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/new-league/card-automatic-thaw"
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
            "description": "<p>会员卡自动解冻结果</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "data字段解释:",
          "content": "{\n\"message\"=>\"自动解冻成功\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/new-league/group-class-data",
    "title": "获取团课课程分页数据",
    "version": "1.0.0",
    "name": "________",
    "group": "NewLeague",
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
            "field": "sortType",
            "description": "<p>排序字段名称、</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序方法（倒序/升序）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "course",
            "description": "<p>课程名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "category",
            "description": "<p>课种名称</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /new-league/group-class-data\n{\n     sortType:category(name：课程名称，category：课种，course_duration：时长，people_limit：人数上限，course_difficulty：课程难度),\n     sortName:ASC     (ASC:升序;DESC:降序)\n     course: 大大    （课程名称）\n     category：大大   （课种名称）\n}\nget /new-league/group-class-data",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取团课课程的分页数据（第一次请求不需要发送参数，点击排序字段的时候需要发送排序字段名称，以及排序方法（见参数）） <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/14</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/group-class-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          },
          {
            "group": "返回值",
            "optional": false,
            "field": "pages",
            "description": "<p>分页数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"data\":\"返回数据\",\n     \"pages\":\"分页信息\"\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/league/coach",
    "title": "团课教练实时搜索",
    "version": "1.0.0",
    "name": "________",
    "group": "NewLeague",
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
            "field": "name",
            "description": "<p>教练姓名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /league/coach\n{\n     name:小明   //教练姓名搜索\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课教练实时搜索 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/13</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/league/coach"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"data\":\"返回数据\",\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/LeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/new-league/get-week",
    "title": "获取每一月的周一和周日",
    "version": "1.0.0",
    "name": "_________",
    "group": "NewLeague",
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
            "field": "date",
            "description": "<p>获取指定周日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /user/get-charge-info\n{\n     date:\"2017-9\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>给课程模板遍历当前月之前的月份 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/get-week"
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
            "description": "<p>当前月份之前的3周</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/new-league/delete-course",
    "title": "*删除指定周期模板信息",
    "version": "1.0.0",
    "name": "__________",
    "group": "NewLeague",
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
            "field": "startDate",
            "description": "<p>周开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endDate",
            "description": "<p>周结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>指定场馆id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "get /new-league/delete-course",
          "type": "json"
        }
      ]
    },
    "description": "<p>删除指定周期模板信息 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/delete-course"
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
            "description": "<p>返回删除之后的报告信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "data字段解释:",
          "content": "{\n'status' :  \"success\"，      //返回删除之后的状态  success或error\n'data' :  '删除成功' ，          //返回删除成功或失败的内容\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "post",
    "url": "/new-league/update-course-detail",
    "title": "团课课程信息修改",
    "version": "1.0.0",
    "name": "_____________",
    "group": "NewLeague",
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
            "field": "date",
            "description": "<p>课程排课日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start",
            "description": "<p>课程开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end",
            "description": "<p>课程结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "classroom_id",
            "description": "<p>教室id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "courseId",
            "description": "<p>团课排课课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "coach_id",
            "description": "<p>教练id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "class_id",
            "description": "<p>课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post /new-league/update-course-detail\n{\n     \"date\"=>\"2017-06-01\"，   //上课开始日期\n     \"start\"=>\"11:28\",        //上课开始时间\n     \"end\"=>\"13:00\",          //上课结束时间\n     \"classroom_id\"=>\"3\",      //教室id\n     \"courseId\"=>112,          //团课排课课程id\n     \"coach_id\"=>66，          //教练id\n      'class_id'=>88,          //课程id\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}\npost /new-league/update-course-detail",
          "type": "json"
        }
      ]
    },
    "description": "<p>对没有预约的团课信息局部修改，各个修改参数都不能为空 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/31</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/update-course-detail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>保存状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回保存状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':“保存成功之后返回的信息”}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':“保存失败之后返回的信息”}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "post",
    "url": "/new-league/insert-data",
    "title": "*团课新增团课（外加数据判断）",
    "version": "1.0.0",
    "name": "______________",
    "group": "NewLeague",
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
            "type": "int",
            "optional": false,
            "field": "date",
            "description": "<p>上课日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start",
            "description": "<p>上课开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end",
            "description": "<p>上课结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "courseId",
            "description": "<p>课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "coach_id",
            "description": "<p>教练id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "classroom_id",
            "description": "<p>教室id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venue_id",
            "description": "<p>场馆id     （新增外加字段）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n'date' => string '2017-06-14'\n'start' => string '09:52'\n'end' => string '15:15'\n'courseId' => string '30'\n'coach_id' => string '152'\n'classroom_id' => string '20'\n'_csrf_backend' => string 'QnFXVEtQZ0IFQBRjCAY2Iys4JxkfNjJxdSBnNnoWP3sTNGEFHhUCEQ=='\n'venue_id' => int 54\n}\nget /new-league/insert-data",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课新增团课（外加数据判断） <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/14</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/insert-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>修改状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data'=>'保存成功的信息'}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data'=>'保存失败的信息'}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/new-league/order-setting",
    "title": "*预约设置信息展示",
    "version": "1.0.0",
    "name": "_______________",
    "group": "NewLeague",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "Request Example",
          "content": "get /new-league/order-setting",
          "type": "json"
        }
      ]
    },
    "description": "<p>预约设置信息展示 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/order-setting"
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
            "description": "<p>返回保存状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功字段解释:",
          "content": "{\n'before_class' :  '60' ，     //课程开始前多长时间不可约课\n'cancel_time' :  '60' ，      //课程开始前多长时间不可取消\n'personLowerLimit' ： '12'    //开课人数最少不得低于人数\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "post",
    "url": "/new-league/insert-group-data",
    "title": "团课排课新增团课提交",
    "version": "1.0.0",
    "name": "___________________",
    "group": "NewLeague",
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
            "type": "int",
            "optional": false,
            "field": "categoryId",
            "description": "<p>当前课程所属 课种id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courseName",
            "description": "<p>课种名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "courseTime",
            "description": "<p>课程时长</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "personLimit",
            "description": "<p>人数上限</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courseDifficult",
            "description": "<p>课程难度（1：初学 2：进阶 3：强化）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "des",
            "description": "<p>课程介绍</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>课程图片网址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post /new-league/update-group-data\n{\n     \"categoryId\"=>25,\n     \"courseName\"=>\"测试kkkk\",\n     \"courseTime\"=>11,\n     \"personLimit\"=>11,\n     \"courseDifficult\"=>“1”,\n     \"des\"=>\"jiesho\",\n     \"pic\"=>\"www.baidu.com\"，\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}\npost /new-league/insert-group-data",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课排课 新增课程 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/insert-group-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>保存状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回保存状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':“保存成功”}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':“保存失败”}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "post",
    "url": "/new-league/update-group-data",
    "title": "团课排课信息修改提交",
    "version": "1.0.0",
    "name": "_____________________",
    "group": "NewLeague",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>当前课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "categoryId",
            "description": "<p>当前课程所属 课种id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courseName",
            "description": "<p>课种名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "courseTime",
            "description": "<p>课程时长</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "personLimit",
            "description": "<p>人数上限</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courseDifficult",
            "description": "<p>课程难度（1：初学 2：进阶 3：强化）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "des",
            "description": "<p>课程介绍</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>课程图片网址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post /new-league/update-group-data\n{\n     \"id\"=>8，\n     \"categoryId\"=>25,\n     \"courseName\"=>\"测试kkkk\",\n     \"courseTime\"=>11,\n     \"personLimit\"=>11,\n     \"courseDifficult\"=>“1”,\n     \"des\"=>\"jiesho\",\n     \"pic\"=>\"www.baidu.com\"，\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}\nget /new-league/group-class-data",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课排课 课程信息修改 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/update-group-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>修改状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data'=>'修改成功'}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data'=>'修改失败的信息'}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "post",
    "url": "/new-league/course-config",
    "title": "团课排课 - 预约设置（预约设置提交数据）",
    "version": "1.0.0",
    "name": "_____________________",
    "group": "NewLeague",
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
            "field": "before_class",
            "description": "<p>课程开始前多长时间不可约课</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "venue_id",
            "description": "<p>预约设置 场馆id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cancel_time",
            "description": "<p>课程开始前多长时间不可取消</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "personLowerLimit",
            "description": "<p>开课人数最少不得低于人数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post  /new-league/course-config\n{\n     \"\"before_class\"=>\"11\"，\n     \"venue_id\"=>\"11\",\n     \"cancel_time\"=>\"33\",\n     \"personLowerLimit\"=>\"44\"，\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>团课排课 - 预约设置表单数据提交 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/course-config"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>操作状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回操作状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':'操作成功'}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':'操作失败的数据'}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/new-league/update-init-data",
    "title": "点击修改按钮课种下拉框数据",
    "version": "1.0.0",
    "name": "_______________________",
    "group": "NewLeague",
    "permission": [
      {
        "name": "管理员\nGET /new-league/update-init-data"
      }
    ],
    "description": "<p>点击修改的时候，下拉列表初始化数据 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/new-league/group-class-data"
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
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"id\":1（课种id）,,\"name\": \"--|瑜伽（课程名称）\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/NewLeagueController.php",
    "groupTitle": "NewLeague"
  },
  {
    "type": "get",
    "url": "/operation-statistics/member-card-count",
    "title": "会员卡种统计",
    "version": "1.0.0",
    "name": "______",
    "group": "OperationStatistics",
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
            "field": "date",
            "description": "<p>日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"date\": \"2017-07-19\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>营运统计 - 会员卡种统计<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/19<br/> <span><strong>调用方法：</strong></span>/operation-statistics/member-card-count</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/operation-statistics/member-card-count"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "[\"5=>时间卡\",\"4=>次卡\",...]",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "[ ]",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/OperationStatisticsController.php",
    "groupTitle": "OperationStatistics"
  },
  {
    "type": "get",
    "url": "/operation-statistics/class-count",
    "title": "会员上课统计",
    "version": "1.0.0",
    "name": "_____________",
    "group": "OperationStatistics",
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
            "field": "date",
            "description": "<p>日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"date\": \"2017-07-19\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>营运统计 - 会员上课统计<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/19<br/> <span><strong>调用方法：</strong></span>/operation-statistics/class-count</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/operation-statistics/class-count"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "[{\"yoga\":4,\"dance\":9,\"bicycle\":3,\"swim\":6,\"private\":5}]",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "[ ]",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/OperationStatisticsController.php",
    "groupTitle": "OperationStatistics"
  },
  {
    "type": "post",
    "url": "/purchase-card/sell-card",
    "title": "扫码购卡",
    "version": "1.0.0",
    "name": "____",
    "group": "PurchaseCard",
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
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "idCard",
            "description": "<p>身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "idAddress",
            "description": "<p>身份证住址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "nowAddress",
            "description": "<p>现居地</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>填写的验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "newCode",
            "description": "<p>生成的验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cardId",
            "description": "<p>卡种id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"name\"=>李丽,\n     \"idCard\"=>411121198512105529,\n     \"idAddress\"=>\"河南省周口县莲花乡大池子村\",\n     \"nowAddress\"=>河南郑州金水区,\n     \"mobile\"=>15736885523,\n     \"code\"=>589412,\n     \"newCode\"=>589412,\n     \"cardId\"=>5,\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>扫码购卡<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6<br> <span><strong>调用方法：</strong></span>/purchase-card/sell-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/purchase-card/sell-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"status\": \"success\",\n\"message\": \"成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"status\": \"error\",\n\"message\": \"失败\",\n\"data\": {\n\"name\": [\n\"请填写姓名\"\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PurchaseCardController.php",
    "groupTitle": "PurchaseCard"
  },
  {
    "type": "get",
    "url": "/purchase-card/get-only-venue",
    "title": "获取售卖场馆",
    "version": "1.0.0",
    "name": "______",
    "group": "PurchaseCard",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>售卡管理 - 获取售卖场馆<br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/21<br> <span><strong>调用方法：</strong></span>/purchase-card/get-only-venue</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/purchase-card/get-only-venue"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{[\n{\"id\": \"1\",\"name\": \"迈步\",}\n]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PurchaseCardController.php",
    "groupTitle": "PurchaseCard"
  },
  {
    "type": "get",
    "url": "/purchase-card/card-category",
    "title": "获取场馆售卖的卡种",
    "version": "1.0.0",
    "name": "_________",
    "group": "PurchaseCard",
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
            "optional": false,
            "field": "venueId",
            "description": "<p>int 场馆ID</p>"
          }
        ]
      }
    },
    "description": "<p>售卡管理 - 获取场馆售卖的卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/15<br> <span><strong>调用方法：</strong></span>/purchase-card/card-category</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/purchase-card/card-category"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{[\n{\"id\": \"1\",\"name\": \"金爵卡\",}\n]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PurchaseCardController.php",
    "groupTitle": "PurchaseCard"
  },
  {
    "type": "post",
    "url": "/purchase-card/deal",
    "title": "获取卡种的合同详情",
    "version": "1.0.0",
    "name": "_________",
    "group": "PurchaseCard",
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
            "type": "int",
            "optional": false,
            "field": "cardId",
            "description": "<p>卡种id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"cardId\"=>5,\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>扫码购卡 - 获取卡种的合同详情<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6<br> <span><strong>调用方法：</strong></span>/purchase-card/deal</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/purchase-card/deal"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"intro\":[\"合同内容\"]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"intro\":[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PurchaseCardController.php",
    "groupTitle": "PurchaseCard"
  },
  {
    "type": "post",
    "url": "/member-ship-pay-two/ali-scan-pay",
    "title": "支付宝二维码支付",
    "version": "1.0.0",
    "name": "________",
    "group": "Register",
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
            "field": "paymentType",
            "description": "<p>付款类型：zfbScanCode</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>卡种id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>购买物品标志 （固定值：card）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "csrf_",
            "description": "<p>防止跨站伪造请求</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST   /member-ship-pay-two/ali-scan-pay\n{\n      \"paymentType\":zfbScanCode    //微信扫码支付(固定值)\n      \"typeId\":45781              // 卡种id\n      \"memberId\":78278           // 会员id\n      \"type\": card              //购买类型 卡\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>支付宝二维码支付 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship-pay-two/ali-scan-pay"
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
          "title": "返回值详情",
          "content": "{\n \"status\":\"success\"   // 请求成功 返回success  请求失败返回error\n \"data\" :  （好像是一个网页）\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipPayTwoController.php",
    "groupTitle": "Register"
  },
  {
    "type": "get",
    "url": "/role/update-status",
    "title": "角色停用",
    "version": "1.0.0",
    "name": "____",
    "group": "Role",
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
            "type": "int",
            "optional": false,
            "field": "roleId",
            "description": "<p>角色id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"roleId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>角色停用<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/role/update-status</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/role/update-status"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"修改失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/RoleController.php",
    "groupTitle": "Role"
  },
  {
    "type": "get",
    "url": "/role/get-role",
    "title": "选择角色 - 列表",
    "version": "1.0.0",
    "name": "_________",
    "group": "Role",
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
            "type": "int",
            "optional": false,
            "field": "companyId",
            "description": "<p>公司id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"companyId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>权限管理 - 选择角色 - 列表<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/5<br/> <span><strong>调用方法：</strong></span>/role/get-role</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/role/get-role"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"1\",\"name\":\"水吧\",\"status\":\"1\"(1正常，2停用)}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/RoleController.php",
    "groupTitle": "Role"
  },
  {
    "type": "get",
    "url": "/sell-card/selected-card",
    "title": "潜在会员购卡选中登记过的卡种",
    "version": "1.0.0",
    "name": "______________",
    "group": "SellCard",
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
            "type": "int",
            "optional": false,
            "field": "idCard",
            "description": "<p>身份证号</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     'idCard' => 410121************,\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>潜在会员购卡选中登记过的卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/17<br> <span><strong>调用方法：</strong></span>/sell-card/selected-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-card/selected-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{[\n{\"data\":\"6\"}\n]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\":null}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellCardController.php",
    "groupTitle": "SellCard"
  },
  {
    "type": "get",
    "url": "/sell-index/entry-count",
    "title": "客流量",
    "version": "1.0.0",
    "name": "___",
    "group": "SellIndex",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>客流量<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/12<br/> <span><strong>调用方法：</strong></span>/sell-index/entry-count</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/entry-count"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\":\"180\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/sell-index/about-count",
    "title": "课程预约",
    "version": "1.0.0",
    "name": "____",
    "group": "SellIndex",
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
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1本月，2上个月</p>"
          }
        ]
      }
    },
    "description": "<p>销售主页 - 课程预约<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/10<br/> <span><strong>调用方法：</strong></span>/sell-index/about-count</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/about-count"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"yoga\":\"2\",\"dance\":\"3\",\"bicycle\":\"1\",\"private\":\"0\",\"bodyBuilding\":\"4\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/sell-index/new-member",
    "title": "本月新增会员",
    "version": "1.0.0",
    "name": "______",
    "group": "SellIndex",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>销售主页 - 本月新增会员<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/8<br/> <span><strong>调用方法：</strong></span>/sell-index/new-member</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/new-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'id' => string '10'\n'mobile' => string '18345125623'\n'name' => string '黄晓明' (会员姓名)\n'sex' => string '1'\n'card_name' => '金爵卡'\n'amount_money' => '3000'\n'card_number' => '011496472588'\n'create_at' => '1496472588'\n'ename' => '李丽' (客服)\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/sell-index/not-entry",
    "title": "销售主页 - 未签到",
    "version": "1.0.0",
    "name": "__________",
    "group": "SellIndex",
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
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>7、15、30、90、180、360</p>"
          }
        ]
      }
    },
    "description": "<p>销售主页 - 未签到<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/10<br/> <span><strong>调用方法：</strong></span>/sell-index/not-entry</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/not-entry"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'id' => string '8'\n'mobile' => string '15736885563'\n'name' => string '朱梦珂'\n'sex' => string '2'\n'entry_time' => string '1496654206' (最近到场)\n'ename' => string '李丽' (客服)\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"data\": [],\n\"pages\": \"\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/sell-index/soon-due-card",
    "title": "销售主页 - 即将到期",
    "version": "1.0.0",
    "name": "___________",
    "group": "SellIndex",
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
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1或3</p>"
          }
        ]
      }
    },
    "description": "<p>销售主页 - 即将到期<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/9<br/> <span><strong>调用方法：</strong></span>/sell-index/soon-due-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/soon-due-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'id' => string '8'\n'mobile' => string '15736885563'\n'name' => string '朱梦珂'\n'sex' => string '2'\n'create_at' => string '1496654206' (开卡时间)\n'invalid_time' => string '1507281406' (到期时间)\n'ename' => string '李丽'\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/sell-index/birthday-member",
    "title": "销售主页 - 本月生日会员",
    "version": "1.0.0",
    "name": "_____________",
    "group": "SellIndex",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>本月生日会员<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/8<br/> <span><strong>调用方法：</strong></span>/sell-index/birthday-member</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-index/birthday-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n'id' => string '21'\n'mobile' => string '15736885523'\n'counselor_id' => string '1'\n'name' => string '杨颖' (会员姓名)\n'sex' => string '2'\n'birth_date' => string '2017-06-30'\n'ename' => string '李丽' (客服)\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellIndexController.php",
    "groupTitle": "SellIndex"
  },
  {
    "type": "get",
    "url": "/shop/del-goods",
    "title": "商品管理-删除商品",
    "version": "1.0.0",
    "name": "_________",
    "group": "Shop",
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
            "type": "int",
            "optional": false,
            "field": "goodsId",
            "description": "<p>商品ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"goodsId\": \"3\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品管理-删除商品<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/21<br/> <span><strong>调用方法：</strong></span>/shop/del-goods</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/del-goods"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"删除成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"删除失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/goods-update",
    "title": "商品管理 - 修改商品",
    "version": "1.0.0",
    "name": "___________",
    "group": "Shop",
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
            "type": "int",
            "optional": false,
            "field": "goodsId",
            "description": "<p>商品id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>商品类别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "brand",
            "description": "<p>商品品牌</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "model",
            "description": "<p>商品型号</p>"
          },
          {
            "group": "Parameter",
            "type": "decimal",
            "optional": false,
            "field": "unit",
            "description": "<p>商品单价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "producer",
            "description": "<p>生产商</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "supplier",
            "description": "<p>供应商</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"goodsId\": \"3\",\n     \"typeId\": \"1\",\n     \"name\": \"欧菲光\",\n     \"brand\": \"酷狗\",\n     \"model\": \"米开功\",\n     \"unit\": \"2222\",\n     \"producer\": \"开工融入\",\n     \"supplier\": \"美国\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品管理-修改商品<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/21<br/> <span><strong>调用方法：</strong></span>/shop/goods-update</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/goods-update"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "get",
    "url": "/site/entry-record",
    "title": "数据统计 - 今日到店详情",
    "version": "1.0.0",
    "name": "_____________",
    "group": "Site",
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
            "field": "date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序字段</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"date\": \"2017-07-19\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>数据统计 - 今日到店详情<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/19<br/> <span><strong>调用方法：</strong></span>/site/entry-record</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site/entry-record"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{member_card_id: 20, venue_id: 2, entry_time: \"1500282881\",...}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{ }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteController.php",
    "groupTitle": "Site"
  },
  {
    "type": "get",
    "url": "/site/entry-num",
    "title": "数据统计 - 到店人数统计图,今日到场人数、男、女",
    "version": "1.0.0",
    "name": "_________________________",
    "group": "Site",
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
            "field": "date",
            "description": "<p>日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"date\": \"2017-07-19\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>数据统计 - 到店人数统计图,今日到场人数、男、女<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/19<br/> <span><strong>调用方法：</strong></span>/site/entry-num</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site/entry-num"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"men\":{\"time7\":0,\"time8\":0,\"time9\":0,\"time10\":0,\"time11\":0,\"time12\":0,\"time13\":0,\"time14\":0,\"time15\":0,\"time16\":0,\"time17\":0,\"time18\":1,\"time19\":0,\"time20\":0,\"time21\":0},\n\"women\":{\"time7\":0,\"time8\":0,\"time9\":0,\"time10\":0,\"time11\":0,\"time12\":0,\"time13\":0,\"time14\":0,\"time15\":0,\"time16\":0,\"time17\":2,\"time18\":1,\"time19\":0,\"time20\":0,\"time21\":0},\n\"menNum\":1,\"womenNum\":3,\"allNum\":4}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{ }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteController.php",
    "groupTitle": "Site"
  },
  {
    "type": "get",
    "url": "/super-jurisdiction-set-ctrl/get-venue",
    "title": "获取权限同步的场馆",
    "version": "1.0.0",
    "name": "_________",
    "group": "SuperJurisdictionSetCtrl",
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
            "type": "int",
            "optional": false,
            "field": "roleId",
            "description": "<p>角色id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"roleId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取权限同步的场馆<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/10<br/> <span><strong>调用方法：</strong></span>/super-jurisdiction-set-ctrl/get-venue</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/super-jurisdiction-set-ctrl/get-venue"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"data\": [{\"id\":\"8\",\"name\":\"大上海\"}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\"data\": []}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SuperJurisdictionSetCtrlController.php",
    "groupTitle": "SuperJurisdictionSetCtrl"
  },
  {
    "type": "get",
    "url": "/user/get-leave-limit",
    "title": "获取会员请假限制信息",
    "version": "1.0.0",
    "name": "__________",
    "group": "USER",
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
            "type": "int",
            "optional": false,
            "field": "cardCategoryId",
            "description": "<p>卡种id</p>"
          }
        ]
      }
    },
    "description": "<p>根据不同的会员卡，获取会员卡的限制信息 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/06/26 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-member-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-leave-limit"
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
            "description": "<p>｛leave_total_days：请假总天数，leave_least_Days：每次最低天数，leave_long_limit: [0]=&gt;每次请假天数，[1]=&gt;每次请假天数｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "USER"
  },
  {
    "type": "post",
    "url": "/user/assign-private",
    "title": "会员管理 - 分配私教",
    "version": "1.0.0",
    "name": "___________",
    "group": "User",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "coachId",
            "description": "<p>私教id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "courseNum",
            "description": "<p>课程节数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"memberCardId\": \"122\",\n    \"coachId\": \"12\",\n    \"courseNum\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员管理 - 分配私教<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/25<br/> <span><strong>调用方法：</strong></span>/user/assign-private</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/assign-private"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => “success”,\n  'data'   => \"分配成功\" ,\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status' => “error”,\n  'data'   => \"分配失败的信息\" ,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/venue-management/save-seat",
    "title": "添加座位排次",
    "version": "1.0.0",
    "name": "______",
    "group": "VenueManagement",
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
            "type": "int",
            "optional": false,
            "field": "roomId",
            "description": "<p>教室id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>座位排次名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "rows",
            "description": "<p>排</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "columns",
            "description": "<p>列</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "rowsArr",
            "description": "<p>排数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "columnsArr",
            "description": "<p>列数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "numberArr",
            "description": "<p>座位号数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "typeArr",
            "description": "<p>座位类型数组</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"roomId\": \"1\",\n    \"name\": \"高温瑜伽\",\n    \"rows\": \"5\",\n    \"columns\": \"6\",\n    \"rowsArr\": \"[1,2,3]\",\n    \"columnsArr\": \"[1,2,3]\",\n    \"numberArr\": \"[1,2,3]\",\n    \"typeArr\": \"[1,2,3]\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>添加座位排次<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/28<br/> <span><strong>调用方法：</strong></span>/venue-management/save-seat</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/venue-management/save-seat"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => “success”,\n  'data'   => \"添加成功\" ,\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status' => “error”,\n  'data'   => \"添加失败的信息\" ,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/VenueManagementController.php",
    "groupTitle": "VenueManagement"
  },
  {
    "type": "get",
    "url": "/venue-management/get-seat",
    "title": "获取所有座位排次",
    "version": "1.0.0",
    "name": "________",
    "group": "VenueManagement",
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
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"venueId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场馆管理 - 获取所有座位排次<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/28<br/> <span><strong>调用方法：</strong></span>/venue-management/get-seat</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/venue-management/get-seat"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'data':['id':'1','name':'高温瑜伽'...]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'data':[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/VenueManagementController.php",
    "groupTitle": "VenueManagement"
  },
  {
    "type": "post",
    "url": "/venue-management/update-seat",
    "title": "修改座位排次",
    "version": "1.0.0",
    "name": "____________",
    "group": "VenueManagement",
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
            "type": "int",
            "optional": false,
            "field": "seatTypeId",
            "description": "<p>座位排次id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "roomIdUp",
            "description": "<p>教室id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "nameUp",
            "description": "<p>座位排次名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "rowsUp",
            "description": "<p>排</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "columnsUp",
            "description": "<p>列</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "rowsArrUp",
            "description": "<p>排数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "columnsArrUp",
            "description": "<p>列数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "numberArrUp",
            "description": "<p>座位号数组</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "typeArrUp",
            "description": "<p>座位类型数组</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"seatTypeId\": \"1\",\n    \"roomIdUp\": \"1\",\n    \"nameUp\": \"高温瑜伽\",\n    \"rowsUp\": \"5\",\n    \"columnsUp\": \"6\",\n    \"rowsArrUp\": \"[1,2,3]\",\n    \"columnsArrUp\": \"[1,2,3]\",\n    \"numberArrUp\": \"[1,2,3]\",\n    \"typeArrUp\": \"[1,2,3]\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>修改座位排次<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/28<br/> <span><strong>调用方法：</strong></span>/venue-management/update-seat</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/venue-management/update-seat"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => “success”,\n  'data'   => \"修改成功\" ,\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status' => “error”,\n  'data'   => \"修改失败的信息\" ,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/VenueManagementController.php",
    "groupTitle": "VenueManagement"
  },
  {
    "type": "get",
    "url": "/venue-management/del-seat",
    "title": "删除座位排次",
    "version": "1.0.0",
    "name": "_____________",
    "group": "VenueManagement",
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
            "type": "int",
            "optional": false,
            "field": "seatTypeId",
            "description": "<p>座位排次id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"seatTypeId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场馆管理 - 删除座位排次<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/28<br/> <span><strong>调用方法：</strong></span>/venue-management/del-seat</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/venue-management/del-seat"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => “success”,\n  'data'   => \"删除成功\" ,\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status' => “error”,\n  'data'   => \"删除失败的信息\" ,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/VenueManagementController.php",
    "groupTitle": "VenueManagement"
  },
  {
    "type": "get",
    "url": "/venue-management/seat-details",
    "title": "获取座位排次详情",
    "version": "1.0.0",
    "name": "______________",
    "group": "VenueManagement",
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
            "type": "int",
            "optional": false,
            "field": "seatTypeId",
            "description": "<p>座位排次id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n    \"seatTypeId\": \"1\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场馆管理 - 获取座位排次详情<br/> <span><strong>作   者：</strong></span>朱梦珂<br/> <span><strong>邮   箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/28<br/> <span><strong>调用方法：</strong></span>/venue-management/seat-details</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/venue-management/seat-details"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'data':['id':'1','name':'高温瑜伽'...]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'data':[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/VenueManagementController.php",
    "groupTitle": "VenueManagement"
  },
  {
    "type": "get",
    "url": "/user/insert-employee-data",
    "title": "轨迹数据记录",
    "version": "1.0.0",
    "name": "______",
    "group": "behaviorTrail",
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
            "type": "int",
            "optional": false,
            "field": "behavior",
            "description": "<p>// 1:浏览 2:编辑 3:修改 4:查看 5:删除</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "moduleId",
            "description": "<p>// 操作菜单模块id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "behaviorIntro",
            "description": "<p>//简洁具体描述 例如 删除某某会员 编辑某某会员 新增白金卡 等</p>"
          }
        ]
      }
    },
    "description": "<p>对员工每一次操作的 具体描述的数据记录 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/07/14 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/insert-employee-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/insert-employee-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "status",
            "description": "<p>数据录入状态  data  返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => “success”,           //数据成功录入状态\n  'data'   => \"成功后返回的信息\" ,    //调换成功返回的信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n'status'=>'error',                //失败状态\n'data'=>“保存失败信息”           //调换失败返回的信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "behaviorTrail"
  },
  {
    "type": "get",
    "url": "/user/member-behavior-trail",
    "title": "员工行为轨迹",
    "version": "1.0.0",
    "name": "______",
    "group": "behaviorTrail",
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
            "field": "employeeName",
            "description": "<p>员工姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>搜索开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>搜索结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "employeeBehavior",
            "description": "<p>员工行为   // 1:浏览 2:编辑 3:修改 4:查看 5:删除</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "operateModule",
            "description": "<p>操作模块</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序的方式</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /user/member-behavior-trail\n{\n      employeeName: \"张三\",      //员工姓名（张三）\n      startTime: \"2017-07-15\"    // 搜索开始时间\n      endTime: \"2017-07-28\"      //搜索结束时间\n      employeeBehavior：\"删除\"   // 1:浏览 2:编辑 3:修改 4:查看 5:删除\n      operateModule:17          // 操作模型\n      sortType :\"employeeName\",    //排序字段名称 1:employeeName（用户名称）2：employeeBehavior（用户行为）3：behavior_intro（功能）4：create_at（操作时间）\n      sortName：\"ASC\",          // 排序方式（1 ASC(升序)DES（降序））\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>员工行为轨迹 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/13</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/member-behavior-trail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'id' => string '1' ,                    //轨迹id\n  \"nam\"=>\"员工姓名\"                      //用户名\n  'behavior' => 1,                     //行为 1:浏览 2:编辑 3:修改 4:查看 5:删除\n  “behavior_intro” => string “删除会员”       // 功能\n   “create_at” => 20122334134       // 操作时间  （时间戳）\n   \"moduleName\" =>\"会员首页\"           // 模型名称\n   “module_id”=>\"53\"                 //模型id\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "behaviorTrail"
  },
  {
    "type": "post",
    "url": "/cabinet/cabinet-update",
    "title": "柜子信息修改",
    "version": "1.0.0",
    "name": "______",
    "group": "cabinetUpdate",
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
            "field": "cabinetModel",
            "description": "<p>柜子型号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetType",
            "description": "<p>柜子类别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cabinetId",
            "description": "<p>柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "monthRentPrice",
            "description": "<p>月租价格</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "yearRentPrice",
            "description": "<p>年租价格</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /cabinet/cabinet-update 柜子信息修改\n{\n      cabinetModel   : 1\n      cabinetType ：1\n      cabinetId ： 1\n      monthRentPrice：12\n      yearRentPrice:12\n      _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>柜子信息修改 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/9</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com /cabinet/cabinet-update"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //修改成功状态\n      \"data\":“添加成功”    //修改成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //修改失败\n     'data'=>“绑定失败”   //修改失败信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinetUpdate"
  },
  {
    "type": "get",
    "url": "/cabinet/frozen-cabinet",
    "title": "冻结柜子",
    "version": "1.0.0",
    "name": "____",
    "group": "cabinet",
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
            "type": "int",
            "optional": false,
            "field": "cabinetId",
            "description": "<p>柜子id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/renew-cabinet\n{\n     cabinetId:12,     //柜子id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>柜子状态的冻结 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/frozen-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //冻结成功\n      \"data\":“冻结成功信息” //冻结成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //续租失败\n     'data'=>“冻结成功信息” //冻结成功信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "post",
    "url": "/cabinet/change-cabinet",
    "title": "会员柜子调换",
    "version": "1.0.0",
    "name": "______",
    "group": "cabinet",
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
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memCabinetId",
            "description": "<p>会员柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cabinetId",
            "description": "<p>新柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "originalCabinetId",
            "description": "<p>老柜子id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET    /cabinet/change-cabinet 会员柜子调换\n{\n      _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n      memCabinetId :52       // 会员柜子id\n      cabinetId : 33,       // 新柜子id\n     originalCabinetId:44   //老柜子id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员柜子调换 操作执行 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/change-cabinet 会员柜子调换"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => \"调换成功\",           //成功状态\n  'data' => \"成功后返回的信息\" ,    //调换成功返回的信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status'=>'error',                //失败状态\n'data'=>“保存失败信息”           //调换失败返回的信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/quite-money",
    "title": "退租-退还金额",
    "version": "1.0.0",
    "name": "_______",
    "group": "cabinet",
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
            "field": "quiteRent",
            "description": "<p>退租日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endRent",
            "description": "<p>到租日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "dayRentPrice",
            "description": "<p>日租价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "monthRentPrice",
            "description": "<p>月租价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "yearRentPrice",
            "description": "<p>年租价</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /cabinet/quite-money\n{\n     quiteRent :'now',   //退租日期 暂定为 now\n     endRent   :111111111 //到租日期 （时间戳）\n     dayRentPrice：600   //日租价\n     monthRentPrice：300 //月租价\n     yearRentPrice:1200 //年租价\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>退租柜子，退还金额的计算 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/quite-money"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"money\":300,             //退换金额\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/filter-data",
    "title": "初始化柜子租用状态",
    "version": "1.0.0",
    "name": "_________",
    "group": "cabinet",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "Request Example",
          "content": "get /cabinet/filter-data",
          "type": "json"
        }
      ]
    },
    "description": "<p>初始化柜子租用状态（将到期柜子，退租柜子，清空租用记录，修改租用状态 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/4</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/filter-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  data:true;            //请求成功\n  data:\"请求参数失败\"   //请求失败（结果不是true）\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/venue-cabinet",
    "title": "获取大上海的各个场馆",
    "version": "1.0.0",
    "name": "__________",
    "group": "cabinet",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "parameter": {
      "examples": [
        {
          "title": "Request Example",
          "content": "get /cabinet/venue-cabinet",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取大上海的各个场馆 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/venue-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"id\":43,                //场馆id\n     \"name\":大上海瑜伽健身馆 //场馆名称\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/get-cabinet-type",
    "title": "获取柜子类型各个 参数类型",
    "version": "1.0.0",
    "name": "_____________",
    "group": "cabinet",
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
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /cabinet/get-cabinet-type\n{\n     venueId :52,   //下拉列表场馆id\n\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取柜子类型的各个参数 1 柜子名称 2 柜子总数量 3 已租柜子数量 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/4</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/get-cabinet-type"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"id\":1,             //柜子类型id\n     \"type_name\":\"女大柜\"， //柜子类型名称\n     'cabinetNum':13，    //柜子总数量\n     'is_rent':12，      //柜子被租数量\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/cabinet/home-data",
    "title": "指定各个柜子的租用状态数据分页显示",
    "version": "1.0.0",
    "name": "_________________",
    "group": "cabinet",
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
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>柜子类型id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序的字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序的方式</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /cabinet/home-data\n{\n      typeId   :52,             //柜子类型id\n      sortType :\"cabinetNum\",  //柜子排序字段1:cabinetNum（柜号）2：customerName（绑定用户）3：cabinetModel（柜子型号）4：cabinetType（柜子类别）5:cabinetEndRent(柜子到期剩余天数)\n      sortName：\"ASC\",         // 排序方式（1 ASC(升序)DES（降序））\n\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>指定各个柜子的租用状态数据分页显示 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club <span><strong>创建时间：</strong></span>2017/6/4</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/home-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'id' => string '1' ,                    //柜子id\n  'cabinet_type_id' => string '1' ,       //柜子类型id\n 'cabinet_number' => string '大大上海瑜伽健身馆-0001'  //柜子编号\n  'status' => string '2'                 // 柜子租用状态 （1未租 2 已租 3 维修状态）\n 'type_name' => string '萨嘎'            // 柜子类型名字\n  'consumerName' => string '王亚娟'      //用户名字\n  'surplus'    => float 7534            // 剩余天数 (如果没有客户租用输出结果为false)\n 'totalDay' => float 128575967.91046    //总天数   （如果没有客户租用输出结果为false）\n 'venueId' => string '47' (length=2)    // 柜子场馆id\n 'cabinetModel' => string '2'           //柜子规格 1:大柜2:中柜3:小柜\n 'cabinetType' => string '2'           //柜子类型  1:临时2:正式\n 'mobile' =>string\"客户电话号码\"       // 客户电话号码\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "cabinet"
  },
  {
    "type": "get",
    "url": "/league/league-reservation",
    "title": "卡种续费预约设置",
    "version": "1.0.0",
    "name": "________",
    "group": "card",
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
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "renew",
            "description": "<p>v 值</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "recharge",
            "description": "<p>键</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>场景</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>场景</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post  /new-league/course-config\n{\n     \"type\"=>\"card\"，\n     \"renew\" =>\"12\",\n     'beforeRenew'=>'13'\n     \"recharge\"=>\"11\",\n     \"scenario\"=>\"card\"，\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员添加教练-卡种续费预约设置 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/league/league-reservatio</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/league/league-reservatio"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>验证状态 ｛error：设置失败，success：设置成功｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/LeagueController.php",
    "groupTitle": "card"
  },
  {
    "type": "post",
    "url": "/user/save-member-charge",
    "title": "私课购买和续费",
    "version": "1.0.0",
    "name": "__________",
    "group": "charge___",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id           （续费继续使用）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "chargeId",
            "description": "<p>购买私课产品id     （续费继续使用）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "chargeType",
            "description": "<p>购买课程的类型：单节用“alone”,多节的套餐用“many”）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "coachId",
            "description": "<p>销售私教id           （续费继续使用）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "nodeNumber",
            "description": "<p>课程节数              (续费继续使用)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "totalPrice",
            "description": "<p>课程总价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "saleType",
            "description": "<p>销售渠道</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>（备注 --续费使用）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "offer",
            "description": "<p>（优惠折扣 --续费使用）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "renewTime",
            "description": "<p>缴费日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "payMethod",
            "description": "<p>支付方式</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>(carry ,续费标识)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /user/save-member-charge\n{\n     \"memberId\": 125,\n     \"chargeId\": 125,\n     \"chargeType\": \"alone\",\n     \"coachId\": 125,\n     \"nodeNumber\" :1,\n     \"saleType\": \"网络\",\n     \"renewTime\":\"2017-06-03\",\n     \"payMethod\":\"现金\",\n     \"totalPrice\":1100,\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以进行私课购买和续费操作 <br/> <span><strong>作   者：</strong></span>黄鹏举<br> <span><strong>邮   箱：</strong></span>huangpengju@itsprts.club <span><strong>创建时间：</strong></span>2017/5/27</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/save-member-charge"
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
            "description": "<p>返回的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "charge___"
  },
  {
    "type": "get",
    "url": "/private-teach/get-private-teach-all",
    "title": "所有私课课程",
    "version": "1.0.0",
    "name": "______",
    "group": "charges",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"memberId\": 2,         //会员id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员管理 - 购买私课 - 获取所有私课课程<br/> <span><strong>作    者：</strong></span>黄鹏举<br/> <span><strong>邮    箱：</strong></span>huangpengju@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/8<br/> <span><strong>调用方法：</strong></span>/private-teach/get-private-teach-all</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/private-teach/get-private-teach-all"
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
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "\"data\": {\n \"alone\": [                                                     //单节课程 \n     {\n     \"id\": \"1\",                                                  //产品id\n     \"packName\": \"单节测试1\",                                    //产品名称\n     \"name\": \"减脂\",                                             //课种名称\n     \"pic\": \"http://oo0oj2qmr.bkt.clouddn.com/0.jpg?e\",          //产品图片\n     \"original_price\": \"200\",                                    //单节售价\n     \"memberOrder\": true,                                        //true表示未买过，false表示买过\n     \"newMember\": false,                                         //true表示是刚办卡会员(有区间数量用pos价)，false表示不是刚办卡会员（没有区间数量用单节售价）\n     \"chargeClassPriceAll\": [\n     {\n         \"intervalStart\": \"1\",                                   //区间开始数量\n         \"intervalEnd\": \"11\",                                    //区间结束数量\n         \"unitPrice\": \"150\",                                     //优惠单价\n         \"posPrice\": \"150\"                                       //pos价\n     },\n     {\n        \"intervalStart\": \"12\",                                   //区间开始数量\n         \"intervalEnd\": \"20\",                                    //区间结束数量\n         \"unitPrice\": \"100\",                                     //优惠单价\n         \"posPrice\": \"100\"                                       //pos价\n     }\n     ],\n \"score\": 4,                                                     //级别\n \"scoreImg\": {                                                   //星星\n      \"one\": \"img/x1.png\",\n     \"two\": \"img/x1.png\",\n     \"three\": \"img/x1.png\",\n     \"four\": \"img/x1.png\",\n     \"five\": \"img/x2.png\"\n      }\n   }\n  ],\n \"many\": [                                                      //套餐课程\n     {\n     \"id\": \"2\",                                                 //产品id \n     \"packName\": \"测试\",                                        //产品名称\n     \"pic\": \"\",                                                 //产品图片 \n     \"courseStr\": \"减脂100节/塑形10节\",                         //套餐详细\n     \"memberOrder\": true,                                       //true表示未买过，false表示买过\n     \"totalPrice\": 222,                                         //套餐总价 \n     \"score\": 4,                                                //级别 \n     \"scoreImg\": {                                              //星星 \n     \"one\": \"img/x1.png\",\n     \"two\": \"img/x1.png\",\n     \"three\": \"img/x1.png\",\n     \"four\": \"img/x1.png\",\n     \"five\": \"img/x2.png\"\n     }\n  }\n ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n \"data\": {\n \"alone\": [],\n \"many\": []\n }\n }",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "charges"
  },
  {
    "type": "get",
    "url": "/check-card/cancel-about-class",
    "title": "取消预约(会员、潜在会员)",
    "version": "1.0.0",
    "name": "____",
    "group": "checkCard___",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>预约ID</p>"
          }
        ]
      }
    },
    "description": "<p>管理员可以进入验卡页面可以给会员取消预约 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/check-card/cancel-about-class"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"取消成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"取消失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "checkCard___"
  },
  {
    "type": "post",
    "url": "/check-card/leave-record",
    "title": "会员请假表单验证",
    "version": "1.0.0",
    "name": "________",
    "group": "checkCard",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "leavePersonId",
            "description": "<p>请假人id（会员ID）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "leaveReason",
            "description": "<p>请假原因（原因）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "leaveStartTime",
            "description": "<p>请假开始时间 （2017-03-06）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "leaveEndTime",
            "description": "<p>请假结束时间 (2017-03-10）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "leaveTotalDays",
            "description": "<p>*请假离开总天数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "leaveLimitStatus",
            "description": "<p>*请假限制识别码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "leaveArrayIndex",
            "description": "<p>*请假识别下标</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /check-card/leave-record\n{\n    \"leavePersonId\": 2,             // 请假会员ID\n    \"leaveReason\": \"不请了\",        // 请假原因\n    \"leaveStartTime\": 2017-06-06,   //请假开始时间\n    \"leaveEndTime\":2017-08-08,      // 请假结束时间\n    \"leaveTotalDays\" :30,           // 请假总天数\n    \"leaveLimitStatus\" :1,          // 请假限制状态  1 按照请假总天数遍历 2 请假次数遍历 3 没有请假限制\n    \"leaveArrayIndex\" :1,           // 请假发送数组下标\n    \"memberCardId\" :3,              // 会员卡id\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员请假表单验证 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/6/27</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/check-card/leave-record"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>请假保存状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回请假状态数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','status'=>'success','data':请假保存数据状态}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','status'=>'error','data':请假保存数据状态}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "checkCard"
  },
  {
    "type": "post",
    "url": "/check-card/set-about-class-record",
    "title": "预约团课课程（会员、潜在会员）",
    "version": "1.0.0",
    "name": "______",
    "group": "checkCard___",
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
            "type": "int",
            "optional": false,
            "field": "classId",
            "description": "<p>课程ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutType",
            "description": "<p>预约类型.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "seatId",
            "description": "<p>座位.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员Id.(员工约课传 员工id)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "is_employee",
            "description": "<p>是不是员工约课，true 表示是员工约课</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /check-card/set-about-class-record\n{\n    \"classId\": 125,\n    \"aboutType\": \"mobile\",\n    \"seatId\": 3,\n    \"memberId\":2,\n    \" is_employee\" :\"true\"\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以进入验卡页面可以给会员预约课程 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/check-card/set-about-class-record"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','message'=>'预约成功','data':返回刚才预约ID}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','message'=>'预约失败','data':预约失败数据}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "checkCard___"
  },
  {
    "type": "get",
    "url": "/check-card/del-leave-record",
    "title": "删除会员请假记录",
    "version": "1.0.0",
    "name": "________",
    "group": "checkCard___",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>请假ID</p>"
          }
        ]
      }
    },
    "description": "<p>管理员可以进入验卡页面可以给会员取消预约 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/check-card/del-leave-record"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"消假成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':\"消假失败\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CheckCardController.php",
    "groupTitle": "checkCard___"
  },
  {
    "type": "post",
    "url": "/function/update-function",
    "title": "功能管理 - 修改功能",
    "version": "1.0.0",
    "name": "___________",
    "group": "function",
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
            "field": "id",
            "description": "<p>修改的id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>功能名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "eName",
            "description": "<p>英文名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>功能描述</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"id\":\"1\"\n     \"name\": \"添加功能\",\n     \"eName\": \"add\",\n     \"note\": \"水吧管理员\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>功能管理 - 修改功能<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/function/update-function</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/function/update-function"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'修改功能名不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/FunctionController.php",
    "groupTitle": "function"
  },
  {
    "type": "post",
    "url": "/function/top-module",
    "title": "功能管理 - 新增功能",
    "version": "1.0.0",
    "name": "___________",
    "group": "function",
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
            "field": "name",
            "description": "<p>功能名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "eName",
            "description": "<p>英文名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>功能描述</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"name\": \"添加功能\",\n     \"eName\": \"add\",\n     \"note\": \"水吧管理员\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>功能管理 - 新增功能<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/function/add-function</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/function/add-function"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'新增功能名不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/FunctionController.php",
    "groupTitle": "function"
  },
  {
    "type": "get",
    "url": "/function/delete-function",
    "title": "功能管理 - 删除功能",
    "version": "1.0.0",
    "name": "___________",
    "group": "function",
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
            "type": "int",
            "optional": false,
            "field": "functionId",
            "description": "<p>功能ID.</p>"
          }
        ]
      }
    },
    "description": "<p>功能管理 - 删除功能<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/function/delete-function</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/function/delete-function"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"删除成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'删除失败'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/FunctionController.php",
    "groupTitle": "function"
  },
  {
    "type": "get",
    "url": "/function/function-info",
    "title": "功能管理 - 功能列表数据遍历",
    "version": "1.0.0",
    "name": "_______________",
    "group": "function",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>功能管理 - 功能列表数据遍历<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/function/function-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/function/function-info"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>功能列表数据</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "pages",
            "description": "<p>分页数据数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/FunctionController.php",
    "groupTitle": "function"
  },
  {
    "type": "get",
    "url": "/shop/set-goods-data",
    "title": "设置商品",
    "version": "1.0.0",
    "name": "____",
    "group": "goods",
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
            "type": "int",
            "optional": false,
            "field": "goodsTypeId",
            "description": "<p>类型ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": true,
            "field": "goodsId",
            "description": "<p>商品ID 修改商品时必传</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "goodsName",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "goodsBrand",
            "description": "<p>商品品牌</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "unitPrice",
            "description": "<p>商品单价</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "goodsModel",
            "description": "<p>商品型号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "goodsProducer",
            "description": "<p>生产商</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "goodsSupplier",
            "description": "<p>供应商</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>场景 添加:'insert' 修改：'update'</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /shop/shop-list\n{\n      goodsTypeId: \"1\",\n      goodsId: \"1\",\n      goodsName: '脉动',\n      goodsBrand: \"百事\",\n      unitPrice: \"100\",\n      goodsModel: \"1\",\n      goodsProducer: \"我爱运动\",\n      goodsSupplier: \"我爱运动\",\n      scenario     :'insert'\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>设置商品 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/set-goods-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status':'success',\n  'data'  :'添加成功'\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "get",
    "url": "/shop/get-goods-detail",
    "title": "获取商品详情",
    "version": "1.0.0",
    "name": "______",
    "group": "goods",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>商品Id</p>"
          }
        ]
      }
    },
    "description": "<p>获取商品详情 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/get-goods-details"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n       id\t:1\t自增ID\n      goods_type_id: \"1\", //类型ID\n      goods_name: '脉动', //商品名称\n      goods_brand: \"百事\", //商品品牌\n      unit_price: \"100\",   //商品单价\n      goods_model: \"1\",    //商品型号\n      goods_producer: \"我爱运动\",// 商品生产商\n      goods_supplier: \"我爱运动\", //商品供应商\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "post",
    "url": "/shop/set-goods-type",
    "title": "设置商品类别",
    "version": "1.0.0",
    "name": "______",
    "group": "goods",
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
            "field": "typeName",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /shop/shop-list\n{\n      typeName: \"食品类\",      //搜索栏搜索内容\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>设置商品类别 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/set-goods-type"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status':'success',\n  'data'  :'添加成功'\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "get",
    "url": "/shop/damage-over-flow",
    "title": "商品损坏 外溢",
    "version": "1.0.0",
    "name": "_______",
    "group": "goods",
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
            "field": "scenario",
            "description": "<p>场景 入库:'damage' 出库：'overflow'</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "goodsId",
            "description": "<p>&quot;商品id&quot;,</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "number",
            "description": "<p>&quot;商品数量&quot;,</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "unit",
            "description": "<p>&quot;商品单位,</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET    /shop/damage-over-flow  商品报损\n{\n      goodsId: \"1\",        //商品id\n      number: 16,          //损坏数量\n      unit: 个,            //损坏单位\n      describe:\"好好好\"，  // 描述\n      scenario:'damage'，\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}\nGET    /shop/damage-over-flow  商品外溢\n{\n      goodsId: \"1\",        //商品id\n      number: 16,          //商品数量\n      unit: 个,            //报溢单位\n      describe:\"好好好\"，  // 描述\n      scenario :'overflow'   // 场景\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品 出入库 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/damage-over-flow"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status':'success',\n  'data'  :'操作成功'\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "get",
    "url": "/shop/get-goods-type-data",
    "title": "获取商品所有类别",
    "version": "1.0.0",
    "name": "________",
    "group": "goods",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取商品所有类别 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/get-goods-type-data"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  id\t:1\t自增ID\n  venueId\t:1\t场馆id\n  companyId\t:1\t公司id\n  goods_type\t:'食品类'\t\t商品类别\n  create_at\t:'215665527772'\t创建时间\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "get",
    "url": "/shop/get-goods-history",
    "title": "商品历史出入库记录",
    "version": "1.0.0",
    "name": "_________",
    "group": "goods",
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
            "type": "int",
            "optional": false,
            "field": "goodsId",
            "description": "<p>&quot;商品id&quot;,</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET     /shop/get-goods-history  商品历史出入库记录\n{\n      goodsId: \"1\",        //商品id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品历史出入库记录 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/damage-over-flow"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n   id:27,\n  goods_id :12,   //商品id\n  status  : 1，   //商品状态（1:入库 2：出库 3：报损 4:退库 5:报溢）\n operation_num:12 //商品操作数量\n list_num:111111  //商品单号\n unit:个          //单位\n create_at:12123131 //创建时间\n describe: \"哈好好\" // 描述\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "goods"
  },
  {
    "type": "post",
    "url": "/member-ship/member-hard-register",
    "title": "会员注册",
    "version": "1.0.0",
    "name": "____",
    "group": "hardRegister",
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
            "description": "<p>手机号</p>"
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
            "type": "int",
            "optional": false,
            "field": "sex",
            "description": "<p>性别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "IDNumber",
            "description": "<p>身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberName",
            "description": "<p>会员姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /member-ship/member-hard-register\n{\n     \"mobile\":15011122233  //会员手机号\n     \"code\":456123         //验证码\n     \"sex\":1              // 1:代表 男 2代表女\n     \"IDNumber\":410278219900881   //身份证号\n     \"memberName\":王大锤    //会员姓名\n      \"password\":123456     // 会员密码\n      \"_csrf_backend\":\"2e12egjkqsdguidaudgiqgd\" // 防止跨站伪造\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>用户注册 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/member-hard-register"
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
          "title": "返回值详情",
          "content": "{\n \"status\":\"success\",\n \"message\":\"注册成功\",\n  \"member\":[\n    \"id\"=>3456,\n     \"memberName\"=>\"王大锤\"\n     ]\n \"data\": \"成功信息\",\n},\n{\n \"status\":\"error\",\n \"message\":\"注册失败\",\n \"data\" : \"失败信息\"\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "hardRegister"
  },
  {
    "type": "post",
    "url": "/member-ship/member-hardware-login",
    "title": "*硬件会员登录",
    "version": "1.0.0",
    "name": "_______",
    "group": "hardwareRegister",
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
            "description": "<p>// 手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>// 姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>// 姓名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST  /member-ship/member-hardware-login\n{\n     \"mobile\":15537312039    //会员手机号\n     \"name\":  王大崔           // 姓名\n     \"_csrf_backend\":'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA=='  // csrf\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>硬件会员 通过 手机号和姓名 登录 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/member-hardware-login"
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
          "title": "返回值详情",
          "content": "{\n    {\n\"status\": \"success\",\n\"paramComplete\": true,   // 参数是否完整  false 不完整  true完整\n\"message\": \"登录成功\",\n\"member\": {\n\"id\": \"3253\",          // 会员id\n\"mobile\": \"13526508176\",  // 会员手机号\n\"memberName\": \"程兵兵测试\",  // 会员姓名\n\"sex\": \"1\",                 // 会员性别  1男 2女\n\"idCard\": \"410328199004129611\"  // 身份证号\n    }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "hardwareRegister"
  },
  {
    "type": "get",
    "url": "/league/get-mobile-info",
    "title": "员工手机号去重",
    "version": "1.0.0",
    "name": "_______",
    "group": "league",
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
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "description": "<p>管理员添加教练-手机号验证去重 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/league/get-mobile-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/league/get-mobile-info"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>验证状态 ｛error：手机号存在，success：手机不存在｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/LeagueController.php",
    "groupTitle": "league"
  },
  {
    "type": "post",
    "url": "/member/set-member-charge-transfer",
    "title": "会员转课记录",
    "version": "1.0.0",
    "name": "____",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberNumber",
            "description": "<p>会员卡号.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "transferPrice",
            "description": "<p>转让金额.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "transferNum",
            "description": "<p>转让节数.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "chargeId",
            "description": "<p>转让课程ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /check-card/set-member-charge-transfer\n{\n    \"memberId\": 125,\n    \"memberNumber\": 0929890,\n    \"transferPrice\": 3000,\n    \"transferNum\":2,\n    \"chargeId\":2,\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员进入会员卡详情-会员转课 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26<br> <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/member/set-member-charge-transfer</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/set-member-charge-transfer"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"转课成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'memberId':'会员ID不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "post",
    "url": "/member/card-update",
    "title": "会员卡升级",
    "version": "1.0.0",
    "name": "_____",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "decimal",
            "optional": false,
            "field": "sellPrice",
            "description": "<p>售价</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "discount",
            "description": "<p>折扣</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "seller",
            "description": "<p>销售员</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cardId",
            "description": "<p>新卡种id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post /member/card-update\n{\n     \"memberCardId\"=>60,\n     \"sellPrice\"=>2000,\n     \"discount\"=>\"8\",\n     \"seller\"=>2,\n     \"cardId\"=>61,\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}\npost /member/card-update",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员卡详情 - 会员卡升级<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26<br> <span><strong>调用方法：</strong></span>/member/card-update</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/card-update"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':“升级成功”}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':“请填写售价”}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/member/get-new-card",
    "title": "查询新卡信息",
    "version": "1.0.0",
    "name": "______",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "cardId",
            "description": "<p>卡种id</p>"
          }
        ]
      }
    },
    "description": "<p>会员卡升级 - 查询新卡信息<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/25<br> <span><strong>调用方法：</strong></span>/member/get-new-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/get-new-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"id\":\"10\",\"category_type_id\":\"1\",...}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "[]",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/member/history",
    "title": "会员缴费记录",
    "version": "1.0.0",
    "name": "______",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          }
        ]
      }
    },
    "description": "<p>会员卡详情 - 会员缴费记录<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/23<br> <span><strong>调用方法：</strong></span>/member/history</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/history"
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
            "description": "<p>会员缴费记录 ｛consumption_date：缴费时间，card_name：缴费名称, consumption_amount：缴费金额, name：客服, invalid_time：有效期, consumption_type：行为｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/member/get-member-card",
    "title": "获取会员卡详情",
    "version": "1.0.0",
    "name": "_______",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          }
        ]
      }
    },
    "description": "<p>会员卡详情<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26<br> <span><strong>调用方法：</strong></span>/member/get-member-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/transfer-card"
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
            "description": "<p>会员卡信息数据 ｛amount_money：金额，duration：总天数, invalid_time：到期时间, 剩余天数需要计算｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/member/get-card-type",
    "title": "查询同类型卡种",
    "version": "1.0.0",
    "name": "_______",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          }
        ]
      }
    },
    "description": "<p>会员卡升级 - 查询同类型卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/25<br> <span><strong>调用方法：</strong></span>/member/get-card-type</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/get-card-type"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "[]",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "post",
    "url": "/member/transfer-card",
    "title": "会员转卡",
    "version": "1.0.0",
    "name": "____________",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "transferPrice",
            "description": "<p>转让金额</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "post /member/transfer-card\n{\n     \"memberCardId\"=>60,\n     \"name\"=>李丽,\n     \"mobile\"=>\"15789562356\",\n     \"transferPrice\"=>2000,\n     \"_csrf_backend\"=>\"_asjbbjkashdjkashdkashdkhasdhaskda==\",\n}\npost /member/transfer-card",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员卡详情 - 会员转卡 <br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26<br> <span><strong>调用方法：</strong></span>/member/transfer-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/transfer-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':“转卡成功”}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':“手机号不存在”}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "post",
    "url": "/member/card-renew",
    "title": "会员卡续费",
    "version": "1.0.0",
    "name": "_____________",
    "group": "member",
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
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "renewDate",
            "description": "<p>续费日期.</p>"
          },
          {
            "group": "Parameter",
            "type": "decimal",
            "optional": false,
            "field": "renewPrice",
            "description": "<p>续费金额.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>到期日.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "seller",
            "description": "<p>销售员</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /member/card-renew\n{\n    \"memberCardId\": 50,\n    \"renewDate\": 2017-5-25,\n    \"renewPrice\": 3000,\n    \"endTime\":2018-5-25,\n    \"seller\":2,\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员进入会员卡详情-会员卡续费 <br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26<br> <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/member/card-renew</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/card-renew"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"续费成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'renewDate':'续费日期不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "member"
  },
  {
    "type": "get",
    "url": "/user/all-modules",
    "title": "获取模块",
    "version": "1.0.0",
    "name": "____",
    "group": "modules",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>模块ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /user/member-behavior-trail\n{\n      id: \"1\",      //模块ID 如果获取顶级 不需要传值\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取模块 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/13</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/all-modules"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\ncreate_at:\"1497788315\"\ncreate_id:\"0\"\ne_name:\"systemIndex\"\nicon:\"glyphicon glyphicon-home\"\nid:\"1\"\nlevel:\"1\"\nname:\"系统首页\"\nnote:\"顶级菜单\"\npid:\"0\"\nupdate_at:null\nurl:null\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "modules"
  },
  {
    "type": "post",
    "url": "/member-ship-pay/sell-card",
    "title": "微信二维码支付",
    "version": "1.0.0",
    "name": "_______",
    "group": "oregister",
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
            "field": "paymentType",
            "description": "<p>付款类型：wx</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>卡种id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>购买物品标志</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "csrf_",
            "description": "<p>防止跨站伪造请求</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /member-ship-pay/sell-card\n{\n      \"paymentType\":wx      //微信扫码支付\n      \"typeId\":45781         // 卡种id\n      \"memberId\":78278       // 会员id\n      \"type\": machineCard    //购买类型 卡\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>迈步微信二维码扫码支付 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship-pay/sell-card"
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
          "title": "返回值详情",
          "content": "{\n \"status\":\"success\"   // 请求成功 返回success  请求失败返回error\n \"data\" : \"weixin://wxpay/bizpayurl?pr=iGfCe4K\"   // 请求的二维码链接 请求失败返回报错信息\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipPayController.php",
    "groupTitle": "oregister"
  },
  {
    "type": "get",
    "url": "/personnel/get-department",
    "title": "获取部门",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取部门<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-department</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-department"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"3\",\n\"pid\": \"2\",\n\"name\": \"私教部\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/LoginController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue-data",
    "title": "获取场馆",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取场馆<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-venue -data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"2\",\n\"pid\": \"1\",\n\"name\": \"大上海馆\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue",
    "title": "获取公司",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取公司<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/login/get-venue"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"1\",\n\"pid\": \"0\",\n\"name\": \"我爱运动瑜伽健身俱乐部\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/LoginController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-department",
    "title": "获取部门",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取部门<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-department</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-department"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"3\",\n\"pid\": \"2\",\n\"name\": \"私教部\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue",
    "title": "获取公司",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取公司<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-venue"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"1\",\n\"pid\": \"0\",\n\"name\": \"我爱运动瑜伽健身俱乐部\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "post",
    "url": "/personnel/assign-admin",
    "title": "分配账号",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>管理员可以进入员工管理给员工分配账号 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "username",
            "description": "<p>账号名称.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码.mobile</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆Id.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "employeeId",
            "description": "<p>员工Id.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": true,
            "field": "adminId",
            "description": "<p>管理员ID修改是必传</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>场景：添加(insert) 修改(update)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "level",
            "description": "<p>级别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/assign-admin"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':‘分配成功’}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':预约失败数据}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue-data",
    "title": "获取场馆",
    "version": "1.0.0",
    "name": "____",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取场馆<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/login/get-venue-data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"2\",\n\"pid\": \"1\",\n\"name\": \"大上海馆\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/LoginController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue-all-data",
    "title": "获取所有场馆",
    "version": "1.0.0",
    "name": "______",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取所有场馆<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue-all-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/login/get-venue-all-data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"2\",\n\"pid\": \"1\",\n\"name\": \"大上海馆\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/LoginController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-assign-admin",
    "title": "获取权限分类",
    "version": "1.0.0",
    "name": "______",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>管理员可以进入分配账号页面可以获取权限分类 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-assign-admin"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':{1:'超级管理员'}}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':预约失败数据}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-department-all-data",
    "title": "获取所有部门",
    "version": "1.0.0",
    "name": "______",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取所有部门<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-department-all-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-department-all-data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"3\",\n\"pid\": \"2\",\n\"name\": \"私教部\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-venue-all-data",
    "title": "获取所有场馆",
    "version": "1.0.0",
    "name": "______",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>获取所有场馆<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/2<br> <span><strong>调用方法：</strong></span>/personnel/get-venue-all-data</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-venue-all-data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n\"venues\": [\n{\n\"id\": \"2\",\n\"pid\": \"1\",\n\"name\": \"大上海馆\",\n}\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n\"venues\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-employee-name",
    "title": "添加员工名去重",
    "version": "1.0.0",
    "name": "_______",
    "group": "personnel",
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
            "field": "name",
            "description": "<p>员工名字</p>"
          }
        ]
      }
    },
    "description": "<p>管理员进入员工管理-添加员工姓名 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/13 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/personnel/get-employee-name</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-employee-name"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>添加状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':‘员工姓名不存在,添加成功’}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':员工姓名存在}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/employee-center",
    "title": "员工个人中心数据",
    "version": "1.0.0",
    "name": "________",
    "group": "personnel",
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
            "field": "name",
            "description": "<p>员工名字</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sex",
            "description": "<p>性别1男2女</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>员工头像</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "admin_user_id",
            "description": "<p>管理员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "position",
            "description": "<p>职务</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>账号</p>"
          }
        ]
      }
    },
    "description": "<p>管理员进入角色管理-查看个人中心数据 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/6 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/personnel/employee-center</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/employee-center"
      }
    ],
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-employee-admin-level-one",
    "title": "获取员工权限信息",
    "version": "1.0.0",
    "name": "________",
    "group": "personnel",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>管理员可以进入分配账号页面可以获取员工权限信息 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-employee-admin-level-one"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':{username:'lll'...}}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','data':[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "post",
    "url": "/personnel/update-pic",
    "title": "角色管理 - 修改头像",
    "version": "1.0.0",
    "name": "___________",
    "group": "personnel",
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
            "field": "id",
            "description": "<p>修改的员工id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>员工头像</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"id\":\"1\"\n     \"pic\": \"fjkdljsldgjsdl\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>角色管理 - 修改头像<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/personnel/update-pic</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/update-pic"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'修改图片名不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "post",
    "url": "/personnel/update-password",
    "title": "角色管理 - 旧密码修改",
    "version": "1.0.0",
    "name": "____________",
    "group": "personnel",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>修改的管理员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "oldPassword",
            "description": "<p>旧密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "rePassword",
            "description": "<p>确认新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"id\":\"1\"\n     \"oldPassword\": \"123456\",\n     \"password\": \"654321\",\n     \"rePassword\": \"654321\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>角色管理 - 旧密码修改<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/7/6<br/> <span><strong>调用方法：</strong></span>/personnel/update-password</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/update-password"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'修改的密码不符合规范'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "get",
    "url": "/personnel/get-employee-member",
    "title": "获取销售顾问下的会员信息",
    "version": "1.0.0",
    "name": "____________",
    "group": "personnel",
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
            "type": "int",
            "optional": false,
            "field": "employeeId",
            "description": "<p>销售员工id</p>"
          }
        ]
      }
    },
    "description": "<p>管理员进入员工详情基本信息-销售人员下的会员信息 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/personnel/get-employee-member</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/personnel/get-employee-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>销售员工下的会员信息 ｛name：会员名称，sex：性别,mobile：手机号，status:会员状态，register_time:注册时间,invalid_time:失效时间｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/PersonnelController.php",
    "groupTitle": "personnel"
  },
  {
    "type": "post",
    "url": "/member/edit-member",
    "title": "潜在会员（编辑）",
    "version": "1.0.0",
    "name": "______",
    "group": "potentialMember",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sex",
            "description": "<p>性别 1：男 2: 女</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "idCard",
            "description": "<p>会员身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "birthDate",
            "description": "<p>会员出生日期</p>"
          },
          {
            "group": "Parameter",
            "type": "decimal",
            "optional": false,
            "field": "deposit",
            "description": "<p>会员定金</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "wayToShop",
            "description": "<p>来电途径</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "counselorId",
            "description": "<p>会籍顾问ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /member/set-member-info\n{\n     \"memberId\": 12,                   // 会员id\n     \"sex\": 1,                         // 会员性别\n     \"idCard \": \"410782199303060958\", // 身份证号\n     \"birthDate: \"1993-05-08\",        // 出生日期\n     \"deposit\":  92,                  // 会员定金\n     \"wayToShop\": 52,                 // 会员到店途径 下拉列表id\n     \"counselorId\": \"1\",              //会籍顾问ID\n     \"note\": \"这是备注,               //备注\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>潜在会员（编辑） <br/> <span><strong>作   者：</strong></span>侯凯新<br> <span><strong>邮   箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/7/12</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/edit-member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"成功信息\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    \"报错信息\"\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "potentialMember"
  },
  {
    "type": "get",
    "url": "/potential-members/get-source",
    "title": "获取潜在会员销售来源",
    "version": "1.0.0",
    "name": "______",
    "group": "potentialMember",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>潜在会员查询下拉列表，返回的销售来源<br/> <span><strong>作    者：</strong></span>黄鹏举<br> <span><strong>邮    箱：</strong></span>huangpengju@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5<br> <span><strong>调用方法：</strong></span>/potential-members/get-source</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/potential-members/get-source"
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
            "description": "<p>返回数据</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "[]",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PotentialMembersController.php",
    "groupTitle": "potentialMember"
  },
  {
    "type": "post",
    "url": "/member/set-member-info",
    "title": "新增潜在会员",
    "version": "1.0.0",
    "name": "________",
    "group": "potentialMember",
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
            "field": "memberName",
            "description": "<p>会员姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberMobile",
            "description": "<p>会员手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": true,
            "field": "memberSex",
            "description": "<p>会员性别（1表示男 2表示女）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": true,
            "field": "memberAge",
            "description": "<p>会员年龄</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "idCard",
            "description": "<p>会员身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "birthDate",
            "description": "<p>会员生日</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "counselorId",
            "description": "<p>会籍顾问ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "note",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /member/set-member-info\n{\n     \"memberName\": \"张三\",\n     \"memberMobile\": \"15713718822\",\n     \"memberSex\": \"1\",\n     \"memberAge\": \"21\",\n     \"idCard\": \"412865658242524217\",\n     \"birthDate\": \"2000-01-01\",\n     \"counselorId\": \"1\",\n     \"note\": \"这是备注,\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以新增潜在会员 <br/> <span><strong>作   者：</strong></span>黄鹏举<br> <span><strong>邮   箱：</strong></span>huangpengju@itsprts.club <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/member/set-member-info"
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
            "description": "<p>返回的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/MemberController.php",
    "groupTitle": "potentialMember"
  },
  {
    "type": "get",
    "url": "/user/member-information",
    "title": "获取潜在会员详细信息",
    "version": "1.0.0",
    "name": "__________",
    "group": "potentialMember",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>潜在会员id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /user/member-information\n{\n    \"memberId\": 125,\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以进入潜在会员详细信息页面 <br/> <span><strong>作   者：</strong></span>黄鹏举<br> <span><strong>邮   箱：</strong></span>huangpengju@itsprts.club <span><strong>创建时间：</strong></span>2017/5/27</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/member-information"
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
            "description": "<p>返回的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "potentialMember"
  },
  {
    "type": "post",
    "url": "/potential-members/set-source",
    "title": "新增潜在会员销售来源",
    "version": "1.0.0",
    "name": "___________",
    "group": "potentialMember",
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
            "field": "source",
            "description": "<p>销售来源</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>场景  （source）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /potential-members/set-source\n{\n     \"source\": \"网络\",\n     \"scenario\":\"source\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以新增销售来源 <br/> <span><strong>作   者：</strong></span>黄鹏举<br> <span><strong>邮   箱：</strong></span>huangpengju@itsprts.club <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/potential-members/set-source"
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
            "description": "<p>返回的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/PotentialMembersController.php",
    "groupTitle": "potentialMember"
  },
  {
    "type": "post",
    "url": "/potential-members/set-member-deposit-form",
    "title": "增加定金",
    "version": "1.0.0",
    "name": "____",
    "group": "potential_members",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "price",
            "description": "<p>定金金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "voucher",
            "description": "<p>代金卷</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "payMode",
            "description": "<p>方式</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"memberId\":\"1\"\n     \"price\":\"100\"\n     \"voucher\":\"500\"\n     \"startTime\":\"2017-6-27\"\n     \"endTime\":\"2017-7-30\"\n     \"member_id\":\"1\"\n     \"payMode\": \"1\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>潜在会员 - 增加定金<br/> <span><strong>作   者：</strong></span>李慧恩<br/> <span><strong>邮   箱：</strong></span>lihuien@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/21<br/> <span><strong>调用方法：</strong></span>/potential-members/set-member-deposit-form</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/potential-members/set-member-deposit-form"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'memberId':'会员ID不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PotentialMembersController.php",
    "groupTitle": "potential_members"
  },
  {
    "type": "post",
    "url": "/potential-members/get-member-deposit-one",
    "title": "获取会员定金",
    "version": "1.0.0",
    "name": "______",
    "group": "potential_members",
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
            "type": "int",
            "optional": false,
            "field": "member_id",
            "description": "<p>会员id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"memberId\":\"1\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>潜在会员 - 增加定金<br/> <span><strong>作   者：</strong></span>李慧恩<br/> <span><strong>邮   箱：</strong></span>lihuien@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/21<br/> <span><strong>调用方法：</strong></span>/potential-members/get-member-deposit-one</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/potential-members/get-member-deposit-one"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{  \"memberId\":\"1\"\n       \"price\":\"100\"\n       \"voucher\":\"500\"\n       \"startTime\":\"2017-6-27\"\n       \"endTime\":\"2017-7-30\"\n       \"member_id\":\"1\"\n       \"payMode\": \"1\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PotentialMembersController.php",
    "groupTitle": "potential_members"
  },
  {
    "type": "post",
    "url": "/potential-members/update-potential",
    "title": "潜在会员 - 修改销售来源",
    "version": "1.0.0",
    "name": "_____________",
    "group": "potential_members",
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
            "field": "id",
            "description": "<p>修改的配置表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "source",
            "description": "<p>销售来源的值</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "scenario",
            "description": "<p>场景  （source）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"id\":\"1\"\n     \"value\": \"网络\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>潜在会员 - 修改销售来源<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/21<br/> <span><strong>调用方法：</strong></span>/potential-members/update-potential</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/potential-members/update-potential"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'修改销售来源的值不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PotentialMembersController.php",
    "groupTitle": "potential_members"
  },
  {
    "type": "get",
    "url": "/contract/get-deal",
    "title": "*添加私课 合同",
    "version": "1.0.0",
    "name": "________",
    "group": "privateDeal",
    "permission": [
      {
        "name": "管理员\n\n{get} /contract/get-deal"
      }
    ],
    "description": "<p>合同管理 *添加私课 合同 <span><strong>作    者：</strong></span>焦冰洋<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/15</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/contract/get-deal"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>保存状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回保存状态的数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/ContractController.php",
    "groupTitle": "privateDeal"
  },
  {
    "type": "get",
    "url": "/private-lesson/private-class",
    "title": "教练-会员约课数据遍历（每一周）",
    "version": "1.0.0",
    "name": "________________",
    "group": "private_lesson",
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
            "field": "weekStart",
            "description": "<p>周开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "weekEnd",
            "description": "<p>周结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "coachId",
            "description": "<p>教练id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{get} /private-lesson/private-class\n{\n     \"weekStart\"=>\"2017-5-29\",  //第一次不用发送（之后每一次发送）\n     \"weekEnd\"=>\"2017-6-4\",     //第一次不用发送（之后每一次发送）\n     \"coachId\"=>2,              //每一次都必须发送\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>教练-会员约课数据遍历（每一周） <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-lesson/private-class"
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
            "description": "<p>返回每一周的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "{'class_start':'1495869155',   //课程开始时间",
          "content": "{'class_start':'1495869155',   //课程开始时间\n 'class_end'  :\"1495879155\",   // 课程结束时间\n 'username'   : \"王亚娟\",      // 会员姓名\n \"name\"       :\"课程名称\"，    // 课程名称\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PrivateLessonController.php",
    "groupTitle": "private_lesson"
  },
  {
    "type": "get",
    "url": "/private-teach/member",
    "title": "搜索会员",
    "version": "1.0.0",
    "name": "____",
    "group": "private_teach",
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
            "type": "int",
            "optional": false,
            "field": "MemberId",
            "description": "<p>会员id</p>"
          }
        ]
      }
    },
    "description": "<p>私教管理-课程排期-登记预约会员 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/31 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/private-teach/member</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/member"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "memberInfo",
            "description": "<p>会员名手机号编号</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "post",
    "url": "/private-teach/update-charge-pic",
    "title": "修改私教图片",
    "version": "1.0.0",
    "name": "______",
    "group": "private_teach",
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
            "type": "int",
            "optional": false,
            "field": "classId",
            "description": "<p>课程ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>会员Id.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /private-teach/set-about-class-charge\n{\n    \"classId\": 125,\n    \"pic\"     : 'jashdkashdkasdjqwwodjwiournsdcm,snxccksmdbjsdfhajks'\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以进入验卡页面可以给会员预约课程 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/update-charge-pic"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','message'=>'成功','data':返回刚才预约ID}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','message'=>'失败','data':预约失败数据}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "post",
    "url": "/private-teach/set-about-class-charge",
    "title": "预约私课课程",
    "version": "1.0.0",
    "name": "______",
    "group": "private_teach",
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
            "type": "int",
            "optional": false,
            "field": "classId",
            "description": "<p>课程ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutType",
            "description": "<p>预约类型.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "classDate",
            "description": "<p>课程时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start",
            "description": "<p>课程开始时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end",
            "description": "<p>课程结束时间.</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员Id.</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST /private-teach/set-about-class-charge\n{\n    \"classId\": 125,\n    \"aboutType\": \"mobile\",\n    \"classDate\": 3,\n    \"start\": 3,\n    \"end\": 3,\n    \"memberId\":2,\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员可以进入验卡页面可以给会员预约课程 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/5/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/set-about-class-charge"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','message'=>'预约成功','data':返回刚才预约ID}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','message'=>'预约失败','data':预约失败数据}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "get",
    "url": "/private-teach/employee-info",
    "title": "获取所有私教员工",
    "version": "1.0.0",
    "name": "______",
    "group": "private_teach",
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
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>私教员工</p>"
          }
        ]
      }
    },
    "description": "<p>私教管理列表-所有私教员工信息 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/27 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/private-teach/employee-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/employee-info"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>私教员工</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "get",
    "url": "/private-teach/member-details",
    "title": "预约课程详情信息",
    "version": "1.0.0",
    "name": "________",
    "group": "private_teach",
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
            "type": "int",
            "optional": false,
            "field": "MemberId",
            "description": "<p>会员id</p>"
          }
        ]
      }
    },
    "description": "<p>私教管理-课程排期-预约课程详情 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/31 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/private-teach/member</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/member-details"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "memberInfo",
            "description": "<p>预约课程详情</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "get",
    "url": "/private-teach/charge-class-details",
    "title": "私教服务详情、私教课程详情",
    "version": "1.0.0",
    "name": "_____________",
    "group": "private_teach",
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
            "type": "int",
            "optional": false,
            "field": "chargeClassId",
            "description": "<p>私课ID.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"chargeClassId\": 2,\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>私课管理 - 私教服务详情、私教课程详情<br/> <span><strong>作    者：</strong></span>朱梦珂<br/> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/8<br/> <span><strong>调用方法：</strong></span>/private-teach/charge-class-details</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/private-teach/charge-class-details"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>取消状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回对应状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "Array\n(\n[id] => 7\n[name] => 形体芭蕾\n[valid_time] => 23\n[activated_time] => 234\n[total_sale_num] => 34\n[sale_start_time] => 1496073600\n[sale_end_time] => 1499356800\n[describe] => 4354波特忍一会涂鸦\n[pic] => http://oo0oj2qmr.bkt.clouddn.com/19.jpeg?e=1496650626&token=su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S:xiC739XZoIqOoLuZVfYV-jPRE8Q=\n[total_amount] => 34\n[total_pos_price] => 34\n[cname] => 舞蹈\n[oname] => 大上海馆\n)",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/PrivateTeachController.php",
    "groupTitle": "private_teach"
  },
  {
    "type": "post",
    "url": "/member-ship/member-register",
    "title": "会员注册",
    "version": "1.0.0",
    "name": "____",
    "group": "register",
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
            "description": "<p>手机号</p>"
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
            "type": "int",
            "optional": false,
            "field": "sex",
            "description": "<p>性别</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "IDNumber",
            "description": "<p>身份证号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberName",
            "description": "<p>会员姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>用户密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>防止跨站伪造</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST /v1/api-member/login\n{\n     \"mobile\":15011122233  //会员手机号\n     \"code\":456123         //验证码\n     \"sex\":1              // 1:代表 男 2代表女\n     \"IDNumber\":410278219900881   //身份证号\n     \"memberName\":王大锤    //会员姓名\n      \"password\":123456     // 会员密码\n      \"_csrf_backend\":\"2e12egjkqsdguidaudgiqgd\" // 防止跨站伪造\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>用户注册 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/24</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/member-register"
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
          "title": "返回值详情",
          "content": "{\n \"status\":\"success\",\n \"message\":\"注册成功\",\n  \"member\":[\n    \"id\"=>3456,\n     \"memberName\"=>\"王大锤\"\n     ]\n \"data\": \"成功信息\",\n},\n{\n \"status\":\"error\",\n \"message\":\"注册失败\",\n \"data\" : \"失败信息\"\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "register"
  },
  {
    "type": "post",
    "url": "/member-ship/member-login",
    "title": "*会员登录",
    "version": "1.0.0",
    "name": "_____",
    "group": "register",
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
            "description": "<p>// 手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>// 验证码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST  /member-ship/potential-member\n{\n     \"mobile\":15537312038    //会员手机号\n     \"code\":253600           // 验证码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员通过手机号登录 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/member-login"
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
          "title": "返回值详情",
          "content": "{\n\"status\":登录状态  // error：登录失败  success:登录成功\n\"message\":您信息还不完善,请从主页进入    // 失败或成功后登录信息\n \"data\" :{  返回老会员登录后的信息  （登录不成功返回空数组）\n    \"id\":54321,   // 会员id\n   \"memberName\": 王大锤,  //会员电话\n  },",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "register"
  },
  {
    "type": "get",
    "url": "/member-ship/card-category-message",
    "title": "卡种详细信息",
    "version": "1.0.0",
    "name": "______",
    "group": "register",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>// 卡种id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET  /member-ship/get-card-category\n{\n      \"id\":56    // 卡种id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取单一卡种的详细信息 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/25</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/card-category-message"
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
          "title": "返回值详情",
          "content": "{\n \"data\" :\n{\n    {\n    \"data\": {\n    \"id\": \"1051\",          // 卡种id\n    \"type_name\": \"时间卡\",  // 卡类型名称\n    \"category_type_id\": \"1\",  //卡类型id\n    \"card_name\": \"444\",      // 卡名称\n    \"original_price\": \"12.00\",  // 一口原价\n    \"sell_price\": \"12.00\",   //一口售价\n    \"max_price\": null,     // 最高价 (目前价格 暂定最高价)\n    \"min_price\": null,     // 最低价\n     \"pic\":fbkwafklgvnklasjdlhshd, // 会员卡图片\n    \"transfer_price\": \"12.00\", //转让金额\n    \"leave_total_days\": \"11\",  //请假总天数\n    \"leave_total_times\": null, // 请假总次数\n     \"attributes\":1,\t// 1个人2公司3团体\n    \"active_time\":5    // 激活期限\n     \"bring\":1        // 0代表不带人 1代表带人\n    \"leave_long_limit\": \"null\", //最长请假天数,最长请假次数[天，次],[天，次]\n    \"leave_least_Days\": \"2\",   //每次请假最少天数\n    \"duration\": 84,     // 卡有效期 84天\n    \"card_type\": \"3\",  // 1:瑜伽,2:健身,3舞蹈,4:综合 5:vip\n    \"leagueBindPack\": [    // 绑定团课套餐（约课范围）\n    {\n    \"id\": \"752\",\n    \"card_category_id\": \"1051\",\n    \"polymorphic_id\": \"1\",\n    \"polymorphic_type\": \"class\",\n    \"number\": \"12\",    // 每天最多预约节数（如果number：1表示不限次数）\n    \"name\": \"瑜伽\"     // 课程名称\n    },\n    {\n    \"id\": \"753\",\n    \"card_category_id\": \"1051\",\n    \"polymorphic_id\": \"2\",\n    \"polymorphic_type\": \"class\",\n    \"number\": \"12\",    // 每天最多预约节数（如果number：1表示不限次数）\n    \"name\": \"舞蹈\"\n    },\n    ],\n    \"privateLessonPack\": [   // 赠送私课套餐\n    {\n    \"id\": \"757\",\n    \"card_category_id\": \"1051\",\n    \"polymorphic_id\": \"14\",\n    \"polymorphic_type\": \"hs\",\n    \"number\": \"11\",    // 赠送私教节数\n    \"name\": \"热瑜伽\"   //赠送课程名称\n    },\n    {\n    \"id\": \"758\",\n    \"card_category_id\": \"1051\",\n    \"polymorphic_id\": \"15\",\n    \"polymorphic_type\": \"pt\",\n    \"number\": \"11\",\n    \"name\": \"普拉提\"\n    },\n    ],\n    \"theLimitCardNumber\": [     // 该卡的通店场馆\n    {\n    \"card_category_id\": \"1054\",   //卡种id\n    \"venue_id\": \"10\",            //场馆id\n    \"level\": \"2\",               // 卡等级 1：普通 2：vip\n    \"week_times\": \"99\",         // 每周限制的通店次数\n    \"month_times\": null,        // 每月的通店次数\n    \"venueName\": \"大学路舞蹈健身馆\"   // 通店场馆名称\n     \"goVenueTime\":\"12:00-16:00\"     // 会员进场时间在 12点-16点\n    },\n     ]\n    \"giftPack\": [               // 绑定的赠品\n    {\n    \"id\": \"756\",\n    \"card_category_id\": \"1051\",\n    \"polymorphic_id\": \"8\",\n    \"polymorphic_type\": \"gift\",\n    \"number\": \"12\",\n    \"name\": \"赠送天数\",\n    \"gift\":{\n    \"id\": \"8\",\n    \"member_id\": \"67308\",\n    \"member_card_id\": \"64445\",\n    \"service_pay_id\": \"5\",\n    \"num\": \"10\",            // 赠品数量\n    \"status\": \"2\",\n    \"name\": \"赠送天数\",     // 赠品名称\n    \"create_at\": \"1508749412\",\n    \"get_day\": \"1508749412\",\n    \"class_type\": \"day\",\n    \"note\": \"是是是\"\n    }\n    }\n    ]\n    }\n    }\n    },",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "register"
  },
  {
    "type": "get",
    "url": "/member-ship/get-card-category",
    "title": "不同的卡种信息",
    "version": "1.0.0",
    "name": "_______",
    "group": "register",
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
            "type": "int",
            "optional": false,
            "field": "cardType",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET  /member-ship/get-card-category\n{\n     \"cardType\":1    //1:瑜伽,2:健身,3舞蹈,4:综合等 5：VIP\n      \"page\":page    // 当前页码\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取不同的卡种信息 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/25</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/get-card-category"
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
          "title": "返回值详情",
          "content": "{\n \"pages\":页码信息\n \"data\" :\n{\n\"id\": \"134\",           //卡种id\n\"venue_id\": \"56\",      //场馆id\n\"category_type_id\": \"1\",  // 卡类型id\n\"card_name\": \"迈步健身卡年卡（预售）\", // 卡名称\n\"sell_start_time\": null,     //售卖开始时间\n\"sell_end_time\": null,       //售卖结束时间\n\"duration\": \"{\\\"day\\\": 360}\",  // 时间长度\n\"original_price\": \"799.00\",   // 一口原价\n\"sell_price\": \"799.00\",      // 一口售价\n\"pic\":fbkwafklgvnklasjdlhshd, // 会员卡图片\n\"max_price\": null,          // 最高价\n\"min_price\": null,          // 最低价\n\"offer_price\": null,        // 优惠价\n\"type_name\": \"时间卡\"       // 卡类型名称\n    },\n}\n \"isEndPage\":是否是最后一页\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "register"
  },
  {
    "type": "post",
    "url": "/member-ship-pay/ali-sell-card",
    "title": "支付宝网站支付",
    "version": "1.0.0",
    "name": "_______",
    "group": "register",
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
            "field": "paymentType",
            "description": "<p>付款类型：zfbScanCode</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "typeId",
            "description": "<p>卡种id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>购买物品标志 （固定值：card）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "csrf_",
            "description": "<p>防止跨站伪造请求</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST   /member-ship-pay/ali-sell-card\n{\n      \"paymentType\":zfbScanCode    //微信扫码支付(固定值)\n      \"typeId\":45781              // 卡种id\n      \"memberId\":78278           // 会员id\n      \"type\": card              //购买类型 卡\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>支付宝二维码支付 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship-pay/ali-sell-card"
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
          "title": "返回值详情",
          "content": "{\n \"status\":\"success\"   // 请求成功 返回success  请求失败返回error\n \"data\" :  （好像是一个网页）\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipPayController.php",
    "groupTitle": "register"
  },
  {
    "type": "get",
    "url": "/member-ship/potential-member",
    "title": "潜在会员信息",
    "version": "1.0.0",
    "name": "________",
    "group": "register",
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
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "GET  /member-ship/potential-member\n{\n     \"mobile\":15537312038    //会员手机号\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取会员潜在信息 并返回会员身份 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/26</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship/potential-member"
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
          "title": "返回值详情",
          "content": "{\n\"isNotPotentialMember\":1，    // 1新会员 2潜在会员 3:老会员\n \"data\" :{  (只有潜在会员会返回信息 老会员 新会员返回空数组)\n\"mobile\": \"15537312038\",  //会员电话\n\"company_id\": \"56\",        //公司id\n\" memberType\": \"1\",       // 会员身份信息\n\"name\": \"张三\",           // 张三\n\"sell_start_time\": null,     //售卖开始时间\n'sex':1,    // 会员性别 (1:男 2:女)\n\"id_card\":\"410782199003060958\"  // 会员身份证号\n\"password\":\"********\"      // 会员密码\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipController.php",
    "groupTitle": "register"
  },
  {
    "type": "post",
    "url": "/member-ship-pay/notify",
    "title": "微信扫码支付回调（测试专用）",
    "version": "1.0.0",
    "name": "______________",
    "group": "register",
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
            "field": "requestData",
            "description": "<p>请求参数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求参数",
          "content": "POST  /member-ship-pay/notify\n{\n      \"requestData\":xml请求数据      //微信请求参数\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>迈步微信二维码扫码支付回调 <br/> <span><strong>作    者：</strong></span>侯凯新<br/> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club <span><strong>创建时间：</strong></span>2017/10/27</p>",
    "sampleRequest": [
      {
        "url": "http://qa.aixingfu.net/member-ship-pay/notify"
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
          "title": "返回值详情",
          "content": "{\n \"data\" : \"请求很多数据\"   // 请求的二维码链接 请求失败返回报错信息\n},",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/MemberShipPayController.php",
    "groupTitle": "register"
  },
  {
    "type": "post",
    "url": "/role/add-role",
    "title": "角色管理 - 新增角色",
    "version": "1.0.0",
    "name": "___________",
    "group": "role",
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
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "companyId",
            "description": "<p>公司id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"name\": \"哈哈\",\n     \"companyId\": \"1\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>角色管理 - 新增角色<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/role/add-role</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/role/add-role"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'新增角色名不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/RoleController.php",
    "groupTitle": "role"
  },
  {
    "type": "post",
    "url": "/role/update-role",
    "title": "角色管理 - 修改角色",
    "version": "1.0.0",
    "name": "___________",
    "group": "role",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>修改的id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>角色名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "companyId",
            "description": "<p>所属公司id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"id\": \"添加功能\",\n     \"name\": \"哈哈\",\n     \"companyId\": \"2\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>角色管理 - 修改角色<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/role/update-role</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/role/role-function"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"修改成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'修改功能名不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/RoleController.php",
    "groupTitle": "role"
  },
  {
    "type": "get",
    "url": "/role/role-info",
    "title": "角色管理 - 角色列表数据遍历",
    "version": "1.0.0",
    "name": "_______________",
    "group": "role",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>角色管理 - 角色列表数据遍历<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/6/17<br/> <span><strong>调用方法：</strong></span>/role/role-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/role/role-info"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>角色列表数据</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "pages",
            "description": "<p>分页数据数据</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/RoleController.php",
    "groupTitle": "role"
  },
  {
    "type": "get",
    "url": "/sell-card/get-all-card",
    "title": "根据名称查询卡种",
    "version": "1.0.0",
    "name": "________",
    "group": "sell_card",
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
            "field": "keywords",
            "description": "<p>输入的名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序状态</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     'keywords' => 金爵,\n     'sortType' => '',\n    'sortName' => '',\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>售卡管理 - 根据名称查询卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6<br> <span><strong>调用方法：</strong></span>/sell-card/get-all-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-card/get-all-card"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{[\n{\"id\": \"1\",\"name\": \"金爵卡\",}\n]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellCardController.php",
    "groupTitle": "sell_card"
  },
  {
    "type": "get",
    "url": "/sell-card/card-category",
    "title": "获取大上海售卖的卡种",
    "version": "1.0.0",
    "name": "__________",
    "group": "sell_card",
    "permission": [
      {
        "name": "管理员"
      }
    ],
    "description": "<p>售卡管理 - 获取大上海售卖的卡种<br/> <span><strong>作    者：</strong></span>朱梦珂<br> <span><strong>邮    箱：</strong></span>zhumengke@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/15<br> <span><strong>调用方法：</strong></span>/sell-card/card-category</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/sell-card/card-category"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>返回状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>返回状态的数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{[\n{\"id\": \"1\",\"name\": \"金爵卡\",}\n]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{[]}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SellCardController.php",
    "groupTitle": "sell_card"
  },
  {
    "type": "get",
    "url": "/shop/shop-list",
    "title": "商品列表分页显示",
    "version": "1.0.0",
    "name": "________",
    "group": "shop",
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
            "field": "topSearch",
            "description": "<p>顶部搜索栏搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序的字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序的方式</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /shop/shop-list\n{\n      topSearch: \"加多宝\",      //搜索栏搜索内容\n      sortType :\"goodsName\",   //柜子排序字段 1:goodsName（商品名称）2：goodsType（商品类别）3：goodsBrand（商品品牌）4：unitPrice（商品单价）5:intoNum(入库数量)6:storeNum结库余存\n      sortName：\"ASC\",         // 排序方式（1 ASC(升序)DES（降序））\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品列表分页显示 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/shop-list"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'id' => string '1' ,                    //商品id\n  \"goodsName\"=>\"加多宝冰红茶\"            //商品名称\n  'goods_type_id' => string '1' ,       //商品类型id\n  'goodsBrand' => string '中国好声音（来）  //商品品牌\n  \"goodsModel\"=> string \"商品型号\"          //商品型号\n  'unitPrice' => string '4'                 // 商品单价\n  'goodsSupplier’ => string '加多宝公司'   // 商品供应商\n  'goodsProducer' => string '加多宝公司'  //商品生产商\n  'storage_num'    => 100              // 结库余存\n 'goods_type' => string '47' (length=2)    // 商品类别\n 'intoNum' => 100                       //商品入库数量\n'unitPrice' => 150                     //商品单价\n'storage_num' => 150                   // 库存数量 150件\n\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "shop"
  },
  {
    "type": "get",
    "url": "/cabinet/quite-cabinet",
    "title": "会员退租柜子",
    "version": "1.0.0",
    "name": "______",
    "group": "theCabinet",
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
            "field": "_csrf_backend",
            "description": "<p>csrf 防止跨站伪造</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memCabinetId",
            "description": "<p>会员柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cabinetId",
            "description": "<p>柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "price",
            "description": "<p>退租金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "quiteDate",
            "description": "<p>退租日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /cabinet/quite-cabinet\n{\n      _csrf_backend :'SG5uZUtDQXokWgBWcxw2Ph8NW1w4dyhJDyoJAA40IzUBWjEGCAoIHA==',  //csrf防止跨站\n      memCabinetId :52       // 会员柜子id\n      cabinetId：13          //柜子id\n      memberId：22           //会员id\n      price：12              //退租金额\n      quiteDate: 2012-3-12,  // 退租日期\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>点击退租柜子的操作 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/quite-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status' => \"退租成功\",           //退租状态\n  'data' => \"成功后返回的信息\" ,    //退租成功返回的信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status'=>'error',                //退租状态\n'data'=>“保存失败信息”           //退租失败返回的信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "theCabinet"
  },
  {
    "type": "post",
    "url": "/shop/out-in-storage",
    "title": "*商品 出入库 退货",
    "version": "1.0.0",
    "name": "_________",
    "group": "theGoods",
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
            "field": "scenario",
            "description": "<p>场景 入库:'storage' 出库：'library' 退货：&quot;quiteGoods&quot;</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "goodsId",
            "description": "<p>&quot;商品id&quot;,</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "number",
            "description": "<p>&quot;商品数量&quot;,</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "unit",
            "description": "<p>&quot;商品单位,</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "listNum",
            "description": "<p>&quot;商品入库单号,</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET    /shop/out-in-storage  商品入库\n{\n      goodsId: \"1\",        //商品id\n      number: 16,          //商品数量\n      unit: 个,            //商品单位\n      listNum : 1111111,   //商品入库单号\n      scenario     :'storage'\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}\npost    /shop/out-in-storage  商品出库\n{\n      goodsId: \"1\",        //商品id\n      number: 16,          //商品数量，\n      describe:\"好好好\"，  // 商品描述\n      listNum：11111       // 商品编号      （增加发送的参数）\n      scenario: 'library'，// 商品出库\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}\npost  /shop/out-in-storage  商品退货\n{\n      goodsId: \"1\",        //商品id\n      number: 16,          //商品数量，\n      describe:\"好好好\"，  // 商品描述\n      scenario: \"quiteGoods\"，// 商品退货\n      _csrf_backend :\"__asjdgsajdgasjdgasgduiasdgasidhaosd==\",\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>商品 出入库 退货 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/shop/out-in-storage"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n  'status':'success',\n  'data'  :'操作成功'\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/ShopController.php",
    "groupTitle": "theGoods"
  },
  {
    "type": "get",
    "url": "/cabinet/renew-cabinet",
    "title": "柜子续租表单",
    "version": "1.0.0",
    "name": "______",
    "group": "theeCabinet",
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
            "type": "int",
            "optional": false,
            "field": "memCabinetId",
            "description": "<p>会员柜子id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "renewDate",
            "description": "<p>续组日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "renewNumDay",
            "description": "<p>续组天数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "renewRentPrice",
            "description": "<p>续组价格</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /cabinet/renew-cabinet\n{\n     memCabinetId :12,      //会员柜子id\n     renewDate   :2017-3-15 //续组日期\n     dayRentPrice：12      //续组天数\n     renewRentPrice：300  //续组价格\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>给已租用的柜子续组(缺续租价格计算) <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/6/5</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/cabinet/renew-cabinet"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n      'status':\"success\",     //续租成功\n      \"data\":“续租成功信息” /续租成功信息\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n     'status'=>'error',      //续租失败\n     'data'=>“续租失败信息” //续租失败信息\n}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/CabinetController.php",
    "groupTitle": "theeCabinet"
  },
  {
    "type": "get",
    "url": "/user/update-member-pic",
    "title": "修改会员头像",
    "version": "1.0.0",
    "name": "______",
    "group": "updatePic",
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
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>会员ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "pic",
            "description": "<p>会员头像</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /user/member-behavior-trail\n{\n      id: \"1\",\n      pic: \"asfdgfdg\"\n      _csrf_backend: \"sdfsdasasasd\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>修改会员头像 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/7/13</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/update-member-pic"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n \"status\"=>\"success\",\n'data'=>\"修改会员头像成功\"\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "updatePic"
  },
  {
    "type": "get",
    "url": "/user/get-charge-info",
    "title": "获取会员私课详情信息",
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
            "type": "int",
            "optional": false,
            "field": "chargeId",
            "description": "<p>转让的课程id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /user/get-charge-info\n{\n     \"chargeId\":9,\n     \"memberId\":169,\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>管理员进入会员详细信息-私课信息可以给会员转让课程 <br/> <span><strong>作    者：</strong></span>黄鹏举<br> <span><strong>邮    箱：</strong></span>huangpengju@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/24 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-charge-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-charge-info"
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
            "description": "<p>会员转让课程信息数据 ｛name：课程名称，pic：图片,chargeId：转让课程id，overage_section:剩余课程,course_amount:总课程，deadline_time：到期时间，money_amount：总金额｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/user/get-charge-array-info",
    "title": "获取课程下拉列表",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          }
        ]
      }
    },
    "description": "<p>管理员进入会员详细信息-私教产品下拉列表 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/24 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-charge-array-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-charge-array-info"
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
            "description": "<p>会员私教课程信息数据 ｛name：课程名称，pic：图片,chargeId：会员课程id，overage_section:剩余课程｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/user/member-keywords",
    "title": "关键字查询会员",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          }
        ]
      }
    },
    "description": "<p>可以根据关键字(会员姓名,会员手机号,会员编号)查询会员 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/24 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-charge-array-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/member-keywords"
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
            "description": "<p>会员私教课程信息数据 ｛name：课程名称，pic：图片,chargeId：会员课程id，overage_section:剩余课程｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/user/get-mobile-info",
    "title": "会员手机号去重",
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
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号</p>"
          }
        ]
      }
    },
    "description": "<p>管理员修改会员-手机号验证去重 <br/> <span><strong>作    者：</strong></span>黄华<br> <span><strong>邮    箱：</strong></span>huanghua@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/26 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-mobile-info</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-mobile-info"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>验证状态 ｛error：手机号存在，success：手机不存在｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/user/get-charge-history",
    "title": "获取课程消费记录列表",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "orderId",
            "description": "<p>会员购买课程id</p>"
          }
        ]
      }
    },
    "description": "<p>管理员进入会员详细信息-私教产品下拉列表 <br/> <span><strong>作    者：</strong></span>李慧恩<br> <span><strong>邮    箱：</strong></span>lihuien@itsports.club<br> <span><strong>创建时间：</strong></span>2017/5/24 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-charge-history</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-charge-history"
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
            "description": "<p>会员私教课程信息数据 ｛create_at:创建时间，consumption_amount：金额,category：状态 id，employeeName:客服名称｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "post",
    "url": "/user/save-delivery-form",
    "title": "会员管理 - 送人卡新增",
    "version": "1.0.0",
    "name": "____________",
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
            "field": "memberId",
            "description": "<p>被转卡会员ID old</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "oldMemberId",
            "description": "<p>老会员会员ID new||old</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机号       new</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>会员名称     new</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sex",
            "description": "<p>会员性别     new</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "idCard",
            "description": "<p>会员身份证号 new</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cardId",
            "description": "<p>卡ID        new||old</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态        老会员2 新会员1 new||old</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>验证码      new</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证  new||old</p>"
          },
          {
            "group": "Parameter",
            "type": "staring",
            "optional": false,
            "field": "scenario",
            "description": "<p>老会员old 新会员new</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"oldMemberId\": 111,\n     \"memberId\": \"157\",\n     \"mobile\": 15138799898,\n     \"name\": 老李,\n     \"sex\": 男,\n     \"idCard\": 411411199910101010,\n     \"cardId\": 1,\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员管理 - 送人卡新增<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/8/5<br/> <span><strong>调用方法：</strong></span>/user/add-note</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/save-delivery-form"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'新增信息不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/user/get-member-card",
    "title": "会员请假-获取会员办卡信息",
    "version": "1.0.0",
    "name": "_____________",
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
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          }
        ]
      }
    },
    "description": "<p>点击请假，会员请假-获取会员办卡信息 <br/> <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsports.club<br> <span><strong>创建时间：</strong></span>2017/06/04 <span><strong>域    名：</strong></span>http://qa.uniwlan.com <br> <span><strong>调用方法：</strong></span>/user/get-member-card</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/get-member-card"
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
            "description": "<p>会员办卡信息｛id:会员卡id｝</p>"
          }
        ]
      }
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "post",
    "url": "/user/add-note",
    "title": "会员管理 - 新增信息记录",
    "version": "1.0.0",
    "name": "_____________",
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
            "field": "note",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "behaviorId",
            "description": "<p>行为id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>会员卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>CSRF验证</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "{\n     \"note\": \"旷课冻结\",\n     \"memberId\": \"157\",\n     \"behaviorId\": \"3\",\n     \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>会员管理 - 新增信息记录<br/> <span><strong>作   者：</strong></span>黄华<br/> <span><strong>邮   箱：</strong></span>huanghua@itsports.club<br/> <span><strong>创建时间：</strong></span>2017/8/5<br/> <span><strong>调用方法：</strong></span>/user/add-note</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/user/add-note"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>提示数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','data':\"新增成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{\n  'status':'error',\n  'data':{\n    'name':'新增信息不能为空'\n  }}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/UserController.php",
    "groupTitle": "user"
  },
  {
    "type": "get",
    "url": "/site-management/cancel-member-about",
    "title": "会员取消预约",
    "version": "1.0.0",
    "name": "______",
    "group": "yardMemberCancel",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>//预约id GET   /site-management/cancel-member-about { id : 2,   // 会员预约id }</p>"
          }
        ]
      }
    },
    "description": "<p>会员取消预约 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/cancel-member-about"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"status\"       :“success”,  // 预约成功（error）\n     'data'         :'预约成功'   // 取消预约成功 （失败:返回失败信息）\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardMemberCancel"
  },
  {
    "type": "get",
    "url": "/site-management/yard-detail",
    "title": "场地课程区段表",
    "version": "1.0.0",
    "name": "_______",
    "group": "yardMemberCancel",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "yardId",
            "description": "<p>//场地id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberAboutDate",
            "description": "<p>// 预约日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cardNumber",
            "description": "<p>// 卡号 （验卡模块）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>// 会员id（目前针对潜在会员）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "aboutObject",
            "description": "<p>// 预约对象 （验卡模块:memberCard）（潜在会员:member）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /site-management/yard-detail\n{\n     yardId : 10,   // 场地id    （备注：潜在会员预约所需参数：yardId,memberAboutDate,memberId,aboutObject）\n     memberAboutDate:2017-11-13  //预约日期\n     cardNumber：051509762544  // 卡号\n     memberId: 117\n     aboutObject：member   //会员\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场地课程区段表 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/yard-detail"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n   \"data\"：“数据信息”\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardMemberCancel"
  },
  {
    "type": "get",
    "url": "/site-management/member-yard-about",
    "title": "会员场地预约",
    "version": "1.0.0",
    "name": "______",
    "group": "yardMemberOrderDetail",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "yardId",
            "description": "<p>//场地id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberId",
            "description": "<p>//会员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "memberCardId",
            "description": "<p>//会员卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutIntervalSection",
            "description": "<p>//预约区间段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutDate",
            "description": "<p>//预约日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutObject",
            "description": "<p>// 预约对象  验卡（memberCard） 潜在会员（potentialMember）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /site-management/search-member\n{\n   yardId: 2,   // 电话\n   memberId: 12, //会员ID\n   memberCardId：223, //会员卡id\n   aboutIntervalSection:\"12:54-16:40\"， // 会员预约区间段\n   aboutDate:\"2017-02-18\"   // 会员日期\n   aboutObject:\"potentialMember\"  // 预约对象(潜在会员) \n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取会员相关信息 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/search-member"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"status\"       :“success”,  // 预约成功\n     'data'         :'预约成功'   //\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardMemberOrderDetail"
  },
  {
    "type": "get",
    "url": "/site-management/search-member",
    "title": "获取会员相关信息",
    "version": "1.0.0",
    "name": "________",
    "group": "yardMemberOrderDetail",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>//电话</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "yardId",
            "description": "<p>//场地id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /site-management/search-member\n{\n   mobile: 15537312038,   // 电话\n   yardId: 3,             // 场地id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取会员相关信息 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/search-member"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"id\"       :12,  // 会员id\n      \"username\"   :会员姓名\n      “mobile”: 15537312038，\n       \"memberCardId\":23,\n       \"card_number\":卡号，\n       “card_name”:卡名称，\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardMemberOrderDetail"
  },
  {
    "type": "get",
    "url": "/site-management/get-about-data-detail",
    "title": "区段会员预约详情",
    "version": "1.0.0",
    "name": "________",
    "group": "yardMemberTheAbout",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "memberAboutDate",
            "description": "<p>// 会员预约日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "aboutIntervalSection",
            "description": "<p>// 预约日期</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>// 排序字段</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>// 排序规则</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET /site-management/get-about-data-detail\n{\n     memberAboutDate : 2017-09-05,   // 会员预约日期\n     aboutIntervalSection:9:00-12:00 // 区段预约日期\n     sortType: mobile  (mobile:手机号,username:会员姓名,aboutYard:预约时间),\n     sortName:ASC,(ASC:升序，DES:降序)\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>区段会员预约详情 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/get-about-data-detail"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n   \"yard_id\"：12， // 场地id\n   “member_id”:12,// 会员id,\n    \"member_card_id\":12, // 会员卡id\n   “about_interval_section”：\"12:00-13:00\" , //会员预约区间段\n   “aboutDate ”：\"2017-03-15\" , //会员预约日期\n    “status”：“会员上课状态”， //1:未开始 2:已开始 3:已结束 4:旷课(没去)5:取消预约\n    \"create_at\":\"预约时间\"，     //会员预约时间\n    “mobile”： “会员电话”   // 会员预约电话\n     “username”：\"会员姓名\"   // 会员姓名\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardMemberTheAbout"
  },
  {
    "type": "get",
    "url": "/site-management/get-venue-yard-page",
    "title": "场地分页信息",
    "version": "1.0.0",
    "name": "______",
    "group": "yardOrderPage",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortType",
            "description": "<p>排序字段名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sortName",
            "description": "<p>排序方法（倒序/升序）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /site-management/get-venue-yard-page\n{\n   venueId  : 12,   // 场馆id\n\n   sortType:yard_name(yard_name：场地名称,场馆名称:name,人数限制:people_limit,开放时间:business_time,每次活动时长:active_duration),\n   sortName:ASC     (ASC:升序;DESC:降序)\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场地主页分页信息 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/get-venue-yard-page"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"id\"      :\"场地id\",\n     \"venue_id\":\"场馆id\",\n      \"yard_name\":\"场地名称\",\n      \"people_limit\":\"人数限制\"，\n      \"business_time\":\"场馆运营时间\"，\n      \"active_duration\":\"每日活动时长\"，\n      \"create_at\": \"场地创建时间\",\n       \"name\":\"场馆名称\"\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrderPage"
  },
  {
    "type": "get",
    "url": "/site-management/get-venue-data",
    "title": "获取指定公司场馆",
    "version": "1.0.0",
    "name": "________",
    "group": "yardOrderR",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "companyId",
            "description": "<p>//公司id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /site-management/get-venue-data\n{\n   companyId: 12,   // 场馆id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取指定公司场馆 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/get-venue-data"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"id\"       :12,\n      \"name\"   :“我爱运动”\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrderR"
  },
  {
    "type": "post",
    "url": "/site-management/update-venue-yard",
    "title": "场地预约修改",
    "version": "1.0.0",
    "name": "______",
    "group": "yardOrderUpdate",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>//场地id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST   /site-management/update-venue-yard\n{\n   id : 12,                   // 场馆id\n   peopleLimit: 12,           //人数限制\n   businessTime:\"9:00-12:00\" // 场馆开放时间\n   everyTimeLong:\"30\"         // 每次时长\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>场地预约修改 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/update-venue-yard"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"status\"      :\"error\",\n     \"data\"       :\"修改失败\",\n};",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "失败示例:",
          "content": "{\n     \"status\"      :\"success\",\n     \"data\"       :\"修改成功\",\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrderUpdate"
  },
  {
    "type": "get",
    "url": "/site-management/deal-venue-yard",
    "title": "删除适用场地",
    "version": "1.0.0",
    "name": "______",
    "group": "yardOrder",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>//场馆适用场地id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET   /site-management/deal-venue-yard\n{\n   id : 12,   // 场馆id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>删除适用场地 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/7</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/deal-venue-yard"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\n     \"status\"      :\"success\",    // 删除成功\n     \"status\"      :\"error\",      // 删除失败\n};",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrder"
  },
  {
    "type": "get",
    "url": "/site-management/venue-card-category",
    "title": "获取指定场馆的卡种",
    "version": "1.0.0",
    "name": "________",
    "group": "yardOrder",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "GET  /site-management/venue-card-category\n{\n     \"venueId\": 12,                 // 场馆id\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>获取指定场馆卡种 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/venue-card-category"
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
            "description": "<p>返回场馆卡种数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'data':\"返回过来的数据\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrder"
  },
  {
    "type": "post",
    "url": "/site-management/add-venue-yard",
    "title": "新增场馆场地",
    "version": "1.0.0",
    "name": "______",
    "group": "yardOrders",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "venueId",
            "description": "<p>场馆id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "yardName",
            "description": "<p>场地名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "peopleLimit",
            "description": "<p>场地人数限制</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "businessTime",
            "description": "<p>营业时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "everyTimeLong",
            "description": "<p>活动时长</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cardCategoryId",
            "description": "<p>卡种ID（可以包括多个卡种）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "_csrf_backend",
            "description": "<p>csrf验证.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Example",
          "content": "POST  /site-management/add-venue-yard\n{\n     \"venueId\": 12,                 // 场馆id\n    \"yardName\": 大上海篮球场,        // 请假会员ID\n    \"peopleLimit : 20,              // 场地人数限制 20\n    \"businessTime\": \"9:00-18:00\",   //场馆开放时间\n    \"everyTimeLong\":300,            // 每次活动时长\n    \"cardCategoryId\" :20,30,40      // 卡种id 以逗号连起来\n    \"_csrf_backend\":\"_asjbbjkashdjkashdkashdkhasdhaskda==\"  // 防止跨站伪造\n}",
          "type": "json"
        }
      ]
    },
    "description": "<p>新增场馆场地 <span><strong>作    者：</strong></span>侯凯新<br> <span><strong>邮    箱：</strong></span>houkaixin@itsprts.club<br> <span><strong>创建时间：</strong></span>2017/9/6</p>",
    "sampleRequest": [
      {
        "url": "http://qa.uniwlan.com/site-management/add-venue-yard"
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
            "description": "<p>返回请假状态数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{'status':'success','status'=>'success','data':\"保存成功\"}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "错误示例:",
          "content": "{'status':'error','status'=>'error','data':\"保存失败信息\"}",
          "type": "json"
        }
      ]
    },
    "filename": "backend/controllers/SiteManagementController.php",
    "groupTitle": "yardOrders"
  }
] });
