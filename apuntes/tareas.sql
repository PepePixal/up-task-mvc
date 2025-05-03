/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `tareas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `proyectoId` int DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `proyectoId` (`proyectoId`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`proyectoId`) REFERENCES `proyectos` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tareas` (`id`, `nombre`, `estado`, `proyectoId`) VALUES
(1, ' Primera tarea en el Primer Proyecto', 0, 1);
INSERT INTO `tareas` (`id`, `nombre`, `estado`, `proyectoId`) VALUES
(2, ' Segunda tarea del Primer Proyecto', 0, 1);
INSERT INTO `tareas` (`id`, `nombre`, `estado`, `proyectoId`) VALUES
(3, ' Tercera tarea del Primer Proyecto', 0, 1);
INSERT INTO `tareas` (`id`, `nombre`, `estado`, `proyectoId`) VALUES
(4, ' Cuarta tarea del Primer Proyecto', 0, 1),
(5, ' Quinta tarea del Primer Proyecto', 0, 1),
(6, ' Primera tarea Confirmada Primer Proyecto', 0, 4),
(7, ' Segunda tarea Confirmada Primer Proyecto', 0, 4),
(8, ' Primera tarea en el Segundo Proyecto pepe', 0, 2),
(9, ' Segunda tarea en el Segundo Proyecto pepe', 0, 2),
(10, ' Segunda tarea en el Segundo Proyecto pepe', 0, 2),
(11, ' Tercera tarea en le Segundo Proyecto pepe', 0, 2),
(12, ' Primera Tarea del Tercer Proyecto pepe', 0, 3),
(13, ' Segunda tarea del Tercer Proyecto pepe', 0, 3),
(14, ' Tercera tarea del Tercer Proyecto pepe', 0, 3),
(18, ' Sexta tarea del Primer Proyecto', 0, 1),
(19, ' Séptima tarea del Primer Proyecto', 0, 1),
(20, ' Cuarta tarea del Tercer Proyecto pepe', 0, 3);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;