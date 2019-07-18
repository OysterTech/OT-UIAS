<?php
function getFileExtension($MIME){
	$MIME_XLS="application/vnd.ms-excel";
	$MIME_XLSX="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

	if($MIME==$MIME_XLS){
		return "xls";
	}else if($MIME==$MIME_XLSX){
		return "xlsx";
	}else{
		return "";
	}
}
