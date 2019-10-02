CREATE TABLE IF NOT EXISTS refresh_tokens
(
    id int auto_increment PRIMARY KEY,
    token_key               VARCHAR(100)     NOT NULL unique ,
    create_datetime_utc    TIMESTAMP    default now()
)