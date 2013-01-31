<?php
//UI language package for us_EN
$language['us_EN'] = array(
        //login UI language package
        'login' => array(
            'title'=>'LOGIN',
            'btn'=>'Login',
            'ifauto'=>'autoLogin Next',
            'account'=>'AccountID',
            'pid'=>'EmployeeID',
            'password'=>'Password',
            'error'=>'Wrong EmployeeID or Password，Login Failed'
        ),
        //calendar UI language package
        'calendar' => array(
            'title'=>'CALENDAR',
            'order'=>array(
                'bytime'=>'ByTime',
                'bypriority'=>'ByPriority',
                'byprogress'=>'ByProgress',
            ),
            'go'=>'Go',
            'thismonth'=>'ThisMonth',
            'today'=>'Today',
            'noevent'=>'No Arrangement',
            'week'=>array(
                'sunday'=>'SUN',
                'monday'=>'MON',
                'tuesday'=>'TUE',
                'wednesday'=>'WED',
                'thursday'=>'THU',
                'friday'=>'FRI',
                'saturday'=>'SAT',
                '0'=>'SUN',
                '1'=>'MON',
                '2'=>'TUE',
                '3'=>'WED',
                '4'=>'THU',
                '5'=>'FRI',
                '6'=>'SAT',
            ),
            'alllegend'=>'All Legend',
            'deleteevent'=>array(
                'tip'=>'Click to Delete Event',
                'confirm'=>'Confirm to Delete THIS Event?'
            ),
            'addevent'=>array(
                'tip'=>'Click to Add Event for THISDAY',
                'title'=>'Add Event',
                'close'=>'close',
                'confirm'=>'Confirm',
            ),
            'atevent'=>array(
                'tip'=>'Click to @ Someone',
                'title'=>'@Who',
                'close'=>'close',
                'confirm'=>'Confirm',
            ),
            'editpriority'=>array(
                'tip'=>'Click to Modify Priority',
                'title'=>'Priority',
                'close'=>'close',
                'confirm'=>'Confirm',
                'ignore'=>'Ignore',
                'wait'=>'Wait',
                'normal'=>'Normal',
                'important'=>'Important',
                'urgent'=>'Urgent'
            ),
            'editprogress'=>array(
                'tip'=>'Click to Modify Progress',
                'title'=>'Progress',
                'close'=>'close',
                'confirm'=>'Confirm',
            )
        ),
        //normal UI language package
        'general'=>array(
            'nopermission'=>'Sorry, No Permission !',
            'showsetupbutton'=>'Setup',
            'employeesetup'=>'Employee',
            'departmentsetup'=>'Department',
            'holidaysetup'=>'Holiday',
            'legendsetup'=>'Legend',
            'informsetup'=>'Inform',
            'backtocalendar'=>'Calendar',
            'welcome'=>'Hi, ',
            'logoutconfirm'=>'Confirm to Logout?',
            'logout'=>'Logout',
            'languageselect'=>'语言',
            'help'=>'Help'
        ),
        //department UI language package
        'department'=>array(
            'title'=>'Department',
            'add'=>'New',
            'addtr'=>array(
                'name'=>'Name',
                'parent'=>'Parent'
            ),
            'addsubtip'=>'Click to Add One SubDepartment',
            'savefirst'=>'Please Save THOSE Unsave First',
            'edittip'=>'Click to Modify THIS Department',
            'deletetip'=>'Click to Delete THIS Department',
            'deleteconfirm'=>'Confirm to Delete THIS Department?',
            'savetip'=>'Click to Save THIS Node',
            'canceltip'=>'Click to Cancel Operation'
        ),
        //employee UI language package
        'employee'=>array(
            'title'=>'Employee',
            'adminth'=>'Admin',
            'th'=>array(
                'name'=>'Name',
                'employeeid'=>'EmployeeID',
                'password'=>'Password',
                'department'=>'Department',
                'operation'=>'Operation'
            ),
            'addtip'=>'Click to Add One Employee for THIS Department',
            'savefirst'=>'Please Save THOSE Unsave First',
            'updatebtn'=>'Update',
            'updatetip'=>'Click to Update THIS Employee',
            'deletebtn'=>'Delete',
            'deletetip'=>'Click to Delete THIS Employee',
            'deleteconfirm'=>'Confirm to Delete THIS Employee?',
            'savebtn'=>'Save',
            'savetip'=>'Click to Save THIS Employee',
            'cancelbtn'=>'Cancel',
            'canceltip'=>'Click to Cancel Operation'
        ),
        //legend UI language package
        'legend'=>array(
            'title'=>'LEGEND',
            'th'=>array(
                'name'=>'Name',
                'color'=>'Color(#RGB or colorName)',
                'operation'=>'Operation'
            ),
            'addbtn'=>'New',
            'savefirst'=>'Please Save THOSE Unsave First',
            'addtr'=>array(
                'name'=>'Name',
                'color'=>'Color',
                'savebtn'=>'Save',
                'cancelbtn'=>'Cancel'
            ),
            'updatebtn'=>'Update',
            'updatetip'=>'Click to Update THIS Legend',
            'deletebtn'=>'Delete',
            'deletetip'=>'Click to Delete THIS Legend',
            'deleteconfirm'=>'Confirm to Delete THIS Legend?',
            'usefulcolor'=>'Conference of Useful Color'
        ),
        //holiday UI language package
        'holiday'=>array(
            'title'=>'HOLIDAY',
            'th'=>array(
                'name'=>'Name',
                'date'=>'Date',
                'operation'=>'Operation'
            ),
            'addbtn'=>'New',
            'savefirst'=>'Please Save THOSE Unsave First',
            'addtr'=>array(
                'name'=>'Name',
                'date'=>'startDate & endDate',
                'savebtn'=>'Save',
                'savetip'=>'Click to Save THIS Holiday',
                'cancelbtn'=>'Cancel',
                'canceltip'=>'Click to Cancel THIS Operation'
            ),
            'updatebtn'=>'Update',
            'updatetip'=>'Click to Update THIS Holiday',
            'deletebtn'=>'Delete',
            'deletetip'=>'Click to Delete THIS Holiday',
            'deleteconfirm'=>'Confirm to Delete THIS Holiday?'
        ),
        //inform setup
        'inform'=>array(
            'title'=>'Inform',
            'th'=>array(
                'title'=>'Title',
                'detail'=>'Detail',
                'publishdate'=>'Date',
                'lasting'=>'Lasting',
                'operation'=>'Operation'
            ),
            'addtr'=>array(
                'savebtn'=>'Save',
                'savetip'=>'Click to save THIS inform',
                'cancelbtn'=>'Cancel',
                'canceltip'=>'Click to Cancel THIS operation'
            ),
            'addbtn'=>'New',
            'addtip'=>'Click to Add an inform',
            'updatebtn'=>'Update',
            'updatetip'=>'Click to update THIS inform',
            'deletebtn'=>'Delete',
            'deletetip'=>'Click to delete THIS inform',
            'deleteconfirm'=>'Confirm to Delete THIS inform?',
            'savefirst'=>'Please Save THOSE Unsave First'
        )
    );

//define this language package name：us_EN
$language['us_EN']['name'] = 'us_EN';

//skilltip language package for us_EN
$language['us_EN']['skilltip'] = array(
                'label'=>'Skill Tip',
                'tip'=>array(
                    'STEP1 If U are the first time to use U@ME, plz goto "Setup->Department" first !!!',
                    'Then, U may setup data for department of UR company ~',
                    'STEP2 Goto "Setup->Employee", to add employees for department ~',
                    'STEP3 Goto "Setup->Holiday", to define holidays for this year ~',
                    'STEP4 Goto "Setup->Legend", to change styles of legends predefined if U wish~',
                    'STEP5 Click the Number Of One Day to Add Event for That Day !!!',
                    'Click legends at right side can help U to filter events in calendar'
                )
    );
?>
