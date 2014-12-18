// $(function () {
// 	$('#checkall').click(function () {
// 		$(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', this.checked);
// 	});
// });
//make sure to implement this

$(function()
{
	$("a.sound").click(function(e)
	{
		e.preventDefault(); 
		$(this).next('audio').get(0).play();
	});
});

$(function(){reduce("Consonants"); reduce("Vowels");});

$(function()
{
	$("button").click(function(e)
	{
		e.preventDefault(); 
		chart = $("#"+$(this).html());
		mode = chart.css("display");
		if (mode == "none") 
		{
			$("#coarticulations").css("display", "none");
			$("#diacritics").css("display", "none");
			$("#suprasegmentals").css("display", "none");
			$("#tones").css("display", "none");
			chart.css("display", "block");
		}
		if (mode == "block") {chart.css("display", "none");}
	});
});


function copyToClipboard(text) {prompt("Copy to clipboard: Ctrl+C, Enter", text)}

function clearcell(chart, x, y, bool) {$('#'+chart)[0].rows[x].deleteCell(y); if (bool) {$('#'+chart)[0].rows[x].insertCell(y)}}

function totoggle(input) 
{
	if (input.style.color == 'red') {input.style.color = 'black';} else {input.style.color = 'red';}
	if (input.parentNode.childNodes[0] instanceof HTMLInputElement) {input.parentNode.childNodes[0].click();}
	if (input.parentNode.childNodes[1] instanceof HTMLInputElement) {input.parentNode.childNodes[1].click();}
}

function display(item)
{
	if (item.id) 
	{$("#display").html(item.id+" "+item.title)}
	else {$("#display").html(item)}
}


function toggle(chart, input, dimension)
{
	if (dimension == 1) 
	{
		var index=(2*input.parentNode.cellIndex)-1
		var rows=$('#'+chart)[0].rows.length
		if (input.checked == false)	{for (var i = 1; i < rows; i++)
			{$('#'+chart)[0].rows[i].cells[index].style.visibility='hidden'
			$('#'+chart)[0].rows[i].cells[index+1].style.visibility='hidden'}}
		else {for (var i = 1; i < rows; i++)
			{$('#'+chart)[0].rows[i].cells[index].style.visibility='visible'
			$('#'+chart)[0].rows[i].cells[index+1].style.visibility='visible'}}
	}
	if (dimension == 2) 
	{
		var index=input.parentNode.parentNode.rowIndex
		var cols=$('#'+chart)[0].rows[2].childNodes.length
		if (input.checked == false) {for (var i=1; i<cols; i++) {$('#'+chart)[0].rows[index].cells[i].style.visibility='hidden';}}
		else {for (var i=1; i<cols; i++) {$('#'+chart)[0].rows[index].cells[i].style.visibility='visible';}}
	}
	if (dimension == 3) 
	{
		var index=input.value
		var rows=$('#'+chart)[0].rows.length
		var cols=$('#'+chart)[0].rows[2].childNodes.length
		if (input.checked == false) {for (var i=1; i<rows; i++)
			{for (var j=index; j<cols; j++)
				{$('#'+chart)[0].rows[i].cells[j].style.visibility='hidden'; j++}}}
		else {for (var i=1; i<rows; i++)
			{for (var j=index; j<cols; j++)
				{$('#'+chart)[0].rows[i].cells[j].style.visibility='visible'; j++}}}
	}
}

