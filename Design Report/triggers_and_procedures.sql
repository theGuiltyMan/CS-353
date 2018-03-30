DELIMITER $$
CREATE PROCEDURE insert_comment (
    IN input_title VARCHAR(255),
    IN input_text LONGTEXT,
    IN type VARCHAR(1)
    )
    BEGIN
        DECLARE id INT;
        INSERT INTO comments(text) VALUES (input_text);
        SET id = LAST_INSERT_ID();
        IF (type = 'P' OR type = 'p') THEN
            INSERT INTO posts(comment_id,title) VALUES (id,input_title);
        ELSE INSERT INTO replies(comment_id) VALUES (id);
        END IF;
    END;
$$
