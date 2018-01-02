-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema movies
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema movies
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `movies` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `movies` ;

-- -----------------------------------------------------
-- Table `movies`.`movies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`movies` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `title` VARCHAR(45) NOT NULL COMMENT '',
  `year` INT NOT NULL COMMENT '',
  `image_url` VARCHAR(255) NOT NULL COMMENT '',
  `certificate` VARCHAR(45) NULL COMMENT '',
  `runtime` INT NULL COMMENT '',
  `imdb_rating` FLOAT NULL COMMENT '',
  `description` TEXT NULL COMMENT '',
  `metascore` INT NULL COMMENT '',
  `votes` INT NULL COMMENT '',
  `gross` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `title_UNIQUE` (`title` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`directors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`directors` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(45) NOT NULL COMMENT '',
  `about` TEXT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`stars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`stars` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(45) NOT NULL COMMENT '',
  `about` TEXT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`genres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`genres` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`movies_directors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`movies_directors` (
  `movies_id` INT NOT NULL COMMENT '',
  `directors_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`movies_id`, `directors_id`)  COMMENT '',
  INDEX `fk_movies_has_directors_directors1_idx` (`directors_id` ASC)  COMMENT '',
  INDEX `fk_movies_has_directors_movies_idx` (`movies_id` ASC)  COMMENT '',
  CONSTRAINT `fk_movies_has_directors_movies`
    FOREIGN KEY (`movies_id`)
    REFERENCES `movies`.`movies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movies_has_directors_directors1`
    FOREIGN KEY (`directors_id`)
    REFERENCES `movies`.`directors` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`movies_stars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`movies_stars` (
  `movies_id` INT NOT NULL COMMENT '',
  `stars_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`movies_id`, `stars_id`)  COMMENT '',
  INDEX `fk_movies_has_stars_stars1_idx` (`stars_id` ASC)  COMMENT '',
  INDEX `fk_movies_has_stars_movies1_idx` (`movies_id` ASC)  COMMENT '',
  CONSTRAINT `fk_movies_has_stars_movies1`
    FOREIGN KEY (`movies_id`)
    REFERENCES `movies`.`movies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movies_has_stars_stars1`
    FOREIGN KEY (`stars_id`)
    REFERENCES `movies`.`stars` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movies`.`movies_genres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movies`.`movies_genres` (
  `movies_id` INT NOT NULL COMMENT '',
  `genres_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`movies_id`, `genres_id`)  COMMENT '',
  INDEX `fk_movies_has_genres_genres1_idx` (`genres_id` ASC)  COMMENT '',
  INDEX `fk_movies_has_genres_movies1_idx` (`movies_id` ASC)  COMMENT '',
  CONSTRAINT `fk_movies_has_genres_movies1`
    FOREIGN KEY (`movies_id`)
    REFERENCES `movies`.`movies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movies_has_genres_genres1`
    FOREIGN KEY (`genres_id`)
    REFERENCES `movies`.`genres` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;