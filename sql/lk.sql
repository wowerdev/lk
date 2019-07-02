SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `lk_bonus`;
CREATE TABLE `lk_bonus`  (
  `acc_id` int(11) NOT NULL,
  `count` int(255) NULL DEFAULT NULL,
  PRIMARY KEY (`acc_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;

DROP TABLE IF EXISTS `lk_vote`;
CREATE TABLE `lk_vote`  (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `vote_id` int(15) UNSIGNED NOT NULL,
  `vote_date` int(255) NOT NULL,
  `vote_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vote_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vote_count` int(11) NOT NULL,
  `vote_today` int(255) NOT NULL DEFAULT 0,
  `acc_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;