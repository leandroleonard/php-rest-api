create database `api`;

create table if not exists `users` (
    `id` int primary key, `name` varchar(255) not null, `email` varchar(255) not null unique
) engine = InnoDB default charset = utf8;