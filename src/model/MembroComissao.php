<?php

namespace src\model;

use src\config\Conexao;

class MembroComissao
{
    private $codMembroComissao;
    private $codConsultaEleitoral;
    private $codUsuario;
    private $codCargoComissao;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCodMembroComissao()
    {
        return $this->codMembroComissao;
    }

    /**
     * @param mixed $codMembroComissao
     */
    public function setCodMembroComissao($codMembroComissao): void
    {
        $this->codMembroComissao = $codMembroComissao;
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
    public function getCodUsuario()
    {
        return $this->codUsuario;
    }

    /**
     * @param mixed $codUsuario
     */
    public function setCodUsuario($codUsuario): void
    {
        $this->codUsuario = $codUsuario;
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

    public function inserirMembroComissao()
    {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO membros_comissao (codConsultaEleitoral, codUsuario, codCargoComissao) VALUES (:codConsultaEleitoral, :codUsuario, :codCargoComissao)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':codUsuario' => $this->getCodUsuario(),
            ':codCargoComissao' => $this->getCodCargoComissao()
        );
        if ($stmt->execute($vals)) {
            $this->setCodMembroComissao($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarMembroComissao()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE membros_comissao SET codConsultaEleitoral = :codConsultaEleitoral, codUsuario = :codUsuario, codCargoComissao = :codCargoComissao WHERE codMembroComissao = :codMembroComissao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':codUsuario' => $this->getCodUsuario(),
            ':codCargoComissao' => $this->getCodCargoComissao(),
            ':codMembroComissao' => $this->getCodMembroComissao()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirMembroComissao()
    {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM membros_comissao WHERE codMembroComissao = :codMembroComissao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codMembroComissao' => $this->getCodMembroComissao()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarMembroComissao($codMembroComissao) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM membros_comissao WHERE codMembroComissao = :codMembroComissao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codMembroComissao' => $codMembroComissao
        );
        if($stmt->execute($vals)) {
            $membroComissao = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodMembroComissao($membroComissao['codMembroComissao']);
            $this->setCodConsultaEleitoral($membroComissao['codConsultaEleitoral']);
            $this->setCodUsuario($membroComissao['codUsuario']);
            $this->setCodCargoComissao($membroComissao['codCargoComissao']);
            return true;
        } else {
            return false;
        }
    }

    public function listarMembrosComissao($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM membros_comissao WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        if($stmt->execute($vals)) {
            $membrosComissao = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $membrosComissao;
        } else {
            return false;
        }
    }

    public function listarMembrosComissaoPorExtenso($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT mc.codMembroComissao, u.cpf, u.nome, cc.descricao as cargo
                FROM membros_comissao as mc, usuarios as u, cargos_comissao as cc
                WHERE mc.codUsuario = u.codUsuario AND mc.codCargoComissao = cc.codCargoComissao AND mc.codConsultaEleitoral = :codConsultaEleitoral";
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

    public function buscarMembroComissaoPorCodUsuarioPorConsultaEleitoral($codUsuario, $codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM membros_comissao WHERE codUsuario = :codUsuario and codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codUsuario' => $codUsuario,
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        $membroComissao = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($stmt->rowCount() != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarCargoEmUso($codConsultaEleitoral) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM membros_comissao WHERE codConsultaEleitoral = :codConsultaEleitoral and codCargoComissao = 1";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        if($stmt->rowCount() != 0) {
            return true;
        } else {
            return false;
        }
    }
}