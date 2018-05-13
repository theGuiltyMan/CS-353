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
        INSERT INTO comments(text) VALUES (input_text);
        SET id = LAST_INSERT_ID();
        IF (type = 'P' OR type = 'p') THEN
            INSERT INTO posts(user_id,comment_id,title,discussion_id) VALUES (user_id,id,input_title,discussion_id);
        ELSE INSERT INTO replies(user_id,comment_id,parent_id,post_id) VALUES (user_id,id,parent_id,post_id);
        END IF;
    END $$
DELIMITER;

DELIMITER $$
CREATE PROCEDURE activate_discount (
    IN input_game_id VARCHAR(30),
    IN amount DECIMAL(2,2)
)
BEGIN
    UPDATE games
    SET price = price - (price * amount), discount_amount = amount
    WHERE game_id = input_game_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE restore_price (
    IN game_id INT
    )
    BEGIN
        UPDATE games
        SET price = price / (1 - discount_amount), discount_amount = 0;
    END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER purchase_verification
AFTER INSERT ON buy
FOR EACH ROW BEGIN
	INSERT INTO library (
		user_id, game_id
	) VALUES(
		NEW.user_id, NEW.game_id
	);
    UPDATE users SET balance = balance - NEW.price WHERE user_id=NEW.user_id;
END $$
DELIMETER ;