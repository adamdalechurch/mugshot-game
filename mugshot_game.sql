CREATE DATABASE mugshot_game;

CREATE TABLE sources (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    source_id VARCHAR(255),
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
    individual_id INT, 
    arrest_id INT, 
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



'''
sources:
source_id - A unique string id for the source.
name - The name of the source.
state - The state (abbreviated) the source is located in.
state_full - The state (full name) the source is located in.
has_mugshots - A boolean value stating if mugshots are available for this source.

individuals:
name - The name of the individual.
mugshot - The image url of mugshot.
id - A unique string id for the record.
source_id - The id of the source.
source - The name of the source.
county_state - The county and state of the booking.
book_date - Book Date string in YYYY-MM-DD format.
book_date_formatted - Book Date string in MMM DD, YYYY format.
more_info_url - The url on JailBase.com to get more info.

details:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
description - The description of the detail.

charges:
individual_id - The id of the individual. (nullable fk)
arrest_id - The id of the arrest. (nullable fk)
charge - The charge.

arrests:
name - The name of the individual.
mugshot - The image url of mugshot.
id - A unique string id for the record.
book_date - Book Date string in YYYY-MM-DD format.
book_date_formatted - Book Date string in MMM DD, YYYY format.
more_info_url - The url on JailBase.com to get more info.
'''
