<?php
    class Pessoa {
        private $nome;
        private $idade;

        public function __construct($nome, $idade) {
            $this->nome = $nome;
            $this->idade = $idade;
        }

        public function getNome() {
            return $this->nome;
        }

        public function getIdade() {
            return $this->idade;
        }

        public function setNome($nome) {
            $this->nome = $nome;
        }

        public function setIdade($idade) {
            $this->idade = $idade;
        }
    }

    interface PessoaDAO {
        public function ler();
        public function criar($nome, $idade);
        public function atualizar($nome, $novaIdade);
        public function remover($nome);
    }

    class PessoaDAOImpl implements PessoaDAO {
        private $conexao;
        
        public function __construct($conexao) {
            $this->conexao = $conexao;
        }

        public function criarTabelaPessoa() {
            $query = "CREATE TABLE IF NOT EXISTS pessoa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                idade INT
            )";
            
            if ($conexao->query($query) === TRUE) {
                echo "Tabela 'pessoa' criada com sucesso.";
            } else {
                echo "Erro ao criar a tabela 'pessoa': " . $this->conexao->error;
            }
        }
        
        public function ler() {
            $query = "SELECT * FROM pessoas";
            $stmt = $conexao->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $pessoas = [];
            
            while ($row = $result->fetch_assoc()) {
                $pessoa = new Pessoa($row['nome'], $row['idade']);
                $pessoas[] = $pessoa;
            }
            
            return $pessoas;
        }
        
        public function criar($nome, $idade) {
            $query = "INSERT INTO pessoas (nome, idade) VALUES (?, ?)";
            $stmt = $conexao->prepare($query);
            $stmt->execute([$nome, $idade]);
        }
        
        public function atualizar($nome, $novaIdade) {
            $query = "UPDATE pessoas SET idade = ? WHERE nome = ?";
            $stmt = $conexao->prepare($query);
            $stmt->execute([$nome, $idade]);
        }
        
        public function remover($nome) {
            $query = "DELETE FROM pessoas WHERE nome = ?";
            $stmt = $onexao->prepare($query);
            $stmt->execute([$nome, $idade]);
        }
    }

    class ConnectionFactory {
        public static function getConnection($host, $database, $user, $password) {
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false, // desativa a execução emulada de prepared statements
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ativa o modo de erros para lançar exceções
                PDO::ATTR_PERSISTENT => true, // ativa o uso de conexões persistentes para maior eficiência
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // altera o modo de busca padrão para FETCH_ASSOC
            ];
            try{
                //Para Postgree (meu PC)
                $pdo = new PDO("pgsql:host=$host;port=5432; dbname=$database;", $user, $password, $options);
                // //Para Mysql
                // $pdo = new PDO("mysql:host=$host; dbname=$database; charset=utf8mb4", $user, $password, $options);
                return $pdo;
            }catch (Exception $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
            
        }
    }

    // Teste
    $host = "localhost";
    $database = "acerlindb";
    $user = "rfcf10";
    $password = "r3h8u4a2n5";

    $conexao = ConnectionFactory::getConnection($host, $database, $user, $password);
    $pessoaDAO = new PessoaDAOImpl($conexao);
    $pessoaDAO->criarTabelaPessoa();

    $pessoaDAO->criar("Kleber", 25);
    $pessoaDAO->criar("Rhuan", 30);
    $pessoaDAO->criar("Thiago", 35);
    $pessoaDAO->criar("Anisio", 40);

    $pessoas = $pessoaDAO->ler();
    foreach ($pessoas as $pessoa) {
        echo "Nome: " . $pessoa->getNome() . ", Idade: " . $pessoa->getIdade() . "<br>";
    }

    $pessoaDAO->remover("Anisio");
    $pessoaDAO->remover("Rhuan");

    $pessoas = $pessoaDAO->ler();
    foreach ($pessoas as $pessoa) {
        echo "Nome: " . $pessoa->getNome() . ", Idade: " . $pessoa->getIdade() . "<br>";
    }

    $pessoaDAO->atualizar("Thiago", 72);

    $pessoas = $pessoaDAO->ler();
    foreach ($pessoas as $pessoa) {
        echo "Nome: " . $pessoa->getNome() . ", Idade: " . $pessoa->getIdade() . "<br>";
    }

    $conexao->close();
?>