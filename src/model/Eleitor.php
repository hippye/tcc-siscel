<?php

namespace src\model;

use src\config\Conexao;

class Eleitor
{
    private $codEleitor;
    private $codConsultaEleitoral;
    private $cpf;
    private $nome;
    private $email;
    private $codVinculo;
    private $codUrna;
    private $aceitaTermo;
    private $comprovanteDeVoto;
    private $token;
    private $ultimaAtualizacao;
    private $idVotacao;
    private $senhaVotacao;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCodEleitor()
    {
        return $this->codEleitor;
    }

    /**
     * @param int $codEleitor
     */
    public function setCodEleitor($codEleitor)
    {
        $this->codEleitor = $codEleitor;
    }

    /**
     * @return int
     */
    public function getCodConsultaEleitoral()
    {
        return $this->codConsultaEleitoral;
    }

    /**
     * @param int $codConsultaEleitoral
     */
    public function setCodConsultaEleitoral($codConsultaEleitoral)
    {
        $this->codConsultaEleitoral = $codConsultaEleitoral;
    }

    /**
     * @return int
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param int $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getCodVinculo()
    {
        return $this->codVinculo;
    }

    /**
     * @param int $codVinculo
     */
    public function setCodVinculo($codVinculo)
    {
        $this->codVinculo = $codVinculo;
    }

    /**
     * @return int
     */
    public function getCodUrna()
    {
        return $this->codUrna;
    }

    /**
     * @param int $codUrna
     */
    public function setCodUrna($codUrna)
    {
        $this->codUrna = $codUrna;
    }

    /**
     * @return bool
     */
    public function isAceitaTermo()
    {
        return $this->aceitaTermo;
    }

    /**
     * @param bool $aceitaTermo
     */
    public function setAceitaTermo($aceitaTermo)
    {
        $this->aceitaTermo = $aceitaTermo;
    }

    /**
     * @return string
     */
    public function getComprovanteDeVoto()
    {
        return $this->comprovanteDeVoto;
    }

    /**
     * @param $comprovanteDeVoto
     */
    public function setComprovanteDeVoto($comprovanteDeVoto)
    {
        $this->comprovanteDeVoto = $comprovanteDeVoto;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getUltimaAtualizacao()
    {
        return $this->ultimaAtualizacao;
    }

    /**
     * @param string $ultimaAtualizacao
     */
    public function setUltimaAtualizacao($ultimaAtualizacao)
    {
        $this->ultimaAtualizacao = $ultimaAtualizacao;
    }

    /**
     * @return string
     */
    public function getIdVotacao()
    {
        return $this->idVotacao;
    }

    /**
     * @param string $idVotacao
     */
    public function setIdVotacao($idVotacao)
    {
        $this->idVotacao = $idVotacao;
    }

    /**
     * @return string
     */
    public function getSenhaVotacao()
    {
        return $this->senhaVotacao;
    }

    /**
     * @param string $senhaVotacao
     */
    public function setSenhaVotacao($senhaVotacao)
    {
        $this->senhaVotacao = $senhaVotacao;
    }

    public function inserirEleitor() {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO eleitores (codConsultaEleitoral, cpf, nome, email, codVinculo, codUrna, aceitaTermo, idVotacao, senhaVotacao) 
                VALUES (:codConsultaEleitoral, :cpf, :nome, :email, :codVinculo, :codUrna, :aceitaTermo, :idVotacao, :senhaVotacao)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':cpf' => $this->getCpf(),
            ':nome' => $this->getNome(),
            ':email' => $this->getEmail(),
            ':codVinculo' => $this->getCodVinculo(),
            ':codUrna' => $this->getCodUrna(),
            ':aceitaTermo' => $this->isAceitaTermo(),
            ':idVotacao' => $this->getIdVotacao(),
            ':senhaVotacao' => $this->getSenhaVotacao()
        );
        if($stmt->execute($vals)) {
            $this->setCodEleitor($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarEleitor() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE eleitores SET cpf = :cpf, nome = :nome, email = :email, codVinculo = :codVinculo, codUrna = :codUrna, aceitaTermo = :aceitaTermo, comprovanteDeVoto = :comprovanteDeVoto, token = :token, ultimaAtualizacao = :ultimaAtualizacao, idVotacao = :idVotacao, senhaVotacao = :senhaVotacao WHERE codEleitor = :codEleitor";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codEleitor' => $this->getCodEleitor(),
            ':cpf' => $this->getCpf(),
            ':nome' => $this->getNome(),
            ':email' => $this->getEmail(),
            ':codVinculo' => $this->getCodVinculo(),
            ':codUrna' => $this->getCodUrna(),
            ':aceitaTermo' => $this->isAceitaTermo(),
            ':comprovanteDeVoto' => $this->getComprovanteDeVoto(),
            ':token' => $this->getToken(),
            ':ultimaAtualizacao' => $this->getUltimaAtualizacao(),
            ':idVotacao' => $this->getIdVotacao(),
            ':senhaVotacao' => $this->getSenhaVotacao()
        );
        if($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirEleitor() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM eleitores WHERE codEleitor = :codEleitor";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codEleitor' => $this->getCodEleitor()
        );
        if($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarEleitor($codEleitor) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM eleitores WHERE codEleitor = :codEleitor";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codEleitor' => $codEleitor
        );
        if($stmt->execute($vals)) {
            $eleitor = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodEleitor($eleitor['codEleitor']);
            $this->setCodConsultaEleitoral($eleitor['codConsultaEleitoral']);
            $this->setCpf($eleitor['cpf']);
            $this->setNome($eleitor['nome']);
            $this->setEmail($eleitor['email']);
            $this->setCodVinculo($eleitor['codVinculo']);
            $this->setCodUrna($eleitor['codUrna']);
            $this->setAceitaTermo($eleitor['aceitaTermo']);
            $this->setComprovanteDeVoto($eleitor['comprovanteDeVoto']);
            $this->setToken($eleitor['token']);
            $this->setUltimaAtualizacao($eleitor['ultimaAtualizacao']);
            $this->setIdVotacao($eleitor['idVotacao']);
            $this->setSenhaVotacao($eleitor['senhaVotacao']);
            return true;
        } else {
            return false;
        }
    }

    public function buscarEleitorPorIdVotacao($idVotacao) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM eleitores WHERE idVotacao = :idVotacao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':idVotacao' => $idVotacao
        );
        if($stmt->execute($vals)) {
            $eleitor = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodEleitor($eleitor['codEleitor']);
            $this->setCodConsultaEleitoral($eleitor['codConsultaEleitoral']);
            $this->setCpf($eleitor['cpf']);
            $this->setNome($eleitor['nome']);
            $this->setEmail($eleitor['email']);
            $this->setCodVinculo($eleitor['codVinculo']);
            $this->setCodUrna($eleitor['codUrna']);
            $this->setAceitaTermo($eleitor['aceitaTermo']);
            $this->setComprovanteDeVoto($eleitor['comprovanteDeVoto']);
            $this->setToken($eleitor['token']);
            $this->setUltimaAtualizacao($eleitor['ultimaAtualizacao']);
            $this->setIdVotacao($eleitor['idVotacao']);
            $this->setSenhaVotacao($eleitor['senhaVotacao']);
            return true;
        } else {
            return false;
        }
    }

    public function listarEleitores($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM eleitores WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codConsultaEleitoral' => $codConsultaEleitoral
        );
        if($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function listarEleitoresComVinculoComUrna($codConsultaEleitoral)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT e.*, v.descricao AS descricaoVinculo, u.descricao AS descricaoUrna 
                FROM eleitores e, vinculos v, urnas u 
                WHERE e.codConsultaEleitoral = :codConsultaEleitoral AND v.codVinculo = e.codVinculo AND u.codUrna = e.codUrna";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codConsultaEleitoral' => $codConsultaEleitoral
        );
        if ($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function buscarEleitorPorNome($nome) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM eleitores WHERE nome LIKE :nome";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':nome' => $nome
        );
        if($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function buscarEleitorPorCpfPorConsultaEleitoral($cpf, $codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM eleitores WHERE cpf LIKE :cpf and codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':cpf' => $cpf,
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            $eleitor = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->setCodEleitor($eleitor[0]['codEleitor']);
            $this->setCodConsultaEleitoral($eleitor[0]['codConsultaEleitoral']);
            $this->setCpf($eleitor[0]['cpf']);
            $this->setNome($eleitor[0]['nome']);
            $this->setEmail($eleitor[0]['email']);
            $this->setCodVinculo($eleitor[0]['codVinculo']);
            $this->setCodUrna($eleitor[0]['codUrna']);
            $this->setAceitaTermo($eleitor[0]['aceitaTermo']);
            $this->setComprovanteDeVoto($eleitor[0]['comprovanteDeVoto']);
            $this->setToken($eleitor[0]['token']);
            $this->setUltimaAtualizacao($eleitor[0]['ultimaAtualizacao']);
            $this->setIdVotacao($eleitor[0]['idVotacao']);
            $this->setSenhaVotacao($eleitor[0]['senhaVotacao']);
            return true;
        } else {
            return false;
        }
    }

    public function aceitarTermo()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE eleitores SET aceitaTermo = :aceitaTermo WHERE codEleitor = :codEleitor";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':aceitaTermo' => $this->isAceitaTermo(),
            ':codEleitor' => $this->getCodEleitor()
        );
        if($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizarToken($token)
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE eleitores SET token = :token, ultimaAtualizacao = :ultimaAtualizacao WHERE codEleitor = :codEleitor";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':token' => $token,
            ':ultimaAtualizacao' => time(),
            ':codEleitor' => $this->getCodEleitor()
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verificarToken($token)
    {
        if($this->getToken() == $token) {
            return true;
        } else {
            return false;
        }
    }

    public function contarVotantes($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(*) AS total FROM eleitores WHERE codConsultaEleitoral = :codConsultaEleitoral AND comprovanteDeVoto <> ''";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codConsultaEleitoral' => $codConsultaEleitoral
        );
        if($stmt->execute($vals)) {
            $total = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $total[0]['total'];
        } else {
            return false;
        }
    }

    public function totalVotantePorVinculo($codConsultaELeitoral, $codVinculo) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codEleitor) as totalVotantePorVinculo FROM eleitores WHERE codConsultaEleitoral = :codConsultaEleitoral and codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaELeitoral,
            ':codVinculo' => $codVinculo
        );
        if($stmt->execute($vals)) {
            $total = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $total['totalVotantePorVinculo'];
        } else {
            return false;
        }
    }
}