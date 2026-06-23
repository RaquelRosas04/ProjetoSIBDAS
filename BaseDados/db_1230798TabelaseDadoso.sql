/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `edificios` (
  `id` int NOT NULL,
  `nome` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `morada` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `localidade` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `edificios` (`id`, `nome`, `morada`, `localidade`, `codPostal`) VALUES
	(1, 'Trofa Saude Penafiel', '', 'Penafiel', ''),
	(2, 'Trofa Saude Porto', '', 'Porto', ''),
	(3, 'Hospital Central', 'Rua Principal 100', 'Porto', '4000-001'),
	(4, 'Unidade Norte', 'Av. da Saude 50', 'Braga', '4700-010'),
	(5, 'Clinica Sul', 'Rua do Sol 22', 'Lisboa', '1200-020'),
	(6, 'Centro Cardiologico', 'Rua Cardio 8', 'Porto', '4100-030'),
	(7, 'Bloco Materno', 'Rua Nova 15', 'Gaia', '4400-040'),
	(8, 'Centro Diagnostico', 'Av. Imagem 9', 'Coimbra', '3000-060');

CREATE TABLE IF NOT EXISTS `equipamentocadastro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idequipamento` int DEFAULT NULL,
  `idlocalizacao` int DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentocadastro_equipamentos` (`idequipamento`),
  KEY `FK_equipamentocadastro_localizacao` (`idlocalizacao`),
  CONSTRAINT `FK_equipamentocadastro_equipamentos` FOREIGN KEY (`idequipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentocadastro_localizacao` FOREIGN KEY (`idlocalizacao`) REFERENCES `localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

REPLACE INTO `equipamentocadastro` (`id`, `idequipamento`, `idlocalizacao`, `estado`, `data`) VALUES
	(25, 1, 1, 'Ativo', '2024-02-10'),
	(26, 2, 3, 'Ativo', '2023-06-15'),
	(27, 3, 2, 'Manutencao', '2024-05-01'),
	(28, 4, 2, 'Ativo', '2021-03-05'),
	(29, 5, 6, 'Inativo', '2025-01-10'),
	(30, 6, 4, 'Ativo', '2023-11-01'),
	(31, 2, 1, 'Manutenção', '2026-05-10'),
	(32, 3, 4, 'Ativo', '2020-04-20'),
	(33, 3, 4, 'Abatido', '2026-06-22');

CREATE TABLE IF NOT EXISTS `equipamentocomponentes` (
  `idEquiPai` int NOT NULL,
  `idEquiComp` int NOT NULL,
  KEY `FK_equipamentocomponentes_equipamentos` (`idEquiPai`),
  KEY `FK_equipamentocomponentes_equipamentos_2` (`idEquiComp`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos` FOREIGN KEY (`idEquiPai`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos_2` FOREIGN KEY (`idEquiComp`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `equipamentocomponentes` (`idEquiPai`, `idEquiComp`) VALUES
	(4, 5),
	(3, 6),
	(2, 6),
	(2, 5),
	(1, 5),
	(1, 6),
	(1, 69);

CREATE TABLE IF NOT EXISTS `equipamentocontratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEquipamentoUni` int DEFAULT NULL,
  `datainicio` date DEFAULT NULL,
  `datafim` date DEFAULT NULL,
  `obs` text COLLATE utf8mb4_bin,
  `caminho` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentocontratos_equipamentounidade` (`idEquipamentoUni`),
  CONSTRAINT `FK_equipamentocontratos_equipamentounidade` FOREIGN KEY (`idEquipamentoUni`) REFERENCES `equipamentounidade` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

REPLACE INTO `equipamentocontratos` (`id`, `idEquipamentoUni`, `datainicio`, `datafim`, `obs`, `caminho`, `ficheiro`) VALUES
	(1, 2, '2025-06-12', '2026-06-12', 'contrato manutenção', '../uploads/contratos/contrato_6a399114794c68.64657497.pdf', 'MF e API.pdf');

CREATE TABLE IF NOT EXISTS `equipamentofornecedores` (
  `idEquipamentoUni` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `TipoFornecedor` enum('','Fabricante','Distribuidor','AT','Consumiveis') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Fabricante/Compra/AT',
  KEY `FK_equipamentofornecedores_fornecedores` (`idFornecedor`),
  KEY `FK_equipamentofornecedores_equipamentos` (`idEquipamentoUni`) USING BTREE,
  CONSTRAINT `FK_equipamentofornecedores_equipamentounidade` FOREIGN KEY (`idEquipamentoUni`) REFERENCES `equipamentounidade` (`id`),
  CONSTRAINT `FK_equipamentofornecedores_fornecedores` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `equipamentofornecedores` (`idEquipamentoUni`, `idFornecedor`, `TipoFornecedor`) VALUES
	(1, 1, 'Distribuidor'),
	(1, 2, 'Distribuidor'),
	(2, 2, 'Consumiveis'),
	(3, 3, 'Distribuidor'),
	(4, 4, 'AT'),
	(6, 5, 'Distribuidor'),
	(33, 3, 'Distribuidor'),
	(33, 4, 'AT');

CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `idTipo` int NOT NULL DEFAULT '0',
  `idMarca` int NOT NULL DEFAULT '0',
  `modelo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `anosGarantia` int NOT NULL COMMENT 'Garantia em Anos',
  `criticidade` enum('','Baixa','Média','Alta','Suporte de vida') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `componente` bit(1) NOT NULL DEFAULT (0),
  `idfabricante` int DEFAULT NULL,
  `manualSer` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `manualTec` varchar(250) DEFAULT NULL,
  `manualCon` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentos_marca` (`idMarca`) USING BTREE,
  KEY `FK_equipamentos_tipoequipamento` (`idTipo`),
  KEY `FK_equipamentos_fabricante` (`idfabricante`),
  CONSTRAINT `FK_equipamentos_fabricante` FOREIGN KEY (`idfabricante`) REFERENCES `fabricante` (`id`),
  CONSTRAINT `FK_equipamentos_marca` FOREIGN KEY (`idMarca`) REFERENCES `marca` (`id`),
  CONSTRAINT `FK_equipamentos_tipoequipamento` FOREIGN KEY (`idTipo`) REFERENCES `tipoequipamento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `equipamentos` (`id`, `descricao`, `idTipo`, `idMarca`, `modelo`, `anosGarantia`, `criticidade`, `componente`, `idfabricante`, `manualSer`, `manualTec`, `manualCon`) VALUES
	(1, 'Monitor Multiparamétrico', 1, 1, 'IntelliVue MZ8000', 3, 'Alta', b'0', 1, NULL, NULL, NULL),
	(2, 'Ventilador Pulmonar', 4, 2, 'Evita V800', 5, 'Suporte de vida', b'0', 2, '../uploads/manuais/manualSer_6a3884982f3c6.pdf', '../uploads/manuais/manualTec_6a388498311a2.pdf', '../uploads/manuais/manualCon_6a38849831b62.pdf'),
	(3, 'Bomba de Infusão', 2, 3, 'Perfusor Space', 2, 'Média', b'0', 3, NULL, NULL, NULL),
	(4, 'Desfibrilhador', 4, 4, 'R Series', 4, 'Suporte de vida', b'0', 4, NULL, NULL, NULL),
	(5, 'Cabo ECG', 6, 1, 'ECG-5L', 1, 'Baixa', b'1', 1, NULL, NULL, NULL),
	(6, 'Sensor SpO2', 6, 5, 'SPO2-MR', 1, 'Baixa', b'1', 5, NULL, NULL, NULL),
	(69, 'Manguito NIBP', 6, 5, 'v1', 1, 'Baixa', b'1', 6, NULL, NULL, NULL),
	(71, 'Balança', 1, 4, '796', 2, 'Baixa', b'0', 4, NULL, NULL, NULL),
	(82, 'Écografo', 4, 7, 'Vivid T8', 5, 'Alta', b'0', 6, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `equipamentounidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `Codigo` varchar(20) NOT NULL DEFAULT '',
  `idLocalizacao` int NOT NULL,
  `numSerie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `estado` enum('Ativo','Inativo','Manutenção','Calibração','Quarentena','Abatido') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `anoFabrico` int NOT NULL DEFAULT '0',
  `dataAquisicao` date NOT NULL,
  `dataFimGarantia` date NOT NULL,
  `tipoEntrada` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'Compra, Doação, Empréstimo',
  `obs` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentounidade_localizacao` (`idLocalizacao`),
  KEY `FK_equipamentounidade_equipamentos` (`idEquipamento`) USING BTREE,
  KEY `Codigo` (`Codigo`),
  CONSTRAINT `FK_equipamentounidade_equipamentos` FOREIGN KEY (`idEquipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentounidade_localizacao` FOREIGN KEY (`idLocalizacao`) REFERENCES `localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `equipamentounidade` (`id`, `idEquipamento`, `Codigo`, `idLocalizacao`, `numSerie`, `estado`, `anoFabrico`, `dataAquisicao`, `dataFimGarantia`, `tipoEntrada`, `obs`) VALUES
	(1, 1, 'MON0001', 2, 'MX450-2024-001', 'Ativo', 2024, '2024-02-10', '2027-02-10', 'Compra', 'Equipamento principal da cardiologia'),
	(2, 2, 'VEN0001', 3, 'EV600-2023-014', 'Ativo', 2023, '2023-06-15', '2028-06-15', 'Compra', 'Afeto a UCI'),
	(3, 3, 'BOM0001', 2, 'BB-SPACE-778', 'Manutenção', 2022, '2022-09-20', '2024-09-20', 'Compra', 'A aguardar revisão'),
	(4, 4, 'DES0001', 2, 'ZOLL-R-9921', 'Ativo', 2021, '2021-03-05', '2025-03-05', 'Compra', 'Urgência'),
	(5, 1, 'MON0002', 6, 'MX450-2024-002', 'Inativo', 2024, '2024-02-12', '2027-02-12', 'Compra', 'Reserva técnica'),
	(6, 3, 'BOM0002', 4, 'BB-SPACE-779', 'Ativo', 2023, '2023-11-01', '2025-11-01', 'Doacao', 'Pediatria'),
	(33, 2, 'VEN0002', 1, 'EV600-2024-021', 'Manutenção', 2024, '2024-04-18', '2029-04-18', 'Compra', 'Ventilador de apoio à cardiologia'),
	(34, 4, 'DES0002', 3, 'ZOLL-R-2022-145', 'Ativo', 2022, '2022-07-12', '2026-07-12', 'Compra', 'Desfibrilhador colocado em gastroenterologia'),
	(35, 1, 'MON0003', 4, 'MX450-2023-087', 'Manutenção', 2023, '2023-01-30', '2026-01-30', 'Compra', 'Monitor em manutenção preventiva'),
	(36, 3, 'BOM0003', 6, 'BB-SPACE-2024-033', 'Ativo', 2024, '2024-05-22', '2026-05-22', 'Compra', 'Bomba de infusão para apoio ao internamento'),
	(37, 4, 'DES0003', 2, 'ZOLL-R-2021-309', 'Inativo', 2021, '2021-11-09', '2025-11-09', 'Doacao', 'Equipamento temporariamente fora de serviço'),
	(38, 1, 'MON0004', 1, 'MX450-2025-004', 'Ativo', 2025, '2025-02-14', '2028-02-14', 'Compra', 'Monitor multiparamétrico principal da sala'),
	(39, 3, 'SV00001', 4, '12863456m', 'Abatido', 2025, '2020-04-20', '2022-04-20', 'Compra', 'Abatido em 22/06/2026. Razão: Equipamento não funcional.');

CREATE TABLE IF NOT EXISTS `fabricante` (
  `nome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `webSite` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`,`nome`),
  KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

REPLACE INTO `fabricante` (`nome`, `id`, `webSite`) VALUES
	('Philips Healthcare', 1, 'www.philips.com'),
	('Dräger', 2, 'www.drager.com'),
	('B.Braun', 3, 'www.bbraun.com'),
	('Zoll Medical', 4, 'www.zollmedical.com'),
	('Mindray', 5, 'www.mindray.com'),
	('GE Healthcare', 6, 'www.gehealtcare.com');

CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` char(100) NOT NULL DEFAULT '',
  `morada` char(100) NOT NULL DEFAULT '',
  `localidade` char(100) NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `telefone` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `nif` char(20) NOT NULL DEFAULT '',
  `www` char(100) NOT NULL DEFAULT '',
  `email` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `fornecedores` (`id`, `nome`, `morada`, `localidade`, `codPostal`, `telefone`, `nif`, `www`, `email`) VALUES
	(1, 'MedicalCare Portugal', 'Rua da Saude 10, Porto', '', '4000-100', '229111222', '501234567', 'www.medicalcare.pt', 'geral@medicalcare.pt'),
	(2, 'Hospitecnica', 'Av. Central 45, Lisboa', '', '1000-200', '213222333', '502345678', 'www.hospitecnica.pt', 'comercial@hospi.pt'),
	(3, 'BioMed Norte', 'Rua Industrial 8, Braga', '', '4700-300', '252333444', '503456789', 'www.biomednorte.pt', 'info@biomednorte.pt'),
	(4, 'TecnoHospital', 'Rua do Hospital 21, Leiria', '', '2400-150', '244444555', '504567890', 'www.tecnohosp.pt', 'apoio@tecnohosp.pt'),
	(5, 'EquipSaude', 'Rua Nova 17, Gaia', '', '4400-120', '226555666', '505678901', 'www.equipsaude.pt', 'geral@equipsaude.pt'),
	(6, 'Clinimed', 'Av. Europa 12, Coimbra', '', '3000-050', '239666777', '506789012', 'www.clinimed.pt', 'contacto@clinimed.pt'),
	(21, 'Olympus', 'Tv. Joana Freitas', 'Braga', '2234-098', '967236153', '132564723', '', 'olympus@gmail.pt'),
	(22, 'Seca', 'Rua Padre Joel,34', 'Leiria', '1243-098', '223454323', '122312234', '', 'seca@gmail.com');

CREATE TABLE IF NOT EXISTS `localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEdificio` int NOT NULL,
  `idServico` int NOT NULL,
  `andar` int NOT NULL,
  `sala` char(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_localizacao_edificios` (`idEdificio`),
  KEY `FK_localizacao_servicos` (`idServico`),
  CONSTRAINT `FK_localizacao_edificios` FOREIGN KEY (`idEdificio`) REFERENCES `edificios` (`id`),
  CONSTRAINT `FK_localizacao_servicos` FOREIGN KEY (`idServico`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `localizacao` (`id`, `idEdificio`, `idServico`, `andar`, `sala`) VALUES
	(1, 2, 3, 5, '321'),
	(2, 2, 1, 5, 'E'),
	(3, 1, 4, 3, '304'),
	(4, 1, 3, 2, '222'),
	(6, 2, 1, 6, '101-112');

CREATE TABLE IF NOT EXISTS `marca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT=' ';

REPLACE INTO `marca` (`id`, `descricao`) VALUES
	(1, 'Philips'),
	(2, 'Drager'),
	(3, 'Braun'),
	(4, 'Zoll'),
	(5, 'Pentax'),
	(6, 'Mindray'),
	(7, 'GE');

CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `servicos` (`id`, `descricao`) VALUES
	(1, 'Cardiologia'),
	(2, 'Pneumologia'),
	(3, 'Pediatria'),
	(4, 'Gastroentrologia'),
	(5, 'Urgência'),
	(6, 'Imagiologia'),
	(7, 'Bloco Operatório');

CREATE TABLE IF NOT EXISTS `tipoequipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `identcod` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

REPLACE INTO `tipoequipamento` (`id`, `descricao`, `identcod`) VALUES
	(1, 'Monitorizacão', 'MO'),
	(2, 'Suporte de Vida', 'SV'),
	(3, 'Terapia', 'TP'),
	(4, 'Diagnóstico', 'DG'),
	(5, 'Esterilização', 'ES'),
	(6, 'Componentes', 'CP');

CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `perfil` enum('Gestor','Técnico') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Nome` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Int` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Int`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

REPLACE INTO `users` (`email`, `password`, `perfil`, `Nome`, `Int`) VALUES
	('gestor@medint.pt', '$2y$10$fPuLOVdZObB3AcELzZdUAuvLXWjuRf7WNcbaTRPhiJb.zwey8qBTe', 'Gestor', 'Gestor', 1),
	('tecnico@medint.pt', '$2y$10$Dlqa/PRHwLgcpUeCKR5Zf.9Oti/kQ/ItlSvjPv3tsxmblinyerZYy', 'Técnico', 'Técnico', 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
