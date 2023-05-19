<?php

namespace src\model;

use src\config\Conexao;
class Candidato
{
    private $codCandidato;
    private $codChapa;
    private $codCargo;
    private $codConsultaEleitoral;
    private $cpf;
    private $nome;
    private $foto;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodCandidato()
    {
        return $this->codCandidato;
    }

    /**
     * @param mixed $codCandidato
     */
    public function setCodCandidato($codCandidato): void
    {
        $this->codCandidato = $codCandidato;
    }

    /**
     * @return mixed
     */
    public function getCodChapa()
    {
        return $this->codChapa;
    }

    /**
     * @param mixed $codChapa
     */
    public function setCodChapa($codChapa): void
    {
        $this->codChapa = $codChapa;
    }

    /**
     * @return mixed
     */
    public function getCodCargo()
    {
        return $this->codCargo;
    }

    /**
     * @param mixed $codCargo
     */
    public function setCodCargo($codCargo): void
    {
        $this->codCargo = $codCargo;
    }

    /**
     * @return mixed
     */
    public function getCodConsultaEleitoral()
    {
        return $this->codConsultaEleitoral;
    }

    /**
     * @param mixed $codConsultaEleitoral
     */
    public function setCodConsultaEleitoral($codConsultaEleitoral): void
    {
        $this->codConsultaEleitoral = $codConsultaEleitoral;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param mixed $foto
     */
    public function setFoto($foto): void
    {
        $this->foto = $foto;
    }

    public function inserirCandidato(){
        $conxexao = Conexao::getInstance();
        $sql = "INSERT INTO candidatos (codChapa, codCargo, codConsultaEleitoral, cpf, nome, foto) VALUES (:codChapa, :codCargo, :codConsultaEleitoral, :cpf, :nome, :foto)";
        $stmt = $conxexao->prepare($sql);
        $vals = array(
            ':codChapa' => $this->getCodChapa(),
            ':codCargo' => $this->getCodCargo(),
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':cpf' => $this->getCpf(),
            ':nome' => $this->getNome(),
            ':foto' => $this->getFoto()
        );
        if ($stmt->execute($vals)) {
            $this->setCodCandidato($conxexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarCandidato(){
        $conxexao = Conexao::getInstance();
        $sql = "UPDATE candidatos SET cpf = :cpf, nome = :nome, foto = :foto WHERE codCandidato = :codCandidato";
        $stmt = $conxexao->prepare($sql);
        $vals = array(
            ':cpf' => $this->getCpf(),
            ':nome' => $this->getNome(),
            ':foto' => $this->getFoto(),
            ':codCandidato' => $this->getCodCandidato()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirCandidato(){
        $conxexao = Conexao::getInstance();
        $sql = "DELETE FROM candidatos WHERE codCandidato = :codCandidato";
        $stmt = $conxexao->prepare($sql);
        if ($stmt->execute($this->getCodCandidato())) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarCandidato($codCandidato){
        $conxexao = Conexao::getInstance();
        $sql = "SELECT * FROM candidatos WHERE codCandidato = :codCandidato";
        $stmt = $conxexao->prepare($sql);
        if ($stmt->execute($codCandidato)) {
            $candidato = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodCandidato($candidato['codCandidato']);
            $this->setCodChapa($candidato['codChapa']);
            $this->setCodCargo($candidato['codCargo']);
            $this->setCodConsultaEleitoral($candidato['codConsultaEleitoral']);
            $this->setCpf($candidato['cpf']);
            $this->setNome($candidato['nome']);
            $this->setFoto($candidato['foto']);
            return true;
        } else {
            return false;
        }
    }

    public function listaCandidatos($codConsultaEleitoral){
        $conxexao = Conexao::getInstance();
        $sql = "SELECT * FROM candidatos WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conxexao->prepare($sql);
        if ($stmt->execute($codConsultaEleitoral)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function listaCandidatosPorChapa($codChapa){
        $conxexao = Conexao::getInstance();
        $sql = "SELECT * FROM candidatos WHERE codChapa = :codChapa";
        $stmt = $conxexao->prepare($sql);
        if ($stmt->execute($codChapa)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

}