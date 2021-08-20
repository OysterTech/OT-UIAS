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

function parseGrade(gradeNum) {
	var grade = '';

	switch (gradeNum) {
		case 1:
			grade = '一年级';
			break;
		case 2:
			grade = '二年级';
			break;
		case 3:
			grade = '三年级';
			break;
		case 4:
			grade = '四年级';
			break;
		case 5:
			grade = '五年级';
			break;
		case 6:
			grade = '六年级';
			break;
		case 7:
			grade = '初一';
			break;
		case 8:
			grade = '初二';
			break;
		case 9:
			grade = '初三';
			break;
		case 10:
			grade = '高一';
			break;
		case 11:
			grade = '高二';
			break;
		case 12:
			grade = '高三';
			break;
		case 13:
			grade = '大一';
			break;
		case 14:
			grade = '大二';
			break;
		case 15:
			grade = '大三';
			break;
		case 16:
			grade = '大四';
			break;
	}

	return grade;
}
