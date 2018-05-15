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
CALL restore_price(input_game_id);
    UPDATE games
    SET price = price - (price * amount), discount_amount = amount
    WHERE game_id = input_game_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE restore_price (
    IN input_game_id INT
    )
    BEGIN
        UPDATE games
        SET price = price / (1 - discount_amount), discount_amount = 0
        WHERE game_id=input_game_id;
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
DELIMITER ;



DELIMITER $$
CREATE TRIGGER discussion_insert
AFTER INSERT ON games
FOR EACH ROW BEGIN
    INSERT INTO discussions (discussion_name,game_id) VALUES
    ('Technical', NEW.game_id),
    ('General', NEW.game_id),
    ('Gameplay', NEW.game_id),
    ('Guides', NEW.game_id);
END $$
DELIMITER ;



DELIMITER $$
CREATE TRIGGER discussion_insert
AFTER INSERT ON banned_users
FOR EACH ROW BEGIN
    UPDATE comments c, posts p
    SET c.text = "DELETED BY ADMIN"
    WHERE c.comment_id = p.comment_id
        AND p.user_id = NEW.banned_user_id;
    UPDATE comments c, replies r
    SET c.text = "DELETED BY ADMIN"
    WHERE c.comment_id = r.comment_id
        AND r.user_id = NEW.banned_user_id;
END $$
DELIMITER ;



SELECT f.friend_name, g.game_name
FROM (SELECT user_id1 as friend_id, user_name as friend_name
								FROM friends f, users u
								WHERE user_id2=1 AND user_id1=u.user_id
								UNION
								SELECT user_id2 as friend, user_name as friend_name
								FROM friends f, users u
								WHERE user_id1=1 AND user_id2=u.user_id
) AS f, games g, plays p
WHERE p.user_id = f.friend_id AND p.game_id = g.game_id AND p.end_date is NULL;

SELECT DISTINCT f.friend_name
FROM (SELECT user_id1 as friend_id, user_name as friend_name
								FROM friends f, users u
								WHERE user_id2=1 AND user_id1=u.user_id
								UNION
								SELECT user_id2 as friend, user_name as friend_name
								FROM friends f, users u
								WHERE user_id1=1 AND user_id2=u.user_id
) AS f, online_users AS ou
WHERE  f.friend_id NOT IN (SELECT user_id FROM online_users);

SELECT DISTINCT f.friend_name
FROM (SELECT user_id1 as friend_id, user_name as friend_name
								FROM friends f, users u
								WHERE user_id2=1 AND user_id1=u.user_id
								UNION
								SELECT user_id2 as friend, user_name as friend_name
								FROM friends f, users u
								WHERE user_id1=1 AND user_id2=u.user_id
) AS f,plays as p, online_users AS ou
WHERE  f.friend_id IN (SELECT user_id FROM online_users) AND p.user_id = f.friend_id
AND p.end_date IS NOT NULL;
