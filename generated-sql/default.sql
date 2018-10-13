
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- pelicula
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pelicula`;

CREATE TABLE `pelicula`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(150) NOT NULL,
    `ano` DATE NOT NULL,
    `sinopsis` TEXT NOT NULL,
    `trailer` VARCHAR(250),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- genero
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `genero`;

CREATE TABLE `genero`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- productor
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `productor`;

CREATE TABLE `productor`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- actor
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `actor`;

CREATE TABLE `actor`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(200) NOT NULL,
    `apellido` VARCHAR(200) NOT NULL,
    `edad` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pelicula_genero
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pelicula_genero`;

CREATE TABLE `pelicula_genero`
(
    `pelicula_id` INTEGER NOT NULL,
    `genero_id` INTEGER NOT NULL,
    PRIMARY KEY (`pelicula_id`,`genero_id`),
    INDEX `pelicula_genero_fi_e45ec9` (`genero_id`),
    CONSTRAINT `pelicula_genero_fk_151ac6`
        FOREIGN KEY (`pelicula_id`)
        REFERENCES `pelicula` (`id`),
    CONSTRAINT `pelicula_genero_fk_e45ec9`
        FOREIGN KEY (`genero_id`)
        REFERENCES `genero` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pelicula_productor
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pelicula_productor`;

CREATE TABLE `pelicula_productor`
(
    `pelicula_id` INTEGER NOT NULL,
    `productor_id` INTEGER NOT NULL,
    PRIMARY KEY (`pelicula_id`,`productor_id`),
    INDEX `pelicula_productor_fi_d79612` (`productor_id`),
    CONSTRAINT `pelicula_productor_fk_151ac6`
        FOREIGN KEY (`pelicula_id`)
        REFERENCES `pelicula` (`id`),
    CONSTRAINT `pelicula_productor_fk_d79612`
        FOREIGN KEY (`productor_id`)
        REFERENCES `productor` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pelicula_actor
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pelicula_actor`;

CREATE TABLE `pelicula_actor`
(
    `pelicula_id` INTEGER NOT NULL,
    `actor_id` INTEGER NOT NULL,
    PRIMARY KEY (`pelicula_id`,`actor_id`),
    INDEX `pelicula_actor_fi_a575e2` (`actor_id`),
    CONSTRAINT `pelicula_actor_fk_151ac6`
        FOREIGN KEY (`pelicula_id`)
        REFERENCES `pelicula` (`id`),
    CONSTRAINT `pelicula_actor_fk_a575e2`
        FOREIGN KEY (`actor_id`)
        REFERENCES `actor` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
