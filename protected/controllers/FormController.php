<?php

/**
 * 处理提交的表单
 */
class FormController extends Q {

    public function actionIndex() {
        $method = zmf::filterInput(Yii::app()->request->getParam('method'), 't', 1);
        if ($method == 'create-project') {
            self::createProject();
        } elseif ($method == 'project') {
            self::project();
        } elseif ($method == 'create-task') {
            self::createTask();
        } elseif ($method == 'task') {
            self::task();
        } elseif ($method == 'complete-task') {
            self::completeTask();
        } elseif ($method == 'comment') {
            self::comment();
        } elseif ($method == 'my-task') {
            self::project('my-task');
        } elseif ($method == 'new-member') {
            self::newMember();
        } elseif ($method == 'create-group') {
            self::newGroup();
        } elseif ($method == 'get-members') {
            self::getMembers();
        }
    }

    private function newGroup() {
        $t = zmf::filterInput($_POST['t'], 't', 1);
        $d = zmf::filterInput($_POST['d'], 't', 1);
        if (!$t) {
            $this->jsonOutPut(0, '团队标题不能为空');
        }
        $attr = array(
            'title' => $t,
            'desc' => $d,
            'uid' => $this->uid
        );
        $model = new Group;
        $model->attributes = $attr;
        if ($model->save()) {
            //初始化关系
            $rlAttr = array(
                'groupid' => $model->id,
                'uid' => $this->uid,
                'isAdmin' => 1, //默认是管理员
            );
            GroupLink::add($rlAttr);
            //更新我已加入过团队
            Users::model()->updateByPk($this->uid, array('groupid' => $model->id));
            $this->jsonOutPut(1, '已创建');
        } else {
            $this->jsonOutPut(0, $model->getErrors());
        }
    }

    /**
     * 创建项目
     */
    private function createProject() {
        $t = zmf::filterInput($_POST['t'], 't', 1);
        $d = zmf::filterInput($_POST['d'], 't', 1);
        $time = zmf::filterInput($_POST['time'], 't', 1);
        if ($time) {
            $time = strtotime($time);
        }
        if (!$t) {
            $this->jsonOutPut(0, '项目标题不能为空');
        }
        if (!$this->groupid) {
            $this->jsonOutPut(0, '请选择团队');
        }
        $attr = array(
            'title' => $t,
            'desc' => $d,
            'expired_time' => $time,
            'uid' => $this->uid,
            'groupid' => $this->groupid,
        );
        $model = new Projects;
        $model->attributes = $attr;
        if ($model->save()) {
            //初始化关系
            ProjectRelation::initRl($model->id, $this->uid);
            //初始化项目统计
            ProjectTasklog::initLog($model->id);
            $this->jsonOutPut(1, '已创建');
        } else {
            $this->jsonOutPut(0, $model->getErrors());
        }
    }

    /**
     * 为团队添加新成员
     */
    private function newMember() {
        $email = zmf::filterInput($_POST['email'], 't', 1);
        if (!$email) {
            $this->jsonOutPut(0, '请填写对方邮箱');
        }
        if (!$this->groupid) {
            $this->jsonOutPut(0, '请选择团队');
        }
        $uinfo = Users::model()->find('email=:email', array(':email' => $email));
        if (!$uinfo) {
            //todo，不存在该用户时发送邀请
            $this->jsonOutPut(0, '对方不是本站用户，将发送邀请');
        }
        $groupLinkInfo = GroupLink::findRelation($uinfo['id'], $this->groupid);
        if ($groupLinkInfo) {
            $this->jsonOutPut(0, '对方已是团队成员');
        }
        $rlAttr = array(
            'groupid' => $this->groupid,
            'uid' => $uinfo['id'],
            'isAdmin' => 0, //默认不是管理员
        );
        if (GroupLink::add($rlAttr)) {
            Users::model()->updateByPk($uinfo['id'], array('groupid' => $this->groupid));
            $this->jsonOutPut(1, '已添加');
        } else {
            $this->jsonOutPut(0, '添加失败');
        }
    }

    /**
     * 获取团队的成员
     */
    private function getMembers() {
        if (!$this->groupid) {
            $this->jsonOutPut(0, '请选择团队');
        }
        $members = Users::getMembers($this->groupid, 'passed');
        $longstr = '<form>';
        foreach ($members as $member) {
            $longstr.='<div class="checkbox"><label><input type="checkbox" value="' . $member['id'] . '" name="assign-user" id="assign-user"> ' . $member['username'] . '</label></div>';
        }
        $longstr.='</form>';
        $this->jsonOutPut(1, $longstr);
    }

    private function project($type = '') {
        $id = zmf::filterInput($_POST['id'], 't', 1);
        $id = tools::jieMi($id);
        $id = (int) $id;
        if (!$id) {
            $this->jsonOutPut(0, '页面不存在');
        }
        if ($type == 'my-task') {
            $sql="SELECT t.* FROM {{tasks}} t,{{task_relation}} tr WHERE t.id=tr.tid AND touid='{$id}'";
//            $tasks = Tasks::model()->findAll('uid=:uid AND tid=0 AND status=' . Tasks::STATUS_PASSED, array(':uid' => $id));
            $tasks=  Yii::app()->db->createCommand($sql)->queryAll();
        } else {
            $tasks = Tasks::model()->findAll('pid=:pid AND tid=0 AND status=' . Tasks::STATUS_PASSED, array(':pid' => $id));
        }
        $model = new Tasks;
        $model->tid = 0;
        $model->pid = tools::jiaMi($id);
        $data = array(
            'type' => $type,
            'tasks' => $tasks,
            'model' => $model,
            'members' => $members,
        );
        $html = $this->renderPartial('/tasks/task', $data, true);
        $this->jsonOutPut(1, $html);
    }

    private function createTask() {
        $t = zmf::filterInput($_POST['t'], 't', 1);
        $pid = zmf::filterInput($_POST['pid'], 't', 1);
        $tid = zmf::filterInput($_POST['tid'], 't', 1);
        $d = zmf::filterInput($_POST['d'], 't', 1);
        $time = zmf::filterInput($_POST['time'], 't', 1);
        $uids = zmf::filterInput($_POST['uids'], 't', 1);
        $uids = array_unique(array_filter(explode('#', $uids)));
        $pid = tools::jieMi($pid);
        $pid = (int) $pid;
        if (!$pid) {
            $this->jsonOutPut(0, '页面不存在');
        }
        if (!$t) {
            $this->jsonOutPut(0, '任务标题不能为空');
        }
        if ($time) {
            $time = strtotime($time,zmf::now());
        }
        if ($tid) {
            $tid = tools::jieMi($tid);
            $tid = (int) $tid;
        }
        $attr = array(
            'title' => $t,
            'desc' => $d,
            'expired_time' => $time,
            'uid' => $this->uid,
            'pid' => $pid,
            'tid' => $tid,
            'status' => Tasks::STATUS_PASSED
        );
        $model = new Tasks;
        $model->attributes = $attr;
        if ($model->save()) {
            //创建任务成功后创建任务对应关系   
            if (empty($uids)) {
                $relAttr = array(
                    'pid' => $pid,
                    'tid' => $model->id,
                    'uid' => $this->uid,
                    'touid' => 0
                );
                TaskRelation::add($relAttr);
            } else {
                foreach ($uids as $_uid) {
                    $relAttr = array(
                        'pid' => $pid,
                        'tid' => $model->id,
                        'uid' => $this->uid,
                        'touid' => $_uid
                    );
                    TaskRelation::add($relAttr);
                }
            }
            //如果指派了用户，则需要提醒用户
            //创建任务日志
            if ($tid) {
                //创建的是子任务
                $relAttr['type'] = TaskLog::exTypes('subtask', 'key');
            } else {
                $relAttr['type'] = TaskLog::exTypes('create', 'key');
            }
            $relAttr['content'] = $t;
            //添加日志
            TaskLog::add($relAttr);
            //统计项目的任务数
            ProjectTasklog::updateLog(array('pid' => $pid, 'total' => 1));
            $html = $this->renderPartial('/tasks/_task', array('data' => $model), true);
            $this->jsonOutPut(1, $html);
        } else {
            $this->jsonOutPut(0, $model->getErrors());
        }
    }

    private function task() {
        $id = zmf::filterInput($_POST['id'], 't', 1);
        $id = tools::jieMi($id);
        $id = (int) $id;
        if (!$id) {
            $this->jsonOutPut(0, '页面不存在');
        }
        $info = Tasks::model()->findByPk($id);
        if (!$info) {
            $this->jsonOutPut(0, '页面不存在');
        }
        //取出任务的所属项目
        $project = Projects::model()->findByPk($info['pid']);
        if (!$project) {
            $this->jsonOutPut(0, '所属项目不存在');
        }
        if ($info['followers']) {
            //取出任务的信息接收者
        }
        if ($info['tags']) {
            //取出任务的标签
        }
        if ($info['attaches']) {
            //取出任务的附件
        }
        //取出任务的子任务
        //$sql="SELECT";
        $subtasks = Tasks::model()->findAll('tid=:tid', array(':tid' => $id));
        $taskIds[] = $id;
        if (!empty($subtasks)) {
            foreach ($subtasks as $k => $v) {
                $_uinfo = UserInfo::model()->findByPk($v['uid']);
                $subtasks[$k]['uid'] = array(
                    'truename' => $_uinfo['truename'],
                    'uid' => $v['uid']
                );
                $taskIds[] = $v['id'];
            }
        }
        //取出任务的评论及操作记录
        //评论
        $comments = Comments::model()->findAll('logid=:logid AND classify=:class', array(':logid' => $id, ':class' => 'task'));
        //操作记录
        $taskIds = join(',', $taskIds);
        $records = TaskLog::model()->findAllBySql("SELECT * FROM {{task_log}} WHERE pid=:pid AND tid IN({$taskIds}) ORDER BY cTime ASC", array(':pid' => $info['pid']));
        //获取参与者
        $members=  TaskRelation::getMembers($id);
        $model = new Tasks;
        $model->tid = tools::jiaMi($id);
        $model->pid = tools::jiaMi($info['pid']);
        $data = array(
            'info' => $info,
            'project' => $project,
            'subtasks' => $subtasks,
            'comments' => $comments,
            'records' => $records,
            'model' => $model,
            'members' => $members,
        );
        $html = $this->renderPartial('/tasks/detail', $data, true);
        $this->jsonOutPut(1, $html);
    }

    private function completeTask() {
        $id = zmf::filterInput($_POST['id'], 't', 1);
        $id = tools::jieMi($id);
        $id = (int) $id;
        if (!$id) {
            $this->jsonOutPut(0, '页面不存在');
        }
        $info = Tasks::model()->findByPk($id);
        if (!$info) {
            $this->jsonOutPut(0, '页面不存在');
        }
        if (Tasks::model()->updateByPk($id, array('status' => Tasks::STATUS_FINISHED))) {
            //todo，通知该项目创建者及跟随者该任务已完成            
            //添加记录，将记录添加到该任务的父级任务下
            if ($info['tid']) {
                //此任务为子任务
                $_type = 'complete_subtask';
            } else {
                $_type = 'complete_task';
            }
            $relAttr = array(
                'pid' => $info['pid'],
                'tid' => $id,
                'uid' => $this->uid,
                'type' => TaskLog::exTypes($_type, 'key'),
                'content' => $info['title']
            );
            TaskLog::add($relAttr);
            //更新统计
            ProjectTasklog::updateLog(array('pid' => $info['pid'], 'finished' => 1));
            $this->jsonOutPut(1, '标记成功');
        } else {
            $this->jsonOutPut(0, '标记已完成失败');
        }
    }

    private function comment() {
        $id = zmf::filterInput($_POST['id'], 't', 1);
        $id = tools::jieMi($id);
        $id = (int) $id;
        if (!$id) {
            $this->jsonOutPut(0, '页面不存在');
        }
        $c = zmf::filterInput($_POST['c'], 't', 1);
        if (!$c) {
            $this->jsonOutPut(0, '评论内容不能为空');
        }
        $task = Tasks::model()->findByPk($id);
        if (!$task) {
            $this->jsonOutPut(0, '页面不存在');
        }
        $attr = array(
            'uid' => $this->uid,
            //'tocommentid' => '',
            'pid' => $task['pid'],
            'logid' => $id,
            'content' => $c,
            'classify' => 'task',
            'status' => Tasks::STATUS_PASSED,
        );
        $model = new Comments;
        $model->attributes = $attr;
        if ($model->save()) {
            $_uinfo = UserInfo::model()->findByPk($model['uid']);
            $model['uid'] = array(
              'truename' => $_uinfo['truename'],
              'uid' => $model['uid']
            );
            $html = $this->renderPartial('/tasks/_comment', $model, true);
            $this->jsonOutPut(1, $html);
        } else {
            $this->jsonOutPut(0, '评论失败');
        }
    }

}
