CREATE TABLE `departments` (`id` INTEGER CONSTRAINT `department_id_pk` PRIMARY KEY AUTOINCREMENT, `name` TEXT NOT NULL);
CREATE TABLE `roles` (`id` INTEGER CONSTRAINT `role_id_pk` PRIMARY KEY AUTOINCREMENT, `name` TEXT NOT NULL);
CREATE TABLE `users` (
    `id` INTEGER NOT NULL CONSTRAINT `user_id_pk` PRIMARY KEY AUTOINCREMENT,
    `username` TEXT(255) NOT NULL,
    `password` TEXT NOT NULL,
    `role_id` INTEGER NOT NULL CONSTRAINT `role_id_fk` REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `firstname` TEXT NOT NULL,
    `lastname` TEXT NOT NULL,
    `othernames` TEXT DEFAULT 'None',
    `status` INTEGER DEFAULT '2',
    `email` TEXT,
    `phone` TEXT,
    `office` INTEGER NOT NULL CONSTRAINT `office_fk` REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `last_login_time` TEXT,
    `is_admin` INTEGER,
    CONSTRAINT `username_uk` UNIQUE (username)
);

CREATE INDEX user_email_idx ON users(email);

CREATE VIEW `users_view` AS SELECT
    `users`.`id`, `username`, `password`, `firstname`, `lastname`, `othernames`,
    `email`, `roles`.`name` as `role`
FROM `users` JOIN `roles` ON `role_id` = `roles`.`id`;
