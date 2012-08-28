var largedataset = {},
	size = 10000;
largedataset.results = new Array(size);
var randomStrings = new Array(size);
for (var i=0; i < size; i++) {
	randomStrings[i] = getRandomString(4,10);
}
randomStrings.sort();

for (var i=0; i < size; i++) {
	largedataset.results[i] = {};
	largedataset.results[i].id = i+'';
	largedataset.results[i].name = randomStrings[i];
}

largedataset.total = size;

function getRandomString(minLength, maxLength) {
	var randomLength = minLength + Math.floor(Math.random() * (maxLength+1-minLength));

	var	charArray = new Array(randomLength),
		alphabet = "eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeetttttttttttttttttttttttttttttt" +
				   "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaoooooooooooooooooooooooonnnnnnnnnnnnnnnnnnnn" +
				   "rrrrrrrrrrrrrrrriiiiiiiiiiiiiiiisssssssssssssssshhhhhhhhhhhhhhhhdddddddddddd" +
				   "llllllllllllffffffccccccmmmmmmuuuuuuggggggyyyyyyppppppwwwwwwbbbbbvvvvkkkxjqz";
	
    for (var i=0; i < randomLength; i++) {
		var randomAlphabetIndex = Math.floor(Math.random() * 300) + 1,
			randomLetter = alphabet.charAt(randomAlphabetIndex);
		if (i===0) randomLetter = randomLetter.toUpperCase();
		charArray[i] = randomLetter;
	}

    return charArray.join('');
}