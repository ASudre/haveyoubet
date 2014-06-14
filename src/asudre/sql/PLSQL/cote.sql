DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE FUNCTION  `haveyoub`.`cote`(idMatch INT, idEquipe INT) RETURNS float
BEGIN

	DECLARE done INT DEFAULT FALSE;

	-- Somme des mises pour une autre equipe que celle passée en paramètre
	DECLARE somme_mises_contre FLOAT DEFAULT 0;

	-- Somme des mises pour l'équipe passée en paramètre
	DECLARE somme_mises_pour FLOAT DEFAULT 0;

	DECLARE somme FLOAT;
	DECLARE idEquipe_mise INT;

	DECLARE curseur CURSOR for
		select 	sum(mi.valeur),
			mi.equipe_id
		from Matchs as m, Mises as mi 
		where m.id = mi.match_id 
		and m.id = idMatch 
		group by mi.equipe_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN curseur;

		cursor_loop:LOOP
			FETCH curseur INTO somme, idEquipe_mise;
			
			IF done THEN
 	              		LEAVE cursor_loop;
                	END IF;

			IF idEquipe_mise = idEquipe THEN
				SET somme_mises_pour = somme;
			ELSE
				SET somme_mises_contre = somme_mises_contre + somme;
			END IF;
		END LOOP cursor_loop;

	CLOSE curseur;

	-- ajouter test division par zéro
	IF somme_mises_pour != 0 THEN
		RETURN somme_mises_contre / somme_mises_pour + 1;
	ELSE
		RETURN 0;
	END IF;

END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;