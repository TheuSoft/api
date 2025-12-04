-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura do banco de dados para feedback_produtos
DROP DATABASE IF EXISTS `feedback_produtos`;
CREATE DATABASE IF NOT EXISTS `feedback_produtos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `feedback_produtos`;

-- Copiando estrutura para tabela produtos
DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` TEXT NOT NULL,
    `preco` DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando estrutura para tabela usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `senha` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando estrutura para tabela feedback
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT,
    `produto_id` INT,
    `nota` INT NOT NULL CHECK (nota >= 1 AND nota <= 5),
    `comentario` TEXT,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`produto_id`) REFERENCES `produtos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ======================================
-- DADOS DE EXEMPLO
-- ======================================

-- Inserindo produtos de exemplo
INSERT INTO `produtos` (`nome`, `descricao`, `preco`) VALUES
('Notebook Dell Inspiron', 'Notebook Dell Inspiron 15, Intel i7, 16GB RAM, 512GB SSD', 3500.00),
('Mouse Gamer Logitech', 'Mouse Gamer Logitech G502 HERO, RGB, 16000 DPI', 250.00),
('Teclado Mecânico Razer', 'Teclado Mecânico Razer BlackWidow V3, Switch Green, RGB', 450.00),
('Monitor LG UltraWide', 'Monitor LG UltraWide 29" IPS Full HD, 75Hz', 1200.00),
('Headset HyperX Cloud', 'Headset Gamer HyperX Cloud II, 7.1 Surround, USB', 350.00);

-- Inserindo usuários de exemplo
-- Senha de todos os usuários: 123456
INSERT INTO `usuarios` (`nome`, `email`, `senha`) VALUES
('João Silva', 'joao.silva@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maria Santos', 'maria.santos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Pedro Oliveira', 'pedro.oliveira@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Ana Costa', 'ana.costa@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Carlos Souza', 'carlos.souza@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Inserindo feedbacks de exemplo
INSERT INTO `feedback` (`usuario_id`, `produto_id`, `nota`, `comentario`) VALUES
(1, 1, 5, 'Excelente notebook! Muito rápido e eficiente para trabalho e estudos.'),
(2, 1, 4, 'Bom custo-benefício, mas o acabamento poderia ser melhor.'),
(1, 2, 5, 'Mouse muito preciso e confortável. Recomendo!'),
(3, 2, 4, 'Bom mouse, mas um pouco pesado para o meu gosto.'),
(2, 3, 5, 'Melhor teclado que já tive! Os switches são perfeitos.'),
(4, 4, 5, 'Monitor excelente para produtividade. A tela ultrawide é incrível!'),
(5, 4, 3, 'Bom monitor, mas o suporte deixa a desejar.'),
(3, 5, 4, 'Ótimo headset, som de qualidade. O microfone poderia ser melhor.'),
(4, 5, 5, 'Confortável para longas sessões de uso. Altamente recomendado!'),
(5, 1, 5, 'Comprei para minha esposa e ela adorou. Desempenho top!');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;