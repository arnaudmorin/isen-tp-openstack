
function testReponse(name){
	// Récupere l'élément
	var obj = document.getElementById(name);
	// Sa valeur
	var value = obj.value;
	// Affiche dans la console
	console.log('testReponse : ' + name + ' / ' + value);
	
	// Test la reponse
	$.get("testReponse.php?q="+name+"&r="+value, function( data ) {
		console.log('Resultat : ' + data);
		// Si reponse bonne
		if (data == "true"){
			document.getElementById(name+"_btn").className = "btn btn-success";
		}
		else{
			document.getElementById(name+"_btn").className = "btn btn-danger";
		}
	});
}
