CREATE TABLE `qz_queue`(
    `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
    `tag` varchar(32) NOT NULL DEFAULT 'default',
    `utc_run` DATETIME NOT NULL,
    `run_script` varchar(255),
    `params` text,
    `priority` SMALLINT NOT NULL DEFAULT 3,
    `retry` SMALLINT NOT NULL DEFAULT 0,
    `utc_created` DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    KEY `ix_tag` (`tag`, `utc_run`, `retry`)
);

CREATE TABLE `qz_queue_running`(
    `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
    `uuid` CHAR(32) NOT NULL,
    `queue_id` INT UNSIGNED NOT NULL,
    `tag` varchar(32) NOT NULL DEFAULT 'default',
    `utc_run` DATETIME NOT NULL,
    `run_script` varchar(255),
    `params` text,
    `priority` SMALLINT NOT NULL DEFAULT 3,
    `retry` SMALLINT NOT NULL DEFAULT 0,
    `queue_utc_created` DATETIME NOT NULL,
    `utc_created` DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    KEY `ix_tag` (`uuid`, `tag`, `utc_run`, `retry`)
);