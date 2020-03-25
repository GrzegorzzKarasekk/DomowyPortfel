$(document).ready(function(){

    $('#category').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var categoryId = $('#category').val();
        var amount = $('input#amount').val();
    
        $('#expenseInfo').load('/expense/showStatusSelectedExpense', {
            categoryId: categoryId,
            amount: amount
        });
    });

    $('input#amount').keyup(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var categoryId = $('#category').val();
        var amount = $('input#amount').val();
    
        $('#expenseInfo').load('/expense/showStatusSelectedExpense', {
            categoryId: categoryId,
            amount: amount
        });
    })
});