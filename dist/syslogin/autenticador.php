<?php
date_default_timezone_set("America/Araguaina");
abstract class AutenticadorPuzzle {
 
    private static $instancia = null;
 
    private function __construct() {}
 
    /**
     * 
     * @return AutenticadorPuzzle
    */
    public static function instanciar() {
      if (self::$instancia == NULL) {
        self::$instancia = new AutenticadorEmBanco();
      }
      return self::$instancia; 
    }
 
    public abstract function logar($email, $password);
    public abstract function esta_logado();
    public abstract function pegar_usuario();
    public abstract function expulsar($d);
}

class AutenticadorEmBanco extends AutenticadorPuzzle {
 
    public function esta_logado() {
      $sess = SessaoPuzzle::instanciar();
      return $sess->existe('usuario');
    }
 
    public function expulsar($d) {
      $_d = (isset($d) && !empty($d))?'?d='.$d:'';      
      header('location: login.php'.$_d);
    }
 
    public function logar($email, $password) {
      try{
        // Faz conexão com banco de daddos
        $pdo = new PDO('mysql:dbname=db_pcloud;host=db-pcloud.mysql.uhserver.com', 'pcloud', 'Pcloud@2018');
        //$pdo = new PDO('mysql:dbname=db_pcloud;host=127.0.0.1', 'root', '');
      }catch(PDOException $e){
        // Caso ocorra algum erro na conexão com o banco, exibe a mensagem
        echo 'Falha ao conectar no banco de dados: '.$e->getMessage();
        die;
      }

      //Inicia a Sessão
      $sess = SessaoPuzzle::instanciar();

      /* Strings de Acessos */
      $strSelect = "select * from tbl_users where tbl_users.email ='{$email}' and tbl_users.password = '{$password}'";
      $strUpdate = "UPDATE tbl_users SET visited = now() WHERE tbl_users.email = '{$email}' AND tbl_users.password = '{$password}'";

      $result = $pdo->query($strSelect);

      if ( ($result->rowCount() > 0) ) {
        $dados = $result->fetch(PDO::FETCH_ASSOC);
        $usuario = new admPuzzleUSER();
        $usuario->setId($dados['id']);
        $usuario->setName($dados['name']);
        $usuario->setEmail($dados['email']);
        $usuario->setPassword($dados['password']);
        $usuario->setType($dados['type']);
        $usuario->setActive($dados['active']);         
        $usuario->setVisited($dados['visited']);

        $sess->set('usuario', $usuario);
        $pdo->query($strUpdate);
        return $usuario->getName();
      }
      else {
        return false;
      }
 
    }
 
    public function pegar_usuario() {
        $sess = SessaoPuzzle::instanciar();
 
        if ($this->esta_logado()) {
            $usuario = $sess->get('usuario');
            return $usuario;
        }
        else {
            return false;
        }
    }
}
?>