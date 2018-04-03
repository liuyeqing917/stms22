<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/',function(){
    return Redirect::to('/pc/mis-stms.html');
});
//登录
Route::any('login','LoginController@login');
Route::get('aa','LoginController@test');
//验证码
Route::get('code','LoginController@code');
//获取所有父科室
Route::get('fatherdepart','DepartmentController@fatherdepart');
//获取所有子科室
Route::get('childdepart','DepartmentController@cilddepart');
//学生注册提交
Route::any('student/registe','LoginController@studentregiste');
//消息中心
Route::post('messageHandle',['uses'=>'MessageController@handle','as'=>'messageHandle']);
//消息查看
Route::get('messageCheck',['uses'=>'MessageCheckController@messageCheck','as'=>'messageCheck']);
Route::get('messageContent',['uses'=>'MessageCheckController@messageContent','as'=>'messageContent']);
Route::get('messageinform',['uses'=>'MessageCheckController@messageinform']);
//修改头像
Route::post('uploadimageurl','UserController@uploadimageurl');
//上传图片
Route::post('upload','UserController@uploadPhoto');

// 科室管理
Route::group(['prefix' => "depart",'middleware' => ['UserLogin']], function()
{


    Route::post('addfather', ['uses'=>'DepartmentController@addfatherdepart']);
    Route::post('add', ['uses'=>'DepartmentController@adddepart']);
    Route::post('edit', ['uses'=>'DepartmentController@modifydepart']);
    Route::get('delete', ['uses'=>'DepartmentController@deletedepart'])->where('id', '[1-9]\d*');
    Route::get('managedepart', ['uses'=>'DepartmentController@managedepartinfo']);
    Route::get('onedepart', ['uses'=>'DepartmentController@searchdepartinfo']);

});



// 教师管理
Route::group(['prefix' => "teacher",'middleware' => ['UserLogin']], function()
{
    Route::get('search', ['uses'=>'UserController@searchteacher']);
    Route::post('add', ['uses'=>'UserController@teacherregiste']);
    Route::post('edit', ['uses'=>'UserController@teachermodify']);
    Route::get('delete', ['uses'=>'UserController@teacherdelete']);
    Route::get('teacherlist', ['uses'=>'UserController@teacherlist']);



});


// 大纲外课程管理管理
Route::group(['prefix' => "elective",'middleware' => [ 'UserLogin']], function()
{
    //新增课程
    Route::post('addcourse', ['uses'=>'ElecticCourseController@addcourse']);
    //修改课程
    Route::post('modifycourse', ['uses'=>'ElecticCourseController@modifycourse']);
    //删除课程
    Route::get('deletecourse', ['uses'=>'ElecticCourseController@deletecourse']);
    //获取所有课程的列表
    Route::get('searchall', ['uses'=>'ElecticCourseController@searchall']);
    //获取一个课程的活动信息
    Route::get('search-coursedata', ['uses'=>'ElecticCourseController@searchactivitylist']);
    //新增活动
    Route::post('addactivity', ['uses'=>'ElecticCourseController@addctivity']);
    //修改活动
    Route::post('modifyactivity', ['uses'=>'ElecticCourseController@modifyactivity']);
    //删除活动
    Route::get('deleteactivity', ['uses'=>'ElecticCourseController@deleteactivity']);
    //确认上课
    Route::get('confirm', ['uses'=>'ElecticCourseController@confirmtofill']);
    //查询一节课的课程信息
    Route::get('search-onecourse', ['uses'=>'ElecticCourseController@searchonecourse']);
    //查询学生未填写活动的课程
    Route::get('search-outactcourse', ['uses'=>'ElecticCourseController@searchactivitycourse']);
    //查询老师未评价活动
    Route::get('search-wpjact', ['uses'=>'ElecticCourseController@searchwpjActivity']);


});

//学生
Route::group(['prefix' => "student",'middleware' => [ 'UserLogin']], function()
{
    //查询学生个人信息
    Route::get('search','UserController@searchstudent');
    //学生修改提交
    Route::post('modify','UserController@studentmodify');
    //学生修改密码
    Route::post('editpassword','UserController@modifypassword');

});

//考勤管理
Route::group(['prefix' => "attendance",'middleware' => [ 'UserLogin']], function()
{
    //新增考勤
    Route::post('add', ['uses'=>'AttendanceController@addattendance']);
    //修改考勤
    Route::post('modify', ['uses'=>'AttendanceController@modifyattendance']);
    //查询考勤信息
    Route::get('search', ['uses'=>'AttendanceController@searchattendance']);
});

//大钢内课程管理
Route::group(['prefix' => "coursemain",'middleware' => [ 'UserLogin']], function()
{
    //课程列表
    Route::get('index','CourseMainController@index');
    //添加课程
    Route::get('add','CourseMainController@add');
    //添加课程提交
    Route::post('insert','CourseMainController@insert');
    //编辑课程
    Route::get('edit','CourseMainController@edit');
    //更新课程
    Route::post('update','CourseMainController@update');
    //显示单个课程信息
    Route::get('show','CourseMainController@show');
    //删除单个课程信息
    Route::get('delete','CourseMainController@delete');
    //复制大钢内课程
    Route::post('copycourse','CourseMainController@copycourse');

});

//学生课程病例管理
Route::group(['prefix' => "stucase",'middleware' => [ 'UserLogin']], function()
{
    //学生课程病例列表
    Route::get('index','StuCaseController@index');
    //添加学生课程病例
    Route::get('add','StuCaseController@add');
    //学生课程病例题目列表
    Route::get('showtype','StuCaseController@showtype');
    //添加学生课程病例提交
    Route::post('insert','StuCaseController@insert');
    //编辑学生课程病例
    Route::get('edit','StuCaseController@edit');
    //更新学生课程病例
    Route::post('update','StuCaseController@update');
    //显示单个学生课程病例
    Route::get('show','StuCaseController@show');
    //删除单个学生课程病例
    Route::get('delete','StuCaseController@delete');
    //标题类别下列表页显示
    Route::get('showlist', ['uses'=>'StuCaseController@showlist']);
});

//学生临床操作技术管理
Route::group(['prefix' => "stuclinical",'middleware' => [ 'UserLogin']], function()
{
    //学生临床操作技术列表
    Route::get('index','StuClinicalController@index');
    //添加学生临床操作技术
    Route::get('add','StuClinicalController@add');
    //添加学生临床操作技术题目列表
    Route::get('showtype','StuClinicalController@showtype');
    //添加学生临床操作技术提交
    Route::post('insert','StuClinicalController@insert');
    //编辑学生临床操作技术
    Route::get('edit','StuClinicalController@edit');
    //更新学生临床操作技术
    Route::post('update','StuClinicalController@update');
    //显示单个学生临床操作技术
    Route::get('show','StuClinicalController@show');
    //删除单个学生临床操作技术
    Route::get('delete','StuClinicalController@delete');
    //标题类别下列表页显示
    Route::get('showlist', ['uses'=>'StuClinicalController@showlist']);
});
//学生大病历操作技术管理
Route::group(['prefix' => "studisease",'middleware' => [ 'UserLogin']], function()
{
    //学生大病历列表
    Route::get('index','StuDiseaseController@index');
    //添加学生大病历
    Route::get('add','StuDiseaseController@add');
    //添加学生大病历题目列表
    Route::get('showtype','StuDiseaseController@showtype');
    //添加学生大病历提交
    Route::post('insert','StuDiseaseController@insert');
    //编辑学生大病历
    Route::get('edit','StuDiseaseController@edit');
    //更新学生大病历
    Route::post('update','StuDiseaseController@update');
    //显示单个学生大病历
    Route::get('show','StuDiseaseController@show');
    //删除单个学生大病历
    Route::get('delete','StuDiseaseController@delete');
    //标题类别下列表页显示
    Route::get('showlist', ['uses'=>'StuDiseaseController@showlist']);
});

//学生活动记录表管理
Route::group(['prefix' => "stuactivity",'middleware' => [ 'UserLogin']], function()
{
    //学生活动记录列表
    Route::get('index','StuActivityController@index');
    //添加学生活动记录
    Route::get('add','StuActivityController@add');
    //添加学生活动记录题目列表
    Route::get('showtype','StuActivityController@showtype');
    //添加学生活动记录提交
    Route::post('insert','StuActivityController@insert');
    //编辑学生活动记录
    Route::get('edit','StuActivityController@edit');
    //更新学生活动记录
    Route::post('update','StuActivityController@update');
    //显示单个学生活动记录
    Route::get('show','StuActivityController@show');
    //删除单个学生活动记录
    Route::get('delete','StuActivityController@delete');
    //标题类别下列表页显示
    Route::get('showlist', ['uses'=>'StuActivityController@showlist']);
});

//学生出科小结管理
Route::group(['prefix' => "stusummary",'middleware' => [ 'UserLogin']], function()
{
    //学生出科小结列表
    Route::get('index','StuSummaryController@index');
    //添加学生出科小结
    Route::get('add','StuSummaryController@add');
    //添加学生出科小结提交
    Route::post('insert','StuSummaryController@insert');
    //编辑学生出科小结
    Route::get('edit','StuSummaryController@edit');
    //更新学生出科小结
    Route::post('update','StuSummaryController@update');
    //显示学生出科小结
    Route::get('show','StuSummaryController@show');
    //删除学生出科小结
    Route::get('delete','StuSummaryController@delete');
    //对应科室下的小结
    Route::post('gettitle', ['uses'=>'StuSummaryController@gettitle']);
});

//轮转管理对应的路由后面根据前端需要会有变动
Route::group(['middleware' => [ 'UserLogin']], function()
{
    //科室列表
    Route::get('cycle/depshow', ['uses'=>'CycleController@depshow']);
    //删除轮转主表
    Route::get('cycle/delcycle', ['uses'=>'CycleController@delcycle']);
    //轮转主表列表
    Route::get('cycle/listmcycle', ['uses'=>'CycleController@listmcycle']);
    //生成轮转表
    Route::post('cycle/makecycle', ['uses'=>'CycleController@makecycle']);
    //取科室对应的负责老师
    Route::get('cycle/ksteacher', ['uses'=>'CycleController@ksteacher']);
    //按时间查找轮转表
    Route::post('cycle/ftsdcycle', ['uses'=>'CycleController@ftsdcycle']);
    //按科室查找轮转表
    Route::post('cycle/fksdcycle', ['uses'=>'CycleController@fksdcycle']);
    //不同科室手册列表
    Route::get('cycle/xcycle', ['uses'=>'CycleController@xcycle']);
    //按照不对类型对应不同表进行列表展示
    Route::post('cycle/showlist', ['uses'=>'CycleController@showlist']);
    //老师确认出科小结
    Route::post('cycle/confirmcycle', ['uses'=>'CycleController@confirmcycle']);
    //学生手册查看
    Route::get('cycle/stulookcycle', ['uses'=>'CycleController@stulookcycle']);
});

// 评价管理
Route::group(['prefix' => "evaluate",'middleware' => ['UserLogin']], function()
{
    Route::get('getroles', ['uses'=>'EvaluateController@getRoles']);
    Route::get('getinfo', ['uses'=>'EvaluateController@getInfo']);
    Route::post('add', ['uses'=>'EvaluateController@addEva']);
    Route::get('list', ['uses'=>'EvaluateController@getList']);
    Route::get('detail', ['uses'=>'EvaluateController@getDetail']);
    Route::post('addbase', ['uses'=>'EvaluateController@addContent']);

    Route::get('statistics', ['uses'=>'EvaluateController@statistics']);


});

//考试管理
Route::group(['prefix' => "test",'middleware' => ['UserLogin']], function()
{
    Route::post('import', ['uses'=>'TestController@import']);
    Route::post('export', ['uses'=>'TestController@export']);

    Route::get('choose', ['uses'=>'TestController@choose']);
    Route::get('classroom', ['uses'=>'TestController@classroom']);

    Route::get('del', ['uses'=>'TestController@del']);

});


//考试管理
Route::group(['prefix' => "exam",'middleware' => ['UserLogin']], function()
{
    Route::post('addexam', ['uses'=>'ExamController@addaExame']);
    Route::get('examlist', ['uses'=>'ExamController@searchExameList']);
    Route::get('dptexamlist', ['uses'=>'ExamController@searchDepartexamList']);
    Route::get('examinfo', ['uses'=>'ExamController@searchExameInfo']);
    Route::get('surestudent', ['uses'=>'ExamController@sureuserExame']);
    Route::get('startexam', ['uses'=>'ExamController@startExame']);
    Route::get('search-answerlist', ['uses'=>'ExamController@searchUserREesult']);
    Route::get('search-examdetail', ['uses'=>'ExamController@searchExamREesult']);
    Route::get('studentlist', ['uses'=>'ExamController@searchDepartStudents']);
    Route::post('addstudentresult', ['uses'=>'ExamController@addExameResult']);
    Route::post('modifyresult', ['uses'=>'ExamController@modifyExamREesult']);
    Route::get('surecanshow', ['uses'=>'ExamController@sureScoreShow']);
    Route::get('scorelist', ['uses'=>'ExamController@searchResultList']);
    Route::get('modelexamnews', ['uses'=>'ExamController@searchModelExam']);

});
//收藏夹
Route::group(['prefix' => "favorite",'middleware' => []], function() {
    Route::get('getList', ['uses'=>'FavoriteController@getList']);//获取主题列表
    Route::post('edit', ['uses'=>'FavoriteController@edit']);//修改新增主题
    Route::get('delete', ['uses'=>'FavoriteController@delete']);//删除主题
});
//考试管理
Route::group(['prefix' => "fourm",'middleware' => []], function()
{
    Route::get('getType', ['uses'=>'FourmTypeController@getType']);//获取主题列表
    Route::get('editType', ['uses'=>'FourmTypeController@editType']);//修改新增主题
    Route::get('deleteType', ['uses'=>'FourmTypeController@deleteType']);//删除主题
    Route::get('getFourmList', ['uses'=>'FourmController@getFourmList']);//获取贴子列表
    Route::post('editFourm', ['uses'=>'FourmController@editFourm']);//修改新增贴子
    Route::get('getFourmDetail', ['uses'=>'FourmController@getFourmDetail']);//查询贴子具体内容
    /*Route::get('getFourmList', function(){
        return App\Entities\GFourm::paginate(10);
    });*/
    Route::post('editFourm', ['uses'=>'FourmController@editFourm']);
    Route::get('deleteFourm', ['uses'=>'FourmController@deleteFourm']);
    //Route::get('addFourmTolal', ['uses'=>'FourmController@addFourmTolal']);//增加访问量
    Route::get('getFloorList', ['uses'=>'FourmFloorController@getFloorList']);//获取贴子列表
    Route::get('replyFourm', ['uses'=>'FourmFloorController@replyFourm']);
    Route::get('replyFloor', ['uses'=>'FourmFloorController@replyFloor']);
    Route::get('deleteFloor', ['uses'=>'FourmFloorController@deleteFloor']);
    Route::get('deleteMessage', ['uses'=>'FourmFloorController@deleteMessage']);
});