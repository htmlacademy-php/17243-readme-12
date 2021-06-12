-- -----------------------------------------------------
-- Schema readme
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `readme` DEFAULT CHARACTER SET utf8;

USE `readme`;

-- -----------------------------------------------------
-- Table `readme`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `email` VARCHAR(255) NOT NULL,
  `login` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `avatar_path` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
);

/* indexes */
CREATE UNIQUE INDEX `email_UNIQUE` ON `readme`.`users` (`email` ASC);

CREATE UNIQUE INDEX `login_UNIQUE` ON `readme`.`users` (`login` ASC);

-- -----------------------------------------------------
-- Table `readme`.`content_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`content_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `classname` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
);

-- -----------------------------------------------------
-- Table `readme`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `original_post_id` INT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `title` TEXT NOT NULL,
  `body` TEXT NULL,
  `views_count` INT NULL DEFAULT 0,
  `user_id` INT NOT NULL,
  `content_type_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_content_type` FOREIGN KEY (`content_type_id`) REFERENCES `readme`.`content_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts` FOREIGN KEY (`original_post_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_posts_user_idx` ON `readme`.`posts` (`user_id` ASC);

CREATE INDEX `fk_posts_content_type_idx` ON `readme`.`posts` (`content_type_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `text` TEXT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_comments_post_idx` ON `readme`.`comments` (`post_id` ASC);

CREATE INDEX `fk_comments_user_idx` ON `readme`.`comments` (`user_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`likes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `post_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_likes_user` FOREIGN KEY (`user_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_likes_post` FOREIGN KEY (`post_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_likes_user_idx` ON `readme`.`likes` (`user_id` ASC);

CREATE INDEX `fk_likes_post_idx` ON `readme`.`likes` (`post_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`subscriptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`subscriptions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `subscriber_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_subscriptions_subscriber` FOREIGN KEY (`subscriber_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_subscriptions_user` FOREIGN KEY (`user_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

-- -----------------------------------------------------
-- Table `readme`.`messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `text` TEXT NULL,
  `sender_id` INT NOT NULL,
  `recipient_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_messages_sender_idx` ON `readme`.`messages` (`sender_id` ASC);

CREATE INDEX `fk_messages_recipient_idx` ON `readme`.`messages` (`recipient_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`hashtags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`hashtags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
);

-- -----------------------------------------------------
-- Table `readme`.`posts_has_hashtags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`posts_has_hashtags` (
  `post_id` INT NOT NULL,
  `hashtag_id` INT NOT NULL,
  PRIMARY KEY (`post_id`, `hashtag_id`),
  CONSTRAINT `fk_posts_has_hashtags_posts` FOREIGN KEY (`post_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_has_hashtags_hashtags` FOREIGN KEY (`hashtag_id`) REFERENCES `readme`.`hashtags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_posts_has_hashtags_hashtag_idx` ON `readme`.`posts_has_hashtags` (`hashtag_id` ASC);

CREATE INDEX `fk_posts_has_hashtags_post_idx` ON `readme`.`posts_has_hashtags` (`post_id` ASC);
