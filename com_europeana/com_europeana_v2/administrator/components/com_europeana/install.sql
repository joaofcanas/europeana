CREATE TABLE IF NOT EXISTS #__europeana_files(
    id INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    user_id INT( 10 ) NOT NULL DEFAULT 0,
    filename VARCHAR( 255 ) NOT NULL DEFAULT  'xml-file',
    deleted ENUM(  '0',  '1' ) NOT NULL DEFAULT  '0',
    datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);