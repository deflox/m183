a-- -----------------------------------------------------
-- Table `m183`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m183`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(80) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m183`.`usersInsecure`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m183`.`usersInsecure` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(80) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m183`.`attempts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m183`.`attempts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(255) NOT NULL,
  `count` TINYINT NOT NULL,
  `lock_time` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m183`.`tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m183`.`tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  INDEX `fk_tokens_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_tokens_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `m183`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m183`.`movies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m183`.`movies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `imdb_id` VARCHAR(45) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `genres` VARCHAR(100) NOT NULL,
  `year` INT NOT NULL,
  `runtime` INT NOT NULL,
  `plot` MEDIUMTEXT NOT NULL,
  `image_url` VARCHAR(1024) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_movies_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_movies_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `m183`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO `m183`.`users` (`email`, `password`, `name`) VALUES ('test@rudin.cc', '$2y$10$/UHKTDIhNR1nBjOU1N0NVebbGOJbbV5LJapocfhcCzrhSSe8PQ2be', 'Leo Rudin');