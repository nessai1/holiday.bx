CREATE TABLE IF NOT EXISTS files
(
    ID int not null auto_increment,
    FILE_NAME varchar(500) not null,
    FILE_LINES int not null,
    PRIMARY KEY (ID)
);


CREATE TABLE IF NOT EXISTS file_content
(
    FILE_ID int not null,
    LINE int not null,
    CONTENT text not null,
    PRIMARY KEY (FILE_ID, LINE),
    FOREIGN KEY FK_FILE_CONTENT_FILES (FILE_ID) references files(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS file_state
(
    FILE_ID int not null,
    LINE int not null,
    LINE_STATE varchar(255),
    PRIMARY KEY (FILE_ID, LINE),
    FOREIGN KEY FK_FILE_LINE_CONTENT (FILE_ID, LINE) references file_content(FILE_ID, LINE)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS compare
(
    ID int not null auto_increment,
    FIRST_FILE int not null,
    SECOND_FILE int not null,
    COMPARE_DATE date not null,
    PRIMARY KEY (ID),
    FOREIGN KEY FK_FIRST_FILE_COMPARE_FILES (FIRST_FILE) references files(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    FOREIGN KEY FK_SECOND_FILE_COMPARE_FILES (SECOND_FILE) references files(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
);