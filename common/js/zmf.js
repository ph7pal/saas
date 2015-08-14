/*
 * a:对话框id
 * t:提示
 * c:对话框内容
 * ac:下一步的操作名
 * time:自动关闭
 */
var beforeModal = '';
function dialog(diaObj) {
    if (typeof diaObj != "object") {
        return false;
    }
    var c = diaObj.msg;
    var a = diaObj.id;
    var t = diaObj.title;
    var ac = diaObj.action;
    var time = diaObj.time;
    $('#' + beforeModal).modal('hide');
    if (typeof t == 'undefined' || t == '') {
        t = '提示';
    }
    if (typeof a == 'undefined' || a == '') {
        a = 'myDialog';
    }
    if (!ac) {
        ac = '';
    }
    $('#' + a).remove();
    var longstr = '<div class="modal fade mymodal" id="' + a + '" tabindex="-1" role="dialog" aria-labelledby="' + a + 'Label" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="' + a + 'Label">' + t + '</h4></div><div class="modal-body">' + c + '</div><div class="modal-footer">';
    longstr += '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>';
    if (ac) {
        longstr += '<button type="button" class="btn btn-primary" action="' + ac + '" data-loading-text="Loading...">确定</button>';
    }
    longstr += '</div></div></div></div>';
    $("body").append(longstr);
    $('#' + a).modal({
        backdrop: true,
        keyboard: false
    });
    beforeModal = a;
    if (time > 0 && typeof time != 'undefined') {
        setTimeout("closeDialog('" + a + "')", time * 1000);
    }
}
function closeDialog(a) {
    if (typeof a == 'undefined' || a == '') {
        a = 'myDialog';
    }
    $('#' + a).modal('hide');
    $('#' + a).remove();
    $('.modal-backdrop').remove();
}
/**
 * 创建项目
 */
function createProject() {
    var t = $('#Projects_title').val();
    var d = $('#Projects_desc').val();
    if (!t) {
        alert('t');
        return false;
    }
    $.post(configs.handleUrl, {
        t: t,
        d: d,
        method: 'create-project',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            closeDialog();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
/**
 * 添加新团队成员
 */
function newMember() {
    var email = $('#new-member-email').val();
    if (!email) {
        return false;
    }
    $.post(configs.handleUrl, {
        email: email,
        method: 'new-member',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            closeDialog();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function getMembers() {
    $.post(configs.handleUrl, {
        method: 'get-members',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $("#float-members-holder").html(result['msg']);
        } else {
            dialog({msg: result['msg']});
        }
    });
}
/**
 * 添加新团队
 */
function newGroup() {
    var t = $('#Group_title').val();
    var d = $('#Group_desc').val();
    if (!t) {
        alert('t');
        return false;
    }
    $.post(configs.handleUrl, {
        t: t,
        d: d,
        method: 'create-group',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            closeDialog();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
/**
 * 列出项目的所有任务
 */
function project(params) {
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;
    var m = params.method;
    if (!m) {
        m = 'project';
    }
    var id = dom.attr('action-data');
    if (!id) {
        return false;
    }
    $("#project-tasks-holder").html('<p class="text-center"><i class="icon-spinner icon-spin"></i> 正在加载中...<p>');
    $.post(configs.handleUrl, {
        id: id,
        method: m,
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        $("#task-detail-holder").html('');
        if (result['status'] == '1') {
            $("#project-tasks-holder").html(result['msg']);
            rebind();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function task(params) {
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;
    var id = dom.attr('action-data');
    if (!id) {
        return false;
    }
    $("#task-detail-holder").html('<p class="text-center"><i class="icon-spinner icon-spin"></i> 正在加载中...<p>');
    $.post(configs.handleUrl, {
        id: id,
        method: 'task',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $("#task-detail-holder").html(result['msg']);
            rebind();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
/**
 * ajax
 * @param {type} params
 * @returns {Boolean}
 */
function _ajax(params) {
    if (typeof params != "object") {
        return false;
    }
    var type = params.type;
    var url = params.url;
    var data = params.data;
    $.ajax({
        type: type ? type : 'POST',
        url: url,
        data: data + '&YII_CSRF_TOKEN=' + configs.csrfToken,
        success: function (result) {
            if (params.callback) {
                params.callback(result);
            } else {
                _searchResult(result);
            }
        }
    });
}
function _post(params) {
    if (typeof params != "object") {
        return false;
    }
    var url = params.url;
    if (!url) {
        return false;
    }
    var data = params.data;
    $.post(url, data, function (data, status) {
        if (params.callback) {
            params.callback(data);
        } else {
            //alert("Data: " + data + "\nStatus: " + status);
        }
    });
}
function checkClick(dom) {
    var action = dom.attr('action');
    if (action == 'create-project') {
        dialog({msg: configs.createProjectHtml, action: 'do-create-project', title: '创建项目'});
        $('[action="do-create-project"]').click(function () {
            createProject();
        });
    } else if (action == 'new-group') {
        dialog({msg: configs.newGroupHtml, action: 'do-create-group', title: '创建团队'});
        $('[action="do-create-group"]').click(function () {
            newGroup();
        });
    } else if (action == 'new-member') {
        dialog({msg: configs.newMemberHtml, action: 'do-new-member', title: '添加新成员'});
        $('[action="do-new-member"]').click(function () {
            newMember();
        });
    } else if (action == 'project') {
        project({dom: dom});
    } else if (action == 'show') {
        var s = dom.attr('action-data');
        $('#' + s).removeClass('hidden').show();
    } else if (action == 'create-task') {
        createTask({dom: dom});
    } else if (action == 'task') {
        task({dom: dom});
    } else if (action == 'complete-task') {
        comTask({dom: dom});
    } else if (action == 'comment') {
        comment({dom: dom});
    } else if (action == 'my-tasks') {
        project({dom: dom, method: 'my-task'});
    } else if (action == 'assign') {
        dialog({msg: '<div id="float-members-holder"></div>', action: 'assign-member', title: '指派任务'});
        getMembers();
        $('[action="assign-member"]').click(function () {
            var type = dom.attr('action-type');
            var ids = '';
            $("input:checkbox[name='assign-user']:checked").each(function () {
                ids += $(this).val() + '#';
            });
            if (ids != '') {
                //新建任务时指派参与者
                $('#Tasks_members_' + type).val(ids);
            }
            closeDialog();
        });
    }else if(action=='my-notices'){
        getNotices({dom: dom});
    }
}
function getNums(){
    $.post(configs.handleUrl, {
        method: 'getNums',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {        
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            var noticeNum=result['msg']['notices'],todo=result['msg']['todos'];
            if(noticeNum>0){
                $("#my-notices-num").removeClass('red-badge').addClass('red-badge').html(noticeNum);
            }else{
                $("#my-notices-num").removeClass('red-badge').html('0');
            }
            $('#todo-num').html(todo);
            
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function getNotices(params){
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;    
    $("#task-detail-holder").html('');
    $("#project-tasks-holder").html('<p class="text-center"><i class="icon-spinner icon-spin"></i> 正在加载中...<p>');
    $.post(configs.handleUrl, {
        method: 'getNotices',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        removeTips();
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            $("#project-tasks-holder").html(result['msg']);
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function createTask(params) {
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;
    var type = dom.attr('action-type');
    if (!type) {
        return false;
    }
    var _dom = $('#Tasks_title_' + type);
    var t = _dom.val();
    var tid = $('#Tasks_tid_' + type).val();
    var pid = $('#Tasks_pid_' + type).val();
    var d = $('#Tasks_desc_' + type).val();
    var time = $('#Tasks_expired_time_' + type).val();
    var uids = $('#Tasks_members_' + type).val();
    if (!t) {
        $('#Tasks_title_' + type).parent('div').addClass('has-error');
        return false;
    } else {
        _dom.parent('div').removeClass('has-error');
    }
    if (typeof uids == 'undefined') {
        uids = '';
    }
    $.post(configs.handleUrl, {
        t: t,
        tid: tid,
        pid: pid,
        d: d,
        time: time,
        uids: uids,
        method: 'create-task',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            if (type == 'task') {
                $("#task-subtasks-" + tid).append(result['msg']);
            } else if (type == 'project') {
                removeTips();
                $("#project-tasks-list").append(result['msg']);
            }
            _dom.val('');
            rebind();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function comTask(params) {
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;
    var id = dom.parent('p').attr('action-data');
    $.post(configs.handleUrl, {
        id: id,
        method: 'complete-task',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        if (result['status'] == '1') {
            dom.parent('p').remove();
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function comment(params) {
    if (typeof params != "object") {
        return false;
    }
    var dom = params.dom;
    var id = dom.attr('action-data');
    if (!id) {
        return false;
    }
    var _dom = $('#comment-' + id);
    var c = _dom.val();
    if (!c) {
        _dom.parent('div').addClass('has-error');
        return false;
    }
    var $btn = dom.button('loading');
    $.post(configs.handleUrl, {
        id: id,
        c: c,
        method: 'comment',
        YII_CSRF_TOKEN: configs.csrfToken
    }, function (result, status) {
        result = eval('(' + result + ')');
        $btn.button('reset');
        if (result['status'] == '1') {
            _dom.val('');
            $('#task-comments-' + id).append(result['msg']);
        } else {
            dialog({msg: result['msg']});
        }
    });
}
function rebind() {
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('[data-toggle="tooltip"]').tooltip();
    $('.task-lists .task-list').unbind('click').click(function () {
//        $('.task-lists tr').removeClass('primary');
//        if($(this).hasClass('primary')){
//            $(this).removeClass('primary');
//        }else{
//            $(this).removeClass('primary').addClass('primary');
//        }
    });
    $('.complete-task').mouseover(function () {
        $(this).removeClass('icon-ok').addClass('icon-ok');
    });
    $('.complete-task').mouseout(function () {
        $(this).removeClass('icon-ok');
    });

    $('.zmf').unbind('click').click(function (e) {
        //var dom=$(this);
        var dom = $(e.target);
        checkClick(dom);
    });
}
function xunhuan(){
    getNums();
    setInterval(function(){getNums();},60000);
}
function removeTips() {
    $('.tips-holder').remove();
}
function sidebar(){
    var h=$(window).height();
    $('.sidebar').css('height',h).show();    
    $('.nav-sidebar li').click(function(){
        $('.nav-sidebar li').each(function(){
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });
    /*任务列表单击选中样式*/
//    $('#project-tasks-list p').click(function(){
//        $('#project-tasks-list p').each(function(){
//            $(this).removeClass('active');
//        });
//        $(this).addClass('active');
//    });
    rebind();
    xunhuan();
}