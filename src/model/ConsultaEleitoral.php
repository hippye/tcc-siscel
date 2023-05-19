<?php

namespace src\model;

use src\config\Conexao;

class ConsultaEleitoral
{
    private $codConsultaEleitoral;
    private $descricao;
    private $dataInicio;
    private $horaInicio;
    private $dataEncerramento;
    private $horaEncerramento;
    private $situacao;
    private $idVotacao;
    private $usarOAuth;
    private $usarLdap;
    private $dataZerezimo;

    public function __construct()
    {
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
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param mixed $dataInicio
     */
    public function setDataInicio($dataInicio): void
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return mixed
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param mixed $horaInicio
     */
    public function setHoraInicio($horaInicio): void
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * @return mixed
     */
    public function getDataEncerramento()
    {
        return $this->dataEncerramento;
    }

    /**
     * @param mixed $dataEncerramento
     */
    public function setDataEncerramento($dataEncerramento): void
    {
        $this->dataEncerramento = $dataEncerramento;
    }

    /**
     * @return mixed
     */
    public function getHoraEncerramento()
    {
        return $this->horaEncerramento;
    }

    /**
     * @param mixed $horaEncerramento
     */
    public function setHoraEncerramento($horaEncerramento): void
    {
        $this->horaEncerramento = $horaEncerramento;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao): void
    {
        $this->situacao = $situacao;
    }

    /**
     * @return mixed
     */
    public function getIdVotacao()
    {
        return $this->idVotacao;
    }

    /**
     * @param mixed $idVotacao
     */
    public function setIdVotacao($idVotacao): void
    {
        $this->idVotacao = $idVotacao;
    }

    /**
     * @return mixed
     */
    public function getUsarOAuth()
    {
        return $this->usarOAuth;
    }

    /**
     * @param mixed $usarOAuth
     */
    public function setUsarOAuth($usarOAuth): void
    {
        $this->usarOAuth = $usarOAuth;
    }

    /**
     * @return mixed
     */
    public function getUsarLdap()
    {
        return $this->usarLdap;
    }

    /**
     * @param mixed $usarLdap
     */
    public function setUsarLdap($usarLdap): void
    {
        $this->usarLdap = $usarLdap;
    }

    /**
     * @return mixed
     */
    public function getDataZerezimo()
    {
        return $this->dataZerezimo;
    }

    /**
     * @param mixed $dataZerezimo
     */
    public function setDataZerezimo($dataZerezimo): void
    {
        $this->dataZerezimo = $dataZerezimo;
    }

    public function inserirConsultaEleitoral()
    {
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO consultas_eleitorais (descricao, dataInicio, horaInicio, dataEncerramento, horaEncerramento, situacao, idVotacao, usarOAuth, usarLdap, dataZerezimo) VALUES (:descricao, :dataInicio, :horaInicio, :dataEncerramento, :horaEncerramento, :situacao, :idVotacao, :usarOAuth, :usarLdap, :dataZerezimo)";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':descricao' => $this->getDescricao(),
            ':dataInicio' => $this->getDataInicio(),
            ':horaInicio' => $this->getHoraInicio(),
            ':dataEncerramento' => $this->getDataEncerramento(),
            ':horaEncerramento' => $this->getHoraEncerramento(),
            ':situacao' => $this->getSituacao(),
            ':idVotacao' => $this->getIdVotacao(),
            ':usarOAuth' => $this->getUsarOAuth(),
            ':usarLdap' => $this->getUsarLdap(),
            ':dataZerezimo' => $this->getDataZerezimo()
        );
        if ($stmt->execute($vals)) {
            $this->setCodConsultaEleitoral($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarConsultaEleitoral()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE consultas_eleitorais SET descricao = :descricao, dataInicio = :dataInicio, horaInicio = :horaInicio, dataEncerramento = :dataEncerramento, horaEncerramento = :horaEncerramento, situacao = :situacao, idVotacao = :idVotacao, usarOAuth = :usarOAuth, usarLdap = :usarLdap, dataZerezimo = :dataZerezimo WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':descricao' => $this->getDescricao(),
            ':dataInicio' => $this->getDataInicio(),
            ':horaInicio' => $this->getHoraInicio(),
            ':dataEncerramento' => $this->getDataEncerramento(),
            ':horaEncerramento' => $this->getHoraEncerramento(),
            ':situacao' => $this->getSituacao(),
            ':idVotacao' => $this->getIdVotacao(),
            ':usarOAuth' => $this->getUsarOAuth(),
            ':usarLdap' => $this->getUsarLdap(),
            ':dataZerezimo' => $this->getDataZerezimo(),
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral()
        );
        if ($stmt->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function localizarConsultaEleitoral($codConsultaEleitoral)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM consultas_eleitorais WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $codConsultaEleitoral
        );
        $stmt->execute($vals);
        if ($stmt->rowCount() > 0) {
            $consultaEleitoral = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodConsultaEleitoral($consultaEleitoral['codConsultaEleitoral']);
            $this->setDescricao($consultaEleitoral['descricao']);
            $this->setDataInicio($consultaEleitoral['dataInicio']);
            $this->setHoraInicio($consultaEleitoral['horaInicio']);
            $this->setDataEncerramento($consultaEleitoral['dataEncerramento']);
            $this->setHoraEncerramento($consultaEleitoral['horaEncerramento']);
            $this->setSituacao($consultaEleitoral['situacao']);
            $this->setIdVotacao($consultaEleitoral['idVotacao']);
            $this->setUsarOAuth($consultaEleitoral['usarOAuth']);
            $this->setUsarLdap($consultaEleitoral['usarLdap']);
            $this->setDataZerezimo($consultaEleitoral['dataZerezimo']);
            return true;
        } else {
            return false;
        }
    }

    public function excluirConsultaEleitoral()
    {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM consultas_eleitorais WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            return true;
        } else {
            return false;
        }
    }

    public function listarConsultasEleitorais()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM consultas_eleitorais";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function localizarConsultaEleitoralPorVotacao($idVotacao)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM consultas_eleitorais WHERE idVotacao like :idVotacao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':idVotacao' => $idVotacao
        );
        $stmt->execute($vals);
        if ($stmt->rowCount() > 0) {
            $consultaEleitoral = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->setCodConsultaEleitoral($consultaEleitoral['codConsultaEleitoral']);
            $this->setDescricao($consultaEleitoral['descricao']);
            $this->setDataInicio($consultaEleitoral['dataInicio']);
            $this->setHoraInicio($consultaEleitoral['horaInicio']);
            $this->setDataEncerramento($consultaEleitoral['dataEncerramento']);
            $this->setHoraEncerramento($consultaEleitoral['horaEncerramento']);
            $this->setSituacao($consultaEleitoral['situacao']);
            $this->setIdVotacao($consultaEleitoral['idVotacao']);
            $this->setUsarOAuth($consultaEleitoral['usarOAuth']);
            $this->setUsarLdap($consultaEleitoral['usarLdap']);
            $this->setDataZerezimo($consultaEleitoral['dataZerezimo']);
            return true;
        } else {
            return false;
        }
    }

    public function contarVotantes()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codEleitor) AS conta FROM eleitores WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral()
        );
        if ($stmt->execute($vals)) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta['conta'];
        } else {
            return false;
        }
    }

    public function contarVotantesPorUrna()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codEleitor) AS conta FROM eleitores where codConsultaEleitoral = :codConsultaEleitoral AND codUrna IN (SELECT codUrna FROM urnas WHERE codConsultaEleitoral = :codConsultaEleitoral)";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta['conta'];
        } else {
            return false;
        }
    }

    public function contarVotantesPorVinculo()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codEleitor) AS conta, codVinculo FROM eleitores where codConsultaEleitoral = :codConsultaEleitoral GROUP BY codVinculo";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function contarVotosTotais()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta FROM votos WHERE codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral()
        );
        if ($stmt->execute($vals)) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta['conta'];
        } else {
            return false;
        }
    }

    public function totalizarVotos()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta, votos.codChapa, vinculo, votoBranco, votoNulo FROM votos INNER JOIN chapas ON votos.codChapa = chapas.codChapa WHERE codConsultaEleitoral = :codConsultaEleitoral GROUP BY votos.codChapa, votos, vinculo, votoBranco, votoNulo ORDER BY codChapa, votoNulo, votoBranco, vinculo ASC ";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function totalizarVotosPorUrna($codUrna)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta, votos.codChapa, vinculo, votoBranco, votoNulo FROM votos INNER JOIN chapas ON votos.codChapa = chapas.codChapa WHERE codConsultaEleitoral = :codConsultaEleitoral AND codUrna = :codUrna GROUP BY votos.codChapa, votos, vinculo, votoBranco, votoNulo ORDER BY codChapa, votoNulo, votoBranco, vinculo ASC ";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute(array($this->getCodConsultaEleitoral(), $codUrna))) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function totalizarVotosNulos()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta FROM votos WHERE codConsultaEleitoral = :codConsultaEleitoral AND votoNulo = 1";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta['conta'];
        } else {
            return false;
        }
    }

    public function totalizarVotosEmBranco()
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta FROM votos WHERE codConsultaEleitoral = :codConsultaEleitoral AND votoBranco = 1";
        $stmt = $conexao->prepare($sql);
        if ($stmt->execute($this->getCodConsultaEleitoral())) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta['conta'];
        } else {
            return false;
        }
    }

    public function totalizarVotosPorVinculo($codVinculo)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS contaVotoVinculo, codVinculo FROM votos WHERE codConsultaEleitoral = :codConsultaEleitoral AND codVinculo = :codVinculo GROUP BY codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            ':codConsultaEleitoral' => $this->getCodConsultaEleitoral(),
            ':codVinculo' => $codVinculo
        );
        if ($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function totalizarVotosValidos()
    {

    }

    public function totalizarVotosValidosPorVinculo()
    {

    }

    public function totalizarVotosValidosPorChapaPorVinculo($codChapa, $codVinculo)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT COUNT(codVoto) AS conta from votos where codConsultaEleitoral = :codConsultaEleitoral and codChapa = :codChapa and codVinculo = :codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "codConsultaEleitoral" => $this->getCodConsultaEleitoral(),
            "codChapa" => $codChapa,
            "codVinculo" => $codVinculo
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            $conta = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $conta;
        } else {
            return false;
        }
    }

    public function totalVotosValidosPorVinculo()
    {
        $conexao = Conexao::getInstance();
        $sql = "select count(vo.codVoto) as conta, vo.codVinculo, vi.descricao as vinculo
                from votos as vo, vinculos as vi
                where codConsultaEleitoral = 1 and vo.codVinculo = vi.codVinculo
                group by codVinculo";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "codConsultaEleitoral" => $this->getCodConsultaEleitoral()
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function totalVotosValidosPorChapa($codChapa)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT votos.codChapa, COUNT(*) AS totalVotos, votos.codVinculo, vinculos.descricao AS descVinculo, votos.codChapa, chapas.descricao AS descChapa
                FROM votos
                JOIN vinculos ON votos.codVinculo = vinculos.codVinculo
                LEFT JOIN chapas ON votos.codChapa = chapas.codChapa
                WHERE votos.codConsultaEleitoral = :codConsultaEleitoral AND votos.codChapa = :codChapa
                GROUP BY votos.codVinculo, votoNulo, votoBranco, codChapa, chapas.descricao;";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "codConsultaEleitoral" => $this->getCodConsultaEleitoral(),
            "codChapa" => $codChapa
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function totalizarVotosNulosPorUrna()
    {

    }

    public function totalizarVotosEmBrancoPorUrna()
    {

    }

    public function totalizarVotosTotais(){
        $conexao = Conexao::getInstance();
        $sql = "SELECT votoNulo, votoBranco, votos.codChapa, COUNT(*) AS total, vinculos.descricao AS descVinculo, urnas.descricao AS descUrna, chapas.descricao AS descChapa
                FROM votos
                JOIN vinculos ON votos.codVinculo = vinculos.codVinculo
                JOIN urnas ON votos.codUrna = urnas.codUrna
                LEFT JOIN chapas ON votos.codChapa = chapas.codChapa
                WHERE votos.codConsultaEleitoral = :codConsultaEleitoral
                GROUP BY votos.codVinculo, votos.codUrna, votoNulo, votoBranco, codChapa, chapas.descricao;";
        $smtp = $conexao->prepare($sql);
        $vals = array(
            "codConsultaEleitoral" => $this->getCodConsultaEleitoral()
        );
        $smtp->execute($vals);
        if($smtp->rowCount() > 0) {
            return $smtp->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function efetuarZerezimo()
    {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE consultas_eleitorais SET dataZerezimo = :dataZerezimo where codConsultaEleitoral = :codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "dataZerezimo" => date('Y-m-d H:i:s'),
            "codConsultaEleitoral" => $this->getCodConsultaEleitoral()
        );
        $stmt->execute($vals);
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function listarConsultasEleitoraisPorUsuario($codUsuario)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT mc.codConsultaEleitoral,mc.codCargoComissao, ce.descricao, ce.dataInicio, ce.horaInicio, ce.dataEncerramento, ce.horaEncerramento, ce.dataZerezimo, cc.descricao AS descricaoCargo
                FROM membros_comissao AS mc, consultas_eleitorais AS ce, cargos_comissao AS cc
                WHERE codUsuario = :codUsuario 
                AND mc.codConsultaEleitoral = ce.codConsultaEleitoral
                AND mc.codCargoComissao = cc.codCargoComissao";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "codUsuario" => $codUsuario
        );
        if ($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function listarConsultasEleitoraisPorUsuarioComCargo($codUsuario)
    {
        $conexao = Conexao::getInstance();
        $sql = "SELECT DISTINCT ce.codConsultaEleitoral, ce.descricao, ce.dataZerezimo, 
                (SELECT dc.descricao FROM membros_comissao AS m
                INNER JOIN cargos_comissao AS dc ON m.codCargoComissao = dc.codCargoComissao
                WHERE m.codConsultaEleitoral = ce.codConsultaEleitoral AND m.codUsuario = :codUsuario) AS descricaoCargo
                FROM consultas_eleitorais AS ce
                LEFT JOIN membros_comissao AS m ON ce.codConsultaEleitoral = m.codConsultaEleitoral";
        $stmt = $conexao->prepare($sql);
        $vals = array(
            "codUsuario" => $codUsuario
        );
        if ($stmt->execute($vals)) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


    public function votacaoAberta() {
        $datainicio = strtotime($this->getDataInicio() . ' ' . $this->getHoraInicio());
        if(time() >= $datainicio) {
            return true;
        } else {
            return false;
        }
    }

    public function votacaoEncerrada() {
        $dataencerramento = strtotime($this->getDataEncerramento() . ' ' . $this->getHoraEncerramento());
        if(time() >= $dataencerramento) {
            return true;
        } else {
            return false;
        }
    }

    public function validarVotos()
    {
        //TODO validar votos
    }
}