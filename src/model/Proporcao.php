<?php

namespace src\model;

use src\config\Conexao;
class Proporcao
{
    private $codProporcao;
    private $codConsultaEleitoral;
    private $codVinculo;
    private $peso;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodProporcao()
    {
        return $this->codProporcao;
    }

    /**
     * @param mixed $codProporcao
     */
    public function setCodProporcao($codProporcao): void
    {
        $this->codProporcao = $codProporcao;
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

    /**
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param mixed $peso
     */
    public function setPeso($peso): void
    {
        $this->peso = $peso;
    }

    public function inserirProporcao()
    {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO proporcoes (codConsultaEleitoral, codVinculo, peso) VALUES (:codConsultaEleitoral, :codVinculo, :peso)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':codVinculo' => $this->getCodVinculo(),
            ':peso' => $this->getPeso()
        );
        if ($stmt->execute($vals)) {
            $this->setCodProporcao($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarProporcao()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE proporcoes SET codConsultaEleitoral = :codConsultaEleitoral, codVinculo = :codVinculo, peso = :peso WHERE codProporcao = :codProporcao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':codVinculo' => $this->getCodVinculo(),
            ':peso' => $this->getPeso(),
            ':codProporcao' => $this->getCodProporcao()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarProporcao($codProporcao) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM proporcoes WHERE codProporcao = :codProporcao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codProporcao' => $codProporcao
        );
        if($stmt->execute($vals)) {
            $proporcao = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodProporcao($proporcao['codProporcao']);
            $this->setCodConsultaEleitoral($proporcao['codConsultaEleitoral']);
            $this->setCodVinculo($proporcao['codVinculo']);
            $this->setPeso($proporcao['peso']);
            return true;
        } else {
            return false;
        }
    }

    public function excluirProporcao() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM proporcoes WHERE codProporcao = :codProporcao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codProporcao' => $this->getCodProporcao()
        );
        if($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function listarProporcaoPorCosultaEleitoral($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT p.codProporcao, p.codConsultaEleitoral, v.codVinculo, v.descricao AS vinculo, p.peso 
                FROM vinculos v, proporcoes p 
                WHERE p.codVinculo = v.codVinculo AND p.codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        if($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function listarProporcaoPorConsultaComDescricaoVinculo($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT p.codProporcao, p.codConsultaEleitoral, p.codVinculo, v.descricao AS descricao, p.peso 
                FROM vinculos v, proporcoes p 
                WHERE p.codVinculo = v.codVinculo AND p.codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        if($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function buscarProporcaoPorConsultaEleitoralPorVinculo($codConsultaEleitoral, $codVinculo) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM proporcoes WHERE codConsultaEleitoral = :codConsultaEleitoral AND codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral,
            ':codVinculo' => $codVinculo
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
             $result = $stmt->fetch(\PDO::FETCH_ASSOC);
             $this->setCodProporcao($result['codProporcao']);
             $this->setCodConsultaEleitoral($result['codConsultaEleitoral']);
             $this->setCodVinculo($result['codVinculo']);
             $this->setPeso($result['peso']);
            return true;
        } else {
            return false;
        }
    }

    public function contarProporcoes($codConsultaEleitoral){
        $conexao = Conexao::getInstance();
        $sql = "SELECT SUM(peso) AS total FROM proporcoes WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        $total = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $total['total'];
    }
}