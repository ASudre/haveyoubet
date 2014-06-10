DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE PROCEDURE  `haveyoub`.`majCagnottes`(IN idEquipe1 INT, IN idEquipe2 INT, IN idMatch INT, IN scoreEquipe1 INT, IN scoreEquipe2 INT)
BEGIN

	DECLARE done INT DEFAULT FALSE;
	DECLARE idEquipe INT;
	DECLARE idHistorique INT;
	DECLARE cote FLOAT;
	DECLARE cagnotte FLOAT;
	DECLARE sommeMisesEq1 FLOAT;
	DECLARE sommeMisesEq2 FLOAT;
	DECLARE sommeMisesNul FLOAT;
	DECLARE idJoueur INT;
	DECLARE ancienGain FLOAT;
	DECLARE nouveauGain FLOAT DEFAULT 0;

	/* Récupération des utilisateurs ainsi que de leur mise pour le match et la mise en historique */
	DECLARE curseur CURSOR for
	select ut.cagnotte, ut.id, h.id,
		gain,
		(select IFNULL(sum(IFNULL(valeur, 0)), 0) from mises where mises.utilisateur_id = ut.id and mises.match_id = idMatch and mises.equipe_id = idEquipe1) as sommeMisesEq1,
		(select IFNULL(sum(IFNULL(valeur, 0)), 0) from mises where mises.utilisateur_id = ut.id and mises.match_id = idMatch and mises.equipe_id = idEquipe2) as sommeMisesEq2,
		(select IFNULL(sum(IFNULL(valeur, 0)), 0) from mises where mises.utilisateur_id = ut.id and mises.match_id = idMatch and mises.equipe_id = 204) as sommeMisesNul
	from utilisateur as ut
		left join mises as mi
			on ut.id = mi.utilisateur_id and
			mi.match_id = idMatch
		left join historique as h
			on ut.id = h.utilisateur_id and 
			h.match_id = idMatch
	group by ut.id;

	/* Utilisé pour arrêter le curseur */
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;	

	/* Récupération des équipes et du vainqueur du match */
	select case when scoreEquipe1 > scoreEquipe2 then m.equipe1_id when scoreEquipe1 < scoreEquipe2 then m.equipe2_id else 204 end as idEquipe,
		(select cote(idMatch, idEquipe)) 
	into idEquipe, cote
	from matchs as m
	where m.id = idMatch;

	/* insertion des scores */
	update matchs set scoreEq1 = scoreEquipe1, scoreEq2 = scoreEquipe2 where matchs.id = idMatch;

	OPEN curseur;
	/* Boucle sur les utilisateurs */
	cursor_loop: LOOP
		FETCH curseur INTO cagnotte, idJoueur, idHistorique, ancienGain, sommeMisesEq1, sommeMisesEq2, sommeMisesNul;

		IF done THEN
            LEAVE cursor_loop;
        END IF;

		/* C'est la première fois que l'administrateur valide le score de ce match */
		IF ancienGain IS NULL THEN
			SET ancienGain = 0;
		END IF;
		
		-- Si le joueur a misé
		IF idEquipe1 = idEquipe THEN
			SET nouveauGain = sommeMisesEq1 * (cote - 1) - (sommeMisesNul + sommeMisesEq2);
		ELSE
			IF idEquipe2 = idEquipe THEN
				SET nouveauGain = sommeMisesEq2 * (cote - 1) - (sommeMisesNul + sommeMisesEq1);
			ELSE
				SET nouveauGain = sommeMisesNul * (cote - 1) - (sommeMisesEq1 + sommeMisesEq2);
			END IF;
		END IF;
		
		update utilisateur set 
			utilisateur.cagnotte = cagnotte + nouveauGain - ancienGain
		where utilisateur.id = idJoueur;
		
		IF idHistorique IS NULL THEN
			insert into historique(match_id, utilisateur_id, gain) 
			values (idMatch, idJoueur, IFNULL(nouveauGain, 0));
		ELSE
			UPDATE historique SET gain = IFNULL(nouveauGain, 0) WHERE id = idHistorique;
		END IF;

	END LOOP cursor_loop;

	CLOSE curseur;

END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;