<?php

namespace src\model;

use src\config\Conexao;
class Urna
{
    private $codUrna;
    private $codConsultaEleitoral;
    private $descricao;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodUrna()
    {
        return $this->codUrna;
    }

    /**
     * @param mixed $codUrna
     */
    public function setCodUrna($codUrna): void
    {
        $this->codUrna = $codUrna;
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

    public function inserirUrna()
    {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO urnas (codConsultaEleitoral, descricao) VALUES (:codConsultaEleitoral, :descricao)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            'descricao' => $this->getDescricao()
        );
        if ($stmt->execute($vals)) {
            $this->setCodUrna($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarUrna()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE urnas SET descricao = :descricao WHERE codUrna = :codUrna";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'descricao' => $this->getDescricao(),
            'codUrna' => $this->getCodUrna()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirUrna()
    {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM urnas WHERE codUrna = :codUrna";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codUrna' => $this->getCodUrna()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarUrna($codUrna)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM urnas WHERE codUrna = :codUrna";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codUrna' => $codUrna
        );
        if ($stmt->execute($vals)) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodUrna($result['codUrna']);
            $this->setCodConsultaEleitoral($result['codConsultaEleitoral']);
            $this->setDescricao($result['descricao']);
            return true;
        } else {
            return false;
        }
    }

    public function buscarUrnaPorDescricao($descricao, $codConsultaEleitoral)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM urnas WHERE descricao = :descricao and codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'descricao' => $descricao,
            'codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodUrna($result['codUrna']);
            $this->setCodConsultaEleitoral($result['codConsultaEleitoral']);
            $this->setDescricao($result['descricao']);
            return true;
        } else {
            return false;
        }
    }

    public function listarUrnas($codConsultaEleitoral)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM urnas WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            'codConsultaEleitoral' => $codConsultaEleitoral
        );
        if ($stmt->execute($vals)) {
            $result = $stmt->fetchAll();
            return $result;
        } else {
            return false;
        }
    }
}