<?php

namespace src\model;

use src\config\Conexao;
class Voto
{
    private $codVoto;
    private $codConsultaEleitoral;
    private $votoNulo;
    private $votoBranco;
    private $codChapa;
    private $codUrna;
    private $codVinculo;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodVoto()
    {
        return $this->codVoto;
    }

    /**
     * @param mixed $codVoto
     */
    public function setCodVoto($codVoto): void
    {
        $this->codVoto = $codVoto;
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
    public function getVotoNulo()
    {
        return $this->votoNulo;
    }

    /**
     * @param mixed $votoNulo
     */
    public function setVotoNulo($votoNulo): void
    {
        $this->votoNulo = $votoNulo;
    }

    /**
     * @return mixed
     */
    public function getVotoBranco()
    {
        return $this->votoBranco;
    }

    /**
     * @param mixed $votoBranco
     */
    public function setVotoBranco($votoBranco): void
    {
        $this->votoBranco = $votoBranco;
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
    public function getCodVinculo()
    {
        return $this->codVinculo;
    }

    /**
     * @param mixed $codVinculo
     */
    public function setCodVinculo($codVinculo): void
    {
        $this->codVinculo = $codVinculo;
    }

    public function inserirVoto() {
        $conexao = Conexao::getInstance();
        $codigo = gerarString(10, false, true);
        $sql = "SELECT codVoto FROM votos";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        while(in_array($codigo, $rs)) {
            $stmt->execute();
            $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $codigo = gerarString(10, false, true);
        }
        $sql = "INSERT INTO votos (codVoto, codConsultaEleitoral, votoNulo, votoBranco, codChapa, codUrna, codVinculo) VALUES (:codVoto, :codConsultaEleitoral, :votoNulo, :votoBranco, :codChapa, :codUrna, :codVinculo)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codVoto' => $codigo,
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':votoNulo' => $this->getVotoNulo(),
            ':votoBranco' => $this->getVotoBranco(),
            ':codChapa' => $this->getCodChapa(),
            ':codUrna' => $this->getCodUrna(),
            ':codVinculo' => $this->getCodVinculo()
        );
        if($stmt->execute($vals)) {
            $this->setCodVoto($codigo);
            return true;
        } else {
            return false;
        }
    }

    public function anularVoto() {
        unset($this->codChapa);
        $this->setVotoNulo(1);
    }

    public function votoBranco() {
        unset($this->codChapa);
        $this->setVotoBranco(1);
    }

    public function contarVotos($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS totalVotos FROM votos WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rs[0]['totalVotos'];
    }

    public function contarVotosUrna($codUrna) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS totalVotos FROM votos WHERE codUrna = :codUrna";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codUrna' => $codUrna
        );
        $stmt->execute($vals);
        $rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rs[0]['totalVotos'];
    }
}