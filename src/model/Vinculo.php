<?php

namespace src\model;

use src\config\Conexao;

class Vinculo
{
    private $codVinculo;
    private $descricao;

    public function __construct()
    {
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

    public function inserirVinculo()
    {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO vinculos (descricao) VALUES (:descricao)";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getDescricao())) {
            $this->setCodVinculo($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarVinculo()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE vinculos SET descricao = :descricao WHERE codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getDescricao())) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirVinculo()
    {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM vinculos WHERE codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodVinculo())) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarVinculo($codVinculo)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM vinculos WHERE codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codVinculo' => $codVinculo
        );
        if ($stmt->execute($vals)) {
            return $stmt->fetch();
        } else {
            return false;
        }
    }

    public function listarVinculos()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM vinculos";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public function buscarVinculoPorDescricao($descricao)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM vinculos WHERE descricao = :descricao";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($descricao)) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public function contarVinculos()
    {
    }
}