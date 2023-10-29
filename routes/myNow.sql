DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `userId` int unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server` tinyint(1) NOT NULL DEFAULT '1',
  `video` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videoServer` tinyint(1) NOT NULL DEFAULT '1',
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `release` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateAndTime` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seoTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seen` int unsigned NOT NULL DEFAULT '0',
  `topNews` tinyint(1) NOT NULL DEFAULT '0',
  `confirm` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rtl` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `newsCategories`
--

DROP TABLE IF EXISTS `news_categories`;

CREATE TABLE `news_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentId` int unsigned NOT NULL DEFAULT '0',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `newsCategoryRelations`
--

DROP TABLE IF EXISTS `news_category_relations`;

CREATE TABLE `news_category_relations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `newsId` bigint unsigned NOT NULL,
  `categoryId` bigint unsigned NOT NULL,
  `isMain` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `newscategoryrelations_newsid_foreign` (`newsId`),
  KEY `newscategoryrelations_categoryid_foreign` (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `newsLimboPics`
--

DROP TABLE IF EXISTS `news_limbo_pics`;

CREATE TABLE `news_limbo_pics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `userId` bigint unsigned NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `server` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `newsTags`
--

DROP TABLE IF EXISTS `news_tags`;

CREATE TABLE `news_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `newsTagsRelations`
--

DROP TABLE IF EXISTS `news_tags_relations`;

CREATE TABLE `news_tags_relations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `newsId` bigint unsigned NOT NULL,
  `tagId` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=503 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;