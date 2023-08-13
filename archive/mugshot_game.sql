CREATE DATABASE mugshot_game;

CREATE TABLE sources (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    source_id VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255),
    state VARCHAR(255),
    state_full VARCHAR(255),
    has_mugshots BOOLEAN
);

CREATE TABLE individuals (
    name VARCHAR(255),
    mugshot VARCHAR(255),
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    source_id INT NOT NULL,
    county_state VARCHAR(100),
    book_date VARCHAR(255),
    book_date_formatted VARCHAR(255),
    more_info_url VARCHAR(255),
    FOREIGN KEY (source_id) REFERENCES sources(id)
);

CREATE TABLE arrests (
    name VARCHAR(255),
    mugshot VARCHAR(255),
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    book_date VARCHAR(255),
    book_date_formatted VARCHAR(255),
    more_info_url VARCHAR(255)
);

CREATE TABLE charges (
    individual_id INT NULL, 
    arrest_id INT NULL, 
    charge VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);

CREATE TABLE details (
    individual_id INT, 
    arrest_id INT,
    description VARCHAR(255),
    FOREIGN KEY (individual_id) REFERENCES individuals(id),
    FOREIGN KEY (arrest_id) REFERENCES arrests(id)
);