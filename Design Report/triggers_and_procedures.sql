DELIMITER $$
CREATE PROCEDURE insert_comment (
    IN user_id INT,
    IN input_title VARCHAR(255),
    IN input_text LONGTEXT,
    IN type VARCHAR(1),
    IN discussion_id INT,
    IN parent_id INT,
    IN post_id INT
    )
    BEGIN
        DECLARE id INT;
        INSERT INTO comments(user_id, text) VALUES (user_id, input_text);
        SET id = LAST_INSERT_ID();
        IF (type = 'P' OR type = 'p') THEN
            INSERT INTO posts(comment_id,title,discussion_id) VALUES (id,input_title,discussion_id);
        ELSE INSERT INTO replies(comment_id,parent_id,post_id) VALUES (id,parent_id,post_id);
        END IF;
    END;
$$