DROP TABLE IF EXISTS rate_information;
CREATE TABLE rate_information (
    id varchar(50) NOT NULL,
    num_code varchar(3) DEFAULT NULL,
    char_code varchar(3) DEFAULT NULL,
    nominal int(11) DEFAULT NULL,
    name varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Хранение информации о валюте';

DROP TABLE IF EXISTS rate_value;
CREATE TABLE rate_value (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_rate varchar(10) DEFAULT NULL,
    created_at date DEFAULT NULL,
    updated_at time DEFAULT NULL,
    value varchar(10) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY rate_value_rate_information_id_fk (id_rate),
    CONSTRAINT rate_value_rate_information_id_fk FOREIGN KEY (id_rate) REFERENCES rate_information (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Хранение значений валют';
