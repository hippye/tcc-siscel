<?php

namespace src\model;

use src\config\Conexao;
class Chapa
{
    private $codChapa;
    private $codConsultaEleitoral;
    private $descricao;
    private $numeroChapa;
    private $fotoChapa;

    public function __construct()
    {
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
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getNumeroChapa()
    {
        return $this->numeroChapa;
    }

    /**
     * @param mixed $numeroChapa
     */
    public function setNumeroChapa($numeroChapa): void
    {
        $this->numeroChapa = $numeroChapa;
    }

    /**
     * @return mixed
     */
    public function getFotoChapa()
    {
        return $this->fotoChapa;
    }

    /**
     * @param mixed $fotoChapa
     */
    public function setFotoChapa($fotoChapa): void
    {
        $this->fotoChapa = $fotoChapa;
    }

    public function inserirChapa() {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO chapas (codConsultaEleitoral, descricao, numeroChapa, fotoChapa) VALUES (:codConsultaEleitoral, :descricao, :numeroChapa, :fotoChapa)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':descricao' => $this->getDescricao(),
            ':numeroChapa' => $this->getNumeroChapa(),
            ':fotoChapa' => $this->getFotoChapa()
        );
        if ($stmt->execute($vals)) {
            $this->setCodChapa($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarChapa() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE chapas SET descricao = :descricao, numeroChapa = :numeroChapa, fotoChapa = :fotoChapa WHERE codChapa = :codChapa";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codChapa' => $this->getCodChapa(),
            ':descricao' => $this->getDescricao(),
            ':numeroChapa' => $this->getNumeroChapa(),
            ':fotoChapa' => $this->getFotoChapa()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirChapa() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM chapas WHERE codChapa = :codChapa";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codChapa' => $this->getCodChapa()
        );
        $stmt->execute($vals);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarChapa($codChapa) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM chapas WHERE codChapa = :codChapa";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codChapa' => $codChapa
        );
        if ($stmt->execute($vals)) {
            $chapa = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodChapa($chapa['codChapa']);
            $this->setCodConsultaEleitoral($chapa['codConsultaEleitoral']);
            $this->setDescricao($chapa['descricao']);
            $this->setNumeroChapa($chapa['numeroChapa']);
            $this->setFotoChapa($chapa['fotoChapa']);
            return true;
        } else {
            return false;
        }
    }

    public function listarChapas($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM chapas WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        if ($stmt->execute($vals)) {
            $chapas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $chapas;
        } else {
            return false;
        }
    }

    public function buscarChapaPorNumero($codConsultaEleitoral, $numeroChapa) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM chapas WHERE codConsultaEleitoral = :codConsultaEleitoral AND numeroChapa = :numeroChapa";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral,
            ':numeroChapa' => $numeroChapa
        );
        $stmt->execute($vals);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}