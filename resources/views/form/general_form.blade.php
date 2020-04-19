<script type="text/javascript" src="{{ URL::asset('assets/global/plugins/autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript">
var fnSetAutoNumeric =  function (obj) {
	$(obj).autoNumeric('init',{
		  aSign:'',
		  aDec:'.',
		  aSep:',',
		  mDec:'2',
		  vMin:'-99',
		  vMax:'9999999999'
	  });
}

var fnSetMoney = function(obj,val)
{
	$(obj).autoNumeric('set',val);
}

var fnTotalingBudget = function(sBody,field,fieldsetmoney)
	{
		var total_budget =0;
		sBody.find(field).each(function()
		{
			var total =  parseFloat($(this).autoNumeric('get'));
			 total_budget += total;
		});

		fnSetMoney(fieldsetmoney,total_budget);
	}
</script>