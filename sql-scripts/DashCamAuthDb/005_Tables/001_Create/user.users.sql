/*create database authentication;*/


use Authentication;

CREATE TABLE IF NOT EXISTS users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NUll,
    role_id  INT NULL ,
    email                   VARCHAR(200)     NOT NULL unique ,
    first_name              VARCHAR(200)         NULL,
    last_name               VARCHAR(200)         NULL,
    mobile_phone            VARCHAR(200)         NULL unique ,
    password_hash           VARCHAR(100)     NOT NULL,
    create_datetime_utc     TIMESTAMP    default now(),
    update_datetime_utc     TIMESTAMP    default now(),
    FOREIGN KEY (user_id) REFERENCES  Authentication.refresh_tokens (id)
)  ENGINE=INNODB;
CREATE INDEX user_id on users(user_id);
CREATE INDEX role_id on users(role_id);
