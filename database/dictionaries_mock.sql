﻿-- MySQL Script generated by MySQL Workbench
-- Thu Apr  6 02:02:54 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema dictionaries
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dictionaries
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dictionaries` DEFAULT CHARACTER SET utf8 ;
USE `dictionaries` ;

-- -----------------------------------------------------
-- Table `dictionaries`.`dictionaries_list`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`dictionaries_list` (
  `iddictionaries_list` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL DEFAULT NULL,
  `code` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`iddictionaries_list`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`english`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`english` (
  `id_en` INT(11) NOT NULL AUTO_INCREMENT,
  `Word` VARCHAR(45) NULL DEFAULT NULL,
  `Etymology` MEDIUMTEXT NULL DEFAULT NULL,
  `Czech` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_en`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`français`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`francais` (
  `id_fr` INT(11) NOT NULL AUTO_INCREMENT,
  `Mot` VARCHAR(45) NULL DEFAULT NULL,
  `Etymologie` MEDIUMTEXT NULL DEFAULT NULL,
  `Anglais` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_fr`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`lietuvių`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`lietuvių` (
  `id_lt` INT(11) NOT NULL AUTO_INCREMENT,
  `Žodis` VARCHAR(45) NULL DEFAULT NULL,
  `Etymologija` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_lt`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`slovenčina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`slovenčina` (
  `id_sk` INT(11) NOT NULL AUTO_INCREMENT,
  `Slovo` VARCHAR(45) NULL DEFAULT NULL,
  `Etymológia` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_sk`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`slovenščina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`slovenščina` (
  `id_sl` INT(11) NOT NULL AUTO_INCREMENT,
  `Beseda` VARCHAR(45) NULL DEFAULT NULL,
  `Etymologija` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_sl`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dictionaries`.`čeština`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dictionaries`.`cestina` (
  `id_cs` INT(11) NOT NULL AUTO_INCREMENT,
  `Slovo` VARCHAR(45) NULL DEFAULT NULL,
  `Etymologie` MEDIUMTEXT NULL DEFAULT NULL,
  `Anglictina` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cs`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
