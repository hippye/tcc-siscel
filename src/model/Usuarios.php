<?php

namespace src\model;

use src\config\Conexao;

class Usuarios {
    private $codUsuario;
    private $cpf;
    private $nomeDeUsuario;
    private $nome;
    private $senha;
    private $email;
    private $eAdministrador;

    public function __construct($id = null) {
        if($id !== null && $id !== 'inserir') {
            $this->localizarUsuario($id);
        }
    }

    /**
     * @return integer
     */
    public function getCodUsuario()
    {
        return $this->codUsuario;
    }

    /**
     * @param integer $codUsuario
     */
    public function setCodUsuario($codUsuario): void
    {
        $this->codUsuario = $codUsuario;
    }

    /**
     * @return float
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param float $cpf
     */
    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string
     */
    public function getNomeDeUsuario()
    {
        return $this->nomeDeUsuario;
    }

    /**
     * @param string $nomeDeUsuario
     */
    public function setNomeDeUsuario($nomeDeUsuario): void
    {
        $this->nomeDeUsuario = $nomeDeUsuario;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha): void
    {
        $this->senha = $senha;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function getEAdministrador()
    {
        return $this->eAdministrador;
    }

    /**
     * @param boolean $eAdministrador
     */
    public function setEAdministrador($eAdministrador): void
    {
        $this->eAdministrador = $eAdministrador;
    }


    public function inserirUsuario() {
        if(substr($this->getSenha(), 0,9) === '$argon2id'){
            $password = $this->getSenha();
        } else {
            $password = password_hash($this->getSenha(), PASSWORD_ARGON2ID);
        }
        $conexao = Conexao::getInstance();
        $sql = "INSERT INTO usuarios (cpf, nomeDeusuario, nome, senha, email, eAdministrador) VALUES (:cpf, :nomeDeUsuario, :nome, :senha, :email, :eAdministrador)";
        $smtp = $conexao->prepare($sql);
        $vals = array(
            ':cpf' => $this->getCpf(),
            ':nomeDeUsuario' => $this->getNomeDeUsuario(),
            ':nome' => $this->getNome(),
            ':senha' => $password,
            ':email' => $this->getEmail(),
            ':eAdministrador' => $this->getEAdministrador()
        );
        if($smtp->execute($vals)) {
            $this->setCodUsuario($conexao->lastInsertId());
            return true;
        } else {
            return false;
        }
    }

    public function alterarUsuario() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE usuarios SET cpf = :cpf, nomeDeUsuario = :nomeDeUsuario, nome = :nome, email = :email, eAdministrador = :eAdministrador WHERE codUsuario = :codUsuario";
        $smtp = $conexao->prepare($sql);
        $vals = array(
            ':codUsuario' => $this->getCodUsuario(),
            ':cpf' => $this->getCpf(),
            ':nomeDeUsuario' => $this->getNomeDeUsuario(),
            ':nome' => $this->getNome(),
            ':email' => $this->getEmail(),
            ':eAdministrador' => $this->getEAdministrador()
        );
        if($smtp->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function alterarSenha() {
        $conexao = Conexao::getInstance();
        $sql = "UPDATE usuarios SET senha = :senha WHERE codUsuario = :codUsuario";
        $smtp = $conexao->prepare($sql);
        $vals = array(
            ':codUsuario' => $this->getCodUsuario(),
            ':senha' => password_hash($this->getSenha(), PASSWORD_ARGON2ID)
        );
        if($smtp->execute($vals)) {
            return true;
        } else {
            return false;
        }
    }

    public function localizarUsuario($codUsuario) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM usuarios WHERE codUsuario = :codUsuario";
        $smtp = $conexao->prepare($sql);
        if($smtp->execute(['codUsuario'=> $codUsuario])) {
            $usuario = $smtp->fetch(\PDO::FETCH_ASSOC);
            $this->setCodUsuario($usuario['codUsuario']);
            $this->setCpf($usuario['cpf']);
            $this->setNomeDeUsuario($usuario['nomeDeUsuario']);
            $this->setNome($usuario['nome']);
            $this->setSenha($usuario['senha']);
            $this->setEmail($usuario['email']);
            $this->setEAdministrador($usuario['eAdministrador']);
            return true;
        } else {
            return false;
        }
    }

    public function excluirUsuario() {
        $conexao = Conexao::getInstance();
        $sql = "DELETE FROM usuarios WHERE codUsuario = :codUsuario";
        $smtp = $conexao->prepare($sql);
        if($smtp->execute($this->getCodUsuario())) {
            return true;
        } else {
            return false;
        }
    }

    public function logarUsuario($nomeDeUsuario, $senha) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM usuarios WHERE nomeDeUsuario = :nomeDeUsuario";
        $smtp = $conexao->prepare($sql);
        $vals = array(
            ':nomeDeUsuario' => $nomeDeUsuario
        );
        if($smtp->execute($vals)) {
            $usuario = $smtp->fetch(\PDO::FETCH_ASSOC);
            if($usuario) {
                if (password_verify($senha, $usuario['senha'])) {
                    $this->setCodUsuario($usuario['codUsuario']);
                    $this->setCpf($usuario['cpf']);
                    $this->setNomeDeUsuario($usuario['nomeDeUsuario']);
                    $this->setNome($usuario['nome']);
                    $this->setSenha($usuario['senha']);
                    $this->setEmail($usuario['email']);
                    $this->setEAdministrador($usuario['eAdministrador']);
                    return true;
                }
            }
        }
        return false;
    }

    public function localizarUsuarioPorNomeDeUsuario($nomeDeUsuario) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM usuarios WHERE nomeDeUsuario = :nomeDeUsuario";
        $smtp = $conexao->prepare($sql);
        if($smtp->execute($nomeDeUsuario)) {
            $usuario = $smtp->fetch(\PDO::FETCH_ASSOC);
            $this->setCodUsuario($usuario['codUsuario']);
            $this->setCpf($usuario['cpf']);
            $this->setNomeDeUsuario($usuario['nomeDeUsuario']);
            $this->setNome($usuario['nome']);
            $this->setSenha($usuario['senha']);
            $this->setEmail($usuario['email']);
            $this->setEAdministrador($usuario['eAdministrador']);
            return true;
        } else {
            return false;
        }
    }

    public function listarUsuarios() {
        $conexao = Conexao::getInstance();
        $sql = "SELECT * FROM usuarios";
        $smtp = $conexao->prepare($sql);
        if($smtp->execute()) {
            return $smtp->fetchAll(\PDO::FETCH_ASSOC);
        }
        return false;
    }
}