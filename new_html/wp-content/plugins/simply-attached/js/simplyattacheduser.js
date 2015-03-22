function setEqualHeight(columns)
{
var tallestcolumn = 0;
columns.each(
function()
{
currentHeight = jQuery(this).height();

if(currentHeight > tallestcolumn)
{
tallestcolumn  = currentHeight;
}
}
);
columns.height(tallestcolumn);
}
jQuery(document).ready(function() {
	setEqualHeight(jQuery("#simplyattached-s4 li"));
	setEqualHeight(jQuery("#simplyattached-s5 li"));
	});