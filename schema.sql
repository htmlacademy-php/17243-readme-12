-- -----------------------------------------------------
-- Schema readme
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `readme` DEFAULT CHARACTER SET utf8 ;
USE `readme` ;

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
  PRIMARY KEY (`id`))

/* indexes */
  CREATE UNIQUE INDEX `email_UNIQUE` ON `readme`.`users` (`email` ASC)  ;
  CREATE UNIQUE INDEX `login_UNIQUE` ON `readme`.`users` (`login` ASC)  ;


-- -----------------------------------------------------
-- Table `readme`.`content_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`content_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `classname` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))


-- -----------------------------------------------------
-- Table `readme`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_add` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `title` TEXT NOT NULL,
  `text` TEXT NULL,
  `quote` TEXT NULL,
  `photo` TEXT NULL,
  `video` TEXT NULL,
  `link` TEXT NULL,
  `views_count` INT NULL DEFAULT 0,
  `users_id` INT NOT NULL,
  `content_type_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_posts_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_content_type`
    FOREIGN KEY (`content_type_id`)
    REFERENCES `readme`.`content_type` (`id`))
    ON DELETE CASCADE
    ON UPDATE CASCADE,

/* indexes */
  CREATE INDEX `fk_posts_users_idx` ON `readme`.`posts` (`users_id` ASC)  ;
  CREATE INDEX `fk_posts_content_type_idx` ON `readme`.`posts` (`content_type_id` ASC)  ;


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
  CONSTRAINT `fk_comments_posts`
    FOREIGN KEY (`posts_id`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comments_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)

/* indexes */
  CREATE INDEX `fk_comments_posts_idx` ON `readme`.`comments` (`posts_id` ASC)  ;
  CREATE INDEX `fk_comments_users_idx` ON `readme`.`comments` (`users_id` ASC)  ;


-- -----------------------------------------------------
-- Table `readme`.`like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`like` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `posts_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_like_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `CASCADEosts`
    FOREIGN KEY (`posts_id`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)

/* indexes */
  CREATE INDEX `fk_like_users_idx` ON `readme`.`like` (`users_id` ASC)  ;
  CREATE INDEX `fk_like_posts_idx` ON `readme`.`like` (`posts_id` ASC)  ;


-- -----------------------------------------------------
-- Table `readme`.`subscription`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`subscription` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `subscriber_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_subscription_subscriber`
    FOREIGN KEY (`subscriber_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_subscription_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)


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
  CONSTRAINT `fk_messages_sender`
    FOREIGN KEY (`sender_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_recipient`
    FOREIGN KEY (`recipient_id`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)

/* indexes */
  CREATE INDEX `fk_messages_sender_idx` ON `readme`.`messages` (`sender_id` ASC)  ;
  CREATE INDEX `fk_messages_recipient_idx` ON `readme`.`messages` (`recipient_id` ASC)  ;

-- -----------------------------------------------------
-- Table `readme`.`hashtag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`hashtag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))


-- -----------------------------------------------------
-- Table `readme`.`posts_has_hashtag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`posts_has_hashtag` (
  `posts_id` INT NOT NULL,
  `hashtag_id` INT NOT NULL,
  PRIMARY KEY (`posts_id`, `hashtag_id`),
  CONSTRAINT `fk_posts_has_hashtag_posts`
    FOREIGN KEY (`posts_id`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_has_hashtag_hashtag`
    FOREIGN KEY (`hashtag_id`)
    REFERENCES `readme`.`hashtag` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)

/* indexes */
  CREATE INDEX `fk_posts_has_hashtag_hashtag_idx` ON `readme`.`posts_has_hashtag` (`hashtag_id` ASC)  ;
  CREATE INDEX `fk_posts_has_hashtag_posts_idx` ON `readme`.`posts_has_hashtag` (`posts_id` ASC)  ;
