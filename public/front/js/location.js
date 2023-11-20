

     function ajaxCall() {
             this.send = function(data, url, method, success, type) {
                       type = type||'json';
                       var successRes = function(data) {
                               success(data);
                           };

                           var errorRes = function(e) {
                               console.log(e);
                               alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
                           };
                         $.ajax({
                                 url: url,
                                 type: method,
                                 data: data,
                                 success: successRes,
                                 error: errorRes,
                                 dataType: type,
                                 timeout: 60000
                         });

                           }

                 }

 function locationInfo() {
         var rootUrl = window.location.host;
         var call = new ajaxCall();
         this.getCities = function(id) {
                 $(".cities option:gt(0)").remove();
                 var pathparts = location.pathname.split('/');
                 var url = location.origin+'/getCities/stateId/'+id;
                 var method = "get";
                 var data = {};
                 $('.cities').find("option:eq(0)").html("Please wait..");
                 call.send(data, url, method, function(data) {
                         $('.cities').find("option:eq(0)").html("Select City");
                         if(data.tp == 1){
                                 $.each(data['result'], function(key, val) {
                                         var option = $('<option />');
                                         option.attr('value', val.id).text(val.name);
                                         $('.cities').append(option);
                                     });
                                 $(".cities").prop("disabled",false);
                             }
                         else{
                                  alert(data.msg);
                             }
                     });
             };

             this.getStates = function(id) {
                 $(".states option:gt(0)").remove();
                 $(".cities option:gt(0)").remove();

                var pathparts = location.pathname.split('/');
                 var url = location.origin+'/getStates/countryId/'+id;
                 var method = "get";
                 var data = {};
                 $('.states').find("option:eq(0)").html("Please wait..");
                 call.send(data, url, method, function(data) {
                         $('.states').find("option:eq(0)").html("Select State");
                         if(data.tp == 1){
                                 $.each(data['result'], function(key, val) {
                                         var option = $('<option />');
                                         option.attr('value', val.id).text(val.name);
                                         $('.states').append(option);
                                     });
                                 $(".states").prop("disabled",false);
                             }
                         else{
                                 alert(data.msg);
                             }
                     });
             };

             this.getCountries = function() {
                 var pathparts = location.pathname.split('/');
                 var url = location.origin+'/getCountries';
                 // var url = rootUrl+'/getCountries';
                     var method = "get";
                 var data = {};
                 $('.countries').find("option:eq(0)").html("Please wait..");
                 call.send(data, url, method, function(data) {
                         $('.countries').find("option:eq(0)").html("Select Country*");
                         // console.log(data);
                             if(data.tp == 1){
                                 $.each(data['result'], function(key, val) {
                                         var option = $('<option />');
                                         option.attr('value', val.id).text(val.name);
                                         $('.countries').append(option);
                                     });
                                 $(".countries").prop("disabled",false);
                             }
                         else{
                                 alert(data.msg);
                             }
                     });
             };

         }

 $(function() {
     var loc = new locationInfo();
     loc.getCountries();
      $(".countries").on("change", function(ev) {
                 var countryId = $(this).val();
                 if(countryId != ''){
                     loc.getStates(countryId);
                     }
                 else{
                         $(".states option:gt(0)").remove();
                     }
             });
      $(".states").on("change", function(ev) {
                 var stateId = $(this).val();
                 if(stateId != ''){
                     loc.getCities(stateId);
                     }
                 else{
                         $(".cities option:gt(0)").remove();
                     }
             });
     });
