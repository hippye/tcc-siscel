<?php

namespace src\model;

use src\config\Conexao;
class Cargo
{
    private $codCargo;
    private $descricao;

    public function __construct()
    {
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

    public function inserirCargo() {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO cargo (descricao) VALUES (:descricao)";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getDescricao())) {
            $this->setCodCargo($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarCargo() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE cargo SET descricao = :descricao WHERE codCargo = :codCargo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getDescricao(), $this->getCodCargo())) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirCargo() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM cargo WHERE codCargo = :codCargo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodCargo())) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarCargo($codCargo) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM cargo WHERE codCargo = :codCargo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($codCargo)) {
            $this->setCodCargo($stmt->fetchColumn(0));
            $this->setDescricao($stmt->fetchColumn(1));
            return true;
        } else {
            return false;
        }
    }

    public function listarCargos() {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM cargo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }
}