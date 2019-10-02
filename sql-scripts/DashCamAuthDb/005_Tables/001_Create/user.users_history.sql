CREATE TABLE IF NOT EXISTS users_history
(
    id                          int           NOT NULL PRIMARY KEY,
    role_id                     INT              NOT NULL,
    email                       VARCHAR(200)     NOT NULL,
    first_name                  VARCHAR(200)         NULL,
    last_name                   VARCHAR(200)         NULL,
    mobile_phone                VARCHAR(200)         NULL,
    password_hash               VARCHAR(100)     NOT NULL,
    
    create_history_datetime_utc TIMESTAMP    default now()
)  ENGINE=MyISAM;