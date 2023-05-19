<?php

namespace src\model;

use src\config\Conexao;

class CargoComissao
{
    private $codCargoComissao;
    private $descricao;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodCargoComissao()
    {
        return $this->codCargoComissao;
    }

    /**
     * @param mixed $codCargoComissao
     */
    public function setCodCargoComissao($codCargoComissao): void
    {
        $this->codCargoComissao = $codCargoComissao;
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

    public function inserirCargoComissao() {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO cargo_comissao (descricao) VALUES (:descricao)";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getDescricao())) {
            $this->setCodCargoComissao($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarCargoComissao() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE cargo_comissao SET descricao = :descricao WHERE codCargoComissao = :codCargoComissao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':descricao' => $this->getDescricao(),
            ':codCargoComissao' => $this->getCodCargoComissao()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirCargoComissao() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM cargo_comissao WHERE codCargoComissao = :codCargoComissao";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodCargoComissao())) {
            return true;
        } else {
            return false;
        }
    }

    public function listarCargoComissao() {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM cargos_comissao";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function buscarCargoComissao($codCargoComissao) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM cargo_comissao WHERE codCargoComissao = :codCargoComissao";
        $stmt = $conexao->prepare($sql);
        $stmt->execute($codCargoComissao);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function buscarCargoComissaoPorDescricao($descricao) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM cargo_comissao WHERE descricao = :descricao";
        $stmt = $conexao->prepare($sql);
        $stmt->execute($descricao);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}