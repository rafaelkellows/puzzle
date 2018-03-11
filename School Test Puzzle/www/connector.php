<?php
error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

class Conn {
    public $pdo = NULL;
    private $sql = NULL;
    
    public function SQLselector($identifiers,$table,$conditions,$orderby) {

        // Estabelece conexão com o Servidor
        //$pdo = mysql_connect('brainvestfiles.db.2054282.hostedresource.com','brainvestfiles', 'Kellows@Rafael4527') or die ('Falha ao conectar no Servidor!');
        $pdo = mysql_connect('127.0.0.1','root', '') or die ('Falha ao conectar no Servidor!');
        // Define o Banco de Dados
        mysql_select_db('db_pcloud', $pdo);
        
        // String de Ação
        $sqlWhere = ($conditions!='') ? 'WHERE ' . $conditions : '';
        $sqlOrderby = ($orderby!='') ? 'ORDER BY ' . $orderby : '';
        $sql = "SELECT {$identifiers} FROM {$table} ".$sqlWhere.' '.$sqlOrderby;

        $all = mysql_query($sql);
        return $all;        
    }
    public function SQLupdater($table,$identifiers,$conditions){
        // Estabelece conexão com o Servidor
        $pdo = mysql_connect('brainvestfiles.db.2054282.hostedresource.com','brainvestfiles', 'Kellows@Rafael4527') or die ('Falha ao conectar no Servidor!');
        //$pdo = mysql_connect('127.0.0.1','root', '') or die ('Falha ao conectar no Servidor!');
       
        // Define o Banco de Dados
        mysql_select_db('brainvestfiles', $pdo);
        
        // String de Ação
        $sql = "UPDATE {$table} SET {$identifiers} WHERE {$conditions}";

        $all = mysql_query($sql);
        return $all;
    }
    public function SQLinserter($table,$identifiers,$values){
        // Estabelece conexão com o Servidor
        $pdo = mysql_connect('brainvestfiles.db.2054282.hostedresource.com','brainvestfiles', 'Kellows@Rafael4527') or die ('Falha ao conectar no Servidor!');
        //$pdo = mysql_connect('127.0.0.1','root', '') or die ('Falha ao conectar no Servidor!');
       
        // Define o Banco de Dados
        mysql_select_db('brainvestfiles', $pdo);
        
        // String de Ação
        $sql = ("INSERT INTO $table ({$identifiers}) VALUES ({$values})");
        
        $all = mysql_query($sql);
        return $all;
    }
    public function SQLdeleter($table,$conditions){
        // Estabelece conexão com o Servidor
        $pdo = mysql_connect('brainvestfiles.db.2054282.hostedresource.com','brainvestfiles', 'Kellows@Rafael4527') or die ('Falha ao conectar no Servidor!');
        //$pdo = mysql_connect('127.0.0.1','root', '') or die ('Falha ao conectar no Servidor!');
       
        // Define o Banco de Dados
        mysql_select_db('brainvestfiles', $pdo);
        
        // String de Ação
        $sql = "DELETE FROM {$table} WHERE {$conditions}";
        //$all = $pdo->query($sql);
        $all = mysql_query($sql);
        return $all;
    }

}

?>


