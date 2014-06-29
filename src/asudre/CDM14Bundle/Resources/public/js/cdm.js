function loading() {
	$('input[name="bouton"]').hide();
	$('img').show();
}

function verifChamps(scoreEquipe1, scoreEquipe2) {
	if(scoreEquipe1 != parseInt(scoreEquipe1) || scoreEquipe2 != parseInt(scoreEquipe2) || parseInt(scoreEquipe1) < 0 || parseInt(scoreEquipe2) < 0) {
		displayMsg("Veuillez entrer une valeur entière positive dans chacun des champs.");
		return -1;
	}
	return 0;
}

function mise(idMatch, msgConfirmation) {
	
	if($("select[name=select" + idMatch + "]").val() == "" || $("input[name=mise" + idMatch + "]").val() == "") {
		displayMsg("Veuillez entrer remplir correctement les deux champs.");
		return;
	}
	
	$('span#boutons' + idMatch).hide().after('<img id="imgLoading" name="imgLoading' + idMatch + '" src="/web/bundles/asudrecdm14/images/ajax-loader.gif" />');
	
	if(!confirm(msgConfirmation)) {
		displayMsg("Annulé.");
		$('img[name="imgLoading' + idMatch + '"]').remove();
		$('span#boutons' + idMatch).show();
		return;
	}

	var param = "match=" + idMatch;
	param += "&select=" + $("select[name=select" + idMatch + "]").val();
	param += "&mise=" + $("input[name=mise" + idMatch + "]").val();
//	param += "&nbMises=" + $("span#ontMise" + idMatch + " span").text();
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_mise'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){
			$('img[name="imgLoading' + idMatch + '"]').remove();
			$('span#boutons' + idMatch).show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				$('div[id="indic' + idMatch + '"]').css('backgroundColor', '#00f000');
				
				$('span#coteEq1.' + idMatch).text($(xml).find('equipe1').text());
				$('span#coteNul.' + idMatch).text($(xml).find('nul').text());
				$('span#coteEq2.' + idMatch).text($(xml).find('equipe2').text());
				
				// On permet d'afficher les paris s'il s'agit du premier
				if(!$('td#points.lignesMises[char="' + idMatch + '"] div').hasClass('white')) {
					$('td#points.lignesMises[char="' + idMatch + '"] div').addClass('white');
					$('td#points.lignesMises[char="' + idMatch + '"] div').click(function(){ afficherMises(idMatch, false); });
				}
				
				var equipe = $(xml).find('equipe').text();
				var valeur = $(xml).find('valeur').text();
				var date = $(xml).find('date').text();
				
				// Affichage de la nouvelle mise dans le tableau de mises
				$('tr.titreMises' + idMatch).after("" +
						"<tr>" +
						"	<td>" + equipe + "</td>" +
						"	<td>" + valeur + "</td>" +
						"	<td>" + date + "</td>" +
						"</tr>");

				afficherMises(idMatch, true);
				
				$('span#cagnotte').text($(xml).find('cagnotte').text());

				displayMsg($(xml).find('messageInfo').text());
			}		
		}
	});
}

/**
 * Affiche ou cache les mises du joueur
 * @param idMatch
 */
function afficherMises(idMatch, forcerAffichage) {
	if(forcerAffichage || $('tr.mises.' + idMatch).is( ":hidden" )) {
		$('tr.mises').hide();
		$('tr.mises.' + idMatch).fadeIn( "slow" );
	}
	else {
		$('tr.mises').hide();
	}
}

function annuleMise(idMatch) {
	
	$('span#boutons' + idMatch).hide().after('<img id="imgLoading" name="imgLoading' + idMatch + '" src="/image/ajax-loader.gif/" />');
	
	var param = "match=" + idMatch;
	param += "&nbMises=" + $("span#ontMise" + idMatch + " span").text();
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_score'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){

			$('img[name="imgLoading' + idMatch + '"]').remove();
			$('span#boutons' + idMatch).show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				
				$('span#coteEq1').text($(xml).find('equipe1').text());
				$('span#coteNul').text($(xml).find('nul').text());
				$('span#coteEq2').text($(xml).find('equipe2').text());
				
				if($(xml).find('nbMises').text() != "") {
					$('span#ontMise' + idMatch).text($(xml).find('nbMises').text());
				}
				
				$('select[name=mise' + idMatch + '] option:first').attr('selected','selected');
				$('select[name=mise' + idMatch + '] option:selected').val("");
				$('select[name=mise' + idMatch + '] option:selected').text("");
				
				$('select[name=select' + idMatch + '] option:first').attr('selected','selected');
				$('select[name=select' + idMatch + '] option:selected').val("");
				$('select[name=select' + idMatch + '] option:selected').text("");
				
				$('span[id="gain' + idMatch + '"]').text($(xml).find('message').text());	
				
				$('span#cagnotte').text($(xml).find('cagnotte').text());
		
				$('div[id="indic' + idMatch + '"]').css('backgroundColor', 'gray');

				displayMsg($(xml).find('messageInfo').text());
			}
				
		}
	});
}

function majNbJokers(nbJokers) {
	if(parseInt(nbJokers) < 0) {
		nbJokers = "Elimine";
	}
	
	$('span#joker').text(nbJokers);
}

function score(idMatch) {
	
	if(verifChamps($("#scoreEq1-" + idMatch).val(), $("#scoreEq2-" + idMatch).val()) == -1) {
		return;
	}
	
	$('span#boutons' + idMatch).hide().after('<img id="imgLoading" name="imgLoading' + idMatch + '" src="/web/bundles/asudrecdm14/images/ajax-loader.gif" />');
	
	var param = "idMatch=" + idMatch;
	param += "&scoreEquipe1=" + $("#scoreEq1-" + idMatch).val();
	param += "&scoreEquipe2=" + $("#scoreEq2-" + idMatch).val();
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_score'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){
			
			$('img[name="imgLoading' + idMatch + '"]').remove();
			$('span#boutons' + idMatch).show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				$('span#cagnotte').text($(xml).find('cagnotte').text());
				displayMsg($(xml).find('messageInfo').text());
			}
		}
	});
}

function creationGroupe() {
	
	var nomGroupe = $("#nomGroupe").val();
	
	if($("#nomGroupe").val().length <= 0) {
		displayMsg("Veuillez entrer un nom de groupe.");
	}
	
	$('span#boutonGroupe').hide().after('<img id="imgLoading" name="imgLoadingGroupe" src="/web/bundles/asudrecdm14/images/ajax-loader.gif" />');
	
	var param = "nomGroupe=" + nomGroupe;
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_groupe'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){
			
			$('img[name="imgLoadingGroupe"]').remove();
			$('span#boutonGroupe').show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				displayMsg($(xml).find('messageInfo').text());
				
				var date = $(xml).find('date').text();
				var idGroupe = $(xml).find('idGroupe').text();
				
				// Affichage du nouveau groupe
				$('tr[type="initGroupes"]').after("" +
						"<tr>" +
						"	<td>" + nomGroupe + "</td>" +
						"	<td>" + date + "</td>" +
						"	<td></td>" +
						"</tr>");
				
				// Ajout le groupe dans la liste des invitations
				$('select#groupe').prepend("<option value='" + idGroupe + "' selected>" + nomGroupe + "</option>");
			}
		}
	});	
}

function inviter() {
	
	var courriels = $('#courriels').val();
	var idGroupe = $('select#groupe').val();
	var langue = $('#langue').val();
	
	if(courriels.length <= 0 && idGroupe != parseInt(idGroupe)) {
		displayMsg("Veuillez entrer remplir correctement les deux champs.");
	}
	
	$('span#boutonInvitation').hide().after('<img id="imgLoading" name="imgLoadingInvitation" src="/web/bundles/asudrecdm14/images/ajax-loader.gif" />');
	
	var param = "courriels=" + courriels;
	param += "&idGroupe=" + idGroupe;
	param += "&langue=" + langue;
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_invitation'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){

			$('img[name="imgLoadingInvitation"]').remove();
			$('span#boutonInvitation').show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				displayMsg($(xml).find('messageInfo').text());
				
				var langue = $(xml).find('langue').text();
				var date = $(xml).find('date').text();
				var courriels = $(xml).find('courriel');
				var nomGroupe = $(xml).find('nomGroupe').text();
				
				for (var i = 0; i < courriels.length; i++) {
					// Affichage de la nouvelle invitation
					$('tr[type="initInvitations"]').after("" +
							"<tr>" +
							"	<td>" + $(courriels[i]).text() + "</td>" +
							"	<td>" + nomGroupe + "</td>" +
							"	<td></td>" +
							"	<td>" + date + "</td>" +
							"	<td></td>" +
							"	<td>" + langue + "</td>" +
							"	<td></td>" +
					"</tr>");
				}
			}
		}
	});	
}

function ajouterJoueur() {
	
	var pseudonyme = $('#pseudonyme').val();
	var idGroupe = $('#selectGroupe option:selected').val();
	
	if(pseudonyme.length <= 0 && idGroupe != parseInt(idGroupe)) {
		displayMsg("Veuillez entrer remplir correctement les deux champs.");
		return;
	}
	
	$('span#boutonAjoutJoueur').hide().after('<img id="imgLoading" name="imgLoadingAjoutJoueur" src="/web/bundles/asudrecdm14/images/ajax-loader.gif" />');
	
	var param = "pseudonyme=" + pseudonyme;
	param += "&idGroupe=" + idGroupe;
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_ajout_joueur'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){

			$('img[name="imgLoadingAjoutJoueur"]').remove();
			$('span#boutonAjoutJoueur').show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				displayMsg($(xml).find('messageInfo').text());
				
				var pseudonyme = $(xml).find('pseudonyme').text();
				var nomGroupe = $(xml).find('nomGroupe').text();
				
				// Affichage de la nouvelle invitation
				$('tr[type="initAjoutJoueur"]').after("" +
						"<tr>" +
						"	<td>" + nomGroupe + "</td>" +
						"	<td>" + pseudonyme + "</td>" +
						"	<td></td>" +
				"</tr>");

			}
			
			$('div#contenu').scrollTop($('div#contenu').prop('scrollHeight'));
		}
	});
}

function displayMsg(msg) {
	$("#msg").fadeIn();
	$("#msg p").text(msg);
	$("#msg").delay(3000).fadeOut();
}

function menuAffiche(vue) {
	$('table#menu td a').css('backgroundColor', 'white');
	$(vue).children().css('backgroundColor', '#dbdbdb');
	$('table#menu td a').hover(
	  function () {
		  $(this).css('backgroundColor', '#e5e5e5');
	  },
	  function () {
		  $(this).css('backgroundColor', 'white');
	  }
	);
	$(vue).children().hover(
	  function () {
		  $(this).css('backgroundColor', '#e5e5e5');
	  },
	  function () {
		  $(this).css('backgroundColor', '#dbdbdb');
	  }
	);
}

/**
 * Affichage de l'information de chargement
 */
function chargement(menu) {
	
	// Affichage du bandeau chargement
	$("#msgChargement").fadeIn();

	menuAffiche(menu);

}

/**
 * Suppression de l'information de chargement
 */
function finChargement() {
	$("#msgChargement").fadeOut();
}

function afficheInfosMatch(idMatch){
	$('tr[id="cote' + idMatch + '"]').toggle();
}

function soumetClassementJoueurs() {
	chargement(classementJoueurs);
	$('form#formClassementJoueurs').submit();
}

function chargeGroupe() {
	var idGroupe = $('#selectGroupe option:selected').val();
	
	if(idGroupe.length <= 0 || idGroupe != parseInt(idGroupe)) {
		displayMsg("Veuillez entrer remplir correctement les deux champs.");
		return;
	}
	
	var param = "idGroupe=" + idGroupe;
	
	$.ajax({
		type: "POST",
		url: Routing.generate('asudre_joueurs_groupe'),
		dataType: "xml",
		data: param,
		success: 
		function(xml){

			$('img[name="imgLoadingAjoutJoueur"]').remove();
			$('span#boutonAjoutJoueur').show();
			
			if($(xml).find('msgErreur').length != 0) {
				displayMsg($(xml).find('msgErreur').text());
			}
			else {
				displayMsg($(xml).find('messageInfo').text());
				
				var groupe = $(xml).find('groupe').text();
				var joueurs = $(xml).find('joueur');
				
				$('.joueursGroupe').remove();
				
				for (var i = 0; i < joueurs.length; i++) {
					// Affichage de la nouvelle invitation
					$('tr[type="initAjoutJoueur"]').after("" +
							"<tr class=\"joueursGroupe\" >" +
							"	<td></td>" +
							"	<td>" + $(joueurs[i]).text() + "</td>" +
							"	<td>" +
//							"		<span id=\"boutonSupprimerJoueur\">" +
//							"			<div onClick=\"supprimerJoueur();\" class=\"buttonAnnuler red\">" +
//							"				X" +
//							"			</div>" +
//							"		</span>" +
							"	</td>" +
							"</tr>");
				}
				
				$('div#contenu').scrollTop($('div#contenu').prop('scrollHeight'));
				
			}
		}
	});	
}

$( document ).ready(function() {
	finChargement();
});