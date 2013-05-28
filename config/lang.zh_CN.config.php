<?php
//界面语言包 简体中文
/*$language['zh_CN'] = array(
        //登录界面语言包 简体中文
        'login' => array(
            'title'=>'登录',
            'btn'=>'登录',
            'ifauto'=>'下次自动',
            'account'=>'公司',
            'pid'=>'工号',
            'password'=>'密码',
            'error'=>'验证失败，无法登录'
        ),
        //日历界面语言包 简体中文
        'calendar' => array(
            'title'=>'日历',
            'order'=>array(
                'bytime'=>'按时刻',
                'bypriority'=>'按优先级',
                'byprogress'=>'按进度',
            ),
            'noevent'=>'今天没有安排',
            'go'=>'显示',
            'thismonth'=>'本月',
            'today'=>'今天',
            'week'=>array(
                'sunday'=>'周日',
                'monday'=>'周一',
                'tuesday'=>'周二',
                'wednesday'=>'周三',
                'thursday'=>'周四',
                'friday'=>'周五',
                'saturday'=>'周六',
                '0'=>'周日',
                '1'=>'周一',
                '2'=>'周二',
                '3'=>'周三',
                '4'=>'周四',
                '5'=>'周五',
                '6'=>'周六',
            ),
            'alllegend'=>'所有图例',
            'deleteevent'=>array(
                'tip'=>'点击删除事件',
                'confirm'=>'确认删除本事件？'
            ),
            'addevent'=>array(
                'tip'=>'点击为这天添加事件',
                'title'=>'添加事件',
                'close'=>'关闭',
                'confirm'=>'确认添加',
            ),
            'atevent'=>array(
                'tip'=>'点击@谁',
                'title'=>'@谁',
                'close'=>'关闭',
                'confirm'=>'确认@',
            ),
            'editpriority'=>array(
                'tip'=>'点击修改优先级',
                'title'=>'修改优先级',
                'close'=>'关闭',
                'confirm'=>'确认修改',
                'ignore'=>'忽略',
                'wait'=>'等待',
                'normal'=>'普通',
                'important'=>'重要',
                'urgent'=>'紧急'
            ),
            'editprogress'=>array(
                'tip'=>'点击修改进度',
                'title'=>'修改进度',
                'close'=>'关闭',
                'confirm'=>'确认修改',
            )
        ),
        //通用界面语言包 简体中文
        'general'=>array(
            'nopermission'=>'您无权访问本页面',
            'showsetupbutton'=>'后台管理',
            'employeesetup'=>'员工管理',
            'departmentsetup'=>'部门管理',
            'holidaysetup'=>'节日管理',
            'legendsetup'=>'图例管理',
            'informsetup'=>'通知管理',
            'backtocalendar'=>'返回日历',
            'welcome'=>'你好，',
            'logoutconfirm'=>'确认注销？',
            'logout'=>'注销',
            'languageselect'=>'Language',
            'help'=>'帮助'
        ),
        //部门管理界面语言包 简体中文
        'department'=>array(
            'title'=>'部门管理',
            'add'=>'添加部门',
            'addtr'=>array(
                'name'=>'名称',
                'parent'=>'上级部门'
            ),
            'addsubtip'=>'点击添加下级部门',
            'savefirst'=>'请先保存未保存的信息',
            'edittip'=>'点击编辑部门信息',
            'deletetip'=>'点击删除此部门',
            'deleteconfirm'=>'确认删除此部门？',
            'savetip'=>'点击保存此部门信息',
            'canceltip'=>'点击取消操作'
        ),
        //员工管理界面语言包 简体中文
        'employee'=>array(
            'title'=>'员工管理',
            'adminth'=>'管理员',
            'th'=>array(
                'name'=>'姓名',
                'employeeid'=>'工号(登录用，唯一)',
                'password'=>'密码(登录用)',
                'department'=>'部门',
                'operation'=>'操作'
            ),
            'addtip'=>'点击为此部门添加一名员工',
            'savefirst'=>'请先保存未保存的信息',
            'updatebtn'=>'更新',
            'updatetip'=>'点击更新此员工信息',
            'deletebtn'=>'删除',
            'deletetip'=>'点击删除此员工',
            'deleteconfirm'=>'确认删除此员工？',
            'savebtn'=>'保存',
            'savetip'=>'点击保存此员工信息',
            'cancelbtn'=>'取消',
            'canceltip'=>'点击取消操作'
        ),
        //图例管理界面语言包 简体中文
        'legend'=>array(
            'title'=>'图例管理',
            'th'=>array(
                'name'=>'名称',
                'color'=>'颜色(#RGB色码 或 颜色名称)',
                'operation'=>'操作'
            ),
            'addbtn'=>'添加图例',
            'savefirst'=>'请先保存未保存的信息',
            'addtr'=>array(
                'name'=>'名称',
                'color'=>'颜色',
                'savebtn'=>'保存',
                'cancelbtn'=>'取消'
            ),
            'updatebtn'=>'更新',
            'updatetip'=>'点击更新此图例信息',
            'deletebtn'=>'删除',
            'deletetip'=>'点击删除此图例',
            'deleteconfirm'=>'确认删除此图例？',
            'usefulcolor'=>'常用颜色参考'
        ),
        //节日管理界面语言包 简体中文
        'holiday'=>array(
            'title'=>'节日管理',
            'th'=>array(
                'name'=>'名称',
                'date'=>'日期',
                'operation'=>'操作'
            ),
            'addbtn'=>'添加节日',
            'savefirst'=>'请先保存未保存的信息',
            'addtr'=>array(
                'name'=>'名称',
                'date'=>'起始日期 ～ 结束日期',
                'savebtn'=>'保存',
                'savetip'=>'点击保存此节日信息',
                'cancelbtn'=>'取消',
                'canceltip'=>'点击取消操作'
            ),
            'updatebtn'=>'更新',
            'updatetip'=>'点击更新此节日信息',
            'deletebtn'=>'删除',
            'deletetip'=>'点击删除此节日信息',
            'deleteconfirm'=>'确认删除此节日？'
        ),
        //通知管理界面语言包 简体中文
        'inform'=>array(
            'title'=>'通知管理',
            'th'=>array(
                'title'=>'标题',
                'detail'=>'内容',
                'publishdate'=>'发布日期',
                'lasting'=>'持续天',
                'operation'=>'操作'
            ),
            'addtr'=>array(
                'savebtn'=>'保存',
                'savetip'=>'点击保存此通知',
                'cancelbtn'=>'取消',
                'canceltip'=>'点击取消操作'
            ),
            'addbtn'=>'新增',
            'addtip'=>'点击新增一条通知',
            'updatebtn'=>'更新',
            'updatetip'=>'点击更新此通知',
            'deletebtn'=>'删除',
            'deletetip'=>'点击删除此通知',
            'deleteconfirm'=>'确认删除此通知？',
            'savefirst'=>'请先保存未保存的通知'
        )
    );*/

//设定本语言包名称为简体中文
/*$language['zh_CN']['name'] = '简体中文';*/

//设置中文小窍门
/*$language['zh_CN']['skilltip'] = array(
                'label'=>'小窍门',
                'tip'=>array(
                    '第一步，请先前往“后台管理->部门管理”，添加组织的相关部门 ～',
                    '第二步，请前往“后台管理->员工管理”，为部门添加相关员工 ～',
                    '第三步，请前往“后台管理->节日管理”，定义当年的节假日（除双休外的假期）～',
                    '第四步，请前往“后台管理->图例管理”，修改预定义图例的样式（如果你希望修改的话 ～）',
                    '然后，请尝试点击日历上的数字，添加事件 ！！！',
                    '点击图例可以过滤事件类型～'
                )
    );*/

$language['zh_CN'] = array(
//global setting
    'name' => '简体中文',

//base module
    'base.index.mainContent' => '欢迎使用OA',
	
    'base.login.title' => '欢迎使用UATME_OA',
    'base.login.version' => '版本 1.0.1',
    'base.login.usernameLabel' => '电邮',
    'base.login.passwordLabel' => '密码',
    'base.login.loginButtonTitle' => '点击按钮或回车登录系统',
    'base.login.loginButtonLabel' => '登&nbsp;&nbsp;录',
	
    'base.self.edit.resetPassword.title' => '密码修改',
    'base.self.edit.resetPassword.oldPasswordLabel' => '原密码',
    'base.self.edit.resetPassword.newPasswordLabel' => '新密码',
    'base.self.edit.resetPassword.repeatPasswordLabel' => '重复新密码',
    'base.self.edit.resetPassword.confirmButtonLabel' => '确认修改',
    'base.self.edit.resetPassword.alertMsg1' => '原密码必须填写！',
    'base.self.edit.resetPassword.alertMsg2' => '新密码必须填写！',
    'base.self.edit.resetPassword.alertMsg3' => '重复新密码必须填写！',
    'base.self.edit.resetPassword.alertMsg4' => '两次输入的新密码必须一致！',
    'base.self.edit.resetPassword.alertMsg5' => '密码修改成功！',
    'base.self.edit.resetPassword.alertMsg6' => '您的原密码不正确！',
	
	'base.self.edit.selfInformation.title' => '个人信息',
	'base.self.edit.selfInformation.namezhLabel' => '中文名',
	'base.self.edit.selfInformation.nameenLabel' => '英文名',
	'base.self.edit.selfInformation.emailLabel' => '电子邮箱',
	'base.self.edit.selfInformation.departmentLabel' => '所属部门',
	'base.self.edit.selfInformation.annualLeaveLabel' => '年假信息',
	'base.self.edit.selfInformation.annualLeaveSubLabel1' => '截止至今日，总共:',
	'base.self.edit.selfInformation.annualLeaveSubLabel2' => '天； 已用:',
	'base.self.edit.selfInformation.annualLeaveSubLabel3' => '天； 剩余:',
	'base.self.edit.selfInformation.annualLeaveSubLabel4' => '天；',
	
	'base.self.task.taskList.tableHeader1' => '任务类型',
	'base.self.task.taskList.tableHeader2' => '申请者',
	'base.self.task.taskList.tableHeader3' => '相关申请信息',
	'base.self.task.taskList.tableHeader4' => '申请时间',
	'base.self.task.taskList.tableHeader5' => '指定代办',
	'base.self.task.taskList.tableHeader6' => '操作',
	'base.self.task.taskList.tableHeader7' => '审批备注',
	'base.self.task.taskList.agreeButtonLabel' => '同意',
	'base.self.task.taskList.declineButtonLabel' => '拒绝',
	
    
//calendar module
//club module
//document module
//expense module
//hr module
//sales module
//support module
//system module
);

?>
