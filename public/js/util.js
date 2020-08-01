function changeCamelToUnderline(str) {
	return str.replace(/([A-Z])/g, "_$1").toLowerCase();
}

function changeArrayKeyToUnderline(arr) {
	var rtn = {};

	for (i in arr) {
		rtn[changeCamelToUnderline(i)] = arr[i];
	}

	return rtn;
}