$(document).ready(function(){
    
  $('#categoryE').click(function(){
      $("#okName"). prop("checked", false); 
      $("#ok"). prop("checked", false);
  });  
  
  $(":checkbox#okName").change(function() {
        if(this.checked) {
            $( "#newNameEditCategoryExpense" ).replaceWith( '<input type="text" id="newNameEditCategoryExpense" name="newNameEditCategoryExpense" minlength="3" placeholder="Nowa nazwa kategorii" required></label>');
        }
        else{
            $( "#newNameEditCategoryExpense" ).replaceWith( '<input type="text" id="newNameEditCategoryExpense" style ="font-weight: 700;color:black;" placeholder="Włącz zmianę nazwy" readonly></input>');
        }
    });

    $(":checkbox#ok").change(function() {
        if(this.checked) {
            //Pozwól kasować  i edytować limity
            $('#limit').replaceWith('<input type="number" name="limit" id="limit" placeholder="Nadaj limit kategorii" step="0.01" min="0.00" required>');
            $( "#deleteLimit" ).replaceWith('<div id="deleteLimit" style="cursor:pointer; font-size=50%;"><u>Usuń limit</u></div>');
            $( "#deleteStatus" ).replaceWith('<span id ="deleteStatus"></span>');
            //Pobierz dane edytowanej kategorii
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
        
            var categoryId = $('#categoryE').val();           
            //limit kategorii
            $('#categoryLimit').load('/settings/showCategoryLimit', {
                categoryId: categoryId
            });      
            
            //kasuj limit
            $('#deleteLimit').click(function(){
                var categoryId = $('#categoryE').val();
          
                $('#deleteStatus').text('Usuwanie...');
                //console.log(categoryId);
                $.post('/settings/deleteLimit',{
                  categoryId: categoryId
                },function(data){
                  $('#deleteStatus').text('Usunięto limit dla kategorii !!!'),
                  $("#ok"). prop("checked", false),
                  $( "#limit" ).replaceWith( '<input type="text" id="limit" style ="font-weight: 700;color:black;" placeholder="Włącz edycję limitu" readonly>'),
                  $( "#deleteLimit" ).replaceWith('<div id="deleteLimit" style="display:none;"></div>'),
                  $('#categoryLimit').load('/settings/showCategoryLimit', {
                    categoryId: categoryId
                  });
                });   
              });
            
        }
        else{
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
        
            var categoryId = $('#categoryE').val();           
            //limit kategorii
            $('#categoryLimit').load('/settings/showCategoryLimit', {
                categoryId: categoryId
            });
            $( "#limit" ).replaceWith( '<input type="text" id="limit" style ="font-weight: 700;color:black;" placeholder="Włącz edycję limitu" readonly>');
            $( "#deleteLimit" ).replaceWith('<div id="deleteLimit" style="display:none;"></div>');
        }
    });
});