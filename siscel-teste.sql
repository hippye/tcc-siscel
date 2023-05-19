-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.10.2-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela siscel.candidatos
CREATE TABLE IF NOT EXISTS `candidatos` (
  `codCandidatos` int(11) NOT NULL AUTO_INCREMENT,
  `codChapa` int(11) DEFAULT NULL,
  `codCargo` int(11) DEFAULT NULL,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `cpf` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codCandidatos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.candidatos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `candidatos` DISABLE KEYS */;
/*!40000 ALTER TABLE `candidatos` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.cargos
CREATE TABLE IF NOT EXISTS `cargos` (
  `codCargo` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codCargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.cargos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.cargos_comissao
CREATE TABLE IF NOT EXISTS `cargos_comissao` (
  `codCargoComissao` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codCargoComissao`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.cargos_comissao: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `cargos_comissao` DISABLE KEYS */;
INSERT INTO `cargos_comissao` (`codCargoComissao`, `descricao`) VALUES
	(1, 'Presidente da Comissão'),
	(2, 'Membro da Comissão');
/*!40000 ALTER TABLE `cargos_comissao` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.chapas
CREATE TABLE IF NOT EXISTS `chapas` (
  `codChapa` int(11) NOT NULL AUTO_INCREMENT,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `numeroChapa` int(11) DEFAULT NULL,
  `fotoChapa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codChapa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.chapas: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `chapas` DISABLE KEYS */;
INSERT INTO `chapas` (`codChapa`, `codConsultaEleitoral`, `descricao`, `numeroChapa`, `fotoChapa`) VALUES
	(1, 1, 'Chapa UFF++', 1, NULL),
	(2, 1, 'Chapa quero mais', 2, NULL);
/*!40000 ALTER TABLE `chapas` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.consultas_eleitorais
CREATE TABLE IF NOT EXISTS `consultas_eleitorais` (
  `codConsultaEleitoral` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) DEFAULT NULL,
  `dataInicio` varchar(45) DEFAULT NULL,
  `horaInicio` varchar(45) DEFAULT NULL,
  `dataEncerramento` varchar(45) DEFAULT NULL,
  `horaEncerramento` varchar(45) DEFAULT NULL,
  `situacao` varchar(45) DEFAULT NULL,
  `idVotacao` varchar(45) DEFAULT NULL,
  `usarOAuth` tinyint(4) DEFAULT NULL,
  `usarLdap` tinyint(4) DEFAULT NULL,
  `dataZerezimo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codConsultaEleitoral`),
  UNIQUE KEY `idVotacao_UNIQUE` (`idVotacao`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.consultas_eleitorais: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `consultas_eleitorais` DISABLE KEYS */;
INSERT INTO `consultas_eleitorais` (`codConsultaEleitoral`, `descricao`, `dataInicio`, `horaInicio`, `dataEncerramento`, `horaEncerramento`, `situacao`, `idVotacao`, `usarOAuth`, `usarLdap`, `dataZerezimo`) VALUES
	(1, 'Eleição para Coordenador', '2023-02-20', '09:01:00', '2023-02-22', '21:00:00', 'criada', 'kjhKJgKJHGKJghKJh', 0, 0, '2023-03-12 14:00:10'),
	(4, 'vcx', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 'vlq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `consultas_eleitorais` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.eleitores
CREATE TABLE IF NOT EXISTS `eleitores` (
  `codEleitor` int(11) NOT NULL AUTO_INCREMENT,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `codVinculo` int(11) DEFAULT NULL,
  `codUrna` int(11) DEFAULT NULL,
  `aceitaTermo` tinyint(4) DEFAULT NULL,
  `comprovanteDeVoto` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ultimaAtualizacao` varchar(45) DEFAULT NULL,
  `idVotacao` varchar(45) DEFAULT NULL,
  `senhaVotacao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codEleitor`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.eleitores: ~50 rows (aproximadamente)
/*!40000 ALTER TABLE `eleitores` DISABLE KEYS */;
INSERT INTO `eleitores` (`codEleitor`, `codConsultaEleitoral`, `cpf`, `nome`, `email`, `codVinculo`, `codUrna`, `aceitaTermo`, `comprovanteDeVoto`, `token`, `ultimaAtualizacao`, `idVotacao`, `senhaVotacao`) VALUES
	(1, 1, '9450395730', 'Cesar Daer', 'cesar.daer@gmail.com', 2, 1, 1, 'a39288e9c65761f9c10c6ac52add310f4ded373d', '1', '1678666316', 'KAA6Y3P7KPZ5', 'wtAVHadk0*koxQJ@'),
	(3, 1, '9419666743', 'Shimeni', 'shi@gmail.com', 1, 1, 1, 'e3eace618907b302ee4a1892d48eae478849a358', '1', '1678667016', 'QBMKJVSDJWJY', 'H-T-S1iE37IAcUhB'),
	(73, 1, '09450395731', 'Fulano de Tal', 'fulano@fulano.com', 2, 1, 1, NULL, NULL, NULL, 'ZN1JG7IAVPPS', 'RFevqJKir1wTXr-x'),
	(74, 1, '29156262166571', 'Eleitor4538', 'eleitor2722@exemplo.com', 3, 43, 1, '8a78081fffefb9fb99c9', NULL, NULL, NULL, NULL),
	(75, 1, '99972318727193', 'Eleitor3604', 'eleitor9866@exemplo.com', 3, 43, 1, '9fae3600d18831ddc4bc', NULL, NULL, NULL, NULL),
	(76, 1, '79495687716021', 'Eleitor4161', 'eleitor6002@exemplo.com', 3, 1, 1, '50b22648a168e17b2a58', NULL, NULL, NULL, NULL),
	(77, 1, '90370442184202', 'Eleitor4990', 'eleitor9689@exemplo.com', 2, 1, 1, 'bf43683f080a2fc6b9e9', NULL, NULL, NULL, NULL),
	(78, 1, '793097415214', 'Eleitor228', 'eleitor9575@exemplo.com', 3, 1, 1, 'f7a6b61754665ba285c7', NULL, NULL, NULL, NULL),
	(79, 1, '15168308536288', 'Eleitor3593', 'eleitor7082@exemplo.com', 2, 43, 1, 'a3b9a8e8b0791d46f637', NULL, NULL, NULL, NULL),
	(80, 1, '25702424358687', 'Eleitor1633', 'eleitor558@exemplo.com', 3, 1, 1, '3f2e4cf979f9562e1332', NULL, NULL, NULL, NULL),
	(81, 1, '31683801899010', 'Eleitor0', 'eleitor297@exemplo.com', 1, 1, 1, 'ce74d7c52acd64840759', NULL, NULL, NULL, NULL),
	(82, 1, '15085676128730', 'Eleitor9839', 'eleitor578@exemplo.com', 2, 1, 1, 'baa8fa56506641aa3fc8', NULL, NULL, NULL, NULL),
	(83, 1, '21989471244478', 'Eleitor5642', 'eleitor4868@exemplo.com', 3, 43, 1, 'a52545254417f16413bd', NULL, NULL, NULL, NULL),
	(84, 1, '30214766648574', 'Eleitor5222', 'eleitor1542@exemplo.com', 1, 1, 1, 'eedae0ea9d826be56d16', NULL, NULL, NULL, NULL),
	(85, 1, '23306425761925', 'Eleitor3970', 'eleitor1276@exemplo.com', 2, 1, 1, '8b249cacd41b86b1f49e', NULL, NULL, NULL, NULL),
	(86, 1, '2338384737082', 'Eleitor7839', 'eleitor8073@exemplo.com', 3, 43, 1, 'e31709d9f1ca0d5cdc91', NULL, NULL, NULL, NULL),
	(87, 1, '7851951064997', 'Eleitor8942', 'eleitor3214@exemplo.com', 3, 1, 1, 'cfb7866674848896ee69', NULL, NULL, NULL, NULL),
	(88, 1, '61267889851101', 'Eleitor7170', 'eleitor521@exemplo.com', 1, 43, 1, '3ff1353b34121963fb33', NULL, NULL, NULL, NULL),
	(89, 1, '97638780199071', 'Eleitor244', 'eleitor1498@exemplo.com', 3, 1, 1, 'b4c40d7aa24942886b70', NULL, NULL, NULL, NULL),
	(90, 1, '34094325482428', 'Eleitor985', 'eleitor200@exemplo.com', 3, 1, 1, '5de29bac116f35b01bcb', NULL, NULL, NULL, NULL),
	(91, 1, '9622355229193', 'Eleitor1709', 'eleitor9791@exemplo.com', 2, 1, 1, '002543171cf35c094725', NULL, NULL, NULL, NULL),
	(92, 1, '74198281350694', 'Eleitor3087', 'eleitor229@exemplo.com', 1, 1, 1, 'bb7643f0f2675df77b49', NULL, NULL, NULL, NULL),
	(93, 1, '40058960958926', 'Eleitor7445', 'eleitor9549@exemplo.com', 2, 1, 1, 'c61155a926d07e50caea', NULL, NULL, NULL, NULL),
	(94, 1, '38365650317435', 'Eleitor7207', 'eleitor808@exemplo.com', 1, 1, 1, 'fcd991f764dceeab6411', NULL, NULL, NULL, NULL),
	(95, 1, '65211112092803', 'Eleitor6838', 'eleitor6350@exemplo.com', 1, 1, 1, '8c4db1095d3cb1454372', NULL, NULL, NULL, NULL),
	(96, 1, '83813675460379', 'Eleitor5045', 'eleitor7113@exemplo.com', 1, 43, 1, 'db7e32bff230854e07a7', NULL, NULL, NULL, NULL),
	(97, 1, '15784131194668', 'Eleitor2599', 'eleitor4594@exemplo.com', 2, 43, 1, 'd0df51d082f2719944a7', NULL, NULL, NULL, NULL),
	(98, 1, '85271106577411', 'Eleitor3124', 'eleitor2399@exemplo.com', 1, 1, 1, '9959d3a5771674d12ee7', NULL, NULL, NULL, NULL),
	(99, 1, '7746721787632', 'Eleitor1492', 'eleitor1170@exemplo.com', 1, 43, 1, '8c415da890895e391bae', NULL, NULL, NULL, NULL),
	(100, 1, '36403677299375', 'Eleitor8766', 'eleitor4020@exemplo.com', 2, 1, 1, 'bf5e7303a72f53563e8a', NULL, NULL, NULL, NULL),
	(101, 1, '604047602511', 'Eleitor2134', 'eleitor518@exemplo.com', 2, 1, 1, 'fef75241d0cccfa9c418', NULL, NULL, NULL, NULL),
	(102, 1, '37213908934743', 'Eleitor6207', 'eleitor614@exemplo.com', 2, 43, 1, 'd9be85573895accc23c8', NULL, NULL, NULL, NULL),
	(103, 1, '20649444643665', 'Eleitor5637', 'eleitor5088@exemplo.com', 3, 1, 1, '445d79f9795fe8aed25d', NULL, NULL, NULL, NULL),
	(104, 1, '46343484590867', 'Eleitor1530', 'eleitor394@exemplo.com', 3, 1, 1, '6bf5240ef6f6bbc2cd95', NULL, NULL, NULL, NULL),
	(105, 1, '48587852551087', 'Eleitor967', 'eleitor9509@exemplo.com', 2, 43, 1, 'e712d1f6e41214befdfc', NULL, NULL, NULL, NULL),
	(106, 1, '3819102423339', 'Eleitor212', 'eleitor61@exemplo.com', 3, 1, 1, '6689c954a7d54954ee14', NULL, NULL, NULL, NULL),
	(107, 1, '44360746468357', 'Eleitor870', 'eleitor3845@exemplo.com', 2, 43, 1, 'dc958b8466caba3b0b1f', NULL, NULL, NULL, NULL),
	(108, 1, '46489921496838', 'Eleitor4472', 'eleitor3309@exemplo.com', 1, 1, 1, '999ab5451a8aedebf6ef', NULL, NULL, NULL, NULL),
	(109, 1, '90429599174788', 'Eleitor265', 'eleitor8890@exemplo.com', 2, 43, 1, 'eca9d5b6f30171646706', NULL, NULL, NULL, NULL),
	(110, 1, '6822660120935', 'Eleitor8421', 'eleitor5824@exemplo.com', 2, 43, 1, '227ab7cca863107e342f', NULL, NULL, NULL, NULL),
	(111, 1, '23280682489512', 'Eleitor7772', 'eleitor2006@exemplo.com', 3, 1, 1, '80eac4507d98d2422bf8', NULL, NULL, NULL, NULL),
	(112, 1, '57953265358707', 'Eleitor1967', 'eleitor2226@exemplo.com', 2, 1, 1, '76203eebc88a8d401eeb', NULL, NULL, NULL, NULL),
	(113, 1, '99135450245713', 'Eleitor3115', 'eleitor1865@exemplo.com', 3, 43, 1, '6e6081d733caeb023a42', NULL, NULL, NULL, NULL),
	(114, 1, '4975554691462', 'Eleitor5804', 'eleitor8584@exemplo.com', 2, 43, 1, 'aa164eb2ae8f7ddca5da', NULL, NULL, NULL, NULL),
	(115, 1, '66057260958571', 'Eleitor9468', 'eleitor9770@exemplo.com', 1, 43, 1, '3836ecec9a11b739baf6', NULL, NULL, NULL, NULL),
	(116, 1, '78206357491283', 'Eleitor2179', 'eleitor3513@exemplo.com', 1, 43, 1, '10f181f67aeea55a32a2', NULL, NULL, NULL, NULL),
	(117, 1, '57437185989958', 'Eleitor7747', 'eleitor1752@exemplo.com', 2, 43, 1, '91abc91018308b86f1dd', NULL, NULL, NULL, NULL),
	(118, 1, '85137126072649', 'Eleitor783', 'eleitor2124@exemplo.com', 3, 43, 1, 'bfcbe61aede6e59a150d', NULL, NULL, NULL, NULL),
	(119, 1, '54177453768820', 'Eleitor8157', 'eleitor138@exemplo.com', 2, 43, 1, 'ba7b3dcf7777617c839d', NULL, NULL, NULL, NULL),
	(120, 1, '20558370757851', 'Eleitor2758', 'eleitor6435@exemplo.com', 2, 43, 1, '631628b92b92f57f9c16', NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `eleitores` ENABLE KEYS */;

-- Copiando estrutura para procedure siscel.insert_votos
DELIMITER //
CREATE PROCEDURE `insert_votos`()
BEGIN
    SET @i = 0;

    WHILE (@i < 48) DO
        SET @codVoto = FLOOR(RAND() * POWER(2, 63)); -- Gerar um número aleatório do tipo BIGINT
        SET @codChapa = NULLIF(FLOOR(RAND() * 3), 2); -- Gerar um número aleatório entre nulo, 1 e 2
        SET @votoNulo = NULLIF(FLOOR(RAND() * 2), 0); -- Gerar um número aleatório entre nulo e 1
        SET @votoBranco = NULLIF(FLOOR(RAND() * 2), 0); -- Gerar um número aleatório entre nulo e 1
        SET @codUrna = FLOOR(RAND() * 2) * 42 + 1; -- Gerar um número aleatório entre 1 e 43
        SET @codVinculo = FLOOR(RAND() * 3) + 1; -- Gerar um número aleatório entre 1 e 3

        IF (@codChapa IS NOT NULL) THEN
            SET @votoNulo = NULL;
            SET @votoBranco = NULL;
        ELSEIF (@votoNulo = 1) THEN
            SET @votoBranco = NULL;
            SET @codChapa = NULL;
        ELSEIF (@votoBranco = 1) THEN
            SET @votoNulo = NULL;
            SET @codChapa = NULL;
        END IF;

        IF (@codChapa IS NOT NULL AND (@votoNulo IS NOT NULL OR @votoBranco IS NOT NULL)) THEN
            SET @votoNulo = NULL;
            SET @votoBranco = NULL;
        END IF;

        INSERT INTO votos (codVoto, codConsultaEleitoral, votoNulo, votoBranco, codChapa, codUrna, codVinculo)
        SELECT @codVoto, 1, @votoNulo, @votoBranco, @codChapa, @codUrna, @codVinculo
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1 FROM votos WHERE codVoto = @codVoto
        );

        SET @i = @i + 1;
    END WHILE;
END//
DELIMITER ;

-- Copiando estrutura para tabela siscel.membros_comissao
CREATE TABLE IF NOT EXISTS `membros_comissao` (
  `codMembroComissao` int(11) NOT NULL AUTO_INCREMENT,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `codUsuario` int(11) DEFAULT NULL,
  `codCargoComissao` int(11) DEFAULT NULL,
  PRIMARY KEY (`codMembroComissao`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.membros_comissao: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `membros_comissao` DISABLE KEYS */;
INSERT INTO `membros_comissao` (`codMembroComissao`, `codConsultaEleitoral`, `codUsuario`, `codCargoComissao`) VALUES
	(1, 1, 2, 1),
	(2, 1, 1, 2),
	(5, 4, 2, 1),
	(7, 6, 1, 1),
	(8, 1, NULL, NULL),
	(9, 1, NULL, NULL),
	(10, 1, NULL, NULL),
	(12, 1, 3, 2);
/*!40000 ALTER TABLE `membros_comissao` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.proporcoes
CREATE TABLE IF NOT EXISTS `proporcoes` (
  `codProporcao` int(11) NOT NULL AUTO_INCREMENT,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `codVinculo` int(11) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL,
  PRIMARY KEY (`codProporcao`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.proporcoes: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `proporcoes` DISABLE KEYS */;
INSERT INTO `proporcoes` (`codProporcao`, `codConsultaEleitoral`, `codVinculo`, `peso`) VALUES
	(1, 1, 1, 50),
	(3, 1, 2, 30),
	(6, 1, 3, 20);
/*!40000 ALTER TABLE `proporcoes` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.urnas
CREATE TABLE IF NOT EXISTS `urnas` (
  `codUrna` int(11) NOT NULL AUTO_INCREMENT,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codUrna`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.urnas: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `urnas` DISABLE KEYS */;
INSERT INTO `urnas` (`codUrna`, `codConsultaEleitoral`, `descricao`) VALUES
	(1, 1, 'Geral'),
	(43, 1, 'VCX');
/*!40000 ALTER TABLE `urnas` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `codUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(14) DEFAULT NULL,
  `nomeDeUsuario` varchar(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `eAdministrador` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`codUsuario`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.usuarios: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`codUsuario`, `cpf`, `nomeDeUsuario`, `nome`, `senha`, `email`, `eAdministrador`) VALUES
	(1, '094.196.667-43', '09419666743', 'Cesar Daer', '$argon2id$v=19$m=65536,t=4,p=1$Ny9SWkIxTU5uUkRBZnZFVw$pdQ//8o+kXD17HK0JDIsj8bfcWSfDSuxc55zaYK6t8w', 'cesar_daer@id.uff.br', 1),
	(2, '094.503.957-30', '09450395730', 'Cesar Daer', '$argon2id$v=19$m=65536,t=4,p=1$Ny9SWkIxTU5uUkRBZnZFVw$pdQ//8o+kXD17HK0JDIsj8bfcWSfDSuxc55zaYK6t8w', 'cesar.daer@gmail.com', 0),
	(3, '11111111111', '11111111111', 'Fulano de Tal', '$argon2id$v=19$m=65536,t=4,p=1$YUxlWkZXTG5LZFdEQ1ZLRA$GM2rXMHV6IUQ6FCupXI7PEj4MvvGvB8uur3Y2bxJFIw', 'fulano@fulano.com', 0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.vinculos
CREATE TABLE IF NOT EXISTS `vinculos` (
  `codVinculo` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codVinculo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.vinculos: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `vinculos` DISABLE KEYS */;
INSERT INTO `vinculos` (`codVinculo`, `descricao`) VALUES
	(1, 'Docente'),
	(2, 'Servidor'),
	(3, 'Aluno');
/*!40000 ALTER TABLE `vinculos` ENABLE KEYS */;

-- Copiando estrutura para tabela siscel.votos
CREATE TABLE IF NOT EXISTS `votos` (
  `codVoto` bigint(20) NOT NULL DEFAULT 0,
  `codConsultaEleitoral` int(11) DEFAULT NULL,
  `votoNulo` tinyint(4) DEFAULT NULL,
  `votoBranco` tinyint(4) DEFAULT NULL,
  `codChapa` int(11) DEFAULT NULL,
  `codUrna` int(11) DEFAULT NULL,
  `codVinculo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codVoto`),
  UNIQUE KEY `codVoto_UNIQUE` (`codVoto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela siscel.votos: ~50 rows (aproximadamente)
/*!40000 ALTER TABLE `votos` DISABLE KEYS */;
INSERT INTO `votos` (`codVoto`, `codConsultaEleitoral`, `votoNulo`, `votoBranco`, `codChapa`, `codUrna`, `codVinculo`) VALUES
	(4008500882, 1, NULL, NULL, 1, 1, 1),
	(7763220301, 1, NULL, NULL, 2, 1, 2),
	(816170144937965952, 1, NULL, NULL, 1, 1, 2),
	(843433772724644352, 1, NULL, NULL, 2, 43, 2),
	(952292072585224832, 1, NULL, NULL, 1, 1, 3),
	(970768867291001984, 1, NULL, NULL, 2, 1, 2),
	(1260803525114140928, 1, NULL, NULL, 1, 43, 2),
	(1318760329256416768, 1, NULL, NULL, 2, 1, 1),
	(1360308571606296064, 1, NULL, NULL, 2, 43, 3),
	(1510435021819253504, 1, NULL, NULL, 2, 43, 3),
	(1654753731349804800, 1, NULL, NULL, 1, 43, 2),
	(1992349035987008512, 1, NULL, NULL, 1, 43, 1),
	(2026761619664667648, 1, NULL, NULL, 1, 1, 2),
	(2402613048446496768, 1, NULL, NULL, 1, 1, 2),
	(2435874366139390976, 1, NULL, 1, NULL, 1, 2),
	(2876193984238660608, 1, NULL, NULL, 1, 43, 2),
	(3018322775842288128, 1, NULL, NULL, 1, 43, 1),
	(3027445904862779392, 1, NULL, NULL, 2, 43, 2),
	(3253914909206964736, 1, NULL, NULL, 1, 1, 1),
	(3605879379133636608, 1, NULL, NULL, 2, 43, 2),
	(4046065483879418880, 1, NULL, NULL, 2, 1, 1),
	(4060683173156734976, 1, NULL, NULL, 2, 43, 2),
	(4151735620923274752, 1, NULL, NULL, 1, 43, 3),
	(4157813326070289408, 1, NULL, 1, NULL, 1, 1),
	(4187627829425718784, 1, NULL, NULL, 1, 1, 1),
	(4347481117852789760, 1, NULL, NULL, 1, 43, 1),
	(4706780550114271232, 1, NULL, NULL, 1, 1, 1),
	(4717540190525081600, 1, NULL, NULL, 1, 1, 3),
	(4799392071010711552, 1, NULL, NULL, 1, 43, 3),
	(4956380014953260032, 1, NULL, NULL, 1, 43, 1),
	(5034201060037905408, 1, NULL, NULL, 1, 43, 2),
	(5054338486670358528, 1, NULL, NULL, 2, 43, 3),
	(5089224435874020352, 1, NULL, 1, NULL, 1, 2),
	(5165746064371854336, 1, NULL, NULL, 2, 43, 2),
	(5276000726821141504, 1, NULL, 1, NULL, 43, 3),
	(5526090771415070720, 1, NULL, 1, NULL, 43, 2),
	(5684941305236676608, 1, NULL, NULL, 2, 43, 2),
	(5742892362712705024, 1, NULL, NULL, 1, 43, 3),
	(6298311675613307904, 1, NULL, NULL, 2, 43, 3),
	(6350594123577647104, 1, NULL, NULL, 1, 1, 1),
	(6410584577556603904, 1, NULL, 1, NULL, 43, 1),
	(6619324781520110592, 1, NULL, NULL, 2, 1, 3),
	(6787475689072743424, 1, NULL, NULL, 2, 1, 1),
	(7248689670251484160, 1, NULL, NULL, 1, 43, 3),
	(7615200575947965440, 1, NULL, 1, NULL, 43, 2),
	(7631494024026786816, 1, NULL, 1, NULL, 1, 1),
	(7955891117489390592, 1, NULL, NULL, 2, 1, 3),
	(8227480007450526720, 1, NULL, NULL, 1, 43, 1),
	(8853475700493480960, 1, 1, NULL, NULL, 1, 3);
/*!40000 ALTER TABLE `votos` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
