-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para geca
CREATE DATABASE IF NOT EXISTS `geca` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `geca`;

-- Volcando estructura para tabla geca.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.cache: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.cache_locks: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla geca.empresa: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.job_batches: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.migrations: ~5 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_06_11_195557_create_permission_tables', 1),
	(5, '2025_06_11_221208_create_pulse_tables', 1);

-- Volcando estructura para tabla geca.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.model_has_roles: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.peso
CREATE TABLE IF NOT EXISTS `peso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `max` float DEFAULT NULL,
  `min` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla geca.peso: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.pulse_aggregates
CREATE TABLE IF NOT EXISTS `pulse_aggregates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bucket` int(10) unsigned NOT NULL,
  `period` mediumint(8) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` mediumtext NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `aggregate` varchar(255) NOT NULL,
  `value` decimal(20,2) NOT NULL,
  `count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_aggregates_bucket_period_type_aggregate_key_hash_unique` (`bucket`,`period`,`type`,`aggregate`,`key_hash`),
  KEY `pulse_aggregates_period_bucket_index` (`period`,`bucket`),
  KEY `pulse_aggregates_type_index` (`type`),
  KEY `pulse_aggregates_period_type_aggregate_bucket_index` (`period`,`type`,`aggregate`,`bucket`)
) ENGINE=InnoDB AUTO_INCREMENT=953 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.pulse_aggregates: ~261 rows (aproximadamente)
INSERT INTO `pulse_aggregates` (`id`, `bucket`, `period`, `type`, `key`, `aggregate`, `value`, `count`) VALUES
	(1, 1749739620, 60, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'count', 1.00, NULL),
	(2, 1749739320, 360, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'count', 1.00, NULL),
	(3, 1749738240, 1440, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'count', 1.00, NULL),
	(4, 1749736800, 10080, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'count', 1.00, NULL),
	(5, 1749739620, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(6, 1749739320, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(7, 1749738240, 1440, 'slow_user_request', '1', 'count', 1.00, NULL),
	(8, 1749736800, 10080, 'slow_user_request', '1', 'count', 14.00, NULL),
	(9, 1749739620, 60, 'user_request', '1', 'count', 2.00, NULL),
	(10, 1749739320, 360, 'user_request', '1', 'count', 2.00, NULL),
	(11, 1749738240, 1440, 'user_request', '1', 'count', 2.00, NULL),
	(12, 1749736800, 10080, 'user_request', '1', 'count', 108.00, NULL),
	(13, 1749739620, 60, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'max', 1036.00, NULL),
	(14, 1749739320, 360, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'max', 1036.00, NULL),
	(15, 1749738240, 1440, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'max', 1036.00, NULL),
	(16, 1749736800, 10080, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 'max', 1036.00, NULL),
	(17, 1749739620, 60, 'slow_request', '["GET","\\/dashboard","Closure"]', 'count', 1.00, NULL),
	(18, 1749739320, 360, 'slow_request', '["GET","\\/dashboard","Closure"]', 'count', 1.00, NULL),
	(19, 1749738240, 1440, 'slow_request', '["GET","\\/dashboard","Closure"]', 'count', 1.00, NULL),
	(20, 1749736800, 10080, 'slow_request', '["GET","\\/dashboard","Closure"]', 'count', 1.00, NULL),
	(21, 1749739620, 60, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'count', 2.00, NULL),
	(22, 1749739320, 360, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'count', 2.00, NULL),
	(23, 1749738240, 1440, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'count', 2.00, NULL),
	(24, 1749736800, 10080, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'count', 2.00, NULL),
	(25, 1749739620, 60, 'slow_request', '["GET","\\/dashboard","Closure"]', 'max', 7490.00, NULL),
	(26, 1749739320, 360, 'slow_request', '["GET","\\/dashboard","Closure"]', 'max', 7490.00, NULL),
	(27, 1749738240, 1440, 'slow_request', '["GET","\\/dashboard","Closure"]', 'max', 7490.00, NULL),
	(28, 1749736800, 10080, 'slow_request', '["GET","\\/dashboard","Closure"]', 'max', 7490.00, NULL),
	(29, 1749739620, 60, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'max', 1749739632.00, NULL),
	(30, 1749739320, 360, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'max', 1749739632.00, NULL),
	(31, 1749738240, 1440, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'max', 1749739632.00, NULL),
	(32, 1749736800, 10080, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 'max', 1749739632.00, NULL),
	(37, 1749739680, 60, 'user_request', '1', 'count', 1.00, NULL),
	(38, 1749739680, 360, 'user_request', '1', 'count', 1.00, NULL),
	(39, 1749739680, 1440, 'user_request', '1', 'count', 1.00, NULL),
	(41, 1749741300, 60, 'user_request', '1', 'count', 1.00, NULL),
	(42, 1749741120, 360, 'user_request', '1', 'count', 5.00, NULL),
	(43, 1749741120, 1440, 'user_request', '1', 'count', 12.00, NULL),
	(45, 1749741360, 60, 'user_request', '1', 'count', 2.00, NULL),
	(53, 1749741420, 60, 'user_request', '1', 'count', 2.00, NULL),
	(61, 1749741480, 60, 'user_request', '1', 'count', 2.00, NULL),
	(62, 1749741480, 360, 'user_request', '1', 'count', 6.00, NULL),
	(65, 1749741480, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'count', 1.00, NULL),
	(66, 1749741480, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'count', 2.00, NULL),
	(67, 1749741120, 1440, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'count', 2.00, NULL),
	(68, 1749736800, 10080, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'count', 2.00, NULL),
	(69, 1749741480, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(70, 1749741480, 360, 'slow_user_request', '1', 'count', 2.00, NULL),
	(71, 1749741120, 1440, 'slow_user_request', '1', 'count', 2.00, NULL),
	(77, 1749741480, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'max', 1052.00, NULL),
	(78, 1749741480, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'max', 1115.00, NULL),
	(79, 1749741120, 1440, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'max', 1115.00, NULL),
	(80, 1749736800, 10080, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'max', 1115.00, NULL),
	(81, 1749741540, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'count', 1.00, NULL),
	(82, 1749741540, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(83, 1749741540, 60, 'user_request', '1', 'count', 1.00, NULL),
	(93, 1749741540, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 'max', 1115.00, NULL),
	(97, 1749741600, 60, 'user_request', '1', 'count', 2.00, NULL),
	(105, 1749741660, 60, 'user_request', '1', 'count', 1.00, NULL),
	(109, 1749742500, 60, 'user_request', '1', 'count', 1.00, NULL),
	(110, 1749742200, 360, 'user_request', '1', 'count', 1.00, NULL),
	(113, 1749742800, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 1.00, NULL),
	(114, 1749742560, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 1.00, NULL),
	(115, 1749742560, 1440, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 6.00, NULL),
	(116, 1749736800, 10080, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 6.00, NULL),
	(117, 1749742800, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(118, 1749742560, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(119, 1749742560, 1440, 'slow_user_request', '1', 'count', 6.00, NULL),
	(120, 1749742800, 60, 'user_request', '1', 'count', 1.00, NULL),
	(121, 1749742560, 360, 'user_request', '1', 'count', 1.00, NULL),
	(122, 1749742560, 1440, 'user_request', '1', 'count', 26.00, NULL),
	(123, 1749742800, 60, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'count', 1.00, NULL),
	(124, 1749742560, 360, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'count', 1.00, NULL),
	(125, 1749742560, 1440, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'count', 1.00, NULL),
	(126, 1749736800, 10080, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'count', 1.00, NULL),
	(129, 1749742800, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 2763.00, NULL),
	(130, 1749742560, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 2763.00, NULL),
	(131, 1749742560, 1440, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 4400.00, NULL),
	(132, 1749736800, 10080, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 4400.00, NULL),
	(133, 1749742800, 60, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'max', 1749742839.00, NULL),
	(134, 1749742560, 360, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'max', 1749742839.00, NULL),
	(135, 1749742560, 1440, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'max', 1749742839.00, NULL),
	(136, 1749736800, 10080, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 'max', 1749742839.00, NULL),
	(137, 1749742980, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 3.00, NULL),
	(138, 1749742920, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 5.00, NULL),
	(139, 1749742980, 60, 'slow_user_request', '1', 'count', 3.00, NULL),
	(140, 1749742920, 360, 'slow_user_request', '1', 'count', 5.00, NULL),
	(141, 1749742980, 60, 'user_request', '1', 'count', 3.00, NULL),
	(142, 1749742920, 360, 'user_request', '1', 'count', 9.00, NULL),
	(143, 1749742980, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 2.00, NULL),
	(144, 1749742920, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 4.00, NULL),
	(145, 1749742560, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 4.00, NULL),
	(146, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 4.00, NULL),
	(153, 1749742980, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 4400.00, NULL),
	(154, 1749742920, 360, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 4400.00, NULL),
	(155, 1749742980, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749742997.00, NULL),
	(156, 1749742920, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749743112.00, NULL),
	(157, 1749742560, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749743112.00, NULL),
	(158, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749743112.00, NULL),
	(185, 1749742980, 60, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'count', 1.00, NULL),
	(186, 1749742920, 360, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'count', 1.00, NULL),
	(187, 1749742560, 1440, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'count', 1.00, NULL),
	(188, 1749736800, 10080, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'count', 1.00, NULL),
	(201, 1749742980, 60, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'max', 1749743026.00, NULL),
	(202, 1749742920, 360, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'max', 1749743026.00, NULL),
	(203, 1749742560, 1440, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'max', 1749743026.00, NULL),
	(204, 1749736800, 10080, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 'max', 1749743026.00, NULL),
	(209, 1749743040, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 1.00, NULL),
	(210, 1749743040, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(211, 1749743040, 60, 'user_request', '1', 'count', 2.00, NULL),
	(212, 1749743040, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 1.00, NULL),
	(225, 1749743040, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 1894.00, NULL),
	(226, 1749743040, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749743061.00, NULL),
	(237, 1749743100, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'count', 1.00, NULL),
	(238, 1749743100, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(239, 1749743100, 60, 'user_request', '1', 'count', 1.00, NULL),
	(240, 1749743100, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'count', 1.00, NULL),
	(253, 1749743100, 60, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 'max', 3060.00, NULL),
	(254, 1749743100, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 'max', 1749743112.00, NULL),
	(261, 1749743160, 60, 'user_request', '1', 'count', 1.00, NULL),
	(265, 1749743220, 60, 'user_request', '1', 'count', 2.00, NULL),
	(273, 1749743280, 60, 'user_request', '1', 'count', 1.00, NULL),
	(274, 1749743280, 360, 'user_request', '1', 'count', 5.00, NULL),
	(277, 1749743340, 60, 'user_request', '1', 'count', 1.00, NULL),
	(281, 1749743460, 60, 'user_request', '1', 'count', 3.00, NULL),
	(293, 1749743760, 60, 'user_request', '1', 'count', 2.00, NULL),
	(294, 1749743640, 360, 'user_request', '1', 'count', 11.00, NULL),
	(301, 1749743820, 60, 'user_request', '1', 'count', 1.00, NULL),
	(305, 1749743880, 60, 'user_request', '1', 'count', 2.00, NULL),
	(313, 1749743940, 60, 'user_request', '1', 'count', 6.00, NULL),
	(337, 1749744240, 60, 'user_request', '1', 'count', 3.00, NULL),
	(338, 1749744000, 360, 'user_request', '1', 'count', 3.00, NULL),
	(339, 1749744000, 1440, 'user_request', '1', 'count', 31.00, NULL),
	(349, 1749744420, 60, 'user_request', '1', 'count', 6.00, NULL),
	(350, 1749744360, 360, 'user_request', '1', 'count', 12.00, NULL),
	(373, 1749744480, 60, 'user_request', '1', 'count', 3.00, NULL),
	(385, 1749744540, 60, 'user_request', '1', 'count', 3.00, NULL),
	(397, 1749744840, 60, 'user_request', '1', 'count', 2.00, NULL),
	(398, 1749744720, 360, 'user_request', '1', 'count', 10.00, NULL),
	(405, 1749744900, 60, 'user_request', '1', 'count', 5.00, NULL),
	(425, 1749744960, 60, 'user_request', '1', 'count', 2.00, NULL),
	(429, 1749744960, 60, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(430, 1749744720, 360, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(431, 1749744000, 1440, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(432, 1749736800, 10080, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(433, 1749744960, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(434, 1749744720, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(435, 1749744000, 1440, 'slow_user_request', '1', 'count', 1.00, NULL),
	(436, 1749744960, 60, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'count', 1.00, NULL),
	(437, 1749744720, 360, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'count', 1.00, NULL),
	(438, 1749744000, 1440, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'count', 1.00, NULL),
	(439, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'count', 1.00, NULL),
	(445, 1749744960, 60, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'max', 2601.00, NULL),
	(446, 1749744720, 360, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'max', 2601.00, NULL),
	(447, 1749744000, 1440, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'max', 2601.00, NULL),
	(448, 1749736800, 10080, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 'max', 2601.00, NULL),
	(449, 1749744960, 60, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'max', 1749744996.00, NULL),
	(450, 1749744720, 360, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'max', 1749744996.00, NULL),
	(451, 1749744000, 1440, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'max', 1749744996.00, NULL),
	(452, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 'max', 1749744996.00, NULL),
	(453, 1749745020, 60, 'user_request', '1', 'count', 1.00, NULL),
	(457, 1749745140, 60, 'user_request', '1', 'count', 4.00, NULL),
	(458, 1749745080, 360, 'user_request', '1', 'count', 6.00, NULL),
	(473, 1749745380, 60, 'user_request', '1', 'count', 2.00, NULL),
	(481, 1749745560, 60, 'user_request', '1', 'count', 2.00, NULL),
	(482, 1749745440, 360, 'user_request', '1', 'count', 11.00, NULL),
	(483, 1749745440, 1440, 'user_request', '1', 'count', 36.00, NULL),
	(489, 1749745620, 60, 'user_request', '1', 'count', 4.00, NULL),
	(505, 1749745680, 60, 'user_request', '1', 'count', 5.00, NULL),
	(525, 1749745860, 60, 'user_request', '1', 'count', 2.00, NULL),
	(526, 1749745800, 360, 'user_request', '1', 'count', 11.00, NULL),
	(533, 1749745920, 60, 'user_request', '1', 'count', 7.00, NULL),
	(561, 1749745980, 60, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 1.00, NULL),
	(562, 1749745800, 360, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 1.00, NULL),
	(563, 1749745440, 1440, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 3.00, NULL),
	(564, 1749736800, 10080, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 3.00, NULL),
	(565, 1749745980, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(566, 1749745800, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(567, 1749745440, 1440, 'slow_user_request', '1', 'count', 4.00, NULL),
	(568, 1749745980, 60, 'user_request', '1', 'count', 1.00, NULL),
	(569, 1749745980, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'count', 1.00, NULL),
	(570, 1749745800, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'count', 1.00, NULL),
	(571, 1749745440, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'count', 1.00, NULL),
	(572, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'count', 1.00, NULL),
	(577, 1749745980, 60, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3071.00, NULL),
	(578, 1749745800, 360, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3071.00, NULL),
	(579, 1749745440, 1440, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3762.00, NULL),
	(580, 1749736800, 10080, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3762.00, NULL),
	(581, 1749745980, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'max', 1749745983.00, NULL),
	(582, 1749745800, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'max', 1749745983.00, NULL),
	(583, 1749745440, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'max', 1749745983.00, NULL),
	(584, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 'max', 1749745983.00, NULL),
	(585, 1749746100, 60, 'user_request', '1', 'count', 1.00, NULL),
	(589, 1749746280, 60, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 2.00, NULL),
	(590, 1749746160, 360, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'count', 2.00, NULL),
	(591, 1749746280, 60, 'slow_user_request', '1', 'count', 2.00, NULL),
	(592, 1749746160, 360, 'slow_user_request', '1', 'count', 2.00, NULL),
	(593, 1749746280, 60, 'user_request', '1', 'count', 3.00, NULL),
	(594, 1749746160, 360, 'user_request', '1', 'count', 8.00, NULL),
	(595, 1749746280, 60, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'count', 1.00, NULL),
	(596, 1749746160, 360, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'count', 1.00, NULL),
	(597, 1749745440, 1440, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'count', 1.00, NULL),
	(598, 1749736800, 10080, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'count', 1.00, NULL),
	(605, 1749746280, 60, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3762.00, NULL),
	(606, 1749746160, 360, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 'max', 3762.00, NULL),
	(607, 1749746280, 60, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'max', 1749746288.00, NULL),
	(608, 1749746160, 360, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'max', 1749746288.00, NULL),
	(609, 1749745440, 1440, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'max', 1749746288.00, NULL),
	(610, 1749736800, 10080, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 'max', 1749746288.00, NULL),
	(633, 1749746340, 60, 'user_request', '1', 'count', 5.00, NULL),
	(653, 1749746580, 60, 'user_request', '1', 'count', 4.00, NULL),
	(654, 1749746520, 360, 'user_request', '1', 'count', 6.00, NULL),
	(657, 1749746580, 60, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'count', 1.00, NULL),
	(658, 1749746520, 360, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'count', 1.00, NULL),
	(659, 1749745440, 1440, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'count', 1.00, NULL),
	(660, 1749736800, 10080, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'count', 1.00, NULL),
	(661, 1749746580, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(662, 1749746520, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(663, 1749746580, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'count', 1.00, NULL),
	(664, 1749746520, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'count', 1.00, NULL),
	(665, 1749745440, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'count', 1.00, NULL),
	(666, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'count', 1.00, NULL),
	(673, 1749746580, 60, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'max', 2925.00, NULL),
	(674, 1749746520, 360, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'max', 2925.00, NULL),
	(675, 1749745440, 1440, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'max', 2925.00, NULL),
	(676, 1749736800, 10080, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 'max', 2925.00, NULL),
	(677, 1749746580, 60, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'max', 1749746592.00, NULL),
	(678, 1749746520, 360, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'max', 1749746592.00, NULL),
	(679, 1749745440, 1440, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'max', 1749746592.00, NULL),
	(680, 1749736800, 10080, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 'max', 1749746592.00, NULL),
	(689, 1749746640, 60, 'user_request', '1', 'count', 2.00, NULL),
	(697, 1749751080, 60, 'user_request', '1', 'count', 1.00, NULL),
	(698, 1749750840, 360, 'user_request', '1', 'count', 1.00, NULL),
	(699, 1749749760, 1440, 'user_request', '1', 'count', 1.00, NULL),
	(700, 1749746880, 10080, 'user_request', '1', 'count', 41.00, NULL),
	(701, 1749751440, 60, 'user_request', '1', 'count', 1.00, NULL),
	(702, 1749751200, 360, 'user_request', '1', 'count', 10.00, NULL),
	(703, 1749751200, 1440, 'user_request', '1', 'count', 36.00, NULL),
	(705, 1749751500, 60, 'user_request', '1', 'count', 9.00, NULL),
	(741, 1749751560, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 3.00, NULL),
	(742, 1749751560, 360, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 4.00, NULL),
	(743, 1749751200, 1440, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 5.00, NULL),
	(744, 1749746880, 10080, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 5.00, NULL),
	(745, 1749751560, 60, 'slow_user_request', '1', 'count', 3.00, NULL),
	(746, 1749751560, 360, 'slow_user_request', '1', 'count', 4.00, NULL),
	(747, 1749751200, 1440, 'slow_user_request', '1', 'count', 5.00, NULL),
	(748, 1749746880, 10080, 'slow_user_request', '1', 'count', 5.00, NULL),
	(749, 1749751560, 60, 'user_request', '1', 'count', 3.00, NULL),
	(750, 1749751560, 360, 'user_request', '1', 'count', 4.00, NULL),
	(751, 1749751560, 60, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'count', 3.00, NULL),
	(752, 1749751560, 360, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'count', 4.00, NULL),
	(753, 1749751200, 1440, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'count', 4.00, NULL),
	(754, 1749746880, 10080, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'count', 4.00, NULL),
	(757, 1749751560, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 4868.00, NULL),
	(758, 1749751560, 360, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 5016.00, NULL),
	(759, 1749751200, 1440, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 5016.00, NULL),
	(760, 1749746880, 10080, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 5016.00, NULL),
	(761, 1749751560, 60, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'max', 1749751597.00, NULL),
	(762, 1749751560, 360, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'max', 1749751733.00, NULL),
	(763, 1749751200, 1440, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'max', 1749751733.00, NULL),
	(764, 1749746880, 10080, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'max', 1749751733.00, NULL),
	(813, 1749751680, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(814, 1749751680, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(815, 1749751680, 60, 'user_request', '1', 'count', 1.00, NULL),
	(816, 1749751680, 60, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'count', 1.00, NULL),
	(829, 1749751680, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 5016.00, NULL),
	(830, 1749751680, 60, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 'max', 1749751733.00, NULL),
	(837, 1749751920, 60, 'user_request', '1', 'count', 1.00, NULL),
	(838, 1749751920, 360, 'user_request', '1', 'count', 15.00, NULL),
	(841, 1749751980, 60, 'user_request', '1', 'count', 4.00, NULL),
	(857, 1749752040, 60, 'user_request', '1', 'count', 5.00, NULL),
	(873, 1749752040, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(874, 1749751920, 360, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'count', 1.00, NULL),
	(875, 1749752040, 60, 'slow_user_request', '1', 'count', 1.00, NULL),
	(876, 1749751920, 360, 'slow_user_request', '1', 'count', 1.00, NULL),
	(885, 1749752040, 60, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 1057.00, NULL),
	(886, 1749751920, 360, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 'max', 1057.00, NULL),
	(889, 1749752160, 60, 'user_request', '1', 'count', 5.00, NULL),
	(909, 1749752280, 60, 'user_request', '1', 'count', 2.00, NULL),
	(910, 1749752280, 360, 'user_request', '1', 'count', 7.00, NULL),
	(917, 1749752520, 60, 'user_request', '1', 'count', 2.00, NULL),
	(925, 1749752580, 60, 'user_request', '1', 'count', 3.00, NULL),
	(937, 1749752760, 60, 'user_request', '1', 'count', 1.00, NULL),
	(938, 1749752640, 360, 'user_request', '1', 'count', 2.00, NULL),
	(939, 1749752640, 1440, 'user_request', '1', 'count', 4.00, NULL),
	(941, 1749752820, 60, 'user_request', '1', 'count', 1.00, NULL),
	(945, 1749753000, 60, 'user_request', '1', 'count', 2.00, NULL),
	(946, 1749753000, 360, 'user_request', '1', 'count', 2.00, NULL);

-- Volcando estructura para tabla geca.pulse_entries
CREATE TABLE IF NOT EXISTS `pulse_entries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` mediumtext NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pulse_entries_timestamp_index` (`timestamp`),
  KEY `pulse_entries_type_index` (`type`),
  KEY `pulse_entries_key_hash_index` (`key_hash`),
  KEY `pulse_entries_timestamp_type_key_hash_value_index` (`timestamp`,`type`,`key_hash`,`value`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.pulse_entries: ~177 rows (aproximadamente)
INSERT INTO `pulse_entries` (`id`, `timestamp`, `type`, `key`, `value`) VALUES
	(1, 1749739626, 'slow_request', '["POST","\\/register","App\\\\Http\\\\Controllers\\\\Auth\\\\RegisteredUserController@store"]', 1036),
	(2, 1749739626, 'slow_user_request', '1', NULL),
	(3, 1749739626, 'user_request', '1', NULL),
	(4, 1749739627, 'slow_request', '["GET","\\/dashboard","Closure"]', 7490),
	(5, 1749739628, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 1749739628),
	(6, 1749739632, 'exception', '["Illuminate\\\\Database\\\\QueryException","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Database\\\\Connection.php:822"]', 1749739632),
	(7, 1749739674, 'user_request', '1', NULL),
	(8, 1749739680, 'user_request', '1', NULL),
	(9, 1749741358, 'user_request', '1', NULL),
	(10, 1749741373, 'user_request', '1', NULL),
	(11, 1749741378, 'user_request', '1', NULL),
	(12, 1749741420, 'user_request', '1', NULL),
	(13, 1749741454, 'user_request', '1', NULL),
	(14, 1749741511, 'user_request', '1', NULL),
	(15, 1749741528, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 1052),
	(16, 1749741528, 'slow_user_request', '1', NULL),
	(17, 1749741528, 'user_request', '1', NULL),
	(18, 1749741571, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresa"]', 1115),
	(19, 1749741571, 'slow_user_request', '1', NULL),
	(20, 1749741571, 'user_request', '1', NULL),
	(21, 1749741613, 'user_request', '1', NULL),
	(22, 1749741621, 'user_request', '1', NULL),
	(23, 1749741669, 'user_request', '1', NULL),
	(24, 1749742553, 'user_request', '1', NULL),
	(25, 1749742838, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 2763),
	(26, 1749742838, 'slow_user_request', '1', NULL),
	(27, 1749742838, 'user_request', '1', NULL),
	(28, 1749742839, 'exception', '["Error","vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Routing\\\\ControllerDispatcher.php:46"]', 1749742839),
	(29, 1749742980, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 4400),
	(30, 1749742980, 'slow_user_request', '1', NULL),
	(31, 1749742980, 'user_request', '1', NULL),
	(32, 1749742981, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 1749742981),
	(33, 1749742996, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 2040),
	(34, 1749742996, 'slow_user_request', '1', NULL),
	(35, 1749742996, 'user_request', '1', NULL),
	(36, 1749742997, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 1749742997),
	(37, 1749743026, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 1540),
	(38, 1749743026, 'slow_user_request', '1', NULL),
	(39, 1749743026, 'user_request', '1', NULL),
	(40, 1749743026, 'exception', '["InvalidArgumentException","app\\\\Http\\\\Controllers\\\\TarifaController.php:15"]', 1749743026),
	(41, 1749743060, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 1894),
	(42, 1749743060, 'slow_user_request', '1', NULL),
	(43, 1749743060, 'user_request', '1', NULL),
	(44, 1749743061, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 1749743061),
	(45, 1749743078, 'user_request', '1', NULL),
	(46, 1749743112, 'slow_request', '["GET","\\/empresa","App\\\\Http\\\\Controllers\\\\TarifaController@getEmpresas"]', 3060),
	(47, 1749743112, 'slow_user_request', '1', NULL),
	(48, 1749743112, 'user_request', '1', NULL),
	(49, 1749743112, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\929851daf22baa805e25bbb4fa864f06.php:14"]', 1749743112),
	(50, 1749743166, 'user_request', '1', NULL),
	(51, 1749743267, 'user_request', '1', NULL),
	(52, 1749743273, 'user_request', '1', NULL),
	(53, 1749743282, 'user_request', '1', NULL),
	(54, 1749743389, 'user_request', '1', NULL),
	(55, 1749743464, 'user_request', '1', NULL),
	(56, 1749743471, 'user_request', '1', NULL),
	(57, 1749743473, 'user_request', '1', NULL),
	(58, 1749743813, 'user_request', '1', NULL),
	(59, 1749743817, 'user_request', '1', NULL),
	(60, 1749743826, 'user_request', '1', NULL),
	(61, 1749743933, 'user_request', '1', NULL),
	(62, 1749743936, 'user_request', '1', NULL),
	(63, 1749743942, 'user_request', '1', NULL),
	(64, 1749743955, 'user_request', '1', NULL),
	(65, 1749743958, 'user_request', '1', NULL),
	(66, 1749743964, 'user_request', '1', NULL),
	(67, 1749743966, 'user_request', '1', NULL),
	(68, 1749743971, 'user_request', '1', NULL),
	(69, 1749744257, 'user_request', '1', NULL),
	(70, 1749744261, 'user_request', '1', NULL),
	(71, 1749744274, 'user_request', '1', NULL),
	(72, 1749744445, 'user_request', '1', NULL),
	(73, 1749744453, 'user_request', '1', NULL),
	(74, 1749744454, 'user_request', '1', NULL),
	(75, 1749744460, 'user_request', '1', NULL),
	(76, 1749744462, 'user_request', '1', NULL),
	(77, 1749744465, 'user_request', '1', NULL),
	(78, 1749744527, 'user_request', '1', NULL),
	(79, 1749744535, 'user_request', '1', NULL),
	(80, 1749744536, 'user_request', '1', NULL),
	(81, 1749744556, 'user_request', '1', NULL),
	(82, 1749744557, 'user_request', '1', NULL),
	(83, 1749744574, 'user_request', '1', NULL),
	(84, 1749744874, 'user_request', '1', NULL),
	(85, 1749744884, 'user_request', '1', NULL),
	(86, 1749744909, 'user_request', '1', NULL),
	(87, 1749744912, 'user_request', '1', NULL),
	(88, 1749744914, 'user_request', '1', NULL),
	(89, 1749744915, 'user_request', '1', NULL),
	(90, 1749744918, 'user_request', '1', NULL),
	(91, 1749744985, 'user_request', '1', NULL),
	(92, 1749744996, 'slow_request', '["POST","\\/empresa","via \\/livewire\\/update"]', 2601),
	(93, 1749744996, 'slow_user_request', '1', NULL),
	(94, 1749744996, 'user_request', '1', NULL),
	(95, 1749744996, 'exception', '["Livewire\\\\Exceptions\\\\MethodNotFoundException","vendor\\\\livewire\\\\livewire\\\\src\\\\Mechanisms\\\\HandleComponents\\\\HandleComponents.php:470"]', 1749744996),
	(96, 1749745046, 'user_request', '1', NULL),
	(97, 1749745145, 'user_request', '1', NULL),
	(98, 1749745159, 'user_request', '1', NULL),
	(99, 1749745165, 'user_request', '1', NULL),
	(100, 1749745183, 'user_request', '1', NULL),
	(101, 1749745423, 'user_request', '1', NULL),
	(102, 1749745431, 'user_request', '1', NULL),
	(103, 1749745605, 'user_request', '1', NULL),
	(104, 1749745613, 'user_request', '1', NULL),
	(105, 1749745674, 'user_request', '1', NULL),
	(106, 1749745677, 'user_request', '1', NULL),
	(107, 1749745678, 'user_request', '1', NULL),
	(108, 1749745679, 'user_request', '1', NULL),
	(109, 1749745683, 'user_request', '1', NULL),
	(110, 1749745685, 'user_request', '1', NULL),
	(111, 1749745687, 'user_request', '1', NULL),
	(112, 1749745689, 'user_request', '1', NULL),
	(113, 1749745691, 'user_request', '1', NULL),
	(114, 1749745907, 'user_request', '1', NULL),
	(115, 1749745910, 'user_request', '1', NULL),
	(116, 1749745928, 'user_request', '1', NULL),
	(117, 1749745936, 'user_request', '1', NULL),
	(118, 1749745941, 'user_request', '1', NULL),
	(119, 1749745951, 'user_request', '1', NULL),
	(120, 1749745955, 'user_request', '1', NULL),
	(121, 1749745956, 'user_request', '1', NULL),
	(122, 1749745958, 'user_request', '1', NULL),
	(123, 1749745983, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 3071),
	(124, 1749745983, 'slow_user_request', '1', NULL),
	(125, 1749745983, 'user_request', '1', NULL),
	(126, 1749745983, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\30ceb4a233e97df98900546161b83dce.php:14"]', 1749745983),
	(127, 1749746101, 'user_request', '1', NULL),
	(128, 1749746288, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 3762),
	(129, 1749746288, 'slow_user_request', '1', NULL),
	(130, 1749746288, 'user_request', '1', NULL),
	(131, 1749746288, 'exception', '["Error","resources\\\\views\\\\tarifario\\\\peso.blade.php"]', 1749746288),
	(132, 1749746327, 'slow_request', '["GET","\\/peso","App\\\\Http\\\\Controllers\\\\TarifaController@getPesos"]', 1106),
	(133, 1749746327, 'slow_user_request', '1', NULL),
	(134, 1749746327, 'user_request', '1', NULL),
	(135, 1749746331, 'user_request', '1', NULL),
	(136, 1749746344, 'user_request', '1', NULL),
	(137, 1749746357, 'user_request', '1', NULL),
	(138, 1749746360, 'user_request', '1', NULL),
	(139, 1749746362, 'user_request', '1', NULL),
	(140, 1749746366, 'user_request', '1', NULL),
	(141, 1749746587, 'user_request', '1', NULL),
	(142, 1749746591, 'slow_request', '["GET","\\/tarifa","App\\\\Http\\\\Controllers\\\\TarifaController@getTarifas"]', 2925),
	(143, 1749746591, 'slow_user_request', '1', NULL),
	(144, 1749746591, 'user_request', '1', NULL),
	(145, 1749746592, 'exception', '["Livewire\\\\Exceptions\\\\ComponentNotFoundException","storage\\\\framework\\\\views\\\\41be8020587e788726f1d8950a903427.php:14"]', 1749746592),
	(146, 1749746610, 'user_request', '1', NULL),
	(147, 1749746626, 'user_request', '1', NULL),
	(148, 1749746644, 'user_request', '1', NULL),
	(149, 1749746652, 'user_request', '1', NULL),
	(150, 1749751115, 'user_request', '1', NULL),
	(151, 1749751494, 'user_request', '1', NULL),
	(152, 1749751500, 'user_request', '1', NULL),
	(153, 1749751506, 'user_request', '1', NULL),
	(154, 1749751508, 'user_request', '1', NULL),
	(155, 1749751512, 'user_request', '1', NULL),
	(156, 1749751522, 'user_request', '1', NULL),
	(157, 1749751524, 'user_request', '1', NULL),
	(158, 1749751534, 'user_request', '1', NULL),
	(159, 1749751536, 'user_request', '1', NULL),
	(160, 1749751540, 'user_request', '1', NULL),
	(161, 1749751575, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 4868),
	(162, 1749751575, 'slow_user_request', '1', NULL),
	(163, 1749751575, 'user_request', '1', NULL),
	(164, 1749751576, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 1749751576),
	(165, 1749751581, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 3568),
	(166, 1749751581, 'slow_user_request', '1', NULL),
	(167, 1749751581, 'user_request', '1', NULL),
	(168, 1749751582, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 1749751582),
	(169, 1749751597, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 2188),
	(170, 1749751597, 'slow_user_request', '1', NULL),
	(171, 1749751597, 'user_request', '1', NULL),
	(172, 1749751597, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 1749751597),
	(173, 1749751732, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 5016),
	(174, 1749751732, 'slow_user_request', '1', NULL),
	(175, 1749751732, 'user_request', '1', NULL),
	(176, 1749751733, 'exception', '["ErrorException","resources\\\\views\\\\livewire\\\\tarifas.blade.php"]', 1749751733),
	(177, 1749751959, 'user_request', '1', NULL),
	(178, 1749752016, 'user_request', '1', NULL),
	(179, 1749752024, 'user_request', '1', NULL),
	(180, 1749752034, 'user_request', '1', NULL),
	(181, 1749752037, 'user_request', '1', NULL),
	(182, 1749752061, 'user_request', '1', NULL),
	(183, 1749752066, 'user_request', '1', NULL),
	(184, 1749752070, 'user_request', '1', NULL),
	(185, 1749752079, 'user_request', '1', NULL),
	(186, 1749752083, 'slow_request', '["POST","\\/tarifa","via \\/livewire\\/update"]', 1057),
	(187, 1749752083, 'slow_user_request', '1', NULL),
	(188, 1749752083, 'user_request', '1', NULL),
	(189, 1749752179, 'user_request', '1', NULL),
	(190, 1749752184, 'user_request', '1', NULL),
	(191, 1749752195, 'user_request', '1', NULL),
	(192, 1749752198, 'user_request', '1', NULL),
	(193, 1749752201, 'user_request', '1', NULL),
	(194, 1749752287, 'user_request', '1', NULL),
	(195, 1749752299, 'user_request', '1', NULL),
	(196, 1749752564, 'user_request', '1', NULL),
	(197, 1749752569, 'user_request', '1', NULL),
	(198, 1749752588, 'user_request', '1', NULL),
	(199, 1749752595, 'user_request', '1', NULL),
	(200, 1749752623, 'user_request', '1', NULL),
	(201, 1749752763, 'user_request', '1', NULL),
	(202, 1749752875, 'user_request', '1', NULL),
	(203, 1749753006, 'user_request', '1', NULL),
	(204, 1749753010, 'user_request', '1', NULL);

-- Volcando estructura para tabla geca.pulse_values
CREATE TABLE IF NOT EXISTS `pulse_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int(10) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` mediumtext NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_values_type_key_hash_unique` (`type`,`key_hash`),
  KEY `pulse_values_timestamp_index` (`timestamp`),
  KEY `pulse_values_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.pulse_values: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.roles: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.role_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.sessions: ~1 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('XzFMItKiHT3dzc91P7WCqs2FZcHH5BzaIgV96Q4W', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTzlyVmQ5Wlg3SjlqUzljWXNLUEtQV3hLQ1VKWnpEZ2NmTmVjdG01diI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6NzoibWVzc2FnZSI7fXM6MzoibmV3IjthOjA6e319czoxODoiZmxhc2hlcjo6ZW52ZWxvcGVzIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Blc28iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1749753010);

-- Volcando estructura para tabla geca.tarifario
CREATE TABLE IF NOT EXISTS `tarifario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `peso` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `local` float DEFAULT NULL,
  `nacional` float DEFAULT NULL,
  `camiri` float DEFAULT NULL,
  `sud` float DEFAULT NULL,
  `norte` float DEFAULT NULL,
  `centro` float DEFAULT NULL,
  `euro` float DEFAULT NULL,
  `asia` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla geca.tarifario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla geca.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla geca.users: ~0 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Marco Antonio Espinoza Rojas', 'marco.espinoza@correos.gob.bo', NULL, '$2y$12$6o996n9VkbL6kW7yTLini.kURmIXU/nSmEfgk8afXS3bt1ntpFjji', NULL, '2025-06-12 18:47:07', '2025-06-12 18:47:07', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
