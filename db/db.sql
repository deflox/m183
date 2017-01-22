-- -----------------------------------------------------
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