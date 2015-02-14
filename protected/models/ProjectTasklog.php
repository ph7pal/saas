<?php

class ProjectTasklog extends CActiveRecord {

    public function tableName() {
        return '{{project_tasklog}}';
    }

    public function rules() {
        return array(
          array('period_time', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
          array('pid,period_time', 'required'),
          array('finished, expired', 'numerical', 'integerOnly' => true),
          array('pid, total, period_time', 'length', 'max' => 11),
          array('id, pid, total, finished, expired, period_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
          'id' => 'ID',
          'pid' => '项目ID',
          'total' => '总任务数',
          'finished' => '任务完成数',
          'expired' => '任务延期数',
          'period_time' => '日志时间',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('total', $this->total, true);
        $criteria->compare('finished', $this->finished);
        $criteria->compare('expired', $this->expired);
        $criteria->compare('period_time', $this->period_time, true);

        return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function initLog($pid) {
        $attr = array(
          'pid' => $pid,
          'period_time' => strtotime(zmf::time('', 'Y-m-d'))
        );
        $model = new ProjectTasklog;
        $model->attributes = $attr;
        $model->save();
    }

    /**
     * 一天内的数据统计到一条数据里
     * @param type $attr
     * @return boolean
     */
    public static function updateLog($attr) {
        $pid = $attr['pid'];
        if (!$pid) {
            return false;
        }
        $_time = strtotime(zmf::time('', 'Y-m-d')); //今天凌晨        
        $info = ProjectTasklog::model()->find('pid=:pid AND period_time=:time', array(':pid' => $pid, ':time' => $_time));
        if (!$info) {
            //没有这条信息时则创建
            $_total=  Tasks::model()->count('pid=:pid', array(':pid'=>$pid));//总任务数
            $_expired=Tasks::model()->count('pid=:pid AND expired_time>0 AND expired_time<:time AND status='.Tasks::STATUS_PASSED, array(':pid'=>$pid,':time'=>zmf::now()));//总任务数
            $_finished=Tasks::model()->count('pid=:pid AND status='.Tasks::STATUS_FINISHED, array(':pid'=>$pid));//已完成任务数
            $_attr=array(
              'pid'=>$pid,
              'total'=>$_total,
              'expired'=>$_expired,
              'finished'=>$_finished,
              'period_time'=>$_time
            );
            $model = new ProjectTasklog;
            $model->attributes = $_attr;
            $model->save();
        } else {
            if ($attr['total']) {
                $_attr['total']=$info['total']+1;
            }
            if ($attr['finished']) {
                $_attr['finished']=$info['finished']+1;
            }
            if ($attr['expired']) {
                $_attr['expired']=$info['expired']+1;
            }
            if(!empty($attr))ProjectTasklog::model()->updateByPk($info['id'], $_attr);
        }
    }

}
