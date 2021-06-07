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
  `users_id` INT NOT NULL,
  `content_types_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_posts_users` FOREIGN KEY (`users_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_content_types` FOREIGN KEY (`content_types_id`) REFERENCES `readme`.`content_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts` FOREIGN KEY (`original_post_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_posts_users_idx` ON `readme`.`posts` (`users_id` ASC);

CREATE INDEX `fk_posts_content_types_idx` ON `readme`.`posts` (`content_types_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `text` TEXT NULL,
  `posts_id` INT NOT NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_comments_posts` FOREIGN KEY (`posts_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_users` FOREIGN KEY (`users_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_comments_posts_idx` ON `readme`.`comments` (`posts_id` ASC);

CREATE INDEX `fk_comments_users_idx` ON `readme`.`comments` (`users_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`likes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `posts_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_likes_users` FOREIGN KEY (`users_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_likes_posts` FOREIGN KEY (`posts_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_likes_users_idx` ON `readme`.`likes` (`users_id` ASC);

CREATE INDEX `fk_likes_posts_idx` ON `readme`.`likes` (`posts_id` ASC);

-- -----------------------------------------------------
-- Table `readme`.`subscriptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`subscriptions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `subscribers_id` INT NOT NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_subscriptions_subscriber` FOREIGN KEY (`subscribers_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_subscriptions_user` FOREIGN KEY (`users_id`) REFERENCES `readme`.`users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `posts_id` INT NOT NULL,
  `hashtags_id` INT NOT NULL,
  PRIMARY KEY (`posts_id`, `hashtags_id`),
  CONSTRAINT `fk_posts_has_hashtags_posts` FOREIGN KEY (`posts_id`) REFERENCES `readme`.`posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_has_hashtags_hashtags` FOREIGN KEY (`hashtags_id`) REFERENCES `readme`.`hashtags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

/* indexes */
CREATE INDEX `fk_posts_has_hashtags_hashtags_idx` ON `readme`.`posts_has_hashtags` (`hashtags_id` ASC);

CREATE INDEX `fk_posts_has_hashtags_posts_idx` ON `readme`.`posts_has_hashtags` (`posts_id` ASC);
