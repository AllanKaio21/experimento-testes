<?php

namespace allankaio\giitester;


use yii\db\Exception;
use yiibr\brvalidator\CpfValidator;

class Informations
{
    /*
     * return information schema db if using pgsql
     */
    public function getForeignKeysInfo($table){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT DISTINCT(ccu.table_name) AS tablefk, kcu.table_name as tableref,
          kcu.column_name as columnref, ccu.column_name as columnfk, rc.delete_rule as actiondelete FROM 
          information_schema.table_constraints AS tc JOIN information_schema.key_column_usage AS kcu USING 
          (constraint_schema, constraint_name) JOIN information_schema.constraint_column_usage AS ccu USING 
          (constraint_schema, constraint_name) join information_schema.referential_constraints as rc using 
          (constraint_schema, constraint_name ) WHERE constraint_type = 'FOREIGN KEY' AND ccu.table_name='$table'");
        $out = $command->queryAll();
        return $out;
    }

    /**
     * @param $table
     * @param $tableRef
     * @return bool
     */
    public function getVariables($table,$tableRef){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("select * from information_schema.constraint_column_usage as ccu
          where ccu.table_name = '$table'");
        $out = $command->queryAll();
        if($out!=null) {
            $schema = $this->getForeignKeysInfo($tableRef);
            foreach ($schema as $i=>$line) {
                if ($line['tableref']===$table && $this->getCount($table)===true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $table
     * @return bool
     */
    public function getCount($table){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT * FROM (SELECT count(a.attname) AS fk FROM pg_catalog.pg_attribute a 
          JOIN pg_catalog.pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r') 
          JOIN pg_catalog.pg_namespace n ON (n.oid = cl.relnamespace) 
          JOIN pg_catalog.pg_constraint ct ON (a.attrelid = ct.conrelid AND 
          ct.confrelid != 0 AND ct.conkey[1] = a.attnum) 
          JOIN pg_catalog.pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r') 
          JOIN pg_catalog.pg_namespace nf ON (nf.oid = clf.relnamespace) 
          JOIN pg_catalog.pg_attribute af ON (af.attrelid = ct.confrelid AND 
          af.attnum = ct.confkey[1]) 
          WHERE cl.relname = '$table') as t1 left JOIN (select count(*) as total from information_schema.columns as ccu
          where ccu.table_name = '$table') as t2 on t1=t1");
        $out = $command->queryAll();
       foreach ($out as $i=>$line){
           if($line['total']==3 && $line['fk']==2) {
               return true;
           }
       }
       return false;
    }

    public function getRelations($table){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT ccu.table_name as table_rel, ccu.column_name as column_related, kcu.column_name as column_relation
          FROM information_schema.table_constraints AS tc JOIN information_schema.key_column_usage AS kcu USING 
          (constraint_schema, constraint_name) JOIN information_schema.constraint_column_usage AS ccu USING 
          (constraint_schema, constraint_name) join information_schema.referential_constraints as rc using 
          (constraint_schema, constraint_name ) 
          WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='$table'");
        $out = $command->queryAll();
        return $out;
    }

    public function getColumns($table){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("select ccu.column_name, ccu.data_type as tipo from information_schema.columns as ccu where ccu.table_name = '$table'");
        $out = $command->queryAll();
        return $out;
    }

    public function getRelationsCRUD($table,$model){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT ccu.table_name as table_rel, ccu.column_name as column_related, kcu.column_name as column_relation
          FROM information_schema.table_constraints AS tc JOIN information_schema.key_column_usage AS kcu USING 
          (constraint_schema, constraint_name) JOIN information_schema.constraint_column_usage AS ccu USING 
          (constraint_schema, constraint_name) join information_schema.referential_constraints as rc using 
          (constraint_schema, constraint_name ) 
          WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='$table' and ccu.table_name!='$model'");
        $out = $command->queryAll();
        return $out;
    }

    public function labelsRules($table, $column){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT c.column_name,pgd.description
          FROM pg_catalog.pg_statio_all_tables AS st
          INNER JOIN pg_catalog.pg_description pgd ON (pgd.objoid=st.relid)
          INNER JOIN information_schema.columns c ON (pgd.objsubid=c.ordinal_position
          AND c.table_schema=st.schemaname AND c.table_name=st.relname)
          WHERE c.table_name='$table' AND c.column_name='$column'");
        $out  = $command->queryAll();
        foreach ($out as $line){
            return $line['description'];
        }
    }

    public function newRules($table){
        $out = $this->getColumns($table);
        $rule = [];
        foreach($out as $field=>$line){
            if($line['column_name']=='email'){
                array_push($rule,"[['email'],'email']");
            }
            else if($line['column_name']=='cpf'){
                array_push($rule,"['cpf', \\yiibr\\brvalidator\\CpfValidator::className()]");
            }
            else if($line['column_name']=='cnpj'){
                array_push($rule,"['cpf', \\yiibr\\brvalidator\\CnpjValidator::className()]");
            }
            else if($line['column_name']=='arquivo'){
                array_push($rule,"['arquivo', 'file']");
            }
        }
        return $rule;
    }

}