<?php
namespace Common\Model;
class HistoryModel extends \Think\Model{
    protected $tableName='history';
    protected $fields=array(
        'ip','dateline','val'
    );
}